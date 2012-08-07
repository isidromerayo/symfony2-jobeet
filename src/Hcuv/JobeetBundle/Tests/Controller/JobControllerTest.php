<?php

namespace Hcuv\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function homeJobHasJobs()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $crawler = $client->request('GET', '/job/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        $this->assertTrue($crawler->filter('table.jobs')->count() > 0);
    }
    /**
     * @test
     */
    public function showJobFromHomePage()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $crawler = $client->request('GET', '/job/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('td.position')->count() > 0);
        // TODO better dom expression
        $link = $crawler->selectLink('Web Designer')->link();
        $crawler = $client->click($link);

        $this->assertTrue(200 === $client->getResponse()->getStatusCode(), 'Don\'t load detail job');
        $this->assertTrue($crawler->filter('h1:contains("Job")')->count() == 1);
    }
}