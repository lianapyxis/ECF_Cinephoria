<?php

namespace App\Film\Controller\Admin;

use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/films', name: 'admin_films_')]
class AdminFilmController extends AbstractController
{
    #[Route('/validate/{id}', name: 'validate')]
    public function list(EntityManagerInterface $em, Film $film = null): RedirectResponse
    {
        $em->persist($film);
        $em->flush();

        return $this->redirectToRoute('films_show', ['id' => $film->getId()]);
    }
}