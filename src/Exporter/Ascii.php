<?php

declare(strict_types = 1);

namespace drupol\phptree\Exporter;

use drupol\phptree\Node\NodeInterface;
use drupol\phptree\Node\ValueNodeInterface;

/**
 * Class Ascii.
 */
class Ascii implements ExporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function export(NodeInterface $node): string
    {
        $tree = new \RecursiveTreeIterator(
            new \RecursiveArrayIterator(
                $this->doExportAsArray($node)
            ),
            \RecursiveTreeIterator::SELF_FIRST,
            \CachingIterator::CATCH_GET_CHILD,
            \RecursiveTreeIterator::SELF_FIRST
        );

        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_LEFT, '');
        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_END_HAS_NEXT, '├─');
        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_END_LAST, '└─');
        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_RIGHT, '');
        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_MID_LAST, '  ');
        $tree->setPrefixPart(\RecursiveTreeIterator::PREFIX_MID_HAS_NEXT, '│ ');

        $output = '';

        foreach ($tree as $value) {
            $entry = ('Array' === $entry = $tree->getEntry()) ?
                    '┐' :
                    ' ' . $entry;

            $output .= $tree->getPrefix() . $entry . $tree->getPostfix() . PHP_EOL;
        }

        return $output;
    }

    /**
     * Get a string representation of the node.
     *
     * @param \drupol\phptree\Node\NodeInterface $node
     *   The node.
     *
     * @return string
     *   The node representation.
     */
    protected function getNodeRepresentation(NodeInterface $node): string
    {
        if ($node instanceof ValueNodeInterface) {
            return (string) $node->getValue();
        }

        return \sha1(\spl_object_hash($node));
    }

    /**
     * Export the tree in an array.
     *
     * @param \drupol\phptree\Node\NodeInterface $node
     *   The node
     *
     * @return array
     *   The tree exported into an array
     */
    private function doExportAsArray(NodeInterface $node): array
    {
        $children = [];
        /** @var ValueNodeInterface $child */
        foreach ($node->children() as $child) {
            $children[] = $this->doExportAsArray($child);
        }

        return [] === $children ?
            [$this->getNodeRepresentation($node)] :
            [$this->getNodeRepresentation($node), $children];
    }
}
