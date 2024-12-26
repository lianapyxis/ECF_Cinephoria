<?php


namespace App\Comment\Controller;

use App\Comment\Constant\CommentStatus;
use App\Comment\Form\CommentType;
use App\Entity\Film;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment', name: 'comments_')]
class CommentController extends AbstractController
{
    #[Route('/create/{film}', name: 'create')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('create', 'comment')]
    #[IsGranted('published', 'film')]
    public function edit(RouterInterface $router, Request $request, EntityManagerInterface $em, Film $film, ?Comment $comment = null): Response
    {

/*        if (FilmStatus::PUBLISHED !== $film->getStatus()) {
            throw new AccessDeniedHttpException('Film non publié');
        }*/

        $comment= new Comment();
        $comment->setFilm($film);
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $router->generate('comments_create', ['film' => $film->getId()]),
            'attr' => ['data-turbo-frame' => '_top']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setStatus(CommentStatus::DRAFT);
            $comment->setDateAdd(new \DateTimeImmutable());
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Le commentaire a été créé' );

            return $this->redirectToRoute('films_show', ['id' => $film->getId()]);

        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('delete', 'comment')]
    public function delete(EntityManagerInterface $em, Comment $comment): RedirectResponse
    {

        $filmId = $comment->getFilm()->getId();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('films_show', ['id' => $filmId]);
    }

}
