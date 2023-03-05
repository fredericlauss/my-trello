<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Entity\Boards;
use App\Form\ColumnsType;
use App\Repository\ColumnsRepository;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/columns')]
class ColumnsController extends AbstractController
{
    #[Route('/', name: 'app_columns_index', methods: ['GET'])]
    public function index(ColumnsRepository $columnsRepository): Response
    {
        return $this->render('columns/index.html.twig', [
            'columns' => $columnsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_columns_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ColumnsRepository $columnsRepository): Response
    {
        $column = new Columns();
        $form = $this->createForm(ColumnsType::class, $column);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $columnsRepository->save($column, true);

            return $this->redirectToRoute('app_columns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('columns/new.html.twig', [
            'column' => $column,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_columns_show', methods: ['GET'])]
    public function show(Columns $column): Response
    {
        return $this->render('columns/show.html.twig', [
            'column' => $column,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_columns_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Columns $column, ColumnsRepository $columnsRepository): Response
    {
        $form = $this->createForm(ColumnsType::class, $column);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $columnsRepository->save($column, true);
    
            return $this->redirectToRoute('app_boards_show', ['id' => $column->getBoard()->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('columns/edit.html.twig', [
            'column' => $column,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_columns_delete', methods: ['POST'])]
public function delete(Request $request, Columns $column, ColumnsRepository $columnsRepository, TicketsRepository $ticketsRepository): Response
{
    if ($this->isCsrfTokenValid('delete'.$column->getId(), $request->request->get('_token'))) {
        // delete des tickets
        foreach ($column->getTickets() as $ticket) {
            $columnsRepository->removeTicket($ticket);
        }
        // Delete de la column
        $columnsRepository->remove($column, true);
    }

    return $this->redirectToRoute('app_boards_show', ['id' => $column->getBoard()->getId()], Response::HTTP_SEE_OTHER);
}


    #[Route('/boards/{boardId}/columns/new', name: 'app_board_columns_new', methods: ['GET', 'POST'])]
    public function newForBoard(Request $request, ColumnsRepository $columnsRepository, EntityManagerInterface $entityManager, $boardId): Response
    {
        $board = $entityManager->getRepository(Boards::class)->find($boardId);
    
        if (!$board) {
            throw $this->createNotFoundException('The board does not exist');
        }
    
        $column = new Columns();
        $column->setBoard($board);
    
        $form = $this->createForm(ColumnsType::class, $column);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $columnsRepository->save($column, true);
    
            return $this->redirectToRoute('app_boards_show', ['id' => $board->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('columns/new.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }
}
