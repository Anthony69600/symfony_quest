<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Program;
use App\Entity\Category;
use App\Form\CategoryType;

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
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', ["form" => $form->createView()]);
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