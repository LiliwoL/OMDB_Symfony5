<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class FirstListener
{
    private $_logger;

    public function __construct(LoggerInterface $logger){
        $this->_logger = $logger;
    }

    // Je vais écouter l'événement kernel.request
    public function onKernelRequest(RequestEvent $event)
    {
        $this->_logger->info("Evenement Kernel Request");
        $this->_logger->debug("Type: " . $event->getRequestType());
    }
}