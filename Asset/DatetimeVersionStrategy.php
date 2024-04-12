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

namespace StfalconStudio\ApiBundle\Asset;

use Fresh\DateTime\DateTimeHelper;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * DatetimeVersionStrategy.
 */
class DatetimeVersionStrategy implements VersionStrategyInterface
{
    /** @var string */
    private readonly string $version;

    /**
     * @param DateTimeHelper $dateTimeHelper
     */
    public function __construct(DateTimeHelper $dateTimeHelper)
    {
        $this->version = $dateTimeHelper->getCurrentDatetimeImmutable()->format('YmdHis');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getVersion(string $path): string
    {
        return $this->version;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    #[Pure]
    public function applyVersion(string $path): string
    {
        return sprintf('%s?v=%s', $path, $this->getVersion($path));
    }
}
