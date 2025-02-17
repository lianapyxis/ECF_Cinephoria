<?php

namespace App\Film\Controller;

use App\Film\Form\FilmType;
use App\Film\Repository\FilmRepository;
use App\Comment\Form\CommentType;
use App\Entity\Film;
use App\Entity\Comment;
use App\Film\Repository\FilmGenreRepository;
use App\City\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/*use PhpParser\Node\Stmt\Expression;*/


#[Route('/films', name: 'films_')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository, Request $request): Response
    {

         if ($security->isGranted('ROLE_ADMIN')) {
             $films = $filmRepository->findAll();

             return $this->render('films/listAdmin.html.twig', [
                 'films' => $films,
             ]);
        } elseif ($security->isGranted('ROLE_WORKER')) {
             $films = $filmRepository->findAll();

             return $this->render('films/listStaff.html.twig', [
                 'films' => $films,
                 'user' => $security->getUser(),
             ]);
        } else {
             $page = $request->query->get('page',1);
             $city = $request->query->get('city');

             $query = $filmRepository->findAllNewWithPagination($page, $city);
             $films = $query->getData();

             return $this->render('films/listHomePage.html.twig', [
                 'films' => $films,
                 'cities' => $cityRepository->findAll(),
                 'totalPages' => $query->getTotalPages(),
                 'currentPage' => $query->getCurrentPage()
             ]);
        }
    }

    #[Route('/filter', name: 'filter', methods: ['POST'])]
    public function filterAjax(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository, Request $request): Response
    {
            $page = $request->get('page',1);
            $city = $request->get('city');

            $query = $filmRepository->findAllNewWithPagination($page, $city);
            $films = $query->getData();
            $filmsObj =[];

            foreach ($films as $key => $film) {
                $filmsObj[$key]['id'] = $film->getId();
                $filmsObj[$key]['title'] = $film->getTitle();
                $filmsObj[$key]['year'] = $film->getYear();
                $filmsObj[$key]['dateAdd'] = $film->getDateAdd()->format('Y-m-d');
                $filmsObj[$key]['imgPath'] = $film->getImgPath();
            }
        $response = [
            'films' => $filmsObj,
            'totalPages' => $query->getTotalPages(),
            'currentPage' => $query->getCurrentPage()
        ];

        return new JsonResponse(json_encode($response));
    }

    #[Route('/filterDate', name: 'filterDate', methods: ['POST'])]
    public function filterDateAjax(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository, Request $request): Response
    {
        $page = $request->get('page',1);
        $city = $request->get('city');
        $date = $request->get('date');
        $query = $filmRepository->findAllWithPagination($page, $city, $date);
        $films = $query->getData();
        $filmsObj =[];

        foreach ($films as $key => $film) {
            $filmsObj[$key]['id'] = $film->getId();
            $filmsObj[$key]['title'] = $film->getTitle();
            $filmsObj[$key]['year'] = $film->getYear();
            $filmsObj[$key]['dateAdd'] = $film->getDateAdd()->format('Y-m-d');
            $filmsObj[$key]['imgPath'] = $film->getImgPath();
        }

        $response = [
            'films' => $filmsObj,
            'totalPages' => $query->getTotalPages(),
            'currentPage' => $query->getCurrentPage()
        ];
        return new JsonResponse(json_encode($response));
    }

    #[Route('/reservationlist', name: 'reservationlist')]
    public function reservationlist(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository, Request $request): Response
    {

        if ($security->isGranted('ROLE_USER')) {
            $page = $request->query->get('page',1);
            $city = $request->query->get('city');
            $date = $request->query->get('date');
            $query = $filmRepository->findAllWithPagination($page, $city, $date);
            $films = $query->getData();

            return $this->render('films/reservationlist.html.twig', [
                'films' => $films,
                'cities' => $cityRepository->findAll(),
                'totalPages' => $query->getTotalPages(),
                'currentPage' => $query->getCurrentPage()
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/collection', name: 'collection')]
    public function collection(Security $security,FilmRepository $filmRepository,FilmGenreRepository $filmGenreRepository, CityRepository $cityRepository, Request $request): Response
    {
        $films = $filmRepository->findAll();
        $city = $request->query->get('city');

        foreach ($films as $film) {
            $filmNotes = $film->getNotes();
            $notesQty = count($filmNotes);
            $notesSum = 0;

            $description= substr($film->getDescription(), 0, 400);
            $film->shortDescription =trim($description).'...';

            $seances = $film->getSeances();
            $seancesDates = [];
            $seancesCities = [];
            foreach ($seances as $seance) {
                $seancesDates[] = $seance->getTimeStart()->format('d.m.Y');
                $seancesCities[] = $seance->getIdRoom()->getIdCity()->getTitle();
            }
            $film->seancesDates = implode(",",$seancesDates);
            $film->seancesCities = implode(",",$seancesCities);

            $filmGenres = [];
            foreach ($film->getGenres() as $genre) {
                $filmGenres[] = $genre->getName();
            }

            $film->genreList = implode(",",$filmGenres);

            if($notesQty > 0) {
                foreach ($filmNotes as $note) {
                    $notesSum += $note->getNote();
                }
                $averageNote = number_format(($notesSum / $notesQty),2);
            } else {
                $averageNote = 0;
            }
            $film->averageNote = (float)$averageNote;
        }

        return $this->render('films/filmscollection.html.twig', [
            'films' => $films,
            'cities' => $cityRepository->findAll(),
            'genres' => $filmGenreRepository->findAll()
        ]);
    }

    #[Route('/getSeances', name: 'getSeances', methods: ['POST'])]
    public function getSeances(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository,EntityManagerInterface $em, Request $request): Response
    {
        $filmId = $request->get('filmId');
        $date = $request->get('date');
        $city = $request->get('city');
        $film = $filmRepository->find($filmId);
        $seances = $film->getSeances();
        $selectedSeances = [];
        if($city == 'Toutes les villes') {
            if(!empty($date)){
                foreach ($seances as $key => $seance) {
                    $dateSeance = $seance->getTimeStart()->format('Y-m-d');
                    if($date == $dateSeance){
                        $selectedSeances[$key]['date'] = $seance->getTimeStart()->format('d.m.Y');
                        $selectedSeances[$key]['heureDebut'] = $seance->getTimeStart()->format('H:i');
                        $selectedSeances[$key]['heureFin'] = $seance->getTimeEnd()->format('H:i');
                        $selectedSeances[$key]['format'] = $seance->getIdRoom()->getFormat()->getTitle();
                        $selectedSeances[$key]['prix'] = $seance->getPriceTtc();
                    }
                }
                $film->selectedSeances = $selectedSeances;
            } else {
                foreach ($seances as $key => $seance) {
                    $selectedSeances[$key]['date'] = $seance->getTimeStart()->format('d.m.Y');
                    $selectedSeances[$key]['heureDebut'] = $seance->getTimeStart()->format('H:i');
                    $selectedSeances[$key]['heureFin'] = $seance->getTimeEnd()->format('H:i');
                    $selectedSeances[$key]['format'] = $seance->getIdRoom()->getFormat()->getTitle();
                    $selectedSeances[$key]['prix'] = $seance->getPriceTtc();
                }
                $film->selectedSeances = $selectedSeances;
            }
        } else {
            if(!empty($date)){
                $seancesInCity = [];
                foreach ($seances as $seance) {
                    $seanceCity = $seance->getIdRoom()->getIdCity()->getTitle();
                    if($city == $seanceCity){
                        $seancesInCity[] = $seance;
                    }
                }
                foreach ($seancesInCity as $key => $seance) {
                    $dateSeance = $seance->getTimeStart()->format('Y-m-d');
                    if($date == $dateSeance){
                        $selectedSeances[$key]['date'] = $seance->getTimeStart()->format('d.m.Y');
                        $selectedSeances[$key]['heureDebut'] = $seance->getTimeStart()->format('H:i');
                        $selectedSeances[$key]['heureFin'] = $seance->getTimeEnd()->format('H:i');
                        $selectedSeances[$key]['format'] = $seance->getIdRoom()->getFormat()->getTitle();
                        $selectedSeances[$key]['prix'] = $seance->getPriceTtc();
                    }
                }
                $film->selectedSeances = $selectedSeances;
            } else {
                $seancesInCity = [];
                foreach ($seances as $seance) {
                    $seanceCity = $seance->getIdRoom()->getIdCity()->getTitle();
                    if($city == $seanceCity) {
                        $seancesInCity[] = $seance;

                    }
                }
                foreach ($seancesInCity as $key => $seance) {
                    $selectedSeances[$key]['date'] = $seance->getTimeStart()->format('d.m.Y');
                    $selectedSeances[$key]['heureDebut'] = $seance->getTimeStart()->format('H:i');
                    $selectedSeances[$key]['heureFin'] = $seance->getTimeEnd()->format('H:i');
                    $selectedSeances[$key]['format'] = $seance->getIdRoom()->getFormat()->getTitle();
                    $selectedSeances[$key]['prix'] = $seance->getPriceTtc();
                }
                $film->selectedSeances = $selectedSeances;
            }
        }

        $filmObj = [];

        $filmObj['id'] = $film->getId();
        $filmObj['title'] = $film->getTitle();
        $filmObj['year'] = $film->getYear();
        $filmObj['seances'] = $film->selectedSeances;

        $response = [
            'film' => $filmObj,
        ];

        return new JsonResponse(json_encode($response));
    }

    #[Route('/show/{id}', name: 'show')]
    #[IsGranted('show', 'film')]
    public function show(RouterInterface $router, Film $film = null, CityRepository $cityRepository): Response
    {

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
            if(!in_array($formatTitle, $formats)){
                $formats[] = $formatTitle;
            }
        }
        $filmNotes = $film->getNotes();
        $notesQty = count($filmNotes);
        $notesSum = 0;

        if($notesQty > 0) {
            foreach ($filmNotes as $note) {
                $notesSum += $note->getNote();
            }
            $averageNote = number_format(($notesSum / $notesQty),2);
        } else {
            $averageNote = 0;
        }

        return $this->render('films/show.html.twig', [
            'film' => $film,
            'form' => $form,
            'seances' => $seances,
            'formats' => $formats,
            'averageNote' => (float)$averageNote,
            'cities' => $cityRepository->findAll()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create', name: 'create')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_WORKER")'))]
    #[IsGranted('edit', 'film')]
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, ?Film $film = null): Response
    {
        $isCreate = !$film;
        $film = $film ?? new Film();

        $form= $this->createForm(FilmType::class, $film);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Film $film */
            $film = $form->getData();

            $imgFile = $form->get('imgPath')->getData();

            if($imgFile instanceof File){

                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();
                $ImgDirectory = $this->getParameter('kernel.project_dir').'/public/uploads';

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move($ImgDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $film->setImgPath($newFilename);
            }

            $film->setDateAdd(new \DateTimeImmutable());
            $em->persist($film);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'Le film a été créé' : 'Le film a été modifié');

            return $this->redirectToRoute('films_list');
        }

        return $this->render('films/edit.html.twig', [
            'form' => $form,
            'is_create'=>$isCreate,
            'film' => $film
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Film $film, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', 'Le film a été supprimé');

        return $this->redirectToRoute('films_list');
    }
}