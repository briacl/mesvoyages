<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author blemeill
 */
class VoyagesControllerTest extends WebTestCase{
    
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
    public function testContenuPage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/voyages');
        $this->assertSelectorTextContains('h1', 'Mes voyages');
        $this->assertSelectorTextContains('th', 'Ville');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Chamonix');
    }
    
    public function testLinkVille() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        // clic sur un lien (nom d'une ville)
        $client->clickLink('Chamonix');
        // récupération du résultat du clic
        $response = $client->getResponse();
        //dd($client->getRequest());
        // contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et controle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/voyage/102', $uri);
    }
    
    public function testFiltreVille() {
        
        $client = static::createClient();
        $client->request('GET', '/voyages');
        // simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Saint-Gervais'
        ]);
        var_dump($crawler->html());

        // vérifie le nombre de lignes obtenues
        $this->assertCount(2, $crawler->filter('h5'));
        // vérifie si la ville correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Saint-Gervais');
    }
}
