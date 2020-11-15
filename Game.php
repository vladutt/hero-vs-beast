<?php

use Classes\Beast;
use Classes\Hero;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Traits\GameTrait;

class Game extends Command
{

    use GameTrait;

    protected $round = 1;
    protected $maxRounds = 20;
    protected $firstPosition = false;

    protected $hero, $beast;
    protected $attacker, $defender;

    protected $commandName = 'start-game';
    protected $commandDescription = "Fight!";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->hero = new Hero();
        $this->beast = new Beast();

        print "Hero: \n";
        print_r($this->hero->getStats());
        print "Beast: \n";
        print_r($this->beast->getStats());

        // Decide who attack first
        $this->decidePositions();

        for ($this->round; $this->round <= $this->maxRounds; $this->round++) {
            print "Round $this->round \n" . $this->attack() . "\n";

            if ($this->hero->getStats('health') <= 0 || $this->beast->getStats('health') <= 0) {
                print "\e[31mGame Over\e[0m \n";
                print $this->hero->getStats('health') <= 0 ? 'Beast Won' : 'Hero Won';
                return Command::SUCCESS;
            }

            sleep(1);
        }

        print $this->hero->getStats('health') > $this->beast->getStats('health') ? 'Hero Won' : 'Beast Won';
        return Command::SUCCESS;

    }
}
