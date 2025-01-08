<?php

namespace App\Room\Controller;

use App\Entity\Room;
use App\Room\Form\RoomType;
use App\Room\Repository\RoomRepository;
use App\Entity\City;
use App\Film\Repository\FilmRepository;
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

/*use PhpParser\Node\Stmt\Expression;*/


#[Route('/rooms', name: 'rooms_')]
class RoomController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(Security $security,RoomRepository $roomRepository, FilmRepository $filmRepository): Response
    {

        if ($security->isGranted('ROLE_ADMIN')) {
            $rooms = $roomRepository->findAll();

            return $this->render('rooms/listAdmin.html.twig', [
                'rooms' => $rooms,
            ]);
        } elseif ($security->isGranted('ROLE_WORKER')) {
            $rooms = $roomRepository->findAll();

            return $this->render('rooms/listStaff.html.twig', [
                'rooms' => $rooms,
                'user' => $security->getUser(),
            ]);
        } elseif ($security->isGranted('ROLE_USER')) {

            $films = $filmRepository->findAll();
            return $this->render('films/listHomePage.html.twig', [
                'films' => $films,
            ]);
        } else {
            $films = $filmRepository->findAll();

            return $this->render('films/listHomePage.html.twig', [
                'films' => $films,
            ]);
        }
    }

    #[Route('/show/{id}', name: 'show')]
    #[IsGranted('show', 'room')]
    public function show(RouterInterface $router, Room $room = null)
    {

        return $this->render('rooms/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create', name: 'create')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_WORKER")'))]
    #[IsGranted('edit', 'room')]
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, ?Room $room = null): Response
    {
        $isCreate = !$room;
        $room = $room ?? new Room();

        $form= $this->createForm(RoomType::class, $room);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Room $room */
            $room = $form->getData();

            $room->setDateAdd(new \DateTimeImmutable());
            $em->persist($room);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'La salle a été créée' : 'La salle a été modifiée');

            return $this->redirectToRoute('rooms_list');
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form,
            'is_create'=>$isCreate,
            'room' => $room
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Room $room, EntityManagerInterface $em): RedirectResponse
    {
        $this->addFlash('success', 'La salle a été supprimée');

        return $this->redirectToRoute('rooms_list');
    }
}