<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 04/09/2015
 * Time: 00:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use AppBundle\Entity\Video;
use AppBundle\Form\ProjectType;
use AppBundle\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ImageType;
use AppBundle\Entity\Content;
class ContentController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/photo/{id_project}/add", name="add_photo")
     */
    public function createImageAction(Request $request, $id_project)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($id_project);
        $order = $this->getDoctrine()->getRepository('AppBundle:Content')->getNewOrder($project);
        $image = new Image();
        $image->setProjectId($project->getId());
        $content = new Content();
        $content->setProject($project);

        $form = $this->createForm(new ImageType(), $image);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $content->setImage($image);
            if(is_null($order)){
                $content->setOrder(1);
            } else {
                $newOrder = $order[1]+1;
                $content->setOrder($newOrder);
            }
            $em->persist($image);
            $em->persist($content);
            $em->flush();

            return $this->redirect($this->generateUrl('view_project', array('id' => $project->getId())));
        }
        return $this->render('AppBundle:Content:add_image.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/video/{id_project}/add", name="add_video")
     */
    public function createVideoAction(Request $request, $id_project)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($id_project);
        $order = $this->getDoctrine()->getRepository('AppBundle:Content')->getNewOrder($project);
        $video = new Video();
        $content = new Content();
        $content->setProject($project);

        $form = $this->createForm(new VideoType(), $video);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $content->setVideo($video);
            if(is_null($order)){
//                $request->getSession()->getFlashBag()->add('info', 'Vous ne pouvez pas ajouter de vidéo en couverture');
//                $content->setOrder(1);
                return $this->redirect('view_project', array('id' => $id_project));
            } else {
                $newOrder = $order[1]+1;
                $content->setOrder($newOrder);
            }
            $em->persist($video);
            $em->persist($content);
            $em->flush();

            return $this->redirect($this->generateUrl('view_project', array('id' => $project->getId())));
        }
        return $this->render('AppBundle:Content:add_video.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/project/photo/{id_project}/remove/{id_content}", name="remove_content")
     */
    public function removeAction(Request $request, $id_project, $id_content)
    {
        $content = $this->getDoctrine()->getRepository("AppBundle:Content")->find($id_content);
        $em = $this->getDoctrine()->getManager();
        $em->remove($content);
        $em->flush();
        return $this->redirect($this->generateUrl('view_project', array('id' => $id_project)));
    }
}