<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePageLoadsSuccessfully(): void
    {
        // Simule un navigateur
        $client = static::createClient();
        // Effectue une requête GET sur la route home
        $crawler = $client->request('GET', '/');
        //Vérifie que la page répond avec un code 200 -> page bien chargé
        $this->assertResponseIsSuccessful();
        //Vérifie que le template contient bien un <h1>
        $this->assertSelectorExists('h1');
        // Vérifie la présence d’une card-artiste
        $this->assertSelectorExists('.card-artiste');
    }
}
