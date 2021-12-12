<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{   
    const PROGRAMS = [
        ['The Big Bang Theory', "Leonard Hofstadter et Sheldon Cooper vivent en colocation à Pasadena, ville de l'agglomération de Los Angeles. Ce sont tous deux des physiciens surdoués, « geeks » de surcroît. C'est d'ailleurs autour de cela qu'est axée la majeure partie comique de la série. Ils partagent quasiment tout leur temps libre avec leurs deux amis Howard Wolowitz et Rajesh Koothrappali pour jouer à des jeux vidéo comme Halo, organiser un marathon de la saga Star Wars, jouer à des jeux de société comme le Boggle klingon ou de rôles tel que Donjons et Dragons, voire discuter de théories scientifiques très complexes.Leur univers routinier est perturbé lorsqu'une jeune femme, Penny, s'installe dans l'appartement d'en face. Leonard a immédiatement des vues sur elle et va tout faire pour la séduire ainsi que l'intégrer au groupe et à son univers, auquel elle ne connaît rien.", 'https://upload.wikimedia.org/wikipedia/fr/6/69/BigBangTheory_Logo.png', '3', ['0', '2']],
        ['Walking Dead', "Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.", 'https://m.media-amazon.com/images/M/MV5BZmFlMTA0MmUtNWVmOC00ZmE1LWFmMDYtZTJhYjJhNGVjYTU5XkEyXkFqcGdeQXVyMTAzMDM4MjM0._V1_.jpg', '5', ['1', '2', '3']],
        ['The Haunting Of Hill House', "Plusieurs frères et sœurs qui, enfants, ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis, sont contraints de se réunir pour finalement affronter les fantômes de leur passé.", 'https://m.media-amazon.com/images/M/MV5BMTU4NzA4MDEwNF5BMl5BanBnXkFtZTgwMTQxODYzNjM@._V1_SY1000_CR0,0,674,1000_AL_.jpg', '5', ['1', '2']],
        ['American Horror Story', "A chaque saison, son histoire. American Horror Story nous embarque dans des récits à la fois poignants et cauchemardesques, mêlant la peur, le gore et le politiquement correct.", 'https://m.media-amazon.com/images/M/MV5BODZlYzc2ODYtYmQyZS00ZTM4LTk4ZDQtMTMyZDdhMDgzZTU0XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg', '5', ['0', '1']],
        ['Stargate SG1','Exploration des différents mondes via la porte des étoiles','https://fr.web.img5.acsta.net/pictures/19/06/14/15/57/3353242.jpg','4',['0', '3']]
        ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::PROGRAMS as $key => $programTab) {
            $program = new Program();

            $program->setTitle($programTab[0]);
            $program->setSummary($programTab[1]);
            $program->setPoster($programTab[2]);
            $program->setCategory($this->getReference('category_' . $programTab[3]));
            $program->setCountry('USA');
            $program->setYear('2014');

            foreach ($programTab[4] as $actorId) {
                $program->addActor($this->getReference('actor_' . $actorId));
            }

            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}