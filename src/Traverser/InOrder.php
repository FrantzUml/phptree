<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\phptree\Traverser;

use loophp\phptree\Node\NodeInterface;
use Traversable;

class InOrder implements TraverserInterface
{
    /**
     * @var int
     */
    private $index = 0;

    public function traverse(NodeInterface $node): Traversable
    {
        $this->index = 0;

        return $this->doTraverse($node);
    }

    /**
     * @return Traversable<NodeInterface>
     */
    private function doTraverse(NodeInterface $node): Traversable
    {
        $middle = floor($node->degree() / 2);

        foreach ($node->children() as $key => $child) {
            if ((int) $key === (int) $middle) {
                yield $this->index => $node;
                ++$this->index;
            }

            if ($child->isLeaf()) {
                yield $this->index => $child;
                ++$this->index;
            }

            yield from $this->doTraverse($child);
        }
    }
}
