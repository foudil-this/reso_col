<?php


namespace App\Controller\Admin;


use App\Entity\Community;
use App\Repository\CommunityRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommunityController
 * @package App\Controller\Admin
 * @Route("/community")
 */
class CommunityController extends AbstractController
{
    /**
     * @param CommunityRepository $repository
     * @return Response
     * @Route("/")
     */
    public function index(CommunityRepository $repository)
    {
        $communities = $repository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/community/index.html.twig',
            ['communities' => $communities]
        );
    }

    /**
     *Paramconverter : le parametre typé Community contient la categorie
     * dont l'id est passé dans la partie variable de l'url
     *
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     * @param EntityManagerInterface $manager
     * @param Community $community
     * @return RedirectResponse
     */
    public function delete(EntityManagerInterface $manager, Community $community)
    {
        // suppression de la categorie en bdd
        $manager->remove($community);
        $manager->flush();
        $this->addFlash('success', 'La community est supprimée');

        return $this->redirectToRoute('app_admin_community_index');

    }
    /**
     * @param PostRepository $repository
     * @param Community $community
     * @Route("/posts/{id}", requirements={"id": "\d+"})
     * @return Response
     */
    public function posts(CommunityRepository $repository, Community $community)
    {
        $posts = $community->getPosts();

        return $this->render('admin/community/posts.html.twig',
            [
                'posts'=>$posts,
                'community'=>$community,

            ]
        );

    }

    /**
     * @param CommunityRepository $repository
     * @param Community $community
     * @return Response
     * @Route("/users/{id}", requirements={"id": "\d+"})
     */
    public function users(CommunityRepository $repository, Community $community)
    {
        $users = $community->getUsers();

        return $this->render('admin/community/users.html.twig',
            [
                'users'=>$users,
                'community'=>$community
            ]
            );
    }

}