<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{

//    private $helper;

//    public function __construct(MarkdownHelper $helper)
//    {
//
//        $this->helper = $helper;
//    }

    private $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe'=>['html']]),
        ];
    }

//    public function getFunctions(): array
//    {
//        return [
//            new TwigFunction('function_name', [$this, 'doSomething']),
//        ];
//    }

    public function processMarkdown($value)
    {
//        return strtoupper($value);
//        return $this->helper->parse($value);
        return$this->container
//            ->get(MarkdownHelper::class)
            ->get('foo')
            ->parse($value);
    }

    public static function getSubscribedServices()
    {
        return [
//            MarkdownHelper::class
            'foo' => MarkdownHelper::class
        ];
    }
}
