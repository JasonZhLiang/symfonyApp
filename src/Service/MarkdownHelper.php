<?php


namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Twig\Environment;

class MarkdownHelper
{
//    public function parse(string $source, AdapterInterface $cache, MarkdownInterface $markdown):string
//    {
//        $item = $cache->getItem('markdown_'.md5($source));
//        if(!$item->isHit()){
//            $item->set($markdown->transform($source));
//            $cache->save($item);
//        }
//
//        return $item->get();
//    }

    private $cache;

    private $markdown;

    private $logger;

    private $isDebug;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug)
        //this the first time we put a constructor argument is not a service, so Symfony will not autowiring this value, so config this at service.yaml
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source):string
    {
        if(stripos($source,'bacon') !== false){
            $this->logger->info('They are talking about bacon again!');
        }
//        dd($this->cache);

        if ($this->isDebug){//using this code to completely disable caching when in the dev environment
            return $this->markdown->transform($source);
        }


        $item = $this->cache->getItem('markdown_'.md5($source));
        if(!$item->isHit()){
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}