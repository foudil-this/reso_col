<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\User;
use App\Repository\CommunityRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(UserRepository $userRepository,
                          CommunityRepository $communityRepository,
                          PostRepository $postRepository
                          )
    {

       // fonction qui renvoi les liste et les poste de notre utilisateur (liste de tous les poste qu'il a posté
        //sur tous les groupe ou il est mombre et proprio
        //elle s'execute uniquement s'il y apas d'id passé dans l'url




       //recuperation des groupe et de leur membres de l'utilisateur conecté
        $groupes=$communityRepository->findBy(['owner' => $this->getUser()]);
        //recuperation des donné de l'utilisateur
        $user=$userRepository->findOneBy(['id'=>$this->getUser()]);
        // recupére les donnée utilistaeur da   ns un tableau pour extraire la liste
        //des nom de groupe ou il est mombre mais pas proprio
        $groupesuser =$userRepository->findBy(['id' => $this->getUser()]);
        //recupére la liste de tous  poste de notre utilisateur
        $posts=$postRepository->findBy(['user'=>$this->getUser()]);

        return $this->render('index/index.html.twig',

            [
                'groupes'=>$groupes,
                 // param obseléte a retiré apres test
                 'nom'=>$this->getUser(),
                'postes'=>$posts,
                'groupesuser'=>$groupesuser,
                'user'=>$user

            ]

            );
    }

    //__________________________________________________________________________________________________________
    /**
     * @Route("/muruser/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function muruser(UserRepository $userRepository,
                          CommunityRepository $communityRepository,
                          PostRepository $postRepository,
    $id
    )
    {
        // fonction qui s'execute uniquement si un id de groupe est passé dans l'url et renvoi
        //le mur du groupe , le fil des poste de tous les membres du groupe


        if (is_null($id)){
            $nom=$this->getUser();

        }else{
            $nom='mur';
            $nomgroupe=$communityRepository->findOneBy(['id'=>$id]);
        }


        $groupes=$communityRepository->findBy(['owner' => $this->getUser()]);


        $groupesuser =$userRepository->findBy(['id' => $this->getUser()]);



        $posts=$postRepository->findBy(['community'=>$id]);
        dump($groupesuser);

        dump($posts);
        dump($groupes[0]);
        return $this->render('index/index.html.twig',

            [
                'groupes'=>$groupes,
                'nom'=>$nom,
                'nomgroupe'=>$nomgroupe,
                'postes'=>$posts,
                'groupesuser'=>$groupesuser,


            ]

        );
    }



}

