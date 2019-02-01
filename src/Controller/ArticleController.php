<?php


namespace App\Controller;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
//use Michelf\MarkdownInterface;
use App\Service\SlackClient;
//use Nexy\Slack\Client;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
//use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

//class ArticleController// as soon as you want to render a template, yuo need to extend a base class
class ArticleController extends AbstractController
{

//    /**
//     * @Route("/", name="app_homepage")
//     */
//    public function homepage(EntityManagerInterface $em)
//    {
////        return new Response('omg! my first page already! woohoo!');
////        return $this->render('article/homepage.html.twig');
//        $repository = $em->getRepository(Article::class);
////        $articles = $repository->findAll();
////        $articles = $repository->findBy([],['publishedAt'=>'DESC']);
//        $articles = $repository->findAllPublishedOrderedByNewest();
//        return $this->render('article/homepage.html.twig',[
//            'articles'=>$articles,
//        ]);
//    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByNewest();
        return $this->render('article/homepage.html.twig',[
            'articles'=>$articles,
        ]);
    }

//    /**
//     * @Route("/news/why-asteroids-taste-like-bacon")
//     */
//    public function show()
//    {
//        return new Response('Future page to show one space article!');
//    }

//    /**
//     * @Route("/news/{slug}", name="article_show")
//     */
////    public function show($slug, Environment $twigEnvironment, MarkdownInterface $markdown, AdapterInterface $cache, MarkdownHelper $markdownHelper)
////    public function show($slug, Environment $twigEnvironment,  MarkdownHelper $markdownHelper, bool $isDebug, Client $slack)
//    public function show($slug, Environment $twigEnvironment,  MarkdownHelper $markdownHelper, bool $isDebug, SlackClient $slack, EntityManagerInterface $em)
//    {
////        return new Response(sprintf('Future page to show the article: %s!', $slug));
//
////        dd($this->getParameter('cache_adapter'));//getParameter() method can get the parameter defined in our container, know debug:container --parameters
//
//        if($slug == 'khaaaaaaan'){
////            $message = $slack->createMessage()
////                ->from('Khan')
////                ->withIcon(':ghost:')
////                ->setText('Ah, Kirk! my old friend....')
////            ;
////
////            $slack->sendMessage($message);
//         //since we created a new service SlackClient
//            $slack->sendMessage('Khan','Ah, sirKirk! my old friend....');
//        }
//
//        $repository = $em->getRepository(Article::class);
//
//        /**@var Article $article */
//        $article = $repository->findOneBy(['slug'=>$slug]);
//        if(!$article){
//            throw $this->createNotFoundException(sprintf('No article for slug "%s"',$slug));
//        }
//
//
//        $comments =[
//            'I ate a normal rock once. It did NOT taste like bacon!',
//            'Woohoo! I\'m going on an all-asteroid diet!',
//            'I like bacon too! Buy some from my site! bakinsomebacon.com',
//        ];
////        dump($slug,$this);
//
////        $articleContent =<<<EOF
////Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
////lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
////labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
////**turkey** shank eu pork belly meatball non cupim.
////
////Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
////laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
////capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
////picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
////occaecat lorem meatball prosciutto quis strip steak.
////
////Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
////mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
////strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
////cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
////fugiat.
////EOF;
//
////        $articleContent = $markdown->transform($articleContent);
////        dd($cache);
//
////*******************************************************************for reuse the codes, better to move it into its own service class
////        $item = $cache->getItem('markdown_'.md5($articleContent));
////        if(!$item->isHit()){
////            $item->set($markdown->transform($articleContent));
////            $cache->save($item);
////        }
////
////        $articleContent = $item->get();
////*******************************the above code is moved to markdownhelper class
//
////        $articleContent= $markdownHelper->parse($articleContent, $cache, $markdown);
//
//        //because using constructor args in our markdownhelper class,  the args to its constructor are autowired
//        //we can use any classes or interfaces from debug:autowiring as type-hints. so below don't need pass $cache and $markdown
////        $articleContent= $markdownHelper->parse($articleContent);
//
//
////*******************************************************************
////        $html = $twigEnvironment->render('article/show.html.twig',[
////            'title'=>ucwords(str_replace('-', ' ', $slug)),
////            'articleContent' => $articleContent,
////            'slug' =>$slug,
////            'comments'=>$comments,
////        ]);
////
////        return new Response($html);
////*********************************************************************
//        //the above two sentences are identical with the below one, $this->render is shortcut of twig environment service.
//
//
//        return $this->render('article/show.html.twig',[
//            'article'=>$article,
//            'comments'=>$comments,
//        ]);
//
//
//    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article, SlackClient $slack)
    {

        if($article->getSlug() == 'khaaaaaaan'){
            $slack->sendMessage('Khan','Ah, sirKirk! my old friend....');
        }


        $comments =[
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig',[
            'article'=>$article,
            'comments'=>$comments,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
//    public function toggleArticleHeart($slug, LoggerInterface $logger)
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {
////        return new Response(json_encode(['heart'=>5]));
////        return new JsonResponse(['hearts' => rand(5,100)]);
//        $logger->info('Article is being hearted');
//        return $this->json(['hearts' => rand(5,100)]);

//        $article->setHeartCount($article->getHeartCount()+1);
        $article->incrementHeartCount();
        $em->flush();
        return $this->json(['hearts' => $article->getHeartCount()]);
    }
}