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
    /**
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('HcuvJobeetBundle:Category')->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $total_jobs = $em->getRepository('HcuvJobeetBundle:Job')->countActiveJobs($category->getId());
        $jobs_per_page = $this->container->getParameter('max_jobs_on_category');
        $last_page = ceil($total_jobs / $jobs_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;
        $category->setActiveJobs($em->getRepository('HcuvJobeetBundle:Job')->getActiveJobs($category->getId(), $jobs_per_page, ($page - 1) * $jobs_per_page ));
        $format = $this->getRequest()->getRequestFormat();

        return $this->render('HcuvJobeetBundle:Category:show.' . $format . '.twig', array(
            'category' => $category,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total_jobs' => $total_jobs,
            'feedId' => sha1($this->get('router')
                            ->generate('HcuvJobeetBundle_category',
                                    array('slug' =>  $category->getSlug(),
                                        '_format' => 'atom'), true)),
        ));
    }

}
