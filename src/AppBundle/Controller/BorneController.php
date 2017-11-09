<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Borne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Borne controller.
 *
 * @Route("administration/borne")
 */
class BorneController extends Controller
{
    /**
     * Lists all borne entities.
     *
     * @Route("/", name="administration_borne_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bornes = $em->getRepository('AppBundle:Borne')->findAll();

        return $this->render('borne/index.html.twig', array(
            'bornes' => $bornes,
        ));
    }

    /**
     * Creates a new borne entity.
     *
     * @Route("/new", name="administration_borne_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $borne = new Borne();
        $form = $this->createForm('AppBundle\Form\BorneType', $borne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($borne);
            $em->flush();

            return $this->redirectToRoute('administration_borne_show', array('id' => $borne->getId()));
        }

        return $this->render('borne/new.html.twig', array(
            'borne' => $borne,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a borne entity.
     *
     * @Route("/{id}", name="administration_borne_show")
     * @Method("GET")
     */
    public function showAction(Borne $borne)
    {
        $deleteForm = $this->createDeleteForm($borne);

        return $this->render('borne/show.html.twig', array(
            'borne' => $borne,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing borne entity.
     *
     * @Route("/{id}/edit", name="administration_borne_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Borne $borne)
    {
        $deleteForm = $this->createDeleteForm($borne);
        $editForm = $this->createForm('AppBundle\Form\BorneType', $borne);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_borne_edit', array('id' => $borne->getId()));
        }

        return $this->render('borne/edit.html.twig', array(
            'borne' => $borne,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a borne entity.
     *
     * @Route("/{id}", name="administration_borne_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Borne $borne)
    {
        $form = $this->createDeleteForm($borne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($borne);
            $em->flush();
        }

        return $this->redirectToRoute('administration_borne_index');
    }

    /**
     * Creates a form to delete a borne entity.
     *
     * @param Borne $borne The borne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Borne $borne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('administration_borne_delete', array('id' => $borne->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
