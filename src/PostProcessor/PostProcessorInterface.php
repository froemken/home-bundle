<?php
declare(strict_types=1);

namespace StefanFroemken\Home\PostProcessor;

/*
 * This file is part of the Home project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Interface for PostProcessors
 */
interface PostProcessorInterface
{
    /**
     * Post process bearer response
     *
     * @param mixed $response
     * @return array
     */
    public function process($response): array;
}
