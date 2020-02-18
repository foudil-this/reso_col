<?php


namespace App\Controller\Admin;


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
        // suppression de la categorie en bdd
        $manager->remove($post);
        $manager->flush();
        $this->addFlash('success', 'Le post est supprimée');

        return $this->redirectToRoute('app_admin_post_index');

    }
}