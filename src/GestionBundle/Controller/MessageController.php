<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Message;
use GestionBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Message controller.
 *
 * @Route("message")
 */
class MessageController extends Controller
{
    /**
     * Lists all message entities.
     *
     * @Route("/", name="message_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();


        $dest= $em->getRepository('UserBundle:User')->findAll();
        $messages = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user));
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));

        return $this->render('message/index.html.twig', array(
            'messages' => $messages,
            'user' => $user,
            'messagesReçus' => $messagesReçus,
            'messagesEnvoyés' => $messagesEnvoyés,
            'destinataires' => $dest,

        ));
    }

    /**
     * Creates a new message entity.
     *
     * @Route("/new", name="message_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
                   
//        if ($this->isGranted('ROLE_ADMIN'))
        $em = $this->getDoctrine()->getManager();

        $dest= $em->getRepository('UserBundle:User')->findAll();

        
        $user = $this->getUser();
        $message = new Message();
        $form = $this->createForm('GestionBundle\Form\MessageType', $message);
        $form->handleRequest($request);
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));

                
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request;
//            $dest=$data->get("dest");
//                var_dump($data);die;
            if (($data->get("dest")))
            {
                $destinataire=$data->get("dest");
                $destination=$em->getRepository('UserBundle:User')->find($destinataire);
                $message->setDest($destination);
            }
            $date= new \DateTime();
            $message->setDate($date);
            $message->setStatut(false);
            $message->setExp($user);
            
            $em->persist($message);
            $em->flush($message);
//            $files=$em->getRepository('UserBundle:User')->find($id); 
            
            if(isset($_FILES["files"]))
            {     
                $len = count($_FILES['files']['name']);
                $ouput_files= "bundles/FilesMessages".'/'.$message->getId();
                for($i = 0; $i < $len; $i++) {
                    $fileSize = $_FILES['files']['size'][$i];
                    
                    if ($fileSize!=0)
                    {
                        if(!is_dir($ouput_files))
                        {
                             mkdir($ouput_files,0777,true);
                        }

                        $tmp_dir = $_FILES['files']['tmp_name'][$i];
                        $name1 = $_FILES['files']['name'][$i];
//                        var_dump($name1);die;
                        move_uploaded_file($tmp_dir, $ouput_files.'/'.$name1);
                        $messagefile = new File();
                        $messagefile ->setNom($name1);
                        $messagefile ->setMessage($message);
                        $em->persist($messagefile);
                        $em->flush();
                    }
                }
            
            }

            return $this->redirectToRoute('message_envoie');
        }

        return $this->render('message/new.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
            'user' => $user,
            'destinataires' => $dest,
            'messagesReçus' => $messagesReçus,
            'messagesEnvoyés' => $messagesEnvoyés,
        ));
    }

    /**
     * Finds and displays a message entity.
     *
     * @Route("/{id}", name="message_show",requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);
        $em = $this->getDoctrine()->getManager();

        $file= $em->getRepository('GestionBundle:File')->findBy(array('message'=>$message));
        return $this->render('message/show.html.twig', array(
            'message' => $message,
            'delete_form' => $deleteForm->createView(),
            'files' => $file

        ));
    }

    /**
     * Displays a form to edit an existing message entity.
     *
     * @Route("/{id}/edit", name="message_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);
        $editForm = $this->createForm('GestionBundle\Form\MessageType', $message);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_edit', array('id' => $message->getId()));
        }

        return $this->render('message/edit.html.twig', array(
            'message' => $message,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a message entity.
     *
     * @Route("/{id}", name="message_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Message $message)
    {
        $form = $this->createDeleteForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('message_index');
    }

    /**
     * Creates a form to delete a message entity.
     *
     * @param Message $message The message entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('message_delete', array('id' => $message->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Afficher les messages reçues
     * @Route("/inbox", name="inbox")
     * @Method("GET")

     * @Template()
     */
    
    public function inboxAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));
         return $this->render('message/inbox.html.twig',array(
             'messagesReçus' => $messagesReçus,
             'messagesEnvoyés' =>$messagesEnvoyés
         ));
    }
    
    
    /**
     * Lists all message entities.
     *
     * @Route("/envoie", name="message_envoie")
     * @Method("GET")
     */
    public function envoieAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));

        return $this->render('message/envoie.html.twig', array(
            'messages' => $messages,
            'user' => $user,
            'messagesReçus' => $messagesReçus,
            'messagesEnvoyés' => $messagesEnvoyés
        ));
    }
    
    /**
     * 
     *
     * @Route("/topbar", name="top_bar")
     * @Method("GET")
     */
public function BarAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));

        return $this->render('bar/topbar.html.twig', array(
            'messages' => $messages,
            'user' => $user,
            'messagesReçus' => $messagesReçus,
            'messagesEnvoyés' => $messagesEnvoyés
        ));
    }
}
