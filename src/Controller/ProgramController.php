<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;


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
         * Getting a program by id
         * 
         * @route("/show/{id<^[0-9]+$>}", name="show")
         * @return Response
         */
    public function show (int $id) : Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
       

        if (!$program) 
        {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()->getRepository(Season::class)->findAll();

        return $this->render('program/show.html.twig', [
           'program' => $program,
           'seasons' => $seasons,
        ]);
    }

    /** 
     * @Route("/{programId}/season/{seasonId}", requirements={"programId"="\d+", "seasonId"="\d+"}, methods={"GET"}, name="season_show")
     */
    public function showSeason(int $programId, int $seasonId)
    {   

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $seasonId]);

        if (!$season) {
            throw $this->createNotFoundException('No season with id: ' . $seasonId . ' found in season\'s table.');
        }
       
        $program = $season->getProgram();
        
        $episodes = $season->getEpisodes();

        return $this->render('program/program_season_show.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes,
        ]);
       
    }
    
}