<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request, UserManager $UserManager, LoggerInterface $logger): Response
    {
        $user = new User();
        // ...

        $form = $this->createForm(UserType::class, $user);
        $errors = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            // Création du slug, avec gestion de suffixe si le slug
            // existe déjà
            $slugger = new AsciiSlugger();
            $slug_suffix = 0;
            $slug_base = $slugger->slug($user->getLogin());
            do {
                $slug = $slug_base;
                if ($slug_suffix !== 0) {
                    $slug .= "-" . $slug_suffix;
                }
                ++$slug_suffix;
            } while (count($UserManager->get_users_by_slug($slug)) != 0);
            $user->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));

            $this->addFlash(
                'notice',
                'Bienvenue sur EkuZone ' . $user->getLogin() . "! Il est temps de compléter votre collection !"
            );

            return $this->redirectToRoute('user_profile', ['slug' => $slug]);

        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
