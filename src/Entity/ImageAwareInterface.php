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

interface ImageAwareInterface
{
    /**
     * Returns image with relative image path.
     *
     * @param string $image
     *
     * @return string
     */
    public function getImageWebPath($image);
}
