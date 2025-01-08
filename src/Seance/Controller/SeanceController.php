<?php

namespace App\Seance\Controller;

use App\Entity\Seance;
use App\Seance\Form\SeanceType;
use App\Seance\Repository\SeanceRepository;
use App\Entity\Film;
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
    #[IsGranted('show', 'seance')]
    public function show(RouterInterface $router, Seance $seance = null, Film $film = null)
    {

/*        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $router->generate('comments_create', ['film' => $film->getId()])
        ]);*/

        return $this->render('seances/show.html.twig', [
            'seance' => $seance,
            'film' => $film,
/*            'form' => $form,*/
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create', name: 'create')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_WORKER")'))]
    #[IsGranted('edit', 'seance')]
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, ?Seance $seance = null): Response
    {
        $isCreate = !$seance;
        $seance = $seance ?? new Seance();

        $form= $this->createForm(SeanceType::class, $seance);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Seance $seance */
            $seance = $form->getData();

            $imgFile = $form->get('imgPath')->getData();

            $seance->setDateAdd(new \DateTimeImmutable());
            $em->persist($seance);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'La séance a été créée' : 'Le séance a été modifiée');

            return $this->redirectToRoute('seances_list');
        }

        return $this->render('seances/edit.html.twig', [
            'form' => $form,
            'is_create'=>$isCreate,
            'seance' => $seance
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Seance $seance, EntityManagerInterface $em): RedirectResponse
    {
        $this->addFlash('success', 'La séance a été supprimée');

        return $this->redirectToRoute('seances_list');
    }
}