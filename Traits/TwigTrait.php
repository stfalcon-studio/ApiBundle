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

use Twig\Environment;

/**
 * TwigTrait.
 */
trait TwigTrait
{
    protected Environment $twig;

    /**
     * @param Environment $twig
     *
     * @required
     */
    public function setTwigEnvironment(Environment $twig): void
    {
        $this->twig = $twig;
    }
}
