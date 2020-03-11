<?php

declare(strict_types=1);

namespace loophp\phptree\Builder;

use loophp\phptree\Node\NodeInterface;

/**
 * Interface BuilderInterface.
 */
interface BuilderInterface
{
    /**
     * @param iterable<mixed> $nodes
     *
     * @return NodeInterface|null
     */
    public static function create(iterable $nodes): ?NodeInterface;
}
