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
use WhteRbt\FoundationBundle\Entity\RouteAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * This subscriber needs the BazingaGeocoderBundle (service
 * "bazinga_geocoder.geocoder") to work properly.
 */
class RouteSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;

    /**
     * Returns fields used for potential origin changes.
     *
     * @var array
     */
    protected $originFields = [
        'originStreet',
        'originPostcode',
        'originCity',
        'originCountry',
    ];

    /**
     * Returns fields used for potential destination changes.
     *
     * @var array
     */
    protected $destinationFields = [
        'destinationStreet',
        'destinationPostcode',
        'destinationCity',
        'destinationCountry',
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
     * Sets origin and destination coordinates before persisting.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getEntity();

        if ($object instanceof RouteAwareInterface) {
            $this->updateOriginCoordinates($object);
            $this->updateDestinationCoordinates($object);
            $this->updateDistance($object);
        }
    }

    /**
     * Sets origin and destination coordinates before updating.
     *
     * Method will only call geocode() if one of the location fields was changed
     * by the user.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getEntity();

        if ($object instanceof RouteAwareInterface) {
            if (count(array_intersect(array_keys($eventArgs->getEntityChangeSet()), $this->originFields)) > 0) {
                $this->updateOriginCoordinates($object);
                $this->updateDistance($object);
            }
            if (count(array_intersect(array_keys($eventArgs->getEntityChangeSet()), $this->destinationFields)) > 0) {
                $this->updateDestinationCoordinates($object);
                $this->updateDistance($object);
            }
        }
    }

    /**
     * Updates origin coordinates of object.
     *
     * @param RouteAwareInterface $object
     *
     * @return ResultInterface|null
     */
    protected function updateOriginCoordinates(RouteAwareInterface $object)
    {
        if ($this->container->has('bazinga_geocoder.geocoder')) {
            /** @var GeocoderInterface $geocoder */
            $geocoder = $this->container->get('bazinga_geocoder.geocoder');

            /** @var ResultInterface $result */
            $result = $geocoder->geocode($object->getOriginTerm());

            $object->setOriginLatitude($result->getLatitude());
            $object->setOriginLongitude($result->getLongitude());

            return $result;
        }

        return;
    }

    /**
     * Updates destination coordinates of object.
     *
     * @param RouteAwareInterface $object
     *
     * @return ResultInterface|null
     */
    protected function updateDestinationCoordinates(RouteAwareInterface $object)
    {
        if ($this->container->has('bazinga_geocoder.geocoder')) {
            /** @var GeocoderInterface $geocoder */
            $geocoder = $this->container->get('bazinga_geocoder.geocoder');

            /** @var ResultInterface $result */
            $result = $geocoder->geocode($object->getDestinationTerm());

            $object->setDestinationLatitude($result->getLatitude());
            $object->setDestinationLongitude($result->getLongitude());

            return $result;
        }

        return;
    }

    /**
     * Updates distance between origin and destination.
     *
     * @param RouteAwareInterface $object
     */
    protected function updateDistance(RouteAwareInterface $object)
    {
        $originLatitude = $object->getOriginLatitude();
        $originLongitude = $object->getOriginLongitude();
        $destinationLatitude = $object->getDestinationLatitude();
        $destinationLongitude = $object->getDestinationLongitude();

        $calculatedDistance = (acos(sin($originLongitude = deg2rad($originLongitude)) * sin($destinationLongitude = deg2rad($destinationLongitude)) + cos($originLongitude) * cos($destinationLongitude) * cos(deg2rad($destinationLatitude) - deg2rad($originLatitude))) * (6378.137)) * 0.621371192237334;

        $object->setDistance($calculatedDistance);
    }
}
