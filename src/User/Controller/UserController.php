<?php


namespace App\User\Controller;

use App\Entity\FilmNote;
use App\Entity\Reservation;
use App\Film\Repository\FilmRepository;
use App\Repository\ReservationRepository;
use App\Seance\Repository\SeanceRepository;
use App\User\Repository\UserRepository;
use App\User\Form\UserType;
use App\User\Form\ContactType;
use App\User\Form\PasswordRestaurationType;
use App\User\Form\NewPasswordType;
use App\Entity\Seance;
use App\Entity\Film;
use App\Entity\User;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route('/myaccount/{id}', name: 'myaccount')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('create', 'user')]
    public function editMonCompte(RouterInterface $router, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ?User $user = null): Response
    {

        if($this->getUser()->getId() == $user->getId()){
        $isCreate = !$user;
        $user = $user ?? new User();
        $role = ["ROLE_USER"];
        $user->setRoles($role);

        $form = $this->createForm(UserType::class, $user, [
            'action' => $router->generate('users_myaccount', ['id' => $user->getId()]),
            'attr' => ['data-turbo-frame' => '_top']
        ]);
        $form->remove('password');
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $id = $_POST['current_id'];

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

            return $this->redirectToRoute('users_myaccount',['id' => $id]);
        }

        return $this->render('user/monCompte.html.twig', [
            'form' => $form,
            'is_create' => $isCreate,
            'user' => $user,
        ]);
        }
        return $this->redirectToRoute('films_list');
    }

    #[Route('/reservations/{id}', name: 'reservations')]
    #[IsGranted('ROLE_USER')]
    public function listReservations(RouterInterface $router, Request $request, EntityManagerInterface $em, ?User $user = null): Response
    {
        if($this->getUser()->getId() == $user->getId()){

            $reservationsList = $user->getReservations();
            $notes = $user->getFilmNotes();

            $reservations = [];
            foreach($reservationsList as $key => $reservation){

                $reservations[$key] = $reservation;
                $seance = $reservation->getIdSeance();
                $film = $seance->getIdFilm();
                $reservations[$key]->film = $film;

                $heure = $seance->getTimeStart();
                $reservations[$key]->heure = $heure;
                $today = new \DateTimeImmutable();
                if($heure < $today) {
                    $reservations[$key]->passed = 1;
                } else {
                    $reservations[$key]->passed = 0;
                }
                $reservations[$key]->note = null;

                foreach($notes as $note){
                    if($note->getFilm()->getId() == $film->getId()){
                        $reservations[$key]->note = $note;
                    }
                }


                $room = $seance->getIdRoom();
                $format = $room->getFormat()->getTitle();
                $reservations[$key]->format = $format;

                $reservationDetails = $reservation->getReservationDetails();

                $places = [];
                foreach($reservationDetails as $reservationDetail){
                    $places[] = $reservationDetail->getPlace();
                }
                $reservations[$key]->places = $places;

            }

            return $this->render('user/mesReservations.html.twig', [
                'user' => $user,
                'reservations' => $reservations,
            ]);
        } else {
            return $this->redirectToRoute('films_list');
        }
    }

    #[Route('/reservation/{id}', name: 'reservation')]
    #[IsGranted('ROLE_USER')]
    public function showReservation(RouterInterface $router, Request $request, EntityManagerInterface $em, User $user = null, Reservation $reservation = null): Response
    {
        $user = $reservation->getIdUser();
        if($this->getUser()->getId() == $user->getId()){

            $seance = $reservation->getIdSeance();
            $room = $seance->getIdRoom();
            $format = $room->getFormat()->getTitle();
            $city = $room->getIdCity();
            $film = $seance->getIdFilm();
            $heure = $seance->getTimeStart();
            $reservationDetails = $reservation->getReservationDetails();

            $places = [];
            foreach($reservationDetails as $reservationDetail){
                $places[] = $reservationDetail->getPlace();
            }

            $reservation->places = $places;
            $reservation->film = $film;
            $reservation->heure = $heure;
            $reservation->city = $city;
            $reservation->format = $format;

            return $this->render('user/maReservation.html.twig', [
                'user' => $user,
                'reservation' => $reservation,
            ]);
        } else {
            return $this->redirectToRoute('films_list');
        }

    }

    #[Route('/cancelreservation/{id}', name: 'cancelreservation')]
    public function cancelReservations(RouterInterface $router, Request $request,Security $security, EntityManagerInterface $em, Reservation $reservation = null, ?User $user = null ): Response
    {
        if ($security->isGranted('ROLE_USER')) {
            if($reservation->getStatus() !== 3 OR $reservation->getStatus() !== 1) {
                $reservation->setStatus(3);

                $em->persist($reservation);
                $em->flush();
                return $this->redirectToRoute('users_reservations', ['id' => $this->getUser()->getId()]);

            } else {
                echo "Erreur lors de la cancellation : la séance n'est plus d'actualité";
                return $this->redirectToRoute('users_reservations', ['id' => $this->getUser()->getId()]);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
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
    #[Route('/addNote', name: 'addNote', methods: ['POST'])]
    public function addNote(RouterInterface $router, Request $request, EntityManagerInterface $em, User $user = null, FilmNote $filmnote = null): Response {
        $idFilm = $request->get("filmId");
        $grade = $request->get("grade");
        $gradeId = $request->get("gradeId");
        $user = $this->getUser();

        if(empty($gradeId)) {
            $filmnote = new FilmNote();
            $filmnote->setUser($user);
            $film = $em->find('App\Entity\Film', (int)$idFilm);
            $filmnote->setFilm($film);
            $filmnote->setNote($grade);
            $em->persist($filmnote);
            $em->flush();
        } else {
            $filmnote = $em->find('App\Entity\FilmNote', (int)$gradeId);
            $filmnote->setNote($grade);
            $em->persist($filmnote);
            $em->flush();
        }

        return new Response("Évaluation réussie");
    }

    #[Route('/contact', name: 'contact')]
    public function contact(RouterInterface $router, Request $request, EntityManagerInterface $em, User $user = null, MailerController $mailer, MailerInterface $mailerInt): Response {

        $form = $this->createForm(ContactType::class, $user, [
            'action' => $router->generate('users_contact'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $email = $request->request->all();
            $firstname = $email['contact']['firstname'];
            $lastname = $email['contact']['lastname'];
            $senderEmail = $email['contact']['email'];
            $subject = $email['contact']['object'];
            $message = $email['contact']['message'];

            if(!empty($firstname) AND !empty($lastname) AND !empty($senderEmail) AND !empty($subject) AND !empty($message)) {
                $mailer->sendEmail($mailerInt, $firstname, $lastname, $senderEmail, $subject, $message);
                $this->addFlash('success', 'Votre message a été envoyé.');
                return $this->redirectToRoute('users_contact');
            }
        }


        return $this->render('user/contact.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/passwordrestauration', name: 'passwordrestauration')]
    public function passwordrestauration(RouterInterface $router, Request $request, EntityManagerInterface $em,UserRepository $userRepository, User $user = null, MailerController $mailer, MailerInterface $mailerInt): Response {

        $form = $this->createForm(PasswordRestaurationType::class, $user, [
            'action' => $router->generate('users_passwordrestauration'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $request->request->all();
            $putEmail = $data['password_restauration']['email'];
            $userSearch = $userRepository->findOneByEmail($putEmail);

            if($userSearch !== null){
                $receiverEmail = $userSearch->getEmail();
                $subject = 'Réinitialisation de votre mot de passe';

                $emailToken = base64_encode(serialize($receiverEmail));
                $link = $this->generateUrl(
                    'users_newpassword', [
                    'token'=>$emailToken
                ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $message = 'Bonjour,<br><br>
                Vous pouvez créer votre nouveau mot de passe en cliquant sur le lien suivant:
                '.$link;


                if(!empty($receiverEmail ) AND !empty($subject) AND !empty($message)) {
                    $mailer->sendPasswordRestore($mailerInt, $receiverEmail, $subject, $message);
                    $this->addFlash('success', 'Un lien de réinitialisation vous a été envoyé.');
                    return $this->redirectToRoute('users_passwordrestauration');
                }
            }

        }

        return $this->render('user/passwordrestauration.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/newpassword', name: 'newpassword')]
    public function newpassword(RouterInterface $router, Request $request, EntityManagerInterface $em,UserRepository $userRepository, User $user = null, UserPasswordHasherInterface $passwordHasher, MailerController $mailer, MailerInterface $mailerInt): Response {

        $receivedToken = $request->get("token");
        if(!empty($receivedToken)) {
            $receivedEmail = unserialize(base64_decode($receivedToken));
            $userSearch = $userRepository->findOneByEmail($receivedEmail);
        } else {
            $receivedEmail = $_POST['new_password']['email'];
            $userSearch = $userRepository->findOneByEmail($receivedEmail);
        }

        $user = $userSearch;

        $form = $this->createForm(NewPasswordType::class, $user, [
            'action' => $router->generate('users_newpassword'),
            'attr' => ['data-turbo-frame' => '_top']
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $request->request->all();
            $putPassword = $data['new_password']['password'];

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $putPassword
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Le mot de passe a été modifié' );

            return $this->redirectToRoute('app_login');

        }

        return $this->render('user/newpassword.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(RouterInterface $router, Request $request,Security $security, EntityManagerInterface $em, ReservationRepository $reservationRepository = null, SeanceRepository $seanceRepository = null ): Response {

        if ($security->isGranted('ROLE_ADMIN')) {
            $allReservations = $reservationRepository->findAll();
            $allSeances = $seanceRepository->findAll();

            $nmbReservationsToday = 0;
            $nmbReservationsThisWeek = 0;
            $nmbReservationsThisYear = 0;
            $nmbSeancesThisYear = 0;

            $today = date("Y-m-d");
            $dayLastWeek = date("Y-m-d",strtotime("-7 days"));
            $firstdaythisYear = date("Y-m-d", strtotime("first day of january this year"));
            $lastdaythisYear  = date("Y-m-d",strtotime("last day of december this year"));

            foreach ($allReservations as $reservation) {
                if($reservation->getDateAdd()->format("Y-m-d") == $today) {
                    $nmbReservationsToday++;
                }
                if($reservation->getDateAdd()->format("Y-m-d") > $dayLastWeek) {
                    $nmbReservationsThisWeek++;
                }

                if($reservation->getDateAdd()->format("Y-m-d") >= $firstdaythisYear AND $reservation->getDateAdd()->format("Y-m-d") <= $lastdaythisYear) {
                    $nmbReservationsThisYear++;
                }
            }

            foreach ($allSeances as $seance) {
                if($seance->getTimeStart()->format("Y-m-d") >= $firstdaythisYear AND $seance->getTimeStart()->format("Y-m-d") <= $lastdaythisYear) {
                    $nmbSeancesThisYear++;
                }
            }

            $nmbReservationPerDay = [];

            $keyObj = 0;
            for($i = 7; $i > 0; $i--) {
                $dayPrecision = "-".$i." days";
                $datePoint =  date("Y-m-d",strtotime($dayPrecision));
                $nmbReservationPerDay[$keyObj]['date'] = date("d.m.Y",strtotime($dayPrecision));
                $nmbReservationPerDay[$keyObj]['nmrReservations'] = 0;
                foreach ($allReservations as $reservation) {
                    if($reservation->getDateAdd()->format("Y-m-d") == $datePoint) {
                        $nmbReservationPerDay[$keyObj]['nmrReservations']++;
                    }
                }
                $keyObj++;
            }

            return $this->render('user/dashboard.html.twig', [
                'nmbReservationsToday' => $nmbReservationsToday,
                'nmbSeancesThisYear' => $nmbSeancesThisYear,
                'nmbReservationsThisWeek' => $nmbReservationsThisWeek,
                'nmbReservationsThisYear' => $nmbReservationsThisYear,
                'allReservations' => $allReservations,
                'nmbReservationPerDay' => $nmbReservationPerDay,
            ]);
        } else {
            return $this->redirectToRoute('films_list');
        }

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
