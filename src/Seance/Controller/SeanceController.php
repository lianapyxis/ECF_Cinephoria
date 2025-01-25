<?php

namespace App\Seance\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationDetails;
use App\Entity\Seance;
use App\Seance\Form\SeanceType;
use App\Seance\Repository\SeanceRepository;
use App\Comment\Form\CommentType;
use App\Entity\Film;
use App\Entity\Comment;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/*use PhpParser\Node\Stmt\Expression;*/


#[Route('/seances', name: 'seances_')]
class SeanceController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(Security $security,SeanceRepository $seanceRepository): Response
    {

        if ($security->isGranted('ROLE_ADMIN')) {
            $seances = $seanceRepository->findAll();

            return $this->render('seances/listAdmin.html.twig', [
                'seances' => $seances,
            ]);
        } elseif ($security->isGranted('ROLE_WORKER')) {
            $seances = $seanceRepository->findAll();

            return $this->render('seances/listStaff.html.twig', [
                'seances' => $seances,
                'user' => $security->getUser(),
            ]);
        } elseif ($security->isGranted('ROLE_USER')) {
            $seances = $seanceRepository->findAll();

            return $this->render('films/listHomePage.html.twig', [
                'seances' => $seances,
            ]);
        } else {
            $seances = $seanceRepository->findAll();

            return $this->render('seances/listHomePage.html.twig', [
                'seances' => $seances,
            ]);
        }
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Security $security, RouterInterface $router, Seance $selectedSeance = null, Film $film = null): Response
    {
        if ($security->isGranted('ROLE_USER')) {

            $comment = new Comment();
            $comment->setFilm($film);
            $form = $this->createForm(CommentType::class, $comment, [
                'action' => $router->generate('comments_create', ['film' => $film->getId()])
            ]);
            $seances = $film->getSeances();
            $formats = [];
            foreach ($seances as $seance) {
                $room = $seance->getIdRoom();
                $allSeats = $room->getNumberSeats();
                $numberReservations = count($seance->getReservations());
                $seance->restingPlaces = $allSeats - $numberReservations;
                $format = $room->getFormat();
                $formatTitle = $format->getTitle();
                if (!in_array($formatTitle, $formats)) {
                    $formats[] = $formatTitle;
                }
            }

            $selectedRoom = $selectedSeance->getIdRoom();
            $allSeatsSelected = $selectedRoom->getNumberSeats();
            $numberReservationsSelected = count($selectedSeance->getReservations());
            $selectedSeance->restingPlaces = $allSeatsSelected - $numberReservationsSelected;
            $formatSelected = $selectedRoom->getFormat();
            $formatTitleSelected = $formatSelected->getTitle();
            $formatsSelected = [];
            if (!in_array($formatTitleSelected, $formatsSelected)) {
                $formatsSelected[] = $formatTitleSelected;
            }

            $numberPlacesPerRow = (int)$allSeatsSelected / $selectedRoom->getNumberRows();
            if ($allSeatsSelected % $selectedRoom->getNumberRows() !== 0) {
                $numberPlacesLeftAfterDivision = $allSeatsSelected % $selectedRoom->getNumberRows();
            }

            $namedPlaces = [];
            $typeSeats = $selectedRoom->getTypeSeats();

            if ($typeSeats->getId() == 1) {
                $str = 'a';
                for ($i = 0; $i <= $selectedRoom->getNumberRows(); $i++) {
                    for ($j = 1; $j <= $numberPlacesPerRow; $j++) {
                        $namedPlaces[$i][] = $str . $j;
                    }
                    $str = chr(ord($str) + 1);
                }
            } else {
                $number = 1;
                for ($i = 0; $i <= $selectedRoom->getNumberRows(); $i++) {
                    for ($j = 1; $j <= $numberPlacesPerRow; $j++) {
                        $namedPlaces[$i][] = $number . $j;
                    }
                    $number++;
                }
            }

            $checkedPlaces = [];

            $reservations = $selectedSeance->getReservations();

            if (!empty($reservations[0])) {

                $reservedPlaces = [];

                foreach ($reservations as $reservation) {
                    if($reservation->getStatus() !== 3) {
                        $reservationDetails = $reservation->getReservationDetails();
                        foreach ($reservationDetails as $reservationDetail) {
                            $reservedPlaces[] = strtolower($reservationDetail->getPlace());
                        }
                    }
                }

                foreach ($namedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $checkedPlaces[$key][$key2]['name'] = $place;
                        if (in_array(strtolower($place), $reservedPlaces)) {
                            $checkedPlaces[$key][$key2]['reserved'] = 1;
                        } else {
                            $checkedPlaces[$key][$key2]['reserved'] = 0;
                        }
                    }
                }

            } else {
                foreach ($namedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $checkedPlaces[$key][$key2]['name'] = $place;
                        $checkedPlaces[$key][$key2]['reserved'] = 0;
                    }
                }
            }

            $allPlaces = [];
            $specialPlaces = $selectedRoom->getSpecialPlaces();
            $specialPlacesArray = [];
            foreach ($specialPlaces as $specialPlace) {
                $specialPlacesArray[] = strtolower($specialPlace->getPlace());
            }

            if (!empty($specialPlaces[0])) {
                foreach ($checkedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $allPlaces[$key][$key2]['name'] = $place['name'];
                        $allPlaces[$key][$key2]['reserved'] = $place['reserved'];
                        $allPlaces[$key][$key2]['toedit'] = 0;
                        if (in_array($place['name'], $specialPlacesArray)) {
                            $allPlaces[$key][$key2]['special'] = 1;
                        } else {
                            $allPlaces[$key][$key2]['special'] = 0;
                        }

                    }
                }
            }

            $selectedSeance->allPlaces = $allPlaces;
            $user = $this->getUser();
            $isEdit = false;

            return $this->render('seances/show.html.twig', [
                'film' => $selectedSeance->getIdFilm(),
                'form' => $form,
                'seances' => $seances,
                'formats' => $formats,
                'selectedSeance' => $selectedSeance,
                'formatsSelected' => $formatsSelected,
                'user' => $user,
                'isEdit' => $isEdit,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    #[Route('/book', name: 'book', methods: ['POST'])]
    public function bookAjax(Request $request, EntityManagerInterface $em, ?Reservation $reservation = null, Seance $selectedSeance = null): Response
    {
        $idSeance = $request->get('idSeance');
        $idUser = $request->get('idUser');
        $totalCost = $request->get('totalCost');
        $places = $request->get('places');

        $reservation = new Reservation();

        if(!empty($idSeance) AND !empty($idUser) AND !empty($places) AND !empty($totalCost)){

            $selectedSeance = $em->find('App\Entity\Seance',$idSeance);
            $user = $this->getUser();

            $reservation->setIdSeance($selectedSeance);
            $reservation->setIdUser($user);
            $reservation->setCostTotal($totalCost);
            $reservation->setStatus(0);
            $reservation->setDateAdd(new \DateTimeImmutable());

            foreach ($places as $place) {
                $reservationDetails = new ReservationDetails();
                $reservationDetails->setPlace($place);
                $reservationDetails->setDateAdd(new \DateTimeImmutable());
                $reservation->addReservationDetail($reservationDetails);
            }
            $selectedSeance->addReservation($reservation);

            $em->persist($reservation);
            $em->flush();
            return new Response("Réservation réussie");
        }

        return new Response("Réservation échouée, essayez de nouveau");
    }

    #[Route('/editBook', name: 'editBook', methods: ['POST'])]
    public function bookEditAjax(Request $request, EntityManagerInterface $em, Reservation $reservation = null): Response
    {
        $totalCost = $request->get('totalCost');
        $places = $request->get('places');
        $reservation = $em->find('App\Entity\Reservation', $request->get('reservationId'));

        if(!empty($places) AND !empty($totalCost) AND !empty($reservation)){

            $reservation->setCostTotal($totalCost);
            $reservation->setStatus(0);

            $oldReservationDetails = $reservation->getReservationDetails();
            foreach ($oldReservationDetails as $oldReservationDetail) {
                $reservation->getReservationDetails()->removeElement($oldReservationDetail);
                $em->remove($oldReservationDetail);
            }

            foreach ($places as $place) {
                $reservationDetails = new ReservationDetails();
                $reservationDetails->setPlace($place);
                $reservationDetails->setDateAdd(new \DateTimeImmutable());
                $reservation->addReservationDetail($reservationDetails);
            }

            $em->persist($reservation);
            $em->flush();
            return new Response("Réservation est modifié");
        }

        return new Response("Réservation échouée, essayez de nouveau");
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create', name: 'create')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_WORKER")'))]
    #[IsGranted('edit', 'seance')]
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, ?Seance $seance = null, City $city = null): Response
    {
        $isCreate = !$seance;
        $seance = $seance ?? new Seance();

        $cities = $em->getRepository(City::class)->findAll();

        $form= $this->createForm(SeanceType::class, $seance);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Seance $seance */
            $seance = $form->getData();

            $date = $_POST['date_select'];

            $timeStart = $form->get('time_start')->getData();

            $timeEnd = $form->get('time_end')->getData();

            if ($timeStart < $timeEnd) {
                $timeStart = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date.' '.date_format($form->get('time_start')->getData(), 'H:i:s'));
                $timeEnd = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s',$date.' '.date_format($form->get('time_end')->getData(), 'H:i:s'));
                $seance->setTimeStart($timeStart);
                $seance->setTimeEnd($timeEnd);
            } else {
                $dateEnd = date('Y-m-d', strtotime($date . ' +1 day'));
                $timeStart = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date.' '.date_format($form->get('time_start')->getData(), 'H:i:s'));
                $timeEnd = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateEnd.' '.date_format($form->get('time_end')->getData(), 'H:i:s'));
                $seance->setTimeStart($timeStart);
                $seance->setTimeEnd($timeEnd);
            }

            $seance->setDateAdd(new \DateTimeImmutable());
            $em->persist($seance);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'La séance a été créée' : 'Le séance a été modifiée');

            return $this->redirectToRoute('seances_list');
        }

        return $this->render('seances/edit.html.twig', [
            'form' => $form,
            'is_create'=>$isCreate,
            'seance' => $seance,
            'cities' => $cities,
        ]);
    }

    #[Route('/editreservation/{id}/reservation/{reservationId}', name: 'editreservation')]
