<?php

namespace Hcuv\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function rootUrlHomeJobs()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $this->assertTrue($crawler->filter('table.jobs')->count() > 0);
    }

    /**
     * @test
     */
    public function homeJobHasJobs()
    {
        $crawler = $this->client->request('GET', '/job/');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $this->assertTrue($crawler->filter('table.jobs')->count() > 0);
    }

    /**
     * @test
     */
    public function showJobFromHomePage()
    {
        $crawler = $this->client->request('GET', '/job/');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('td.position')->count() > 0);
        // TODO better dom expression
        $link = $crawler->selectLink('Web Designer')->link();
        $crawler = $this->client->click($link);

        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode(), 'Don\'t load detail job');
        $this->assertTrue($crawler->filter('h3:contains("Web Designer")')->count() == 1);
    }
    /**
     * @test
     */
    public function showJobNotExistServerResponse404()
    {
        $crawler = $this->client->request('GET', '/job/sensio-labs/paris-france/0/web-developer0/');
        $this->assertTrue(404 === $this->client->getResponse()->getStatusCode());
        // $this->assertTrue($crawler->filter('html:contains("Unable to find Job entity.")')->count() == 1);
    }

    /**
     * @test
     */
    public function showJobExpiredForwared404Page()
    {
        $crawler = $this->client->request('GET', '/job/sensio-labs/paris-france/3/web-developer-expired');
        // Assert the response status code.
        $this->assertTrue($this->client->getResponse()->isNotFound(),$crawler->filter('div.text_exception h1')->text());
        $this->assertTrue($crawler->filter('html:contains("Unable to find Job entity.")')->count() == 1);
    }
}