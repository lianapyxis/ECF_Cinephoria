<?php

namespace App\Film\Controller;

use App\Film\Form\FilmType;
use App\Film\Repository\FilmRepository;
use App\Comment\Form\CommentType;
use App\Entity\Film;
use App\Entity\Comment;
use App\City\Repository\CityRepository;
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

    #[Route('/reservationlist', name: 'reservationlist')]
    public function reservationlist(Security $security,FilmRepository $filmRepository, CityRepository $cityRepository, Request $request): Response
    {

        if ($security->isGranted('ROLE_USER')) {
            $page = $request->query->get('page',1);
            $city = $request->query->get('city');
            $query = $filmRepository->findAllWithPagination($page, $city);
            $films = $query->getData();

            foreach ($films as $film) {
                $cities = [];
                $seances = $film->getSeances();
                $seancesDates = [];
                if(isset($seances)) {
                    foreach ($seances as $seance) {
                        $dateSeance = $seance->getTimeStart();
                        $room = $seance->getIdRoom();
                        $city = $room->getIdCity();
                        $cityTitle = $city->getTitle();
                        if(!in_array($cityTitle, $cities)) {
                            $cities[] = $cityTitle;
                        }
                        if(!in_array($dateSeance, $seancesDates)) {
                            $seancesDates[] = $dateSeance->format('d.m.Y');
                        }
                    }
                }
                $film->citiesTitles = implode(",",$cities);
                $film->seancesDates = implode(",",$seancesDates);
            }

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
        $this->addFlash('success', 'Le film a été supprimé');

        return $this->redirectToRoute('films_list');
    }
}