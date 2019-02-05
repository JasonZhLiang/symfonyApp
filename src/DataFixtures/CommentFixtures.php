<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

//class CommentFixtures extends BaseFixture
//in order to make fixture load different fixtures in a certain order, we need implements this interface, then implement a method from this interface
class CommentFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class,100, function(Comment $comment){
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2,true)
            );
            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

//            $comment->setArticle($this->getReference(Article::class.'_'.$this->faker->numberBetween(0,9)));//when we build articles, call createMany create 10 and addReference 0-9 due to the for loop

            //using the common method getRandomReference() from BaseFixture class which written by Ryan
            $comment->setArticle($this->getRandomReference(Article::class));
        });

        $manager->flush();
    }

    //this method will ensure fixtures:load order and avoid get an not generate class reference
    public function getDependencies()
    {
        //make sure ArticleFixtures load before CommentFixtures
        return [
            ArticleFixtures::class,
        ];
    }


}
