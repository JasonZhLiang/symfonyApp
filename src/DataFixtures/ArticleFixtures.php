<?php

namespace App\DataFixtures;

use App\Entity\Article;
//use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    private static $articleTitles = [
        'Why Asteroids Taste Like Bacon',
        'Life on Planet Mercury: Tan, Relaxing and Fabulous',
        'Light Speed Travel: Fountain of Youth or Fallacy',
    ];
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];
    private static $articleAuthors = [
        'Mike Ferengi',
        'Amy Oort',
    ];

// when command doctrine:fixtures:load execute, will look for load() method, since ArticleFixtures class extends BaseFixture, so will execute the load() method which extends from BaseFixture
    protected function loadData(ObjectManager $manager)
    {
//        $this->createMany(Article::class,10,function($article,$count){//look below add use ($manager) right after function parameters
        //add Article before $article argument of callback function will let $article explicit what type of it is. then phpstorm can auto-complete when using it.
        $this->createMany(Article::class,10,function($article, $count) use ($manager){//add the $manager inside the function
//        for ($i=0; $i<10; $i++) {
//            $article = new Article();
            $article->setTitle($this->faker->randomElement(self::$articleTitles))
//                ->setSlug($this->faker->slug) //since we add sluggable feature on entity, so we do not need set it manually
                ->setContent(<<<EOF
Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.

Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.

Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF

                );

            //publish most articles
//            if (rand(1, 10) > 2) {
//                $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
//            }

            if($this->faker->boolean(70)){
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article->setAuthor($this->faker->randomElement(self::$articleAuthors))
                ->setHeartCount($this->faker->numberBetween(5,100))
                ->setImageFilename($this->faker->randomElement(self::$articleImages));
//
//            $manager->persist($article);

//***************************move comments to a separate fixture class****************************
//            $comment1 = new Comment();
//            $comment1->setAuthorName('Mike Ferengi')
//                ->setContent('I ate a normal rock once. It did NOT taste like bacon!')
//                ->setArticle($article);
//            $manager->persist($comment1);
//
//            $comment2 = new Comment();
//            $comment2->setAuthorName($this->faker->name)
//                ->setContent('Woohoo! I\'m going on an all-asteroid diet!')
//                ->setArticle($article);
//            $manager->persist($comment2);

            //the belows codes lives after the call to persist() is fine, just need to come before flush()
//            $article->addComment($comment1);
//            $article->addComment($comment2);
//************************************************************************************************
        });

        $manager->flush();
    }
}
