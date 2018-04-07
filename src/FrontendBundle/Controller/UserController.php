<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;

class UserController extends Controller
{
    public function completeProfileAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $age = $request->get("age");
        $path = $request->get("path");

        $user->getPlayerUser()->setFirstname($firstname);
        $user->getPlayerUser()->setLastname($lastname);
        $user->getPlayerUser()->setAge($age);
        $user->getPlayerUser()->setImgSrc("uploads/users/".$path);
        $user->setFullPlayer(true);

        $em->persist($user);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function completeSkillAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $gameLevel = $request->get("gameLevel");
        $gameStyle = $request->get("gameStyle");
        $typeBackhand = $request->get("typeBackhand");
        $forehand = $request->get("forehand");
        $backhand = $request->get("backhand");
        $volley = $request->get("volley");
        $service = $request->get("service");
        $resistence = $request->get("resistence");

        $user->getSkillUser()->setGameLevel($gameLevel);
        $user->getSkillUser()->setGameStyle($gameStyle);
        $user->getSkillUser()->setTypeBackHand($typeBackhand);
        $user->getSkillUser()->setForehand($forehand);
        $user->getSkillUser()->setBackhand($backhand);
        $user->getSkillUser()->setVolley($volley);
        $user->getSkillUser()->setService($service);
        $user->getSkillUser()->setResistence($resistence);
        $user->setFullGame(true);

        $em->persist($user);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function getUserAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        $arr["username"] = $user->getUsername();
        $arr["email"] = $user->getEmail();
        $arr["path"] = $this->get('kernel')->getRootDir() . '/../web/'.$user->getPlayerUser()->getImgSrc();
        $arr["firstname"] = $user->getPlayerUser()->getFirstname();
        $arr["lastname"] = $user->getPlayerUser()->getLastname();
        $arr["age"] = $user->getPlayerUser()->getAge();
        $arr["gameStyle"] = $user->getSkillUser()->getGameStyle();
        $arr["gameLevel"] = $user->getSkillUser()->getGameLevel();
        $arr["backhandType"] = $user->getSkillUser()->getTypeBackhand();
        $arr["forehand"] = $user->getSkillUser()->getForehand();
        $arr["backhand"] = $user->getSkillUser()->getBackhand();
        $arr["service"] = $user->getSkillUser()->getService();
        $arr["volley"] = $user->getSkillUser()->getVolley();
        $arr["resistence"] = $user->getSkillUser()->getResistence();

        return ResponseRest::returnOk($arr);
    }

    public function getUserStatusAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        $arr["fullPlayer"] = $user->getFullPlayer();
        $arr["fullGame"] = $user->getFullGame();

        return ResponseRest::returnOk($arr);
    }

    public function getUsersRandomAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $users = $em->createQuery(
        'SELECT u
        FROM BackendBundle:User u
        WHERE u.id != :id AND u.fullGame = 1
        ')->setParameter('id', $id)
        ->setMaxResults(3)->getResult();

        $arr = [];
        $arr1 = [];

        foreach($users as $u){

            $arr1['id'] = $u->getId();
            $arr1['username'] = $u->getUsername();
            $arr1['firstname'] = $u->getPlayerUser()->getFirstname();
            $arr1['lastname'] = $u->getPlayerUser()->getLastname();
            $arr1['gameStyle'] = $u->getSkillUser()->getGameStyle();
            $arr1['gameLevel'] = $u->getSkillUser()->getGameLevel();
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);
    }



}