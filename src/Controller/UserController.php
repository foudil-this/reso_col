<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/inscription")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $manager)
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

            dump($user);
            // retour de l'objet userController vers index
           // return $this->redirectToRoute('app_index_index');

        } else {
            $this->addFlash('error', 'Le formulaire contient des erreurs');
        }
    }
    // retour de l'objet userController vers inscription
    return $this->render(
        'user/register.html.twig',
        ['form' => $form->createView()]
    );
}


    /**
     * @Route("/login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        // utilisation de la classe authentification
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        if(!empty($error)){
            $this->addFlash('error', 'Identifiants incorrects');
        }

        return $this->render(
            'user/login.html.twig',
            ['last_username' => $lastUsername]
        );
    }


    /**
     * @Route("/logout")
     */
    public function logout()
    {

    }





}
