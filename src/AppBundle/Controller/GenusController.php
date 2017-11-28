<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use AppBundle\Service\MarkdownTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//  cd C:/symfony3/Symfiny_Web/bin/php console list

/**
 * @Route("/main")
 * Class GenusController
 * @package AppBundle\Controller
 */
class GenusController extends Controller {

    /**
     * @Route("/list")
     */
    public function listAction() {

        $em = $this->getDoctrine()->getManager();
        $genuses = $em->getRepository('AppBundle:Genus')->findAllPublishedOrderedBySize();

        return $this->render('main/list.html.twig', [
          'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/new")
     */
    public function newAction() {

       $first = new Genus();
       $first->setName('Ant ' . rand(1, 100));
       $first->setFunFact('Fact ' . rand(1, 100));
       $first->setSpecCount(rand(1, 100));
       $first->setSubFamily('Family ' . rand(1, 100));

       $note = new GenusNote();
       $note->setUsername('AquaWeaver');
       $note->setUserAvatarFilename('ryan.jpeg');
       $note->setNote('I counted 8 legs... as they wrapped around me');
       $note->setCreatedAt(new \DateTime('-1 month'));
       $note->setGenus($first);

       $em = $this->getDoctrine()->getManager();
       $em->persist($first);
       $em->persist($note);
       $em->flush();

       return new Response('<html><body>Genus created!</body></html>');
   }

    /**
     * @Route("/index/{genusName}", name="main_index")
     * @return Response
     */
    public function indexAction($genusName = "Harmony Erdman", MarkdownTransformer $cache) {
        $em = $this->getDoctrine()->getManager();
        $genus = $em->getRepository('AppBundle:Genus')->findOneBy(['name' => $genusName]);

        if (!$genus) {
            throw $this->createNotFoundException('Error bro !!!');
        }

        $this->get('logger')->info('Showing genus: ' . $genusName);

        $recentNotes = $em->getRepository('AppBundle:GenusNote')->findAllRecentNotesForGenus($genus);

        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';

        $cache->parse($funFact);

        return $this->render("main/show.html.twig", [
            'genus' => $genus,
            'funFact' => $funFact,
            'recentNoteCount' => count($recentNotes)
        ]);
    }

    /**
     * @Route("/login", name="main_show_notes")
     */
    public function homepageAction() {
        return $this->render('main/homepage.html.twig');
    }

    /**
     * @Route("/{id}/notes", name="main_show_notes")
     * @Method("GET")
     * @return JsonResponse
     */
    public function getNotesAction(Genus $genus) {

        foreach ($genus->getNotes() as $note) {
           $notes = [
               'id' => $note->getId(),
               'username' => $note->getUsername(),
               'avatarUri' => '/images/' . $note->getUserAvatarFilename(),
               'note' => $note->getNote(),
               'date' => $note->getCreatedAt()->format('M d, Y')
           ];
        }

        $data = [
            'notes' => $notes
        ];
        return new JsonResponse($data);
    }

}