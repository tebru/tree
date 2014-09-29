<?php
/**
 * File NodeArrayCollection.php
 */

namespace Tebru\Tree\Storage;

use ArrayIterator;
use Tebru\Tree\Exception\NodeNotFoundException;
use Tebru\Tree\Node;
use Tebru\Tree\NodeStorage;

/**
 * Class NodeArrayCollection
 *
 * A collection wrapping an array of nodes.  Provides the functionality
 * to easily find, add, and remove nodes.
 *
 * This is used to provide a hash table lookup for the tree.
 *
 * @author Nate Brunette <n@tebru.net>
 * @package Tebru\Tree
 */
class NodeArrayCollection implements NodeStorage
{
    /**
     * An array of nodes
     *
     * @var array $nodes
     */
    private $nodes = array();

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function add(Node $node)
    {
        $this->nodes[$node->getId()] = $node;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($nodeId)
    {
        $this->assertNodeExists($nodeId);

        unset($this->nodes[$nodeId]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function find($nodeId)
    {
        $this->assertNodeExists($nodeId);

        return $this->nodes[$nodeId];
    }

    /**
     * {@inheritdoc}
     */
    public function exists($nodeId)
    {
        return isset($this->nodes[$nodeId]);
    }

    /**
     * Throws an exception if a node doesn't exist with id
     *
     * @param string $nodeId
     *
     * @throws NodeNotFoundException
     */
    private function assertNodeExists($nodeId)
    {
        if ($this->exists($nodeId)) {
            return;
        }

        throw new NodeNotFoundException('Could not find node with id ' . "'$nodeId'");
    }
}
