<?php
/**
 * File NodeCollection.php
 *
 * Copyright (C) Tebru Corp.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace Tebru\Tree;

use Tebru\Tree\Exception\NodeNotFoundException;
use Traversable;

/**
 * Class NodeCollection
 *
 * A collection of wrapping an array of nodes.  Provides the functionality
 * to easily find, add, and remove nodes.
 *
 * This is used to provide a hash table lookup for the tree.
 *
 * @author Nate Brunette <n@tebru.net>
 * @package Tebru\Tree
 */
class NodeCollection implements \IteratorAggregate
{
    /**
     * An array of nodes
     *
     * @var array $nodes
     */
    private $nodes = array();

    /**
     * Return the nodes as an array
     *
     * @return array
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
        return new \ArrayIterator($this->nodes);
    }

    /**
     * Add a node to the array
     *
     * Uses the node's id as a key for easy access
     *
     * @param Node $node
     *
     * @return $this
     */
    public function add(Node $node)
    {
        $this->nodes[$node->getId()] = $node;

        return $this;
    }

    /**
     * Remove the node by its id
     *
     * @param $nodeId
     *
     * @return $this
     * @throws NodeNotFoundException If node could not be found
     */
    public function remove($nodeId)
    {
        $this->assertNodeExists($nodeId);

        unset($this->nodes[$nodeId]);

        return $this;
    }

    /**
     * Find a node by its id
     *
     * @param string $nodeId
     *
     * @return Node
     * @throws NodeNotFoundException If node could not be found
     */
    public function find($nodeId)
    {
        $this->assertNodeExists($nodeId);

        return $this->nodes[$nodeId];
    }

    /**
     * Checks if a node exists with id
     *
     * @param string $nodeId
     *
     * @return bool
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
