<?php

/*
 * This file is part of the WhteRbtFoundationBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FoundationBundle\Entity;

interface RouteAwareInterface
{
    /**
     * Sets distance.
     *
     * @param float $distance
     *
     * @return LocationAwareInterface
     */
    public function setDistance($distance);

    /**
     * Returns distance.
     *
     * @return float
     */
    public function getDistance();

    /**
     * Returns destinationTerm.
     *
     * @return string
     */
    public function getDestinationTerm();

    /**
     * Returns originTerm.
     *
     * @return string
     */
    public function getOriginTerm();

    /**
     * Returns destinationCoordinates.
     *
     * @return array
     */
    public function getDestinationCoordinates();

    /**
     * Sets destinationLatitude.
     *
     * @param float $destinationLatitude
     *
     * @return LocationAwareInterface
     */
    public function setDestinationLatitude($destinationLatitude);

    /**
     * Returns destinationLatitude.
     *
     * @return
     */
    public function getDestinationLatitude();

    /**
     * Sets destinationLongitude.
     *
     * @param float $destinationLongitude
     *
     * @return LocationAwareInterface
     */
    public function setDestinationLongitude($destinationLongitude);

    /**
     * Returns destinationLongitude.
     *
     * @return float
     */
    public function getDestinationLongitude();

    /**
     * Returns originCoordinates.
     *
     * @return array
     */
    public function getOriginCoordinates();

    /**
     * Sets originLatitude.
     *
     * @param float $originLatitude
     *
     * @return LocationAwareInterface
     */
    public function setOriginLatitude($originLatitude);

    /**
     * Returns originLatitude.
     *
     * @return
     */
    public function getOriginLatitude();

    /**
     * Sets originLongitude.
     *
     * @param float $originLongitude
     *
     * @return LocationAwareInterface
     */
    public function setOriginLongitude($originLongitude);

    /**
     * Returns originLongitude.
     *
     * @return float
     */
    public function getOriginLongitude();
}
