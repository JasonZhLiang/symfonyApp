<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use function GuzzleHttp\Psr7\_parse_request_uri;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleAdminController extends AbstractController
{
    /**
     * @Route("admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        //*************use fixtures, so will delete this block below************
//        $article = new Article();
//        $article->setTitle('Why Asteroids Taste Like Bacon')
//            ->setSlug('why-asteroids-taste-like-bacon-'.rand(100,999))
//            ->setContent(<<<EOF
//Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
//lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
//labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
//**turkey** shank eu pork belly meatball non cupim.
//
//Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
//laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
//capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
//picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
//occaecat lorem meatball prosciutto quis strip steak.
//
//Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
//mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
//strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
//cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
//fugiat.
//EOF
//
//            );
//
//        //publish most articles
//        if(rand(1,10) > 2){
//            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
//        }
//
//        $article->setAuthor('Mike Ferengi')
//            ->setHeartCount(rand(5,100))
//            ->setImageFilename('asteroid.jpeg');
//
//        $em->persist($article);
//        $em->flush();
        //*****************************move above block to fixtures class************************

//        return new Response(sprintf(
//            'Hiya! New article id: #%d slug: :%s',
//            $article->getId(),
//            $article->getSlug()
//        ));

        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);//only handle the data when it's a post request
        if ($form->isSubmitted() && $form->isValid()){
//            $data = $form->getData();
//            dd($data);
//            $article = new Article();
//            $article->setTitle($data['title']);
//            $article->setContent($data['content']);

            /** @var Article $article */
            $article = $form->getData();//since in ArticleFormType class we bind the form to Article, so now $form->getData return Article object directly.
//            $article->setAuthor($this->getUser());//since getUser is not a field of form getData now, so we need keep setAuthor here, we will remove it once set this field

            $em->persist($article);
            $em->flush();

            $this->addFlash('success','Article Created! Knowledge is power!');//is a shortcut to set a message in the session. But, flash messages only live in the session until they are read for the first time.

            return $this->redirectToRoute('admin_article_list');
        }
        return $this->render('article_admin/new.html.twig',[
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
//        if ($article->getAuthor() != $this->getUser() && !$this->isGranted('ROLE_ADMIN_ARTICLE')){//to centralize this logic for reusing, use voter
//        if (!$this->isGranted('MANAGE', $article)){
//            throw $this->createAccessDeniedException('No access!');
//        }
//        $this->denyAccessUnlessGranted('MANAGE',$article);//this is s shortcut for above, can move to annotation
//        dd($article);

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);//only handle the data when it's a post request
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($article);
            $em->flush();
            $this->addFlash('success','Article Updated! Inaccuracies Squashed!');//is a shortcut to set a message in the session. But, flash messages only live in the session until they are read for the first time.
            return $this->redirectToRoute('admin_article_edit',[
                'id' => $article->getId(),
            ]);
        }
        return $this->render('article_admin/edit.html.twig',[
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article", name="admin_article_list")
     */
    public function list(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();
        return $this->render('article_admin/list.html.twig',[
            'articles' => $articles,
        ]);
    }
}