<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Boards;
use App\Entity\Columns;
use App\Form\TicketsType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/tickets')]
class TicketsController extends AbstractController
{
    #[Route('/', name: 'app_tickets_index', methods: ['GET'])]
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TicketsRepository $ticketsRepository): Response
    {
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketsRepository->save($ticket, true);

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tickets_show', methods: ['GET'])]
    public function show(Tickets $ticket): Response
    {
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tickets $ticket, TicketsRepository $ticketsRepository): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketsRepository->save($ticket, true);

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{columnid}', name: 'app_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Tickets $ticket, TicketsRepository $ticketsRepository, EntityManagerInterface $entityManager, $columnid): Response
    {
        $column = $entityManager->getRepository(Columns::class)->find($columnid);
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketsRepository->remove($ticket, true);
        }

        return $this->redirectToRoute('app_boards_show', ['id' => $column->getBoard()->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/new/{columnid}', name: 'app_column_tickets_new', methods: ['GET', 'POST'])]
public function newForColumn(Request $request, TicketsRepository $ticketsRepository, EntityManagerInterface $entityManager, $columnid): Response
{

    $ticket = new Tickets();
    $form = $this->createForm(TicketsType::class, $ticket);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Retrieve the Column entity by its primary key
        $column = $entityManager->getRepository(Columns::class)->find($columnid);

        // Set the column association for the new ticket
        $ticket->setColumnid($column);

        $ticketsRepository->save($ticket, true);

        return $this->redirectToRoute('app_boards_show', ['id' => $column->getBoard()->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('tickets/new.html.twig', [
        'ticket' => $ticket,
        'form' => $form,
    ]);
}

}
