<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController
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
        return new Response(sprintf('Future page to show the article: %s!', $slug));
    }

}