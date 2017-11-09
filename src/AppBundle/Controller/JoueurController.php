<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Joueur;
use AppBundle\Entity\Partie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Joueur controller.
 *
 * @Route("/joueur")
 */
class JoueurController extends Controller
{
    /**
     * Lists all joueur entities.
     *
     * @Route("/", name="administration_joueur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $joueurs = $em->getRepository('AppBundle:Joueur')->findAll();

        return $this->render('joueur/index.html.twig', array(
            'joueurs' => $joueurs,
        ));
    }

    /**
     * Creates a new joueur entity.
     *
     * @Route("/new", name="administration_joueur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $joueur = new Joueur();
        $form = $this->createForm('AppBundle\Form\JoueurType', $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($joueur);
            $em->flush();

            return $this->redirectToRoute('administration_joueur_show', array('id' => $joueur->getId()));
        }

        return $this->render('joueur/new.html.twig', array(
            'joueur' => $joueur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a joueur entity.
     *
     * @Route("/show/{id}", name="administration_joueur_show")
     * @Method("GET")
     */
    public function showAction(Joueur $joueur)
    {
        $deleteForm = $this->createDeleteForm($joueur);

        return $this->render('joueur/show.html.twig', array(
            'joueur' => $joueur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing joueur entity.
     *
     * @Route("/{id}/edit", name="administration_joueur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Joueur $joueur)
    {
        $deleteForm = $this->createDeleteForm($joueur);
        $editForm = $this->createForm('AppBundle\Form\JoueurType', $joueur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_joueur_edit', array('id' => $joueur->getId()));
        }

        return $this->render('joueur/edit.html.twig', array(
            'joueur' => $joueur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a joueur entity.
     *
     * @Route("/{id}", name="administration_joueur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Joueur $joueur)
    {
        $form = $this->createDeleteForm($joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($joueur);
            $em->flush();
        }

        return $this->redirectToRoute('administration_joueur_index');
    }

    /**
     * Creates a form to delete a joueur entity.
     *
     * @param Joueur $joueur The joueur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Joueur $joueur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('administration_joueur_delete', array('id' => $joueur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/profil", name="profil")
     *
     */
    public function afficherPartiesEnCours()
    {
        $id = $this->getUser()->getId();
        $partieJ1=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur1'=>$id, 'encours'=>1));
        $partieJ2=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur2'=>$id, 'encours'=>1));
        $nbparties = count($partieJ2) + count($partieJ1);
        $pJ1f=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur1'=>$id, 'encours'=>0));
        $pJ2f=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur2'=>$id, 'encours'=>0));
        $nbpf = count($pJ2f) + count($pJ1f);


        return $this->render('afficher_profil.html.twig', array(
            'partieJ1' => $partieJ1,
            'partieJ2' => $partieJ2,
            'user' => $this->getUser(),
            'nbparties' => $nbparties,
            'pJ1f' => $pJ1f,
            'pJ2f' => $pJ2f,
            'nbpf' => $nbpf,
        ));

    }

    /**
     * @Route("/admin", name="admin")
     *
     */
    public function afficherAdmin()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:Joueur')->findAll();
        $tusers = array();
        $i = 0;
        foreach ($users as $user) {
            $tusers[$user->getId()] = $user;
            $i++;
        }

        //var_dump($tusers);
        $nbu = count($tusers);



        return $this->render('afficher_admin.html.twig', array(
            'tusers' => $tusers,
            'nbu' => $nbu,
            'user' => $this->getUser(),
        ));

    }


    /**
     * @Route("/admin/suppjoueur/{joueur}", name="supp_joueur")
     */
    public function suppJoueur($joueur)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $admin = $em->getRepository('AppBundle:Joueur')->find($joueur);
        $partieJ1=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur1'=>$joueur));
        $partieJ2=$this->getDoctrine()->getRepository('AppBundle:Partie')->findby(array('joueur2'=>$joueur));

        foreach ($partieJ1 as $partie){
            $em->remove($partie);
        }

        foreach ($partieJ2 as $partie){
            $em->remove($partie);
        }

        $em->remove($admin);
        $em->flush();

        return $this->redirectToRoute($admin);
    }


}
