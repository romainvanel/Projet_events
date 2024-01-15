<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepository,
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    /**
     * Affichage de tous les évènements triés par date décroissante
     */
    #[Route('/', name: 'app_events')]
    public function index(): Response
    {

        $events = $this->eventRepository->findBy(
            [],
            ['startDate' => 'DESC']
        ); 

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/{id}', name: 'app_event')]
    public function getOneEvent(Event $event): Response
    {
        return $this->render('event/event.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * Edition d'un évènement
     */
    #[Route('/event/{id}/edit', name: 'app_event_edit', requirements: ['id'=> '\d+'])]
    public function editEvent(Event $event, Request $request): Response 
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', "L'évènement à bien été modifié");
        }

        return $this->render('event/editEvent.html.twig', [
            'formEditEvent' => $form,
            'event' => $event
        ]);
    }

    /**
     * Suppression d'un évènement
     */
    #[Route('/event/{id}/delete', name: 'app_event_delete', requirements: ['id'=> '\d+'])]
    public function deleteEvent(Event $event): RedirectResponse
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        $this->addFlash('success', "L'évènement {$event->getName()} a bien été supprimé");

        return $this->redirectToRoute('app_events');
    }

    /**
     * Ajout d'un nouvel évènement
     */
    #[Route('/question/add', name: 'app_question_add')]
    public function addEvent(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', "L'évènement à bien été ajouté");

            return $this->redirectToRoute('app_events');
        }

        return $this->render('event/addEvent.html.twig', [
            'formAddEvent' => $form
        ]);
    }

}
