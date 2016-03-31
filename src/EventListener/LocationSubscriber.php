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

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Geocoder\GeocoderInterface;
use Geocoder\Result\ResultInterface;
use WhteRbt\FoundationBundle\Entity\LocationAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * This subscriber needs the BazingaGeocoderBundle (service
 * "bazinga_geocoder.geocoder") to work properly.
 */
class LocationSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;

    /**
     * Returns fields used for potential location changes.
     *
     * @var array
     */
    protected $locationFields = [
        'street',
        'postcode',
        'city',
        'country',
    ];

    /**
     * Returns events to listen.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Sets location coordinates before persisting.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getEntity();

        if ($object instanceof LocationAwareInterface) {
            $this->updateCoordinates($object);
        }
    }

    /**
     * Sets location coordinates before updating.
     *
     * Method will only call geocode() if one of the location fields was changed
     * by the user.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getEntity();

        if ($object instanceof LocationAwareInterface) {
            if (count(array_intersect(array_keys($eventArgs->getEntityChangeSet()), $this->locationFields)) > 0) {
                $this->updateCoordinates($object);
            }
        }
    }

    /**
     * Updates coordinates of object.
     *
     * @param LocationAwareInterface $object
     *
     * @return ResultInterface|null
     */
    protected function updateCoordinates(LocationAwareInterface $object)
    {
        if ($this->container->has('bazinga_geocoder.geocoder')) {
            /** @var GeocoderInterface $geocoder */
            $geocoder = $this->container->get('bazinga_geocoder.geocoder');

            /** @var ResultInterface $result */
            $result = $geocoder->geocode($object->getLocationTerm());

            $object->setLatitude($result->getLatitude());
            $object->setLongitude($result->getLongitude());

            return $result;
        }

        return;
    }
}
