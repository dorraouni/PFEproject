<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace GestionBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GestionBundle\Entity\Planning;
/**
 * ws controller.
 *
 * @Route("WS")
 */
class WSController extends Controller{
     /**
     * Login
     *
     * @Route("/login", name="WS_login")
     * @Method({"GET", "POST"})
     */
     public function loginAction(){
         
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $username = $request->username;
        $password = $request->password;
        
        $array = array();
        $em = $this->getDoctrine()->getManager();
        $array['result'] = 0;     
        $array['iduser'] = 0;
        $encoder = new BCryptPasswordEncoder(13);

        $user = $em->getRepository('UserBundle:User')->findOneBy(array('username'=>$username));
        if ($user){
            $pass= $user->getPassword();

            if ( $encoder->isPasswordValid($pass, $password, null)) {
                $array['result'] = 1;   
                $array['iduser'] = $user->getId();
            }
        }
        $response = new Response();
        $response->setContent(json_encode($array));
        return $response;
    }
    
      /**
     * show profile
     *
     * @Route("/Profile", name="WS_profile")
     * @Method({"POST"})
     */
   public function showProfileAction(){
       
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
     
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->find($id);

        
        $array= array();
            if ($user){
                $array['id'] = $user->getId();
                $array['nom'] = $user->getNom();
                $array['prenom'] = $user->getPrenom();
                $array['telephone'] = $user->getTelephone();
                $array['photo'] = $user->getPhoto();
                $array['email'] = $user->getEmail();
                $array['adresse'] = $user->getAdresse();
               
            }
        $response = new Response();
        $response->setContent(json_encode($array));
        return $response;
   }
     
     /**
     * List cours
     *
     * @Route("/Cours", name="WS_list_cours")
     * @Method({"GET"})
     */
   public function showListCoursAction(){
       
        $em = $this->getDoctrine()->getManager();

        $cours = $em->getRepository('GestionBundle:Cours')->findAll();
        $list = array();
        $array = array();
        foreach ($cours as $value) {
            $array['name'] = $value->getTitre();
            $array['id'] = $value->getId();
            $array['about'] = $value->getTexte();
            $array['profilePic'] =  "http://localhost/AutoEcole/web/bundles/FilesCours/".$value->getId()."/".$value->getFile();
//            $array['profilePic'] = "assets/img/speakers/puppy.jpg";

            
           array_push($list,$array);
        }
        $response = new Response();
        $response->setContent(json_encode($list));
//        var_dump(json_encode($list));die;
        return $response;
   }
   
    /**
     * Detail cours
     *
     * @Route("/Cours/detail", name="WS_detail_cours")
     * @Method({"GET", "POST"})
     */
   public function showDetailCoursAction(){
       
        $em = $this->getDoctrine()->getManager();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
        
        $cour = $em->getRepository('GestionBundle:FileCours')->findBy(array('cours'=>$id));
        $list = array();
        $array = array();
      
        foreach ($cour as $value) {
            $array['profilePic'] =  "http://localhost/AutoEcole/web/bundles/FilesCours/fichiers/".$value->getCours()->getId()."/".$value->getNom();
//            $array['profilePic'] = "assets/img/speakers/puppy.jpg";
           array_push($list,$array);
        }
       
        $response = new Response();
        $response->setContent(json_encode($list));
        return $response;
   }
   
   
    /**
     * new planning
     *
     * @Route("/Planning/new", name="WS_new_planning")
     * @Method({"GET", "POST"})
     */
   public function addPlanningAction(){
       
        $em = $this->getDoctrine()->getManager();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
        $datedebut = $request->datedebut;
        $datefin = $request->datefin;
//        $idCandidat = $request->idCandidat;
        $user = $this->getUser();
        $planning = new Planning();
        $planning->setStartDate(new \DateTime ($datedebut));
        $planning->setEndDate(new \DateTime ($datefin));
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CANDIDAT')){
            $planning->setUser($user);
            $planning->setUserDest($user->getMoniteur());
        } else  if ($this->get('security.authorization_checker')->isGranted('ROLE_MONITEUR')){ 
             $can = $em->getRepository('UserBundle:User')->findBy(array('id'=>$id));
             $planning->setUserDest($can->getId());
            
        }
        $em->persist($planning);
        $em->flush();

   }
   
   /**
     * show planning
     *
     * @Route("/Planning", name="WS_show_planning")
     * @Method({"POST"})
     */
   public function showPlanningAction(){
       
        $em = $this->getDoctrine()->getManager();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
        
        $plan = $em->getRepository('GestionBundle:Planning')->findBy(array('userDest'=>$id));
        $list = array();
        $array = array();
        foreach ( $plan as $value ) {
            $array['id'] = $value->getId();
            $array['titre'] = $value->getTitre();
            $array['statut'] = $value->getStatut();
            $array['startDate'] = $value->getStartDate()->format('d-m-Y H:i:s');
            $array['endDate'] = $value->getEndDate()->format('d-m-Y H:i:s');
            $array['userDest'] = $value->getUserDest();
            $array['séance'] = $value->getSeance();

            
           array_push($list,$array);
        }
        
        $response = new Response();
        $response->setContent(json_encode($list));
        return $response;
      
   }
   
   
    /**
     * list message
     *
     * @Route("/Message", name="WS_List_message")
     * @Method({"GET", "POST"})
     */
   public function MessageAction()
    {
   
        $em = $this->getDoctrine()->getManager();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
        $user = $this->getUser();

//        $messages = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));
        $messagesReçus = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>false));
        $messagesEnvoyés = $em->getRepository('GestionBundle:Message')->findBy(array('exp'=>$user));
        $list = array();
        $array = array();
       foreach ($messagesReçus as $value) {
           
            $array['exp'] = $value->getExp();
            $array['objet'] = $value->getObjet();
            $array['date'] = $value->getDate();
            

            
           array_push($list,$array);
        }
         foreach ($messagesEnvoyés as $value) {
            $array['objet'] = $value->getObjet();
            $array['contenu'] = $value->getContenu();
            $array['date'] = $value->getDate();
            

           array_push($list,$array);
        }
        $response = new Response();
        $response->setContent(json_encode($list));
        return $response;
}
}


