<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;

/**
 * @route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
    * Show all rows from Program's entity
    * 
    * @route("/", name="index")
    * @return Response A response instance
    * @Route("/category/", name="category_index")
    */
    public function index(): Response
    {
        $categorys = $this->getDoctrine()
        ->getRepository(category::class)
        ->findAll();

        return $this->render(
        'category/index.html.twig',
        ['categorys' => $categorys]
        );
    }

        /**
         * Getting a category by name
         * 
         * @Route("/{categoryName}/", requirements={"categoryName"="[a-zA-Z\s]*"}, methods={"GET"}, name="show")
         * @return Response
         */
    public function show (string $categoryName) : Response
    {
        $category = $this->getDoctrine()
            ->getRepository(category::class)
            ->findOneBy(['name' => $categoryName]);


        if (!$category) 
        {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in category\'s table.'
            );
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category],['id' => 'desc'],3);

        
        return $this->render('category/show.html.twig', [
           'category' => $category,
           'programs' => $programs,
        ]);

    }
}