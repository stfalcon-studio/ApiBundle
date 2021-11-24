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

use Symfony\Component\Workflow\Registry;

/**
 * WorkflowsTrait.
 */
trait WorkflowsTrait
{
    protected Registry $workflows;

    /**
     * @param Registry $workflows
     *
     * @required
     */
    public function setWorkflows(Registry $workflows): void
    {
        $this->workflows = $workflows;
    }
}
