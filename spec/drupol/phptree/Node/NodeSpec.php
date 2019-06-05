<?php

declare(strict_types = 1);

namespace spec\drupol\phptree\Node;

use drupol\phptree\Node\Node;
use drupol\phptree\Node\NodeInterface;
use drupol\phptree\Node\ValueNode;

class NodeSpec extends NodeObjectBehavior
{
    public function it_can_add(NodeInterface $node)
    {
        $node->setParent($this)->shouldBeCalledOnce();

        $this->add($node)
            ->shouldReturn($this);
    }

    public function it_can_check_if_its_a_leaf()
    {
        $this
            ->isLeaf()
            ->shouldReturn(true);

        $node = new Node();

        $this
            ->add($node)
            ->isLeaf()
            ->shouldReturn(false);
    }

    public function it_can_check_if_node_is_root()
    {
        $this
            ->isRoot()
            ->shouldReturn(true);

        $node = new Node();

        $this
            ->setParent($node)
            ->isRoot()
            ->shouldReturn(false);
    }

    public function it_can_count_its_children()
    {
        $this
            ->count()
            ->shouldReturn(0);

        $node = new Node();

        $this
            ->add($node)
            ->count()
            ->shouldReturn(1);
    }

    public function it_can_get_and_set_the_parent(NodeInterface $parent)
    {
        $this
            ->getParent()
            ->shouldReturn(null);

        $this
            ->setParent($parent)
            ->getParent()
            ->shouldReturn($parent);
    }

    public function it_can_get_its_ancestors()
    {
        $this
            ->getAncestors()
            ->shouldYield(new \ArrayIterator([]));

        $root = new Node();
        $level1 = new Node($root);
        $level2 = new Node($level1);

        $this->setParent($level2);

        $this
            ->getAncestors()
            ->shouldYield(new \ArrayIterator([$level2, $level1, $root]));
    }

    public function it_can_get_its_children()
    {
        $this
            ->children()
            ->shouldBeAnInstanceOf(\Generator::class);

        $this
            ->children()
            ->shouldYield(new \ArrayIterator([]));

        $node = new Node();

        $this
            ->add($node)
            ->add($node)
            ->children()
            ->shouldYield(new \ArrayIterator([$node, $node]));
    }

    public function it_can_get_its_depth()
    {
        $this
            ->depth()
            ->shouldReturn(0);

        $tree = new ValueNode('root', 2);

        $tree->add($this->getWrappedObject());

        $this
            ->depth()
            ->shouldReturn(1);

        $nodes = [];
        foreach (\range('A', 'Z') as $v) {
            $nodes[] = new ValueNode($v, 2);
        }

        $tree->add(...$nodes);

        $this
            ->depth()
            ->shouldReturn(1);
    }

    public function it_can_get_its_height()
    {
        $this
            ->height()
            ->shouldReturn(0);

        $tree = $this;
        foreach (\range('A', 'B') as $key => $v) {
            $node = new ValueNode($v, 1);
            $tree->add($node);
            $tree = $node;
        }

        $this
            ->height()
            ->shouldReturn(2);

        foreach (\range('C', 'F') as $key => $v) {
            $node = new ValueNode($v, 1);
            $tree->add($node);
            $tree = $node;
        }

        $this
            ->height()
            ->shouldReturn(6);

        $this
            ->withChildren()
            ->height()
            ->shouldReturn(0);
    }

    public function it_can_get_its_sibblings()
    {
        $this
            ->getSibblings()
            ->shouldYield(new \ArrayIterator([]));

        $node1 = new Node();
        $node2 = new Node();
        $node3 = new Node();

        $node1->add($this->getWrappedObject(), $node2, $node3);

        $this
            ->getSibblings()
            ->shouldYield(new \ArrayIterator([$node2, $node3]));
    }

    public function it_can_get_the_size()
    {
        $nodes = [];
        $linearNodes = [];
        foreach (\range('a', 'e') as $lowercaseValue) {
            $node1 = new Node();
            $linearNodes[] = $node1;

            foreach (\range('A', 'E') as $uppercaseValue) {
                $node2 = new Node();
                $linearNodes[] = $node2;

                $node1->add($node2);
            }

            $nodes[] = $node1;
        }

        $this->add(...$nodes);

        $this
            ->count()
            ->shouldReturn(30);
    }

    public function it_can_remove()
    {
        $node1 = new Node();
        $node2 = new Node();
        $node3 = new Node();

        $this
            ->add($node1, $node2);

        $this
            ->remove($node2);

        $this
            ->count()
            ->shouldReturn(1);

        $this
            ->remove($node1);

        $this
            ->remove($node3);

        $this
            ->count()
            ->shouldReturn(0);
    }

    public function it_can_use_withChildren()
    {
        $this
            ->withChildren()
            ->shouldNotReturn($this);

        $child = new Node();

        $this
            ->withChildren($child)
            ->children()
            ->shouldYield(new \ArrayIterator([$child]));

        $this
            ->withChildren()
            ->children()
            ->shouldYield(new \ArrayIterator([]));
    }

    public function it_has_a_degree()
    {
        $this
            ->degree()
            ->shouldReturn(0);

        $node = new Node();

        $this
            ->add($node)
            ->degree()
            ->shouldReturn(1);
    }

    public function it_is_a_traversable()
    {
        $node1 = new Node();
        $node2 = new Node();
        $node3 = new Node();

        $this
            ->add($node1, $node2, $node3);

        $this->shouldIterateLike($this->children());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Node::class);
    }
}
