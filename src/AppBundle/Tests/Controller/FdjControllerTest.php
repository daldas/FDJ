<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Controller\FdjController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bridge\PhpUnit;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class FdjControllerTest extends TestCase
{

    /**
     *
     * Test Api results
     */
    public function testGetApiData()
    {
        $FDJ = new FdjController();

        $query = array('l' => 'French Ligue 1');

        $url = 'https://www.thesportsdb.com/api/v1/json/1/search_all_teams.php';

        $response = $FDJ->GetApiData($url, $query);

      $results = array();
        foreach ($response as $datas)
        {

            foreach($datas as $data)
            {

                $results =   $data;

            }
        }
        $this->assertSame('France', $results['strCountry']);
        $this->assertContains('Toulouse', $results);


    }

    /**
     *
     * Test Api results players
     */
    public function testGetPlayersAction()
    {
        $FDJ = new FdjController();


        $query = array('id' => 133714);

        $url = 'https://www.thesportsdb.com/api/v1/json/1/lookup_all_players.php';
        $response = $FDJ->getApiData($url,$query);


        foreach ($response as $player)
        {

            foreach($player as $joueur)
            {

                $idplayer =   $joueur['idPlayer'];
                $idTeam =   $joueur['idTeam'];
                $strTeam =   $joueur['strTeam'];
                $strPlayer =   $joueur['strPlayer'];

            }
        }


        $this->assertStringMatchesFormat('%s',$strPlayer);
        $this->assertSame('133714',$idTeam);
        $this->assertSame('Paris SG',$strTeam);



    }

}
