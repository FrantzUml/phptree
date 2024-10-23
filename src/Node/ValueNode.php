<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\phptree\Node;

use loophp\phptree\Traverser\TraverserInterface;

/**
 * @template TValue
 * @implementents ValueNodeInterface<TValue>
 */
class ValueNode extends NaryNode implements ValueNodeInterface
{
    /** @var TValue */
    private $value;

    /**
     * @param TValue $value
     */
    public function __construct(
        $value,
        int $capacity = 0,
        ?TraverserInterface $traverser = null,
        ?NodeInterface $parent = null
    ) {
        parent::__construct($capacity, $traverser, $parent);

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function label(): string
    {
        return (string) $this->getValue();
    }
}
