<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\CommunityRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig');
    }





}

