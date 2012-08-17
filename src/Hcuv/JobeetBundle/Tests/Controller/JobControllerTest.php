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
        $this->assertTrue($this->client->getResponse()->isNotFound(), $crawler->filter('div.text_exception h1')->text());
        $this->assertTrue($crawler->filter('html:contains("Unable to find Job entity.")')->count() == 1);
    }

    /**
     * @test
     * @group testing_forms
     */
    public function jobForm()
    {
        $crawler = $this->client->request('GET', '/job/new');
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::newAction',
                $this->client->getRequest()->attributes->get('_controller'));

        $form = $crawler->selectButton('Preview your job')->form(array(
            'job[company]'      => 'Sensio Labs',
            'job[url]'          => 'http://www.sensio.com/',
            'job[file]'         => __DIR__.'/../../../../../web/bundles/hcuvjobeet/images/sensio-labs.gif',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[description]'  => 'You will work with symfony to develop websites for our customers.',
            'job[how_to_apply]' => 'Send me an email',
            'job[email]'        => 'for.a.job@example.com',
            'job[is_public]'    => false,
        ));

        $this->client->submit($form);
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::createAction',
                $this->client->getRequest()->attributes->get('_controller'));
        $this->client->followRedirect();
        $this->assertEquals('Hcuv\JobeetBundle\Controller\JobController::previewAction',
                $this->client->getRequest()->attributes->get('_controller'));
    }
    /**
     * @test
     * @depends jobForm
     * @group testing_forms
     */
    public function createJobDatabaseColumnIsActivatedIsFalse()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from HcuvJobeetBundle:Job j WHERE j.location = :location AND j.is_activated IS NULL AND j.is_public = 0');
        $query->setParameter('location', 'Atlanta, USA');
        $this->assertTrue(0 < $query->getSingleScalarResult());
    }
    /**
     * @todo
     * @group testing_forms
     * string(40101) "    An exception occurred while executing
     * 'INSERT INTO job (type, company, logo, url, position, location, description, how_to_apply, token, is_public, is_activated, email, expires_at, created_at, updated_at, category_id)
     * VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
     * with params {"1":"full-time","2":"Sensio Labs","3":null,"4":null,"5":"Developer","6":"Atlanta, USA","7":null,"8":null,"9":"db38ee74c7145b48a6c69de29a933a02e5cff6c9","10":0,"11":null,"12":"not.an.email","13":"2012-09-16 14:31:21","14":"
     */
    public function jobFormErrors()
    {
        $crawler = $this->client->request('GET', '/job/new');
        $form = $crawler->selectButton('Preview your job')->form(array(
            'job[company]'      => 'Sensio Labs',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[email]'        => 'not.an.email',
        ));
        $crawler = $this->client->submit($form);

        // var_dump($crawler->filter('html')->text());

        // check if we have 3 errors
        $this->assertTrue($crawler->filter('.error_list')->count() == 3);
        // check if we have error on job_description field
        $this->assertTrue($crawler->filter('#job_description')->siblings()->first()->filter('.error_list')->count() == 1);
        // check if we have error on job_how_to_apply field
        $this->assertTrue($crawler->filter('#job_how_to_apply')->siblings()->first()->filter('.error_list')->count() == 1);
        // check if we have error on job_email field
        $this->assertTrue($crawler->filter('#job_email')->siblings()->first()->filter('.error_list')->count() == 1);

    }

    /**
     * @test
     * @group testing_forms
     */
    public function publishJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO1'));
        $crawler = $client->getCrawler();
        $form = $crawler->selectButton('Publish')->form();
        $client->submit($form);

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from HcuvJobeetBundle:Job j WHERE j.position = :position AND j.is_activated = 1');
        $query->setParameter('position', 'FOO1');
        $this->assertTrue(0 < $query->getSingleScalarResult());
    }
    /**
     * @test
     * @group testing_forms
     */
    public function deleteJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO2'));
        $crawler = $client->getCrawler();
        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from HcuvJobeetBundle:Job j WHERE j.position = :position');
        $query->setParameter('position', 'FOO2');
        $this->assertTrue(0 == $query->getSingleScalarResult());
    }

    /**
     * @test
     * @group testing_forms
     */
    public function editJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO3'), true);
        $crawler = $client->getCrawler();
        $crawler = $client->request('GET', sprintf('/job/%s/edit', $this->getJobByPosition('FOO3')->getToken()));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
    }

    /**
     * Aux create job
     *
     * @param array $values
     * @param bool $publish
     *
     * @return mixed
     */
    public function createJob($values = array(), $publish = false)
    {
        $crawler = $this->client->request('GET', '/job/new');
        $form = $crawler->selectButton('Preview your job')->form(array_merge(array(
            'job[company]'      => 'Sensio Labs',
            'job[url]'          => 'http://www.sensio.com/',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[description]'  => 'You will work with symfony to develop websites for our customers.',
            'job[how_to_apply]' => 'Send me an email',
            'job[email]'        => 'for.a.job@example.com',
            'job[is_public]'    => false,
        ), $values));

        $this->client->submit($form);
        $this->client->followRedirect();

        if($publish) {
            $crawler = $this->client->getCrawler();
            $form = $crawler->selectButton('Publish')->form();
            $this->client->submit($form);
            $this->client->followRedirect();
        }

        return $this->client;
    }
    /**
     * Aux get Job by position
     *
     * @param $position
     * @return mixed
     */
    public function getJobByPosition($position)
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT j from HcuvJobeetBundle:Job j WHERE j.position = :position');
        $query->setParameter('position', $position);
        $query->setMaxResults(1);
        return $query->getSingleResult();
    }
}