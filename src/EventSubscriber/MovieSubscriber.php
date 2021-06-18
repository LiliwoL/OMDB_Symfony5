<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events;

class MovieSubscriber implements EventSubscriberInterface
{
    private $_logger;

    public function __construct(LoggerInterface $logger){
        $this->_logger = $logger;
    }

    public function onKernelController( $event)
    {
        dump($event);
        die();
    }

    public function onMovieCreated($event)
    {                   
        $this->_logger->info('★★★★★ Movie created ' . $event->getSubject());
    }

    public static function getSubscribedEvents()
    {
        return [
            // clé nom de l'événement => valeur méthode à lancer
            //'kernel.controller' => 'onKernelController',

            // Bonne pratique via App/Events.php
            Events::MOVIE_CREATED => 'onMovieCreated'
        ];
    }
}