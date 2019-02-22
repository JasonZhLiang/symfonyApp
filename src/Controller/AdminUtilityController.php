<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilityController extends AbstractController
{
    /**
     * @Route("/admin/utility/users", methods="GET", name="admin_utility_users")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function getUserApi(UserRepository $userRepository, Request $request)
    {
//        $users = $this->getUser();//when using getUser() method from parent class, no circular reference serializing error occurred, but below one does.
        //by default, the serializer will serialize every property that has a getter method, but apiTokens contain a user object, so back serialize user
        //so we can serialize same group as we did in AccountController to solve this issue.
//        $users = $userRepository->findOneBy(['email' => 'spacebar0@example.com']);
        $users = $userRepository->findAllMatching($request->query->get('query'));
        return $this->json([
            'users' => $users,
        ],200,[],['groups'=>['main']]);
    }
}