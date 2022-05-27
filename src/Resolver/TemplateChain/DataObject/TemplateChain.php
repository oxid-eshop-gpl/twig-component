<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Twig\Resolver\TemplateChain\DataObject;

use OxidEsales\Twig\Resolver\TemplateChain\TemplateType\DataObject\TemplateTypeInterface;
use Traversable;

class TemplateChain implements \IteratorAggregate
{
    private array $chain = [];

    public function append(TemplateTypeInterface $templateType): void
    {
        $this->chain[$templateType->getFullyQualifiedName()] = $templateType;
    }

    public function appendChain(TemplateChain $chain): void
    {
        foreach ($chain as $templateType) {
            $this->append($templateType);
        }
    }

    public function has(TemplateTypeInterface $templateType): bool
    {
        return isset($this->chain[$templateType->getFullyQualifiedName()]);
    }

    public function getParent(TemplateTypeInterface $templateType): TemplateTypeInterface
    {
        $keys = array_keys($this->chain);
        $position = array_search($templateType->getFullyQualifiedName(), $keys, true);
        return $this->chain[$keys[++$position]];
    }

    public function getLastChild(): TemplateTypeInterface
    {
        reset($this->chain);
        return current($this->chain);
    }

    public function hasParent(TemplateTypeInterface $templateType): bool
    {
        return $templateType->getFullyQualifiedName() !== end($this->chain)->getFullyQualifiedName();
    }

    public function count(): int
    {
        return count($this->chain);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->chain);
    }
}
