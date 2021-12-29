<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\ProgramType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/** 
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /** 
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        return $this->render('program/index.html.twig', ['programs' => $programs]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, MailerInterface $mailer, Slugify $slugify): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);

            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('your_email@example.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', ['form' => $form->createView()]);
    }

    /** 
     * @Route("/{slug}/", methods={"GET"}, name="show")
     */
    public function show(Program $program): Response
    {
        $params = [];
        $params['program'] = $program;

        $seasons = $program->getSeasons();
        if (count($seasons)) {
            $params['seasons'] = $seasons;
        }

        return $this->render('program/show.html.twig', $params);
    }

    /** 
     * @Route("/{program}/season/{season}", requirements={"season"="\d+"}, methods={"GET"}, name="season_show")
     * @ParamConverter("program", options={"mapping": {"program": "slug"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $params = [];
        $params['program'] = $program;
        $params['season'] = $season;

        $episodes = $season->getEpisodes();
        if (count($episodes)) {
            $params['episodes'] = $episodes;
        }

        return $this->render('program/season_show.html.twig', $params);
    }

    /**
     * @Route("/{program}/season/{season}/episode/{episode}", requirements={"season"="\d+", "episode"="\d+"}, methods={"GET"}, name="episode_show")
     * @ParamConverter("program", options={"mapping": {"program": "slug"}})
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        $params = [];
        $params['program'] = $program;
        $params['season'] = $season;
        $params['episode'] = $episode;

        return $this->render('program/episode_show.html.twig', $params);
    }
}