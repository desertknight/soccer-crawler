<?php

namespace Application\SoccerBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class XMLSoccer
{

    const API_KEY = '';
    const API_URL = '';

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $manager;

    /**
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * 
     * @param ContainerInterface $container
     * @param EntityManager $manager
     */
    public function __construct(ContainerInterface $container, EntityManager $manager)
    {
        $container->getParameter('api_key') = $container->getParameter('api_key');
        $container->getParameter('api_key') = $container->getParameter('api_key');
        $this->manager = $manager;
        $this->container = $container;
    }

    private function getXMLResponse($url, $data = array())
    {

        $build_query = http_build_query(array_merge(array('ApiKey' => $container->getParameter('api_key')), $data));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $container->getParameter('api_url') . $url . '?' . $build_query,
            CURLOPT_RETURNTRANSFER => TRUE
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xmlResponse = @simplexml_load_string($response);

        if (!$xmlResponse) {
            throw new \Exception('Error parsing XML documents');
        }

        if (strchr($xmlResponse[0], 'To avoid misuse')) {
            throw new \Exception($xmlResponse[0]);
        }

        return $xmlResponse;
    }

    public function getStaticContent($type)
    {
        switch ($type) {
            case 'teams' :
                return simplexml_load_string(file_get_contents("C:/Users/Replace/Desktop/GetAllTeams.xml"));
            case 'leagues' :
                return simplexml_load_string(file_get_contents("C:/Users/Replace/Desktop/GetAllLeagues.xml"));
            case 'fixtures' :
                return simplexml_load_string(file_get_contents("C:/Users/Replace/Desktop/GetFixturesByDateInterval.xml"));
            case 'history-league';
                return simplexml_load_string(file_get_contents("C:/Users/Replace/Desktop/GetHistoricMatchesByLeagueAndSeason.xml"));
        }
    }

    public function getLeagues()
    {

        $leagues = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:League')->findAll() AS $league) {
            $leagues[$league->getLeagueId()] = $league;
        }

