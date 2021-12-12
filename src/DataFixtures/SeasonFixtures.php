<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    const SEASONS = [['0', 1, 2007, "Season 1 - TBBT"], ['0', 2, 2008, "Season 2 - TBBT"], ['0', 3, 2009, "Season 3 - TBBT"], ['4', 1, 2010, "Season 1"], ['4', 2, 2011, "Season 2"]];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $key => $seasonTab) {
            $season = new Season();

            $season->setProgram($this->getReference('program_' . $seasonTab[0]));
            $season->setNumber($seasonTab[1]);
            $season->setYear($seasonTab[2]);
            $season->setDescription($seasonTab[3]);

            $manager->persist($season);
            $this->addReference('season_' . $key, $season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class
        ];
    }
}
