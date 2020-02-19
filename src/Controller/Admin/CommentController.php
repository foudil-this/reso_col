<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller\Admin
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @param CommentRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/index")
     */
    public function index(CommentRepository $repository)
    {
        $comments = $repository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/comment/index.html.twig',
            ['comments' => $comments]
        );
    }



    public function delete(EntityManagerInterface $manager, Comment $comment)
{
    // suppression de la categorie en bdd
    $manager->remove($comment);
    $manager->flush();
    $this->addFlash('success', 'Le comment est supprimé');

    return $this->redirectToRoute('app_admin_comment_index');

}


}