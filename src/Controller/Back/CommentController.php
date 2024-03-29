<?php

namespace App\Controller\Back;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/back/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Comment list
     * @Security("is_granted('ROLE_MANAGER')")
     * @Route("/", name="app_back_comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('back/comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_comment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $commentRepository->add($comment, true);

            return $this->redirectToRoute('app_back_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('back/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_comment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUpdatedAt(new DateTime());

            $commentRepository->add($comment, true);

            return $this->redirectToRoute('app_back_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_back_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
