<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Run/Walk 812 Login',
            'csrf_token_intention' => 'authenticate',
            'favicon_path' => 'images/favicon-32x32.png',
            'target_path' => $this->generateUrl('admin'),
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {

    }
}
