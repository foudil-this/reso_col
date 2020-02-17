<?php

namespace App\Controller;

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
                          PostRepository $postRepository)
    {

        $groupes  = $postRepository->findBy(['user' => $this->getUser()]);
        $posts=$postRepository->findBy(['user'=>$this->getUser()]);
        dump($posts);
        dump($groupes);
        return $this->render('index/index.html.twig',

            [
                'groupes'=>$groupes,
                 'nom'=>$this->getUser(),
                'postes'=>$posts
            ]

            );
    }


}

