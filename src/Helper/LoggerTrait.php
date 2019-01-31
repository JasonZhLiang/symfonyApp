<?php
/**
 * Created by PhpStorm.
 * User: jliang
 * Date: 1/31/2019
 * Time: 12:25 PM
 */

namespace App\Helper;


use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logInfo(string $message, array $context = [])
    {
        if($this->logger){
            $this->logger->info($message, $context);
        }
    }
}