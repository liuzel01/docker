<?php

namespace DockwareSamplePlugin\Subscriber\Storefront;

use Shuwei\Storefront\Event\StorefrontRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            StorefrontRenderEvent::class => 'onStorefrontRender',
        ];
    }

    /**
     * @param StorefrontRenderEvent $event
     */
    public function onStorefrontRender(StorefrontRenderEvent $event)
    {
        $data = array(
            'dockware' => 1,
        );

        $event->setParameter('dockware', $data);
    }

}
