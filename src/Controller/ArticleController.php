<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

//class ArticleController// as soon as you want to render a template, yuo need to extend a base class
class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage(){
        return new Response('omg! my first page already! woohoo!');
    }

    /**
     * @Route("/news/why-asteroids-taste-like-bacon")
     */
//    public function show()
//    {
//        return new Response('Future page to show one space article!');
//    }

    /**
     * @Route("/news/{slug}")
     */
    public function show($slug)
    {
//        return new Response(sprintf('Future page to show the article: %s!', $slug));

        $comments =[
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig',[
            'title'=>ucwords(str_replace('-', ' ', $slug)),
            'comments'=>$comments,
        ]);
    }

}