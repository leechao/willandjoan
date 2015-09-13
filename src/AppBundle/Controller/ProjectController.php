<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 04/09/2015
 * Time: 00:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/add", name="add_project")
     */
    public function createProjectAction(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(new ProjectType(), $project);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('view_project', array('id' => $project->getId())));
        }
        return $this->render('AppBundle:Project:add_project.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/project/view/{id}", name="view_project")
     */
    public function viewAction($id)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($id);
        $contents = $this->getDoctrine()->getRepository('AppBundle:Content')->findByProject($project);

        return $this->render('AppBundle:Project:view.html.twig', array(
            'contents' => $contents,
            'project' => $project
        ));
    }

    /**
     * @Route("/project", name="list_project")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAll();
        return $this->render('AppBundle:Project:list.html.twig', array(
            'projects' => $projects
        ));

    }
    /**
     * @Route("/project/remove/{id}", name="remove_project")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction($id)
    {
        $project = $this->getDoctrine()->getRepository("AppBundle:Project")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        return $this->redirect($this->generateUrl('list_project'));
    }
}