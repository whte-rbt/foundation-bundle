<?php

/*
 * This file is part of the WhteRbtFoundationBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FoundationBundle\EventListener;

use InvalidArgumentException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractFlashSubscriber implements EventSubscriberInterface
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var SessionBagInterface
     */
    protected $flashBag;

    /**
     * Constructor.
     *
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;

        /* @var SessionBagInterface flashBag */
        $this->flashBag = $this->session->getFlashBag();
    }

    /**
     * Returns success messages.
     *
     * @return array
     */
    abstract public function getSuccessMessages();

    /**
     * Returns error messages.
     *
     * @return array
     */
    abstract public function getErrorMessages();

    /**
     * Sets success type flash message.
     *
     * @param Event  $event
     * @param string $eventName
     */
    public function addSuccessFlash(Event $event, $eventName)
    {
        $successMessages = $this->getSuccessMessages();

        if (!isset($successMessages[$eventName])) {
            throw new InvalidArgumentException('This event does not correspond to a known flash message.');
        }

        $this->flashBag->add('success', $this->translator->trans($successMessages[$eventName]));
    }

    /**
     * Sets error type flash message.
     *
     * @param Event  $event
     * @param string $eventName
     */
    public function addErrorFlash(Event $event, $eventName)
    {
        $errorMessages = $this->getErrorMessages();

        if (!isset($errorMessages[$eventName])) {
            throw new InvalidArgumentException('This event does not correspond to a known flash message.');
        }

        $this->flashBag->add('danger', $this->translator->trans($errorMessages[$eventName]));
    }
}
