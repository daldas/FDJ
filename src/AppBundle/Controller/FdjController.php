<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Unirest;

class FdjController extends Controller
{

    /**
     * return Api Response
     */
    public function getApiData($url, $query=array()){

        // Disables SSL cert validation temporary
        Unirest\Request::verifyPeer(false);
        // search teams
        $headers = array('Accept' => 'application/json');
        $response = Unirest\Request::get($url,$headers,$query);

        $responses = get_object_vars($response->body);

        $datas=array();

            $datas = json_decode(json_encode($responses), True);

                return $datas;

    }
    /**
     * @Route("/", name="homepage")
     */
    public function getSearchAction()
    {

        $data = array();

        $form = $this->createFormBuilder( $data, array(
            'action' => $this->generateUrl('homepage').'?term=',
            'method' => 'GET',
        ) )
            ->add('team', null,['label' => ' '])
            ->getForm();

        return $this->render('default/home.html.twig', ['form' => $form->createView() ]);
    }

    /**
     * @Route("/search-leagues", name="search_leagues", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function getLeaguesAction(Request $request)
    {

        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui

        $url ='https://www.thesportsdb.com/api/v1/json/1/all_leagues.php';
        $response = $this->getApiData($url);

        $my_leagues=array();
        foreach ($response as $league)
        {

            foreach($league as $my_league)
            {

                $League_name =   $my_league['strLeague'];

                $pos = strpos($League_name, $q);

                if($pos !== false)
                $my_leagues[$League_name] = $my_league;
            }
        }

        // Display results
     //  return new JsonResponse($response->body->teams);

       return $this->render("default/leagues.json.twig", ['leagues' => $my_leagues]);
    }

    /**
     * @Route("/teams/{strLeague}", name="teams")
     * @Method("GET")
     */
    public function getTeamsAction(Request $request,$id = null)
    {

        $q = $request->get('strLeague'); // use "term" instead of "q" for jquery-ui

        $query = array('l' => $q);

        $url = 'https://www.thesportsdb.com/api/v1/json/1/search_all_teams.php';
        $response = $this->getApiData($url,$query);

        $equipes=array();
        foreach ($response as $team)
        {

            foreach($team as $equipe)
            {

                $id_team =   $equipe['idTeam'];
                $equipes[$id_team] = $equipe;

            }
        }

        return $this->render("default/teams.html.twig", ['teams' => $equipes]);
    }

    /**
     * @Route("/players/{id}", name="players")
     * @Method("GET")
     */
    public function getPlayersAction(Request $request,$id)
    {

        $q = $request->get('id'); // use "term" instead of "q" for jquery-ui

        $query = array('id' => $q);

        $url = 'https://www.thesportsdb.com/api/v1/json/1/lookup_all_players.php';
        $response = $this->getApiData($url,$query);

        $joueurs=array();
        foreach ($response as $player)
        {

            foreach($player as $joueur)
            {

                $idplayer =   $joueur['idPlayer'];
                $joueurs[$idplayer] = $joueur;

            }
        }


        return $this->render("default/players.html.twig", ['joueurs' => $joueurs]);
    }

}
