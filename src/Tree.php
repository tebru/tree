<?php
/**
 * File Tree.php
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

use Tebru\Tree\Exception\DuplicateNodeException;
use Tebru\Tree\Exception\NodeNotFoundException;
use Tebru\Tree\Exception\OperationNotAllowedException;

/**
 * Class Tree
 *
 * Represents a tree data structure.  Ability to create, remove, and move nodes
 * around within the tree.  Also provides functionality for searching the tree
 * for specific nodes by id.
 *
 * @author Nate Brunette <n@tebru.net>
 * @package Tebru\Tree
 */
class Tree
{
    /**
     * The id for the root node
     */
    const ROOT_NODE_ID = 'root';

    /**
     * A collection of nodes
     *
     * @var NodeCollection $nodeCollection
     */
    private $nodeCollection;

    /**
     * The root node
     *
     * This must be available as the starting point for other nodes
     *
     * @var Node $rootNode
     */
    private $rootNode;

    /**
     * Constructor
     */
    public function __construct()
    {
        // create the root node
        $this->rootNode = new Node(self::ROOT_NODE_ID);

        // create a new node collection and add the root node
        $this->nodeCollection = new NodeCollection();
        $this->nodeCollection->add($this->rootNode);
    }

    /**
     * Get the full tree structure as an array
     *
     * @return array
     */
    public function getTree()
    {
        return $this->nodeCollection->toArray();
    }

    /**
     * Determine if node exists in tree with id
     *
     * @param string $nodeId
     *
     * @return bool
     */
    public function nodeExists($nodeId)
    {
        return $this->nodeCollection->exists($nodeId);
    }

    /**
     * Find node in tree with id
     *
     * @param string $nodeId
     *
     * @return Node
     * @throws NodeNotFoundException If node could not be found
     */
    public function findNode($nodeId)
    {
        return $this->nodeCollection->find($nodeId);
    }

    /**
     * Create a node
     *
     * If parentId is not passed in, append to root node
     *
     * @param string $nodeId A unique identifier for the node
     * @param string|null $parentId Which node the node should be append to
     * @param mixed $data Arbitrary data
     * @param int|null $position Position the node should appear in parent's children
     *
     * @return Node
     * @throws DuplicateNodeException When node id is already in use
     */
    public function createNode($nodeId, $parentId = null, $data = null, $position = null)
    {
        if ($this->nodeExists($nodeId)) {
            throw new DuplicateNodeException("ID '$nodeId' is already in use.  Node ids must be unique.");
        }

        // use root node if parent id is not passed in
        $parent = (null === $parentId)
            ? $this->rootNode
            : $this->nodeCollection->find($parentId);

        // create a new node setting position and data
        $node = new Node($nodeId, $parent);
        $node->setPosition($position);
        $node->setData($data);

        // add node to parent and collection
        $parent->addChild($node);
        $this->nodeCollection->add($node);

        return $node;
    }

    /**
     * Remove node from tree with id
     *
     * This is a recursive method that will remove the node and all of its
     * children from the tree and collection.
     *
     * @param string $nodeId
     *
     * @throws NodeNotFoundException If node could not be found
     * @throws OperationNotAllowedException If performing illegal operation on root node
     */
    public function removeNode($nodeId)
    {
        /** @var Node $node */
        $node = $this->findNode($nodeId);
        $this->assertIsNotRoot($node);

        /** @var Node $childNode */
        foreach ($node->getChildren() as $childNode) {
            $childId = $childNode->getId();

            // recursively remove children
            $this->removeNode($childId);
        }

        // remove the node
        $this->removeNodeById($nodeId);
    }

    /**
     * Move a node to a different parent
     *
     * Ensures that references between parent/child are kept up to date
     * during the move.
     *
     * @param string $nodeId
     * @param string $newParentId
     *
     * @throws NodeNotFoundException If node could not be found
     * @throws OperationNotAllowedException If performing illegal operation on root node
     */
    public function moveNode($nodeId, $newParentId)
    {
        // remove node from current parent
        $this->removeNodeFromParent($nodeId);

        /** @var Node $node */
        $node = $this->findNode($nodeId);

        /** @var Node $newParent */
        $newParent = $this->findNode($newParentId);

        // add node to new parent, setting both child id on the parent
        // and parent id on the child
        $newParent->addChild($node);
        $node->setParent($newParent);
    }

    /**
     * Remove a node and delete node from parent's children
     *
     * @param string $nodeId
     *
     * @throws NodeNotFoundException
     * @throws OperationNotAllowedException
     */
    private function removeNodeById($nodeId)
    {
        $this->removeNodeFromParent($nodeId);
        $this->nodeCollection->remove($nodeId);
    }

    /**
     * Detach a node from current parent
     *
     * Removes the node from the parent's children
     *
     * @param string $nodeId
     *
     * @throws NodeNotFoundException
     * @throws OperationNotAllowedException
     */
    private function removeNodeFromParent($nodeId)
    {
        /** @var Node $node */
        $node = $this->findNode($nodeId);
        $this->assertIsNotRoot($node);

        $parent = $node->getParent();
        $parent->removeChild($node);
    }

    /**
     * Will throw an exception if the node is a root node
     *
     * @param Node $node
     *
     * @throws Exception\OperationNotAllowedException
     */
    private function assertIsNotRoot(Node $node)
    {
        if (!$node->isRoot()) {
            return;
        }

        throw new OperationNotAllowedException('Could not perform operation on root node.');
    }
}
