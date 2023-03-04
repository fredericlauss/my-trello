<?php

namespace App\Controller;

use App\Entity\Boards;
use App\Form\BoardsType;
use App\Repository\BoardsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boards')]
class BoardsController extends AbstractController
{
    #[Route('/', name: 'app_boards_index', methods: ['GET'])]
    public function index(BoardsRepository $boardsRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $boards = $boardsRepository->findBy(['Owner' => $user]);
        return $this->render('boards/index.html.twig', [
            'boards' => $boards,
        ]);
    }

    #[Route('/new', name: 'app_boards_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BoardsRepository $boardsRepository): Response
    {
        $board = new Boards();
        $user = $this->getUser();
        $board->setOwner($user);
        $form = $this->createForm(BoardsType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardsRepository->save($board, true);

            return $this->redirectToRoute('app_boards_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boards/new.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boards_show', methods: ['GET'])]
    public function show(Boards $board): Response
    {
        return $this->render('boards/show.html.twig', [
            'board' => $board,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_boards_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boards $board, BoardsRepository $boardsRepository): Response
    {
        $form = $this->createForm(BoardsType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardsRepository->save($board, true);

            return $this->redirectToRoute('app_boards_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boards/edit.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boards_delete', methods: ['POST'])]
    public function delete(Request $request, Boards $board, BoardsRepository $boardsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $boardsRepository->remove($board, true);
        }

        return $this->redirectToRoute('app_boards_index', [], Response::HTTP_SEE_OTHER);
    }
}
