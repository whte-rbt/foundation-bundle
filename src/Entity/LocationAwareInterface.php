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

interface LocationAwareInterface
{
    /**
     * Returns coordinates.
     *
     * @return array
     */
    public function getCoordinates();

    /**
     * Sets latitude.
     *
     * @param float $latitude
     *
     * @return LocationAwareInterface
     */
    public function setLatitude($latitude);

    /**
     * Returns latitude.
     *
     * @return
     */
    public function getLatitude();

    /**
     * Returns longitude.
     *
     * @return float
     */
    public function getLongitude();

    /**
     * Sets longitude.
     *
     * @param float $longitude
     *
     * @return LocationAwareInterface
     */
    public function setLongitude($longitude);

    /**
     * Returns locationTerm.
     *
     * @return string
     */
    public function getLocationTerm();
}
