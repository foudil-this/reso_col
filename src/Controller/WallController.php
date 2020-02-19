<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommunityRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WallController extends AbstractController
{
    /**
     * @Route("/wall")
     */
    public function index(UserRepository $userRepository,
                          CommunityRepository $communityRepository,
                          PostRepository $postRepository)
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

        return $this->render('wall/index.html.twig',

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



    /**
     * @Route("/murcommunity/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function murcommunity(UserRepository $userRepository,
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
        return $this->render('wall/index.html.twig',

            [
                'groupes'=>$groupes,
                'nom'=>$nom,
                'nomgroupe'=>$nomgroupe,
                'postes'=>$posts,
                'groupesuser'=>$groupesuser,


            ]

        );
    }

    /**
     * @Route("/newpost/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function newpost(Request $request,
                            Community $community,
                            PostRepository $postRepository,
                            EntityManagerInterface $manager,
                            CommunityRepository $communityRepository,
                            $id
    )
    {
        $post = new Post();
        // $comm= new Community();
        $comm=$communityRepository->findOneBy(['id'=>$id]);


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var    UploadedFile|null $image */
                $image = $post->getImage();

                // test si une image est saisi dans le formulaire
                if (!is_null($image)) {
                    // nom pour la BDD
                    $image = $form->get('image')->getData();
                    $imagefilename = uniqid() . '.' . $image->guessExtension();

                    // deplacer le fichier vers le repertoire de stockage
                    $image->move(
                    // repertoire de destination fait dans config/services.yaml
                        $this->getParameter('upload_dir'),
                        // nom du fichier
                        $imagefilename
                    );
                    // on sette l'image' de l'article avec le nom du fichier
                    // pour enregistrement
                    $post->setImage($imagefilename);
                    $post->setCommunity($comm);
                    $post->setUser($this->getUser());
                    $post->setType('image');

                    // insertion dans la BDD
                    $manager->persist($post);
                    $manager->flush();

                    $this->addFlash('success', 'Votre post a était rajouté');

                    // dump($user);
                    // retour de l'objet userController vers index
                    return $this->redirectToRoute('app_wall_index');


                } else {
                    $this->addFlash('error', 'le formulaire contient des erreurs');
                }
            }

        }
        // retour de l'objet userController vers inscription
        return $this->render(
            'wall/newpost.html.twig',
            ['form' => $form->createView()]
        );
    }
}
