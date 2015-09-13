<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAll();
        return $this->render('AppBundle:Default:index.html.twig', array('projects' => $projects));
    }

    /**
     * @Route("/view/{id}", name="view_album")
     */
    public function viewAction($id)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($id);
        $contents = $this->getDoctrine()->getRepository('AppBundle:Content')->findByProject($project);

        return $this->render('AppBundle:Default:list.html.twig', array(
            'project' => $project,
            'contents' => $contents
        ));
    }

}
