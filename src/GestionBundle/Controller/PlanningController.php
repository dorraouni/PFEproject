<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Planning;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RMS\PushNotificationsBundle\Message\AndroidMessage;

/**
 * Planning controller.
 *
 * @Route("planning")
 */
class PlanningController extends Controller
{
    /**
     * Lists all planning entities.
     *
     * @Route("/", name="planning_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $plannings = $em->getRepository('GestionBundle:Planning')->findAll();

        return $this->render('planning/index.html.twig', array(
            'plannings' => $plannings,
        ));
    }

    /**
     * Creates a new planning entity.
     *
     * @Route("/new", name="planning_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
//        var_dump($user); die;
        $planning = new Planning();

//        $userDest = $this->getUserDest();

//        $plannings= $em->getRepository('GestionBundle:Planning')->findBy(array('userDest'=>$user));
        
        $plan= $em->getRepository('GestionBundle:Planning')->findByUser($user);
//        $planDest= $em->getRepository('GestionBundle:Planning')->findBy(array('userDest'=>$userDest));
//        $plan= $em->getRepository('GestionBundle:Planning')->findAll();
        
        $form = $this->createForm('GestionBundle\Form\PlanningType', $planning);
        $form->handleRequest($request);
        $dest = array();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_MONITEUR')){
            $dest = $em->getRepository('UserBundle:User')->findBy(array('moniteur'=>$user));
        }else if ($this->get('security.authorization_checker')->isGranted('ROLE_CANDIDAT')){
            array_push($dest, $user->getMoniteur());
        }
//        var_dump($dest);die;
         if (isset($_POST['userDest']))
        {
            $userDest = $em->getRepository('UserBundle:User')->find($_POST['userDest']);

            $planning->setUserDest($userDest);
        }
        
        if (isset($_POST['séance']))
        {
            $planning->setSeance($_POST['séance']);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $planning->setUser($user);
            
            $start = new \DateTime($_POST['startDate']);
            $end = new \DateTime($_POST['endDate']);
            $planning->setStartDate($start);
            $planning->setEndDate($end);
            $planning->setStatut("en attente");
            $em->persist($planning);
            $em->flush($planning);
//            $response=new response();
//            $response->setContent($planning->getId());
//            return $response;
            
            return $this->redirectToRoute('planning_new');
        }

        return $this->render('planning/new.html.twig', array(
            'planning' => $planning,
            'form' => $form->createView(),
            'user' => $user,
            'destinataires' => $dest,
            'plannings' => $plan,
//            'plannings' =>$planDest,
        ));
    }

    /**
     * Finds and displays a planning entity.
     *
     * @Route("/{id}", name="planning_show")
     * @Method("GET")
     */
    public function showAction(Planning $planning)
    {
        $deleteForm = $this->createDeleteForm($planning);

        return $this->render('planning/show.html.twig', array(
            'planning' => $planning,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing planning entity.
     *
     * @Route("/{id}/edit", name="planning_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Planning $planning)
    {
        $deleteForm = $this->createDeleteForm($planning);
        $editForm = $this->createForm('GestionBundle\Form\PlanningType', $planning);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('planning_edit', array('id' => $planning->getId()));
        }

        return $this->render('planning/edit.html.twig', array(
            'planning' => $planning,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a planning entity.
     *
     * @Route("/{id}", name="planning_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Planning $planning)
    {
        $form = $this->createDeleteForm($planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planning);
            $em->flush();
        }

        return $this->redirectToRoute('planning_index');
    }
    
   
    /**
     * Creates a form to delete a planning entity.
     *
     * @param Planning $planning The planning entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Planning $planning)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planning_delete', array('id' => $planning->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * refuser un planning.
     *
     * @Route("/{id}/refuser_planning", name="planning_refuse")
     
     */
    public function refuseAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();

        $planning = $em->getRepository('GestionBundle:Planning')->find($id);
        $planning->setStatut("refusé");
        $em->persist($planning);
        $em->flush();
        $response= new Response();
        $response->setContent("OK");
        return $response;
        
        $message = new AndroidMessage();
        $message->setGCM(true);
        $message->setMessage('Oh my! A push notification!');
        $message->setDeviceIdentifier('test012fasdf482asdfd63f6d7bc6d4293aedd5fb448fe505eb4asdfef8595a7');

        $this->container->get('rms_push_notifications')->send($message);

        return new Response('Push notification send!');
    }

    /**
     * accepter un planning.
     *
     * @Route("/{id}/accepter_planning", name="planning_accept")
     */
    public function acceptAction($id)
    {
         $em = $this->getDoctrine()->getManager();

        $planning = $em->getRepository('GestionBundle:Planning')->find($id);
        $planning->setStatut("accepté");
        $em->persist($planning);
        $em->flush();
        $response= new Response();
        $response->setContent("OK");
        return $response;
        
        $message = new AndroidMessage();
        $message->setGCM(true);
        $message->setMessage('Oh my! A push notification!');
        $message->setDeviceIdentifier('test012fasdf482asdfd63f6d7bc6d4293aedd5fb448fe505eb4asdfef8595a7');

        $this->container->get('rms_push_notifications')->send($message);

        return new Response('Push notification send!');
    }

}
