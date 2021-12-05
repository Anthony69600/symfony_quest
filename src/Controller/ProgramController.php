<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;


/**
 * @route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
    * Show all rows from Program's entity
    * 
    * @route("/", name="index")
    * @return Response A response instance
    * @Route("/program/", name="program_index")
    */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(program::class)
        ->findAll();

        return $this->render(
        'program/index.html.twig',
        ['programs' => $programs]
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
        // Create a new program Object
        $newProgram = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $newProgram);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($newProgram);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }



        /**
         * Getting a program by id
         * 
         * @route("/show/{id<^[0-9]+$>}",methods={"GET"}, name="show")
         * @return Response
         */
    public function show (Program $program) : Response
    {
        if (!$program)
        {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
           'program' => $program,
           'seasons' => $seasons,
        ]);
    }

    /** 
     * @Route("/{program}/season/{season}", requirements={"program"="\d+", "season"="\d+"}, methods={"GET"}, name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {
     
        $episodes = $season->getEpisodes();

        return $this->render('program/program_season_show.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
            'program' => $program,
        ]);
       
    }

    /** 
     * @Route("/{program}/season/{season}/episode/{episode}", requirements={"program"="\d+", "season"="\d+", "episode"="\d+"}, methods={"GET"}, name="episode_show")
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'season' => $season,
            'episode' => $episode,
            'program' => $program,
        ]);
    }
    
}