/*    #[ParamConverter('Seance', options: ['mapping' => ['id' => 'id']])]
    #[ParamConverter('Reservation', options: ['mapping' => ['reservationId' => 'id']])]*/
    public function editReservation(Request $request, Security $security, EntityManagerInterface $em, RouterInterface $router, Seance $selectedSeance = null, Film $film = null, Reservation $reservation): Response
    {
        if ($security->isGranted('ROLE_USER')) {

            $user = $this->getUser();
            $userReservations = $user->getReservations();
            $reservation = $em->find('App\Entity\Reservation', $request->get('reservationId'));

            foreach ($userReservations as $userReservation) {
                if ($userReservation->getId() == $reservation->getId()) {
                    $userReservationDetails = $userReservation->getReservationDetails();
                }
            }

            $userReservationPlaces = [];
            foreach ($userReservationDetails as $userReservationDetail) {
                $userReservationPlaces[] = strtolower($userReservationDetail->getPlace());
            }

            $comment = new Comment();
            $comment->setFilm($film);
            $form = $this->createForm(CommentType::class, $comment, [
                'action' => $router->generate('comments_create', ['film' => $film->getId()])
            ]);
            $seances = $film->getSeances();
            $formats = [];
            foreach ($seances as $seance) {
                $room = $seance->getIdRoom();
                $allSeats = $room->getNumberSeats();
                $numberReservations = count($seance->getReservations());
                $seance->restingPlaces = $allSeats - $numberReservations;
                $format = $room->getFormat();
                $formatTitle = $format->getTitle();
                if (!in_array($formatTitle, $formats)) {
                    $formats[] = $formatTitle;
                }
            }

            $selectedRoom = $selectedSeance->getIdRoom();
            $allSeatsSelected = $selectedRoom->getNumberSeats();
            $numberReservationsSelected = count($selectedSeance->getReservations());
            $selectedSeance->restingPlaces = $allSeatsSelected - $numberReservationsSelected;
            $formatSelected = $selectedRoom->getFormat();
            $formatTitleSelected = $formatSelected->getTitle();
            $formatsSelected = [];
            if (!in_array($formatTitleSelected, $formatsSelected)) {
                $formatsSelected[] = $formatTitleSelected;
            }

            $numberPlacesPerRow = (int)$allSeatsSelected / $selectedRoom->getNumberRows();
            if ($allSeatsSelected % $selectedRoom->getNumberRows() !== 0) {
                $numberPlacesLeftAfterDivision = $allSeatsSelected % $selectedRoom->getNumberRows();
            }

            $namedPlaces = [];
            $typeSeats = $selectedRoom->getTypeSeats();

            if ($typeSeats->getId() == 1) {
                $str = 'a';
                for ($i = 0; $i <= $selectedRoom->getNumberRows(); $i++) {
                    for ($j = 1; $j <= $numberPlacesPerRow; $j++) {
                        $namedPlaces[$i][] = $str . $j;
                    }
                    $str = chr(ord($str) + 1);
                }
            } else {
                $number = 1;
                for ($i = 0; $i <= $selectedRoom->getNumberRows(); $i++) {
                    for ($j = 1; $j <= $numberPlacesPerRow; $j++) {
                        $namedPlaces[$i][] = $number . $j;
                    }
                    $number++;
                }
            }

            $checkedPlaces = [];

            $reservations = $selectedSeance->getReservations();

            if (!empty($reservations[0])) {

                $reservedPlaces = [];

                foreach ($reservations as $reservation) {
                    if($reservation->getStatus() !== 3) {
                        $reservationDetails = $reservation->getReservationDetails();
                        foreach ($reservationDetails as $reservationDetail) {
                            $reservedPlaces[] = strtolower($reservationDetail->getPlace());
                        }
                    }
                }

                foreach ($namedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $checkedPlaces[$key][$key2]['name'] = $place;
                        if (in_array(strtolower($place), $reservedPlaces)) {
                            $checkedPlaces[$key][$key2]['reserved'] = 1;
                        } else {
                            $checkedPlaces[$key][$key2]['reserved'] = 0;
                        }
                    }
                }

            } else {
                foreach ($namedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $checkedPlaces[$key][$key2]['name'] = $place;
                        $checkedPlaces[$key][$key2]['reserved'] = 0;
                    }
                }
            }

            $allPlaces = [];
            $specialPlaces = $selectedRoom->getSpecialPlaces();
            $specialPlacesArray = [];
            foreach ($specialPlaces as $specialPlace) {
                $specialPlacesArray[] = strtolower($specialPlace->getPlace());
            }

            if (!empty($specialPlaces[0])) {
                foreach ($checkedPlaces as $key => $row) {
                    foreach ($row as $key2 => $place) {
                        $allPlaces[$key][$key2]['name'] = $place['name'];
                        $allPlaces[$key][$key2]['reserved'] = $place['reserved'];

                        if (in_array($place['name'], $specialPlacesArray)) {
                            $allPlaces[$key][$key2]['special'] = 1;
                        } else {
                            $allPlaces[$key][$key2]['special'] = 0;
                        }

                        if(in_array($place['name'], $userReservationPlaces)) {
                            $allPlaces[$key][$key2]['toedit'] = 1;
                        } else {
                            $allPlaces[$key][$key2]['toedit'] = 0;
                        }

                    }
                }
            }

            $selectedSeance->allPlaces = $allPlaces;
            $isEdit = true;

            return $this->render('seances/show.html.twig', [
                'film' => $selectedSeance->getIdFilm(),
                'form' => $form,
                'seances' => $seances,
                'formats' => $formats,
                'selectedSeance' => $selectedSeance,
                'formatsSelected' => $formatsSelected,
                'user' => $user,
                'isEdit' => $isEdit,
                'reservationId' => $reservation->getId(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Seance $seance, EntityManagerInterface $em): RedirectResponse
    {
        $this->addFlash('success', 'La séance a été supprimée');

        return $this->redirectToRoute('seances_list');
    }
}