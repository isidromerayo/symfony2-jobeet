<?php
/**
 * Created by JetBrains PhpStorm.
 * User: imerayo
 * Date: 8/10/12
 * Time: 1:11 PM
 * To change this template use File | Settings | File Templates.
  */
namespace Hcuv\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function showCategoryProgrammingHavePagination()
    {
        $crawler = $this->client->request('GET','/category/programming');
        // $mensaje = $crawler->filter('div.text_exception h1')->text();
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Programming")')->count());
        $this->assertEquals(1, $crawler->filter('div.pagination_desc:contains("page")')->count());
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::indexAction',
            $this->client->getRequest()->attributes->get('_controller'));
        $this->assertTrue($crawler->filter('.jobs td.position:contains("Expired")')->count() == 0);
    }

    /**
     * @test
     */
    public function onlyNJobsAreListedForACategory()
    {
        $crawler = $this->client->request('GET', '/');
        $kernel = static::createKernel();
        $kernel->boot();
        $max_jobs_on_homepage = $kernel->getContainer()->getParameter('max_jobs_on_homepage');
        $this->assertTrue($crawler->filter('.category_programming tr')->count() == $max_jobs_on_homepage);
        $this->assertTrue($crawler->filter('.category_design .more_jobs')->count() == 0);
        $this->assertTrue($crawler->filter('.category_programming .more_jobs')->count() == 1);

    }
}
