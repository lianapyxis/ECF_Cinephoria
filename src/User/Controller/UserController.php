<?php


namespace App\User\Controller;

use App\Film\Repository\FilmRepository;
use App\User\Repository\UserRepository;
use App\User\Form\UserType;
use App\Entity\Film;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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

    #[Route('/', name: 'list')]
    public function list(Security $security,UserRepository $userRepository, FilmRepository $filmRepository): Response
    {

        if ($security->isGranted('ROLE_ADMIN')) {

            $users = $userRepository->findStaff();

            return $this->render('user/listAdmin.html.twig', [
                'users' => $users,
            ]);
        } else  {
            $films = $filmRepository->findAll();

            return $this->render('films/listHomePage.html.twig', [
                'films' => $films
            ]);
        }
    }
    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create/{user}', name: 'create')]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('create', 'user')]
    public function edit(RouterInterface $router, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ?User $user = null): Response
    {
        $isCreate = !$user;
        $user = $user ?? new User();
        $role = ["ROLE_WORKER"];
        $user->setRoles($role);

        $form = $this->createForm(UserType::class, $user, [
            'action' => $router->generate('users_create'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);
        $form->remove('password');
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $id = $_POST['current_id'];

            if($_POST['current_id'] == '') {
                $user = $form->getData();
                $plaintextPassword = $_POST['user_password_first'];

                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                $user->setDateAdd(new \DateTimeImmutable());

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'L\'utilisateur a été créé' );
            } else {
                $originalUser = $em->find('App\Entity\User',$_POST['current_id']);
                $originalPassword = $originalUser->getPassword();

                $user = $form->getData();
                $oldpassword = $_POST['current_password'];
                $plaintextPassword = $_POST['user_password_first'];

                if($oldpassword !== '' AND $plaintextPassword !== ''){

                    if ($passwordHasher->isPasswordValid($originalUser, $oldpassword)) {
                        $hashedPassword = $passwordHasher->hashPassword(
                            $user,
                            $plaintextPassword
                        );

                        $originalUser->setFirstname($form->get('firstname')->getData());
                        $originalUser->setLastname($form->get('lastname')->getData());
                        $originalUser->setEmail($form->get('email')->getData());
                        $originalUser->setUsername($form->get('username')->getData());

                        $originalUser->setPassword($hashedPassword);

                        $originalUser->setDateAdd(new \DateTimeImmutable());

                        $em->persist($originalUser);
                        $em->flush();

                        $this->addFlash('success', 'L\'utilisateur a été modifié' );
                    }
/*                    else {
                        $this->addFlash('fail', 'L\'utilisateur n\'a pas été créé car l\'ancien mot de passe était pas renseigné correctement.' );
                    }*/
                } else {

                    $originalUser = $em->find('App\Entity\User',$id);

                    $originalUser->setFirstname($form->get('firstname')->getData());
                    $originalUser->setLastname($form->get('lastname')->getData());
                    $originalUser->setEmail($form->get('email')->getData());
                    $originalUser->setUsername($form->get('username')->getData());

                    $em->persist($originalUser);
                    $em->flush();
                    $this->addFlash('success', 'L\'utilisateur a été modifié' );
                }
            }

            return $this->redirectToRoute('users_list');

        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'is_create' => $isCreate,
            'user' => $user,
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
