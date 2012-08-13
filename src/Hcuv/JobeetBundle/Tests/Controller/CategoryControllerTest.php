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
        // get the custom parameters from app config.yml
        $kernel = static::createKernel();
        $kernel->boot();
        $max_jobs_on_homepage = $kernel->getContainer()->getParameter('max_jobs_on_homepage');
        $max_jobs_on_category = $kernel->getContainer()->getParameter('max_jobs_on_category');

        $crawler = $this->client->request('GET', '/');
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::indexAction', $this->client->getRequest()->attributes->get('_controller'));

        // expired jobs are not listed
        $this->assertTrue($crawler->filter('.jobs td.position:contains("Expired")')->count() == 0);

        // only $max_jobs_on_homepage jobs are listed for a category
        $this->assertTrue($crawler->filter('.category_programming tr')->count() > 0);
        $this->assertTrue($crawler->filter('.category_design .more_jobs')->count() == 0);
        $this->assertTrue($crawler->filter('.category_programming .more_jobs')->count() == 1);

        // jobs are sorted by date
        $this->assertTrue($crawler->filter('.category_programming tr')->first()->filter(sprintf('a[href*="/%d/"]', $this->getMostRecentProgrammingJob()->getId()))->count() == 1);

        // each job on the homepage is clickable and give detailed information
        $job = $this->getMostRecentProgrammingJob();
        $link = $crawler->selectLink('Web Developer')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::showAction', $this->client->getRequest()->attributes->get('_controller'));
        $this->assertEquals($job->getCompanySlug(), $this->client->getRequest()->attributes->get('company'));
        $this->assertEquals($job->getLocationSlug(), $this->client->getRequest()->attributes->get('location'));
        $this->assertEquals($job->getPositionSlug(), $this->client->getRequest()->attributes->get('position'));
        $this->assertEquals($job->getId(), $this->client->getRequest()->attributes->get('id'));

        // a non-existent job forwards the user to a 404
        $crawler = $this->client->request('GET', '/job/foo-inc/milano-italy/0/painter');
        $this->assertTrue(404 === $this->client->getResponse()->getStatusCode());

        // an expired job page forwards the user to a 404
        $crawler = $this->client->request('GET', sprintf('/job/sensio-labs/paris-france/%d/web-developer', $this->getExpiredJob()->getId()));
        $this->assertTrue(404 === $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        // get the custom parameters from app config.yml
        $kernel = static::createKernel();
        $kernel->boot();
        $max_jobs_on_homepage = $kernel->getContainer()->getParameter('max_jobs_on_homepage');
        $max_jobs_on_category = $kernel->getContainer()->getParameter('max_jobs_on_category');

        $this->client = static::createClient();

        // categories on homepage are clickable
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Programming')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals('Hcuv\JobeetBundle\Controller\CategoryController::showAction', $this->client->getRequest()->attributes->get('_controller'));
        $this->assertEquals('programming', $this->client->getRequest()->attributes->get('slug'));

        // categories with more than $max_jobs_on_homepage jobs also have a "more" link
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('21')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals('Hcuv\JobeetBundle\Controller\CategoryController::showAction', $this->client->getRequest()->attributes->get('_controller'));
        $this->assertEquals('programming', $this->client->getRequest()->attributes->get('slug'));

        // only $max_jobs_on_category jobs are listed
        $this->assertTrue($crawler->filter('.jobs tr')->count() > 0, $crawler->filter('.jobs tr')->text());
        $this->assertRegExp('/31 jobs/', $crawler->filter('.pagination_desc')->text());
        $this->assertRegExp('/page 1\/2/', $crawler->filter('.pagination_desc')->text());

        $link = $crawler->selectLink('2')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(2, $this->client->getRequest()->attributes->get('page'));
        $this->assertRegExp('/page 2\/2/', $crawler->filter('.pagination_desc')->text());
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
    /**
     * @test
     */
    public function jobsAreSortedByDate()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($crawler->filter('.category_programming tr')->first()->filter(sprintf('a[href*="/%d/"]', $this->getMostRecentProgrammingJob()->getId()))->count() == 1);
    }
    /**
     * @test
     */
    public function eachJobOnTheHomePageIsClickable()
    {
        $crawler = $this->client->request('GET', '/');
        $job = $this->getMostRecentProgrammingJob();
        $link = $crawler->selectLink('Web Developer')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::showAction',
            $this->client->getRequest()->attributes->get('_controller'));
        $this->assertEquals($job->getCompanySlug(),
            $this->client->getRequest()->attributes->get('company'));
        $this->assertEquals($job->getLocationSlug(),
            $this->client->getRequest()->attributes->get('location'));
        $this->assertEquals($job->getPositionSlug(),
            $this->client->getRequest()->attributes->get('position'));
        $this->assertEquals($job->getId(),
            $this->client->getRequest()->attributes->get('id'));
    }
    public function getMostRecentProgrammingJob()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT j from HcuvJobeetBundle:Job j LEFT JOIN j.category c WHERE c.slug = :slug AND j.expires_at > :date ORDER BY j.created_at DESC');
        $query->setParameter('slug', 'programming');
        $query->setParameter('date', date('Y-m-d H:i:s', time()));
        $query->setMaxResults(1);
        return $query->getSingleResult();
    }
    public function getExpiredJob()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT j from HcuvJobeetBundle:Job j WHERE j.expires_at < :date');     $query->setParameter('date', date('Y-m-d H:i:s', time()));
        $query->setMaxResults(1);
        return $query->getSingleResult();
    }
}
