<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ROLE_ADMIN")
 */
class CommentAdminController extends AbstractController
{
//     @IsGranted("ROLE_ADMIN")        //move @IsGranted Annotation from controller to the above class to control the whole class range  methods
    /**
     * @Route("/admin/comment", name="comment_admin")
     */
    public function index(CommentRepository $repository, Request $request, PaginatorInterface $paginator)
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN'); //better to use it in annotations
        $q = $request->query->get('q');
//        $comments = $repository->findBy([],['createdAt'=>'DESC']);
//        $comments = $repository->findAllWithSearch($q);

        $queryBuilder = $repository->getWithSearchQueryBuilder($q );

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );



        return $this->render('comment_admin/index.html.twig', [
//            'comments' => $comments,
            'pagination' => $pagination,
        ]);
    }
}
