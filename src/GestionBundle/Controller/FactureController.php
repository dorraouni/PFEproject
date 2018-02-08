<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Facture controller.
 *
 * @Route("facture")
 */
class FactureController extends Controller
{
    /**
     * Lists all facture entities.
     *
     * @Route("/", name="facture_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('GestionBundle:Facture')->findAll();
        $paginator = $this->get('knp_paginator');
        $queryFactures =$paginator->paginate(
                $factures,
                $request->query->getInt('pagename1', 1),/*page number*/
                $request->query->getInt('limit', 10),
                array('pageParameterName' => 'pagename1')
                
        );
        
        return $this->render('facture/index.html.twig', array(
            'factures' => $factures,
            'factures' => $queryFactures
        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}", name="facture_generate")
     * @Method("GET")
     */
   
    
    public function generatePdfAction($id)
    {
       // initialize the $emp variable
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id); 
        $now = new \DateTime();
        $newdate = $now->format('d-m-Y');
        $statut= 'accepté';
        $seance='conduite';
        
        $planConduite = $em->createQuery('Select p from GestionBundle:Planning p where (p.userDest =:userDest or p.user =:user) and p.statut like :statut and  p.seance like :seance')
                ->setParameters(array('userDest'=>$user,'user'=>$user,'statut'=>$statut,'seance'=>$seance))
                ->getResult();
        
        $seance='Code de la route';
        $planCodeRoute = $em->createQuery('Select p from GestionBundle:Planning p where (p.userDest =:userDest or p.user =:user) and p.statut like :statut and  p.seance like :seance')
                ->setParameters(array('userDest'=>$user,'user'=>$user,'statut'=>$statut,'seance'=>$seance))
                ->getResult();
                       
        $montantConduite = count($planConduite)*20;
        $montantCodeRoute = count($planCodeRoute)*12;
        
        $total = $montantCodeRoute + $montantConduite;
        $html = $this->renderView('facture/facture.html.twig',array(
                'candidat'=> $user,
                'now' =>   $now,
                'numberofconduitehours' =>  count($planConduite),
                'numberofcodehours' =>  count($planCodeRoute),
                'total' =>  $total,
                'totalConduite'=>$montantConduite,
                'totalCodeRoute'=>$montantCodeRoute,
                )
        );
        
        $outputfiles = 'factures/'.$id;
        $this->get('knp_snappy.pdf')->generateFromHtml(
                $html,
            $outputfiles.'/facture.pdf'
            );
        
        $facture = new Facture();
        
        
        $facture->setDate($now);
        $facture->setMontant($total);
        $facture->setClient($user);
        $facture->setReference($id);
        $em->persist($facture);
        $em->flush();
      
        
        return $this->redirectToRoute('facture_index');
    }
    
    
}
