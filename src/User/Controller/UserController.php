<?php


namespace App\User\Controller;

use App\User\Form\UserType;
use App\Entity\Film;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user', name: 'users_')]
class UserController extends AbstractController
{
    #[Route('/create/{user}', name: 'create')]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('create', 'user')]
    public function edit(RouterInterface $router, Request $request, EntityManagerInterface $em, ?User $user = null): Response
    {

        $user= new User();
        $role = ["ROLE_WORKER"];
        $user->setRoles($role);
        $form = $this->createForm(UserType::class, $user, [
            'action' => $router->generate('users_create'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $form->getData();
            $user->setDateAdd(new \DateTimeImmutable());
            $user->setUser($this->getUser());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a été créé' );

/*            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);*/
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);

        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/register/{user}', name: 'register')]
    public function create(RouterInterface $router, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ?User $user = null): Response
    {

        $user= new User();
        $role = ["ROLE_USER"];
        $user->setRoles($role);
        $form = $this->createForm(UserType::class, $user, [
            'action' => $router->generate('users_register'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $form->getData();
            $plaintextPassword = $form->get('password')->getData();

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );

            $user->setPassword($hashedPassword);

            $user->setDateAdd(new \DateTimeImmutable());
/*            $user->setFirstname($this->getUser()->getFirstname());
            $user->setLastname($this->getUser());
            $user->setUsername($this->getUser());
            $user->setEmail($this->getUser());*/

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a été créé' );

            /*            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);*/
            return $this->redirectToRoute('app_login');

        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted('delete', 'user')]
    public function delete(EntityManagerInterface $em, User $user): RedirectResponse
    {

/*        $filmId = $comment->getFilm()->getId();*/
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('films_show');
    }

}
