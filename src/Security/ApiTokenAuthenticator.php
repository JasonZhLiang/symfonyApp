<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
//use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{

    private $apiTokenRepo;

    private $translation;

    public function __construct(ApiTokenRepository $apiTokenRepo)//, TranslationExtension $translation)
    {

        $this->apiTokenRepo = $apiTokenRepo;
        //$this->translation = $translation;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        //skip beyond "Bearer "
        return substr($authorizationHeader,7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepo->findOneBy([
            'token' => $credentials,
        ]);

        if(!$token){
//            return; //return null, will make this authentication fail
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }

        if($token->isExpired()){
            throw new CustomUserMessageAuthenticationException('Token Expired');
        }

        //if we do find one token, we need return the associated user
        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
//        dd('checking credentials');
        //since no password check for api token
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            //whenever authentication fails -for any reason-it's because, internally, some sort of AuthenticationException is thrown.
            //and it has a method - getMessageKey()- that holds a message about what went wrong.
            'message' =>$exception->getMessageKey(),
//            'message' => $this->translation->trans($exception->getMessageKey(),$exception->getMessageData(), 'apitoken'),

        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // allow the request to continue
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new \Exception('Not used: entry_point from other authenticator is used.');
    }

    public function supportsRememberMe()
    {
        return false;//return true just means the 'the remember me" system is activated and looking for that _remember_me checkbox to be checked. no sense for an API
    }
}
