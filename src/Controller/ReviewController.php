<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Review;
use App\Form\ReviewType;


class ReviewController extends AbstractController
{
    /**
     * @Route("/review", name="review_index")
     */
    public function index(): Response
    {   
        $reviews = new Review();
        $reviews = $this->getDoctrine()
        ->getRepository(Review::class)
        ->findAll();

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
        ]);
    }

        /**
     * @Route("/review/new", name="review_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('review/new.html.twig', [
            'form' => $form,
        ]);
    }

}
