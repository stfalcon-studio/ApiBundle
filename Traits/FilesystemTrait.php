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

namespace StfalconStudio\ApiBundle\Traits;

use Symfony\Component\Filesystem\Filesystem;

/**
 * FilesystemTrait.
 */
trait FilesystemTrait
{
    protected Filesystem $filesystem;

    /**
     * @param Filesystem $filesystem
     *
     * @required
     */
    public function setFilesystem(Filesystem $filesystem): void
    {
        $this->filesystem = $filesystem;
    }
}
