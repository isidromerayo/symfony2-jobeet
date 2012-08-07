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

    }
}