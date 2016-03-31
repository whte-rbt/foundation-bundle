<?php

/*
 * This file is part of the WhteRbtFoundationBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FoundationBundle\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This controller acts as a base controller for the application. It extends the
 * Symfony base controller and provides even more common methods.
 */
class Controller extends BaseController
{
    /**
     * Returns Event Dispatcher.
     *
     * @return EventDispatcherInterface
     *
     * @throws LogicException If Event Dispatcher is not available
     */
    protected function getDispatcher()
    {
        if (!$this->container->has('event_dispatcher')) {
            throw new LogicException('The Event Dispatcher is not registered in your application.');
        }

        return $this->container->get('event_dispatcher');
    }

    /**
     * Dispatches an event to all registered listeners (shortcut).
     *
     * @param string $eventName
     * @param Event  $event
     */
    protected function dispatchEvent($eventName, Event $event = null)
    {
        $this->getDispatcher()->dispatch($eventName, $event);
    }
}
