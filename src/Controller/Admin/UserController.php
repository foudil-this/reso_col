<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @Route("/")
     * @return Response
     */
    public function index(UserRepository $repository)
    {
        $users = $repository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/user/index.html.twig',
            ['users' => $users]
        );
    }

    /**
     *Paramconverter : le parametre typé User contient la categorie
     * dont l'id est passé dans la partie variable de l'url
     *
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     * @param EntityManagerInterface $manager
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(EntityManagerInterface $manager, User $user)
    {
        // suppression de la categorie en bdd
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Le user est supprimée');

        return $this->redirectToRoute('app_admin_user_index');

    }


    /**
     * @param PostRepository $repository
     * @param User $user
     * @return Response
     * @Route("/post/{id}", requirements={"id": "\d+"})
     */
    public function posts(PostRepository $repository, User $user)
    {
        $posts = $repository->findBy(
            ['user' => $user], // filtre
            ['publicationDate' => 'DESC'] // tri

        );

        return $this->render('admin/user/post.html.twig',
            ['posts' => $posts,
             'user' => $user
            ]
        );
    }




}