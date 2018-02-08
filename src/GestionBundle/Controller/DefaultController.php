<?php

namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $monitors = $this->findByRole("ROLE_MONITEUR");
        return $this->render('GestionBundle:Default:index.html.twig', array(
            'monitors'=>$monitors
        ));
        
         
    }
    
      /**
 * @param string $role
 *
 * @return array
 */
    public function findByRole($role)
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('u')
                ->from("UserBundle:User", 'u')
                ->where('u.roles LIKE :roles')
                ->setParameter('roles', '%"'.$role.'"%');

            return $qb->getQuery()->getResult();
        }
    
    public function topBarAction()
    {   
        $user= $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $plan= $em->getRepository('GestionBundle:Planning')->findBy(array('userDest'=>$user,'statut'=>'en attente'));

        $dest= $em->getRepository('UserBundle:User')->findAll();
        $message = $em->getRepository('GestionBundle:Message')->findBy(array('dest'=>$user,'statut'=>FALSE));
        return $this->render('bar/topbar.html.twig',
                              array( 'user' => $user,
                                    'messages' => $message,
                                    'destinataires' => $dest,
                                    'plannings' => $plan,
                                  ));
        
                                                         
    }
    
    public function leftBarAction()
    {
        
        return $this->render('bar/leftbar.html.twig');
    }
}
