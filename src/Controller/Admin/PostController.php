<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller\Admin
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @param PostRepository $repository
     * @return Response
     * @Route("/")
     */
    public function index(PostRepository $repository)
    {

        $posts = $repository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/post/index.html.twig',
            ['posts' => $posts]
        );
    }

    /**
     *Paramconverter : le parametre typé Post contient la categorie
     * dont l'id est passé dans la partie variable de l'url
     *
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     * @param EntityManagerInterface $manager
     * @param Post $post
     * @return RedirectResponse
     */
    public function delete(EntityManagerInterface $manager, Post $post)
    {
        $post->setStatus(false);
        // suppression de la categorie en bdd
        $manager->persist($post);
        $manager->flush();
        $this->addFlash('success', 'Le post est modéré');

        return $this->redirectToRoute('app_admin_post_index');

    }

    /**
     * @Route("/comments{id}", requirements={"id": "\d+"})
     * @param PostRepository $repository
     * @param Post $post
     * @return Response
     */
    public function comments(PostRepository $repository, Post $post)
    {
        $comments = $post->getComments();
        return $this->render('admin/post/comments.html.twig',
            [
                'comments'=>$comments,
                'post'=>$post
            ]
        );
    }

    /**
     * @param PostRepository $repository
     * @param Post $post
     * @Route("/detail{id}", requirements={"id": "\d+"})
     * @return Response
     */
    public function detail(PostRepository $repository, Post $post)
    {
        return $this->render('admin/post/detail.html.twig',
            [
                'post'=>$post
            ]
        );
    }


}