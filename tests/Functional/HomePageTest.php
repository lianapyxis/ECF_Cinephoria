<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testPagesCrawl(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/films/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'NOUVEAUTÉS');

        $button = $crawler->selectLink('Films');

        $this->assertEquals(2, $button->count());

        $filmsPage = $client->request('GET', '/films/collection');

        $this->assertResponseIsSuccessful();

        $films = $filmsPage->filter('#collectionFilms tbody');

        $this->assertEquals(1, $films->count());
    }
}
