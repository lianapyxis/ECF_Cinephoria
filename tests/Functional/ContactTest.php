<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testContactForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'CONTACTEZ-NOUS');

        // Récupérer le formulaire

        $submitButton = $crawler->selectButton('Envoyer');

        $form = $submitButton->form();

        $form["contact[firstname]"] = "Jean";
        $form["contact[lastname]"] = "Dupont";
        $form["contact[object]"] = "Test objet";
        $form["contact[email]"] = "jean_dupont@mail.com";
        $form["contact[message]"] = "Test message";

        // Soumettre le formulaire
        $client->submit($form);

        // Verifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier l'envoie du mail
        $this->assertEmailCount(1);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'Votre message a été envoyé.');

    }
}
