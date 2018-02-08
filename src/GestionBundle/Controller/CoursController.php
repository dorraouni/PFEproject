<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Cours;
use FOS\UserBundle\Event\FormEvent;
use GestionBundle\Entity\FileCours;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Cour controller.
 *
 * @Route("cours")
 */
class CoursController extends Controller
{
    /**
     * Lists all cour entities.
     *
     * @Route("/", name="cours_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cours = $em->getRepository('GestionBundle:Cours')->findAll();

        return $this->render('cours/index.html.twig', array(
            'cours' => $cours,
        ));
    }

    /**
     * Creates a new cour entity.
     *
     * @Route("/new", name="cours_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cours = new Cours();
        $form = $this->createForm('GestionBundle\Form\CoursType', $cours);
        $form->handleRequest($request);

          if (isset($_POST['texte']))
        {
            $cours->setTexte($_POST['texte']);
        }
        
       
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request;

            $event = new FormEvent($form, $request);
                if ($data->get("date")){
                    $date = new \DateTime ($data->get("date"));
                    $cours->setDate($date);
                }else{
                    $cours->setDate(null);
                }
            $em = $this->getDoctrine()->getManager();
            $em->persist($cours);
            $em->flush($cours);
            if (isset($_FILES['input-1']))
            {
                 $ouput_files= "bundles/FilesCours".'/'.$cours->getId();
                 if(!is_dir($ouput_files))
                    {
                        mkdir($ouput_files,0777,true);
                    }

                $tmp_dir = $_FILES['input-1']['tmp_name'];
                $name1 = $_FILES['input-1']['name'];
//                        var_dump($name1);die;
                move_uploaded_file($tmp_dir, $ouput_files.'/'.$name1);
                $cours->setFile($name1);
//                $cours->setTitre( $name1);
                $em->persist($cours);
                $em->flush($cours);
            }
            if(isset($_FILES["input-6"]))
            {     
                $len = count($_FILES['input-6']['name']);
                $ouput_files= "bundles/FilesCours/fichiers".'/'.$cours->getId();
                for($i = 0; $i < $len; $i++) {
                    $fileSize = $_FILES['input-6']['size'][$i];
                    
                    if ($fileSize!=0)
                    {
                        if(!is_dir($ouput_files))
                        {
                             mkdir($ouput_files,0777,true);
                        }

                        $tmp_dir = $_FILES['input-6']['tmp_name'][$i];
                        $name2 = $_FILES['input-6']['name'][$i];
//                        var_dump($name1);die;
                        move_uploaded_file($tmp_dir, $ouput_files.'/'.$name2);
                        $coursFiles = new FileCours();
                        $coursFiles->setCours($cours);
                        $coursFiles->setNom($name2);
                        $em->persist($coursFiles);
                        $em->flush($coursFiles);
                        
                    }
                }
            
            }
          
            return $this->redirectToRoute('cours_show', array('id' => $cours->getId()));
        }

        return $this->render('cours/new.html.twig', array(
            'cours' => $cours,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cour entity.
     *
     * @Route("/{id}", name="cours_show")
     * @Method("GET")
     */

    public function showAction(Cours $cours)
    {
        $deleteForm = $this->createDeleteForm($cours);
        $em = $this->getDoctrine()->getManager();
        $files= $em->getRepository('GestionBundle:FileCours')->findBy(array('cours'=>$cours));
//        var_dump($files);die;
        return $this->render('cours/show.html.twig', array(
            'cours' => $cours,
            'delete_form' => $deleteForm->createView(),
            'files' => $files
        ));
    }

    /**
     * Displays a form to edit an existing cour entity.
     *
     * @Route("/{id}/edit", name="cours_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cours $cours)
    {
        $deleteForm = $this->createDeleteForm($cours);
        $editForm = $this->createForm('GestionBundle\Form\CoursType', $cours);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cours_edit', array('id' => $cours->getId()));
        }

        return $this->render('cours/edit.html.twig', array(
            'cours' => $cours,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cour entity.
     *
     * @Route("/{id}", name="cours_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cours $cours)
    {
        $form = $this->createDeleteForm($cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cours);
            $em->flush();
        }

        return $this->redirectToRoute('cours_index');
    }

    /**
     * Creates a form to delete a cour entity.
     *
     * @param Cours $cour The cour entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cours $cours)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cours_delete', array('id' => $cours->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
}
