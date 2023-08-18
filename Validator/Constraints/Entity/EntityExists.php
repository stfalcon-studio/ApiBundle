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

namespace StfalconStudio\ApiBundle\Validator\Constraints\Entity;

use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class EntityExists extends Constraint
{
    public const ENTITY_DOES_NOT_EXIST = 'ENTITY_DOES_NOT_EXIST';

    /** @var array<string, string> */
    protected const ERROR_NAMES = [
        self::ENTITY_DOES_NOT_EXIST => self::ENTITY_DOES_NOT_EXIST,
    ];

    public string $message = 'entity_does_not_exist';

    public string $class;

    public function __construct(string $class, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        if (empty($class)) {
            throw new ConstraintDefinitionException('The "class" parameter can not be empty.');
        }

        if (!\class_exists($class)) {
            throw new InvalidArgumentException(\sprintf('Class %s does not exist.', $class));
        }

        $this->class = $class;
    }
}
