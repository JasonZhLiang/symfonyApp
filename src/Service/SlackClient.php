<?php


namespace App\Service;


use App\Helper\LoggerTrait;
use Nexy\Slack\Client;
//use Psr\Log\LoggerInterface;

class SlackClient
{
    use LoggerTrait;

    private $slack;

    //by adding some PHPDoc on the property, help phpstorm auto-completion
//    /**
//     * @var LoggerInterface|null
//     */
//    private $logger;
//  move this property to trait


    public function __construct(Client $slack)
    {

        $this->slack = $slack;
    }

    //for optional dependencies, setter inject is a way to pass dependencies to the class
    //as long as you put required at annotation, symfony will call this method before give us this object
//    /**
//     * @required
//     */
//    public function setLogger(LoggerInterface $logger)
//    {
//        $this->logger = $logger;
//    }
//  move this method to trait

    public function sendMessage(string $from, string $message)
    {
//        if($this->logger){
//            $this->logger->info('Beaming a message to slack.');
//        }

        $this->logInfo('Beaming a message to slack!',[
            'message'=> $message
        ]);

        $slackMessage = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message)
        ;

        $this->slack->sendMessage($slackMessage);
    }
}