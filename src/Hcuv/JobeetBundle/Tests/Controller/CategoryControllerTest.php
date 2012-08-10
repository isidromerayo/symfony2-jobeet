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
    public function showCategoryProgramming()
    {
        $crawler = $this->client->request('GET','/category/programming');
        // $mensaje = $crawler->filter('div.text_exception h1')->text();
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Programming")')->count());
    }
}
