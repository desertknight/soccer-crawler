<?php

namespace Application\SoccerBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CrawlerCommand extends ContainerAwareCommand
{

    /**
     * 
     */
    protected function configure()
    {

        $startTimeDefault = \DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('-1 month')));
        $endTimeDefault = \DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('+1 week')));

        $this
                ->setName('crawler:fetch')
                ->addArgument('option', InputArgument::REQUIRED, 'What type of information do you wanna fetch?')
                ->addOption("start", "-st", InputOption::VALUE_OPTIONAL, "Start time for fixtures", $startTimeDefault)
                ->addOption("end", "-et", InputOption::VALUE_OPTIONAL, "End time for fixtures", $endTimeDefault)
        ;
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $timeoutCommands = array(
            'teams' => 3600,
            'leagues' => 3600,
            'fixtures' => 300,
            'history-match' => 300,
            'history-league' => 300
        );

        $commands = array();
        foreach ($this->getContainer()->get('doctrine')->getRepository('ApplicationSoccerBundle:System')->findAll() AS $command) {
            $commands[$command->getCommand()] = $command;
        }

//        if (isset($commands[$input->getArgument("option")])) {
//            $now = new \DateTime();
//            $timeout = "+" . $timeoutCommands[$input->getArgument("option")] . " seconds";
//            $nextStart = $commands[$input->getArgument("option")]->getUpdatedTime()->modify($timeout);
//            if ($nextStart->format("YmdHis") > $now->format("YmdHis")) {
//                throw new \Exception(sprintf("Timeout exception %s seconds (elapse %s seconds)", $timeoutCommands[$input->getArgument("option")], $nextStart->format("YmdHis") - $now->format("YmdHis")
//                ));
//            }
//        }

        try {
            $startTime = $input->getOption("start");
            if (!$input->getOption("start") instanceof \DateTime) {
                $startTime = new \DateTime($startTime);
            }
            $endTime = $input->getOption("end");
            if (!$input->getOption("end") instanceof \DateTime) {
                $endTime = new \DateTime($input->getOption("end"));
            }
            switch ($input->getArgument("option")) {
                case 'leagues' :
                    $response = $this->getContainer()->get('util.xmlsoccer')->getLeagues();
                    break;
                case 'teams' :
                    $response = $this->getContainer()->get('util.xmlsoccer')->getTeams();
                    break;
                case 'fixtures' :
                    $response = $this->getContainer()->get('util.xmlsoccer')->getFixtures($startTime, $endTime);
                    break;
                case 'history-match' :
                    $response = $this->getContainer()->get('util.xmlsoccer')->getHistoryMatch();
                    break;
                case 'history-league' :
                    $response = $this->getContainer()->get('util.xmlsoccer')->getHistoryLeague();
                    break;
                default :
                    $help = <<<HELP
                        
Please enter a valid option to fetch information. Available options are:
`- leagues
`- teams
`- fixtures
`- history-match
`- history-league
HELP;
                    $output->writeln($help);
            }
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        $oSystem = new \Application\SoccerBundle\Entity\System();

        $oSystem->setCommand($input->getArgument("option"));
        $oSystem->setResponse($response);

        $this->getContainer()->get('doctrine')->getManager()->persist($oSystem);
        $this->getContainer()->get('doctrine')->getManager()->flush();
    }

}
