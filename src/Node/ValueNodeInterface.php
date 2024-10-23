<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\phptree\Node;

/**
 * Interface ValueNodeInterface.
 * @template TValue
 */
interface ValueNodeInterface extends NaryNodeInterface
{
    /**
     * Get the value property.
     *
     * @return TValue
     *   The value property
     */
    public function getValue();
}
