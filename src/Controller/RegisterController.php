<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    // Récupération dans la BDD
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
    
        // Sauvegarder les informations dans la BDD
        $notification = null;
        
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        //Si envoyé et valide alors 
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            //Voir si utilisateur n'est pas existant dans la BDD
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                // Encodage du mot de passe
                $password = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                //Envoi dans la BDD
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                //Envoi d'un email
                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstname() . "<br/>Bienvenue sur le site de Lili Giroud, massage madérothérapeutique.<br><br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam expedita fugiat ipsa magnam mollitia optio voluptas! Alias, aliquid dicta ducimus exercitationem facilis, incidunt magni, minus natus nihil odio quos sunt?";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Lili Giroud', $content);

                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte";
            } else {
                $notification = "L'email que vous avez renseignée existe déjà";
            }
        }

        // Gérer la vue
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
