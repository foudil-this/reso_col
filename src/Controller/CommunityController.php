<?php

namespace App\Controller;

use App\Entity\Community;
use App\Form\CommunityType;
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
                    $community->setOwner($owner);

                    // insertion dans la BDD
                    $manager->persist($community);
                    $manager->flush();


                    $this->addFlash('success', 'Votre association est créee');

                    // dump($user);
                    // retour de l'objet userController vers index
                    return $this->redirectToRoute('app_index_index');


                } else {
                    $this->addFlash('error', 'le formulaire contient des erreurs');
                }

            }

        return $this->render('community/edit.html.twig',
            [ 'form' => $form->createView()]
        );
    }
}
