<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/films/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'NOUVEAUTÉS');
    }

        // Afin d'effectuer un test de login, il faut avoir des données dans la base de données de test
        // possible de créer des fixtures dans le fichier App\DataFixtures\AppFixtures.php
        // et les enregistrer avec la commande :
        // php bin/console --env=test doctrine:fixtures:load

/*      public function testLoginAndSeeReservationPlaces(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $submitButton = $crawler->selectButton('CONNEXION');

        $formConnect =  $submitButton->form();


        // mettre un username & un password existants dans BDD de test:
        $formConnect["_username"] = "username";
        $formConnect["_password"] = "123456";

        $client->submit($formConnect);

        // Verifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier la redirection vers la page de connexion
        $client->followRedirect();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'NOUVEAUTÉS');

        $crawler2 = $client->request('GET', '/films/reservationlist');
        $this->assertSelectorTextContains('h1', 'RÉSERVER UNE SÉANCE');
        $filmShowBtn = $crawler2->filter('a[href="/films/show/3"]')->link();

        $crawler3 = $client->click($filmShowBtn);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Mourir peut attendre (2021)');
        $filmSelectSeanceBtn = $crawler3->filter('a[href="/seances/show/4"]')->link();
        $crawler4 = $client->click($filmSelectSeanceBtn);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.seance-info-format', '2D');
    }*/
}
