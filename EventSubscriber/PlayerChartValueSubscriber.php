<?php
namespace VideoGamesRecords\CoreBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use VideoGamesRecords\CoreBundle\Entity\PlayerChart;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

final class PlayerChartValueSubscriber implements EventSubscriberInterface
{

    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setValue', EventPriorities::POST_VALIDATE],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function setValue(GetResponseForControllerResultEvent $event)
    {
        $playerChart = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$playerChart instanceof PlayerChart || Request::METHOD_PUT !== $method) {
            return;
        }

        foreach ($playerChart->getLibs() as $lib) {
            $lib->setValueFromPaseValue();
        }
    }
}
