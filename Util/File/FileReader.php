<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Util\File;

/**
 * FileReader.
 */
class FileReader
{
    /**
     * @param string $filename
     *
     * @return false|string
     */
    public function getFileContents(string $filename)
    {
        return \file_get_contents($filename);
    }
}
