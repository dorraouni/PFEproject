<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListUserController extends Controller
{
    
    /**
     * Afficher les utilisateurs
     * @Route("/listUser", name="user_list")
     * @Method("GET")

     * @Template()
     */
    public function listUserAction(Request $request)
    {
        
        $monitors = $this->findByRole("ROLE_MONITEUR");
        $candidats = $this->findByRole("ROLE_CANDIDAT");
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $queryMoniteurs =$paginator->paginate(
                $monitors,
                $request->query->getInt('pagename1', 1),/*page number*/
                $request->query->getInt('limit', 5),
                array('pageParameterName' => 'pagename1')
                
        );
        
         $queryCandidats =$paginator->paginate(
                $candidats,
                $request->query->getInt('pagename1', 1),/*page number*/
                $request->query->getInt('limit', 5),
                array('pageParameterName' => 'pagename1')
                
        );
        
        return $this->render('User/listUser.html.twig', array(
            'monitors' => $queryMoniteurs,
            'candidats'=>$queryCandidats
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

        
    /**
    * @param User $entity
    *
    * @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="delete_route_name")
    * @return RedirectResponse
    *
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user_list'));
    }
    
    
    
    /**
     * Finds and displays a message entity.
     *
     * @Route("/details/{id}", name="details_route_name")
     * @Method("GET")
     */
    
    
    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        
        return $this->render('User/details.html.twig',["user"=>$user]);

    }
}
