<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Genus;
use AppBundle\Form\GenusFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_MANAGE_GENUS')")
 */
class GenusAdminController extends Controller {

    /**
     * @Route("/genus/list", name="admin_genus_list")
     */
    public function indexAction() {
        $genuses = $this->getDoctrine()->getRepository('AppBundle:Genus')->findAll();

        return $this->render('admin/genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/new", name="admin_genus_new")
     */
    public function newAction(Request $request) {

        $form = $this->createForm(GenusFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $this->addFlash('success', sprintf('Genus created by you: %s!', $this->getUser()->getEmail()));
            $em->flush();

            return $this->redirectToRoute('admin_genus_list');
        }

        return $this->render('admin/genus/new.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/genus/{id}/edit", name="admin_genus_edit")
     */
    public function editAction(Request $request, Genus $genus) {

        $form = $this->createForm(GenusFormType::class, $genus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $this->addFlash('success','Genus edit');
            $em->flush();

            return $this->redirectToRoute('admin_genus_list');
        }

        return $this->render('admin/genus/edit.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }
}