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

        // Disables SSL cert validation temporary
        Unirest\Request::verifyPeer(false);
        // search teams
        $headers = array('Accept' => 'application/json');
        $query = array();
        $response = Unirest\Request::get('https://www.thesportsdb.com/api/v1/json/1/all_leagues.php',$headers,$query);

         $leagues = get_object_vars($response->body);

        $my_leagues=array();
        foreach ($leagues as $league)
        {

            foreach($league as $my_league)
            {

                $my_league = json_decode(json_encode($my_league), True);

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

        // Disables SSL cert validation temporary
        Unirest\Request::verifyPeer(false);
        // search teams
        $headers = array('Accept' => 'application/json');
        $query = array('l' => $q);

        $response = Unirest\Request::get('https://www.thesportsdb.com/api/v1/json/1/search_all_teams.php',$headers,$query);

        $teams = get_object_vars($response->body);

        $equipes=array();
        foreach ($teams as $team)
        {

            foreach($team as $equipe)
            {

                $equipe = json_decode(json_encode($equipe), True);

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

        // Disables SSL cert validation temporary
        Unirest\Request::verifyPeer(false);
        // search teams
        $headers = array('Accept' => 'application/json');
        $query = array('id' => $q);

        $response = Unirest\Request::get('https://www.thesportsdb.com/api/v1/json/1/lookup_all_players.php',$headers,$query);

        $players = get_object_vars($response->body);

        $joueurs=array();
        foreach ($players as $player)
        {

            foreach($player as $joueur)
            {

                $joueur = json_decode(json_encode($joueur), True);

                $idplayer =   $joueur['idPlayer'];
                $joueurs[$idplayer] = $joueur;

            }
        }


        return $this->render("default/players.html.twig", ['joueurs' => $joueurs]);
    }

}
