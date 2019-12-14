<?php

declare(strict_types=1);

namespace spec\drupol\phptree\Modifier;

use drupol\phptree\Exporter\Ascii;
use drupol\phptree\Modifier\RemoveNullNode;
use drupol\phptree\Node\ValueNode;
use PhpSpec\ObjectBehavior;

class RemoveNullNodeSpec extends ObjectBehavior
{
    public function it_can_remove_node_with_null_values()
    {
        $tree = new ValueNode('root', 10);

        $nodes = [];

        foreach ([null, null, null, 'a', 'b', null, 'c'] as $value) {
            $nodes[] = new ValueNode($value);
        }
        $tree->add(...$nodes);

        $this
            ->modify($tree)
            ->count()
            ->shouldReturn(3);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoveNullNode::class);
    }
}
