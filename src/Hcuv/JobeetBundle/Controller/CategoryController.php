<?php

namespace Hcuv\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Hcuv\JobeetBundle\Entity\Category;
/**
 * Category controller
 *
 * User: imerayo
 * Date: 8/9/12
 * Time: 1:22 PM
 * To change this template use File | Settings | File Templates.
 */
class CategoryController extends Controller
{
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category = $em->getRepository('HcuvJobeetBundle:Category')->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $category->setActiveJobs($em->getRepository('HcuvJobeetBundle:Job')->getActiveJobs($category->getId()));

        return $this->render('HcuvJobeetBundle:Category:show.html.twig', array(
            'category' => $category,
        ));
    }

}
