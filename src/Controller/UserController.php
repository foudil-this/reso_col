<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // cryptage du mot de passe
                $encodedPassword = $passwordEncoder->encodePassword(
                    $user,
                    $user->getPlainpassword()
                );
                $user->setPassword($encodedPassword);

                // insertion dans la BDD
                $manager->persist($user);
                $manager->flush();


                $this->addFlash('success', 'Votre compte est crée');

                // retour de l'objet userController vers index
                return $this->redirectToRoute('app_index_index');

            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        // retour de l'objet userController vers inscription

        return $this->render('user/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
