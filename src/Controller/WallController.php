<?php


namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
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
     * @Route("/wall/{id}/{type}", defaults={"id": null ,"type":null})
     */
    public function index(UserRepository $userRepository,
                          CommunityRepository $communityRepository,
                          PostRepository $postRepository,
                          $id,
                          $type,
                          EntityManagerInterface $manager,
                          CommentRepository $commentRepository,

                          Request $request


    )
    {

        $vue_formulaire = false;
        // fonction qui s'execute uniquement si un id de groupe est passé dans l'url et renvoi
        //le mur du groupe , le fil des poste de tous les membres du groupe

        //recuperation des groupe et de leur membres de l'utilisateur conecté
        $groupes = $communityRepository->findBy(['owner' => $this->getUser()]);
        //recuperation des donné de l'utilisateur
        $user = $userRepository->findOneBy(['id' => $this->getUser()]);
        // recupére les donnée utilistaeur da   ns un tableau pour extraire la liste
        //des nom de groupe ou il est mombre mais pas proprio
        $groupesuser = $userRepository->findBy(['id' => $this->getUser()]);

        if (is_null($id)) {
            //si y a pas d'id dans l'url c'est que c'est la page utilisateur qui est chargé
            //recupére la liste de tous  poste de notre utilisateur
            $posts = $postRepository->findBy(['user' => $this->getUser()]);
            $nom = 'afficheMuruser';
            $commu = $userRepository->findOneBy(['id' => $this->getUser()]);

        }

        if (!is_null($id)) {
            // s'il y a un id dans l'url c'est que c'est la demande d'ouverture du mur d'un groupe
            //donc l'id passé est celui d'une community a afficher sur la partie droit de la vue
            //la variable $nom transmé u,e i,formation pour l'affichage du mur groupe ou du mur user
            $nom = 'afficheMurGroupe';
            $commu = $communityRepository->findOneBy(['id' => $id]);
            $posts = $postRepository->findBy(['community' => $id]);

        }
        //_________________________________________________________________
        // test s'il y a un paramétre type passé pour envoyer le formulaire
        //________________________________________________________________
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        if (isset($type)) {

            // renvoi le mur du groupe pour affichage
            $comm = $communityRepository->findOneBy(['id' => $id]);
            $vue_formulaire = true;
            // affichage avec les poste du groupe
            $nom = 'afficheMurGroupe';


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
                        // on sette l'image' dposte avec le nom du fichier
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

                        return $this->redirectToRoute('app_wall_index', ['id' => $id]);


                    } else {
                        $this->addFlash('error', 'le formulaire contient des erreurs');
                    }
                }

            }

        }


        dump($nom);
        dump($commu);

        return $this->render('wall/index.html.twig',

            [
                'groupes' => $groupes,
                //variable pour indiquer si c'est pour le mur de l'utilistauer ou le fil d'un groupe

                'nom' => $nom,
                //donnée a afficher sur le banner
                'nomgroupe' => $commu,
                //l'ensemble des postes
                'postes' => $posts,
                'groupesuser' => $groupesuser,
                //renvoi de l'id du groupe qui a était selectionné
                'id_groupe' => $id,
                // variable qui ne doit plus servir a retirer apres teste
                'user' => $this->getUser(),
                'vue' => $vue_formulaire,
                'form' => $form->createView(),


            ]

        );
    }
}

