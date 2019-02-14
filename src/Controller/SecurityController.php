<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('will be intercepted before getting here, CHECK security.yaml file to see what added');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator)
    {
        //todo - use symfony forms & validation later
        if ($request->isMethod('POST')){
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setFirstName('Mystery');//we don't have this field in our register form, hard code fro now
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));

            $em = $this->getDoctrine()->getManager();//this is another way to use ObjectManager than using this service by passing it as an argument. Controller build in method.
            $em->persist($user);
            $em->flush();

//            return $this->redirectToRoute('app_account');
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );//providerKey is just the name of your firewall
        }
        return $this->render('security/register.html.twig');
    }
}
