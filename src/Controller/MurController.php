<?php

namespace App\Controller;

use App\Repository\CommunityRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MurController extends AbstractController
{
    /**
     * @Route("/mur/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function index(UserRepository $userRepository,
                          CommunityRepository $communityRepository,
                          PostRepository $postRepository, $id)
    {
        //______________requet pour remplir les liste des groupe et membres __________________________
        $groupes  = $this->getUser()->getCommunities();//$communityRepository->findBy(['owner' => $this->getUser()]);


        dump($groupes);

         //______________requet pour recup les poste du mur du groupe___________________________________________
        $posts=$communityRepository->findBy(['id'=> $id]);
        dump($posts);



        return $this->render('index/index.html.twig',

            [
                'groupes'=>$groupes,
                'nom'=>$this->getUser(),
                'postes'=>$posts
            ]

        );
    }
}