        $countries = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Country')->findAll() AS $country) {
            $countries[$country->getName()] = $country;
        }

        foreach ($this->getXMLResponse("GetAllLeagues") AS $type => $league) {

            if ($type !== "League") {
                continue;
            }

            $league = (array) $league;

            if (isset($leagues[$league['Id']])) {
                continue;
            }

            // save country
            if (!isset($countries[$league['Country']])) {

                $country = new \Application\SoccerBundle\Entity\Country();

                $country->setName($league['Country']);

                $this->manager->persist($country);

                $countries[$country->getName()] = $country;
            } else {
                $country = $countries[$league['Country']];
            }

            $oLeague = new \Application\SoccerBundle\Entity\League();

            $oLeague->setCountry($country);
            $oLeague->setLeagueId($league['Id']);
            $oLeague->setName($league['Name']);

            if (!isset($league['Historical_Data']) || strchr($league['Historical_Data'], 'No'))
                $oLeague->setHasHistory(FALSE);
            if (!isset($league['Fixtures']) || strchr($league['Fixtures'], 'No'))
                $oLeague->setHasFixture(FALSE);
            if (!isset($league['Livescore']) || strchr($league['Livescore'], 'No'))
                $oLeague->setHasLiveScore(FALSE);
            if (isset($league['NumberOfMatches']))
                $oLeague->setCountMatches($league['NumberOfMatches']);
            if (isset($league['LatestMatch']) && $latest_match = new \DateTime($league['LatestMatch']))
                $oLeague->setLatestMatch($latest_match);

            $this->manager->persist($oLeague);

            $leagues[$oLeague->getId()] = $oLeague;
        }

        $this->manager->flush();
    }

    public function getTeams()
    {

        $teams = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Team')->findAll() AS $team) {
            $teams[$team->getTeamId()] = $team;
        }

        $countries = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Country')->findAll() AS $country) {
            $countries[$country->getName()] = $country;
        }

        foreach ($this->getXMLResponse("GetAllTeams") AS $type => $team) {

            if ($type !== 'Team') {
                continue;
            }

            // cast SimpleXMLObject to array
            $team = (array) $team;

            if (isset($teams[$team['Team_Id']])) {
                continue;
            }

            // save country, if not exists
            if (!isset($countries[$team['Country']])) {

                $country = new \Application\SoccerBundle\Entity\Country();

                $country->setName($team['Country']);

                $this->manager->persist($country);

                $countries[$country->getName()] = $country;
            } else {
                $country = $countries[$team['Country']];
            }

            // save team
            $oTeam = new \Application\SoccerBundle\Entity\Team();

            $oTeam->setCountry($country);
            $oTeam->setName($team['Name']);
            $oTeam->setTeamId($team['Team_Id']);

            if (!empty($team['Stadium']))
                $oTeam->setStadiumName($team['Stadium']);
            if (!empty($team['HomePageURL']))
                $oTeam->setHomePageUrl($team['HomePageURL']);
            if (!empty($team['WIKILink']))
                $oTeam->setWikiPageUrl($team['WIKILink']);

            $this->manager->persist($oTeam);

            $teams[$team['Team_Id']] = $oTeam;
        }

        $this->manager->flush();
    }

    public function getFixtures(\DateTime $startTime = NULL, \DateTime $endTime = NULL)
    {

        $teams = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Team')->findAll() AS $team) {
            $teams[$team->getTeamId()] = $team;
        }

        $leagues = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:League')->findAll() AS $league) {
            $leagues[$league->getName()] = $league;
        }

        $matches = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:SoccerMatch')->findAll() AS $match) {
            $matches[$match->getMatchId()] = $match;
        }

        $data = array('startDateString' => $startTime->format('Y-m-d'), 'endDateString' => $endTime->format('Y-m-d'));

        foreach ($this->getXMLResponse("GetFixturesByDateInterval", $data) AS $type => $fixture) {
            
            if ($type !== 'Match') {
                continue;
            }

            $match = (array) $fixture;

            if (isset($matches[$match['Id']])) {
                continue;
            }

            $current = new \Application\SoccerBundle\Entity\SoccerMatch();

            $current->setMatchId($match['Id']);

            if (FALSE !== ( $match_date = new \DateTime($match['Date'])))
                $current->setMatchTime($match_date);
            if (isset($leagues[$match['League']]))
                $current->setLeague($leagues[$match['League']]);
            if (isset($match['Id']))
                $current->setMatchId($match['Id']);
            if (isset($leagues[$match['League']]))
                $current->setLeague($leagues[$match['League']]);
            if (isset($teams[$match['HomeTeam_Id']]))
                $current->setHomeTeam($teams[$match['HomeTeam_Id']]);
            if (isset($teams[$match['AwayTeam_Id']]))
                $current->setAwayTeam($teams[$match['AwayTeam_Id']]);
            if (isset($match['HomeGoals']))
                $current->setHomeFullTime($match['HomeGoals']);
            if (isset($match['HalfTimeHomeGoals']))
                $current->setHomeHalfTime($match['HalfTimeHomeGoals']);
            if (isset($match['AwayGoals']))
                $current->setAwayFullTime($match['AwayGoals']);
            if (isset($match['HalfTimeAwayGoals']))
                $current->setAwayHalfTime($match['HalfTimeAwayGoals']);

            // save some cool and not needed informations for later, maybe
            $informations = array('home' => array(), 'away' => array());
            // information about Home team
            if (isset($match['HomeCorners']))
                $informations['home']['corners'] = $match['HomeCorners'];
            if (isset($match['HomeShots']))
                $informations['home']['shots'] = $match['HomeShots'];
            if (isset($match['HomeShotsOnTarget']))
                $informations['home']['shots_on_target'] = $match['HomeShotsOnTarget'];
            if (isset($match['HomeFouls']))
                $informations['home']['fouls'] = $match['HomeFouls'];
            if (isset($match['HomeYellowCards']))
                $informations['home']['yellow_cards'] = $match['HomeYellowCards'];
            if (isset($match['HomeRedCards']))
                $informations['home']['red_cards'] = $match['HomeRedCards'];
            // informations about Away team
            if (isset($match['AwayCorners']))
                $informations['away']['corners'] = $match['AwayCorners'];
            if (isset($match['AwayShots']))
                $informations['away']['shots'] = $match['AwayShots'];
            if (isset($match['AwayShotsOnTarget']))
                $informations['away']['shots_on_target'] = $match['AwayShotsOnTarget'];
            if (isset($match['AwayFouls']))
                $informations['away']['fouls'] = $match['AwayFouls'];
            if (isset($match['AwayYellowCards']))
                $informations['away']['yellow_cards'] = $match['AwayYellowCards'];
            if (isset($match['AwayRedCards']))
                $informations['away']['red_cards'] = $match['AwayRedCards'];

            $current->setInformation($informations);

            $current->setIsSample(FALSE);

            $this->manager->persist($current);

            $matches[$current->getMatchId()] = $current;
        }

        $this->manager->flush();
    }

    public function getHistoryMatch()
    {

        $teams = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Team')->findAll() AS $team) {
            $teams[$team->getTeamId()] = $team;
        }

        $leagues = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:League')->findAll() AS $league) {
            $leagues[$league->getName()] = $league;
        }

        $matches = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:SoccerMatch')->findAll() AS $match) {
            $matches[$match->getMatchId()] = $match;
        }

        $current = $this->manager->getRepository('ApplicationSoccerBundle:SoccerMatch')->findOneBy(array(
            'isSample' => TRUE
                ), array('updatedTime' => 'ASC'));

        if ($current) {

            $response = $this->getXMLResponse("GetHistoricMatchesByID", array("Id" => $current->getMatchId()));

            $current->setUpdatedTime(new \DateTime());
            
            foreach ($response AS $type => $match) {

                if ($type !== "Match") {
                    continue;
                }

                $match = (array) $match;

                if (FALSE !== ( $match_date = new \DateTime($match['Date'])))
                    $current->setMatchTime($match_date);
                if (isset($leagues[$match['League']]))
                    $current->setLeague($leagues[$match['League']]);
                if (isset($match['Id']))
                    $current->setMatchId($match['FixtureMatch_Id']);
                if (isset($match['FixtureMatch_Id']))
                    $current->setFixtureId($match['FixtureMatch_Id']);
                if (isset($leagues[$match['League']]))
                    $current->setLeague($leagues[$match['League']]);
                if (isset($teams[$match['HomeTeam_Id']]))
                    $current->setHomeTeam($teams[$match['HomeTeam_Id']]);
                if (isset($teams[$match['AwayTeam_Id']]))
                    $current->setAwayTeam($teams[$match['AwayTeam_Id']]);
                if (isset($match['HomeGoals']))
                    $current->setHomeFullTime($match['HomeGoals']);
                if (isset($match['HalfTimeHomeGoals']))
                    $current->setHomeHalfTime($match['HalfTimeHomeGoals']);
                if (isset($match['AwayGoals']))
                    $current->setAwayFullTime($match['AwayGoals']);
                if (isset($match['HalfTimeAwayGoals']))
                    $current->setAwayHalfTime($match['HalfTimeAwayGoals']);

                // save some cool and not needed informations for later, maybe
                $informations = array('home' => array(), 'away' => array());
                // information about Home team
                if (isset($match['HomeCorners']))
                    $informations['home']['corners'] = $match['HomeCorners'];
                if (isset($match['HomeShots']))
                    $informations['home']['shots'] = $match['HomeShots'];
                if (isset($match['HomeShotsOnTarget']))
                    $informations['home']['shots_on_target'] = $match['HomeShotsOnTarget'];
                if (isset($match['HomeFouls']))
                    $informations['home']['fouls'] = $match['HomeFouls'];
                if (isset($match['HomeYellowCards']))
                    $informations['home']['yellow_cards'] = $match['HomeYellowCards'];
                if (isset($match['HomeRedCards']))
                    $informations['home']['red_cards'] = $match['HomeRedCards'];
                // informations about Away team
                if (isset($match['AwayCorners']))
                    $informations['away']['corners'] = $match['AwayCorners'];
                if (isset($match['AwayShots']))
                    $informations['away']['shots'] = $match['AwayShots'];
                if (isset($match['AwayShotsOnTarget']))
                    $informations['away']['shots_on_target'] = $match['AwayShotsOnTarget'];
                if (isset($match['AwayFouls']))
                    $informations['away']['fouls'] = $match['AwayFouls'];
                if (isset($match['AwayYellowCards']))
                    $informations['away']['yellow_cards'] = $match['AwayYellowCards'];
                if (isset($match['AwayRedCards']))
                    $informations['away']['red_cards'] = $match['AwayRedCards'];

                $current->setInformation($informations);

                $current->setIsSample(FALSE);

            }
            
            $this->manager->persist($current);
        }

        $this->manager->flush();
    }

    public function getHistoryLeague()
    {

        $teams = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:Team')->findAll() AS $team) {
            $teams[$team->getTeamId()] = $team;
        }

        $leagues = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:League')->findAll() AS $league) {
            $leagues[$league->getName()] = $league;
        }

        $matches = array();
        foreach ($this->manager->getRepository('ApplicationSoccerBundle:SoccerMatch')->findAll() AS $match) {
            $matches[$match->getMatchId()] = $match;
        }

        $currentLeague = $this->manager->getRepository('ApplicationSoccerBundle:League')
                ->findOneBy(['historyUpdate' => FALSE], ['id' => 'ASC']);

        if ($currentLeague) {

            $response = $this->getXMLResponse('GetHistoricMatchesByLeagueAndSeason', array(
                'league' => $currentLeague->getLeagueId(), 'seasonDateString' => ''
            ));

            foreach ($response AS $type => $match) {

                if ($type !== "Match") {
                    continue;
                }

                $match = (array) $match;

                if (isset($matches[$match['Id']])) {
                    $current = $matches[$match['Id']];
                } else {
                    $current = new \Application\SoccerBundle\Entity\SoccerMatch();
                }

                $current->setMatchId($match['Id']);

                if (FALSE !== ( $match_date = new \DateTime($match['Date'])))
                    $current->setMatchTime($match_date);
                if (isset($leagues[$match['League']]))
                    $current->setLeague($leagues[$match['League']]);
                if (isset($match['FixtureMatch_Id']))
                    $current->setFixtureId($match['FixtureMatch_Id']);
                if (isset($leagues[$match['League']]))
                    $current->setLeague($leagues[$match['League']]);
                if (isset($teams[$match['HomeTeam_Id']]))
                    $current->setHomeTeam($teams[$match['HomeTeam_Id']]);
                if (isset($teams[$match['AwayTeam_Id']]))
                    $current->setAwayTeam($teams[$match['AwayTeam_Id']]);
                if (isset($match['HomeGoals']))
                    $current->setHomeFullTime($match['HomeGoals']);
                if (isset($match['HalfTimeHomeGoals']))
                    $current->setHomeHalfTime($match['HalfTimeHomeGoals']);
                if (isset($match['AwayGoals']))
                    $current->setAwayFullTime($match['AwayGoals']);
                if (isset($match['HalfTimeAwayGoals']))
                    $current->setAwayHalfTime($match['HalfTimeAwayGoals']);

                // save some cool and not needed informations for later, maybe
                $informations = array('home' => array(), 'away' => array());
                // information about Home team
                if (isset($match['HomeCorners']))
                    $informations['home']['corners'] = $match['HomeCorners'];
                if (isset($match['HomeShots']))
                    $informations['home']['shots'] = $match['HomeShots'];
                if (isset($match['HomeShotsOnTarget']))
                    $informations['home']['shots_on_target'] = $match['HomeShotsOnTarget'];
                if (isset($match['HomeFouls']))
                    $informations['home']['fouls'] = $match['HomeFouls'];
                if (isset($match['HomeYellowCards']))
                    $informations['home']['yellow_cards'] = $match['HomeYellowCards'];
                if (isset($match['HomeRedCards']))
                    $informations['home']['red_cards'] = $match['HomeRedCards'];
                // informations about Away team
                if (isset($match['AwayCorners']))
                    $informations['away']['corners'] = $match['AwayCorners'];
                if (isset($match['AwayShots']))
                    $informations['away']['shots'] = $match['AwayShots'];
                if (isset($match['AwayShotsOnTarget']))
                    $informations['away']['shots_on_target'] = $match['AwayShotsOnTarget'];
                if (isset($match['AwayFouls']))
                    $informations['away']['fouls'] = $match['AwayFouls'];
                if (isset($match['AwayYellowCards']))
                    $informations['away']['yellow_cards'] = $match['AwayYellowCards'];
                if (isset($match['AwayRedCards']))
                    $informations['away']['red_cards'] = $match['AwayRedCards'];
                $current->setInformation($informations);

                $current->setIsSample(FALSE);

                $this->manager->persist($current);
            }

            $currentLeague->setHistoryUpdate(TRUE);

            $this->manager->persist($currentLeague);

            $this->manager->flush();
        }
    }

}
