<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    const EPISODES = [
        [0, "Unaired Pilot", 0, "The first Pilot of what will become The Big Bang Theory. Leonard and Sheldon are two awkward scientists who share an apartment. They meet a drunk girl called Katie and invite her to stay at their place, because she has nowhere to stay. The two guys have a female friend, also a scientist, called Gilda."],
        [0, "Pilot", 1, "A pair of socially awkward theoretical physicists meet their new neighbor Penny, who is their polar opposite."],
        [0, "The Big Bran Hypothesis", 2, "Penny is furious with Leonard and Sheldon when they sneak into her apartment and clean it while she is sleeping."],
        [0, "The Fuzzy Boots Corollary", 3, "
        Leonard gets upset when he discovers that Penny is seeing a new guy, so he tries to trick her into going on a date with him."],
        [0, "The Luminous Fish Effect", 4, "
        Sheldon's mother is called to intervene when he delves into numerous obsessions after being fired for being disrespectful to his new boss."],
        [0, "The Hamburger Postulate", 5, "
        Leslie seduces Leonard, but afterwards tells him that she is only interested in a one-night stand."],
        ];


    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $episodeTab) {
            $episode = new Episode();

            $episode->setSeason($this->getReference('season_' . $episodeTab[0]));
            $episode->setNumber($episodeTab[2]);
            $episode->setTitle($episodeTab[1]);
            $episode->setSynopsis($episodeTab[3]);

            $manager->persist($episode);
            $this->addReference('episode_' . $key, $episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class
        ];
    }
}
