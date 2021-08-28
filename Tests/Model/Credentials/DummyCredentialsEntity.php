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

namespace StfalconStudio\ApiBundle\Tests\Model\Credentials;

use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use StfalconStudio\ApiBundle\Model\Credentials\CredentialsTrait;

class DummyCredentialsEntity implements CredentialsInterface
{
    use CredentialsTrait;
}
