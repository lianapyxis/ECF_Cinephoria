<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    public function testCreateAccount(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'CRÉATION DE COMPTE');

        // Récupérer le formulaire

        $submitButton = $crawler->selectButton('Enregistrer');

        $form =  $submitButton->form();

        $form["user[firstname]"] = "Jean";
        $form["user[lastname]"] = "Dupont";
        $form["user[username]"] = "jean_dupont";
        $form["user[email]"] = "jean_dupont@mail.com";
        $form["user[password]"] = "123456";

        // Soumettre le formulaire
        $client->submit($form);

        // Verifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier la redirection vers la page de connexion
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'CONNEXION');
    }


    //

}
