<?php

namespace App\Controller;

use App\Entity\Community;
use App\Form\CommunityType;
use App\Repository\CommunityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommunityController
 * @package App\Controller
 *
 * @Route("/community")
 */
class CommunityController extends AbstractController
{
    /**
     * @Route("/index")
     */
    public function index()
    {
        return $this->render('community/index.html.twig', [
            'controller_name' => 'CommunityController',
        ]);
    }

    /**
     * @Route("/edit")
     *
     */
    public function edit(Request $request, EntityManagerInterface $manager)
    {
        $community = new Community();

        $form = $this->createForm(CommunityType::class, $community);

        $form->handleRequest($request);
            if($form->isSubmitted()){
                if($form->isValid()){
                    dump($community);

                    /** @var    UploadedFile|null $image */
                    $image = $community->getImage();

                    // test si un avatar est saisi dans le formulaire
                    if (!is_null($image)) {
                        // nom pour la BDD
                        $imageFileName = uniqid() . '.' . $image->guessExtension();

                        // deplacer le fichier vers le repertoire de stockage
                        $image->move(
                        // repertoire de destination fait dans config/services.yaml
                            $this->getParameter('upload_dir'),
                            // nom du fichier
                            $imageFileName
                        );

                        // on sette l'image' de l'article avec le nom du fichier
                        // pour enregistrement
                        $community->setImage($imageFileName);
                    }
                    $owner = $this->getUser();
                    $community
                        ->setOwner($owner)
                        // ajout dans dans community de l'owner
                        ->addUser($owner)
                    ;

                    // insertion dans la BDD, mise à jour des 2 tables
                    // table community et table community user
                    $manager->persist($community);
                    $manager->flush();




                    $this->addFlash('success', 'Votre association est créee');

                    // dump($user);
                    // retour de l'objet userController vers index
                    return $this->redirectToRoute('app_community_addusercommunity', ['id' => $community->getId()]);


                } else {
                    $this->addFlash('error', 'le formulaire contient des erreurs');
                }

            }

        return $this->render('community/edit.html.twig',
            [ 'form' => $form->createView()]
        );
    }


    /**
     * @Route("/adduser/{id}")
     *
     *
     */
    public function addUserCommunity(Community $community, Request $request, UserRepository $userRepository, EntityManagerInterface $manager, $id)
    {
        $users = $community->getUsers();


        $owner = $community->getOwner();
        dump($users[1]);
        // test si il y a bien quelque chose dans $_POST
        //// if (!empty($_POST['email_user_add']))
        if($request->request->has('email_user_add')){
            $email = $request->request->get('email_user_add');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->addFlash('error', 'l\'Email saisi n\'est pas valide');
            } else {
                // recherche dans la table user du user ayant l'email saisi
                $userAdded= $userRepository->findOneBy(
                    ['email' => $request->request->get('email_user_add')]
                );
                if(is_null($userAdded)){
                    $this->addFlash('error', 'le membre n\'est pas inscrit, son inscription est obligatoire');
                }else{

                    // test si le membre est deja dans l'association
                    if ($community->getUsers()->contains($userAdded)) {
                        $this->addFlash('error', 'le membre est dejà dans cette association');
                    }else{



                        dump($userAdded);
                        dump($community);


                        // ajout dans community du user saisi dans le formulaire (son email)
                        $community->addUser($userAdded);



                        // connexion à la BDD
                        $manager->persist($community);
                        $manager->flush();
                    }
                }
            }


        }


        dump($request);
        return $this->render('community/addUserCommunity.html.twig', ['community' => $community,
            'users' => $users]);
    }


    /**
     * @Route("/deleteuser/{id_c}/{id_u}")
     *
     */
    public function deleteUserCommunity($id_c, $id_u, CommunityRepository $communityRepository, UserRepository $userRepository, EntityManagerInterface $manager)
    {

        $community = $communityRepository->find($id_c);
        $user = $userRepository->find($id_u);
        dump($community);
        dump($user);

        $community->removeUser($user);

        $manager->persist($community);
        $manager->flush();



       return $this->redirectToRoute('app_wall_index');
    }


}
