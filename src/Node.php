<?php
/**
 * File Node.php
 */

namespace Tebru\Tree;

/**
 * Class Node
 *
 * This represents a node in a tree.  It has references child nodes and
 * a parent node.  It is purely used as a data object.
 *
 * @author Nate Brunette <n@tebru.net>
 * @package Tebru\Tree
 */
class Node
{
    /**
     * A unique identifier for this node
     *
     * This value is immutable
     *
     * @var string $id
     */
    private $id;

    /**
     * A node's parent node
     *
     * @var Node $parent
     */
    private $parent;

    /**
     * A list of children for this node
     *
     * @var \SplDoublyLinkedList $children
     */
    private $children;

    /**
     * The position the node should appear in relative to other nodes on the
     * same level
     *
     * @var int $position
     */
    private $position;

    /**
     * Arbitrary data applied to the node
     *
     * @var mixed $data
     */
    private $data;

    /**
     * Constructor
     *
     * @param string $id
     * @param Node $parent
     */
    public function __construct($id, Node $parent = null)
    {
        $this->id = $id;
        $this->parent = $parent;
        $this->children = new \SplDoublyLinkedList();
    }

    /**
     * Get the node's unique identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the node's parent node
     *
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the node's parent node
     *
     * @param Node $parent
     *
     * @return self
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Add a child node
     *
     * If position if set on the node, add the child at that position.  This
     * will bump any node at that current position and all subsequent nodes
     * down in the list.
     *
     * This method also gives the user the ability to override the current
     * position set on the node.
     *
     * @param Node $node
     * @param int|null $position
     */
    public function addChild(Node $node, $position = null)
    {
        // if position was passed in, override current position
        if (null !== $position) {
            $node->setPosition($position);
        }

        // if the node doesn't have a position, just add it to the end
        $nodePosition = $node->getPosition();
        if (null === $nodePosition) {
            $this->children->push($node);

            return;
        }

        $this->children->add($nodePosition, $node);
    }

    /**
     * Look for the node in the list and remove it
     *
     * @param Node $node
     */
    public function removeChild(Node $node)
    {
        foreach ($this->children as $key => $child) {
            if ($child !== $node) {
                continue;
            }

            $this->children->offsetUnset($key);

            break;
        }
    }

    /**
     * Get all of the children
     *
     * @return \SplDoublyLinkedList
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Overwrite all children with new list
     *
     * @param \SplDoublyLinkedList $children
     */
    public function setChildren(\SplDoublyLinkedList $children)
    {
        $this->children = $children;
    }

    /**
     * Get the node's position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the node's position
     *
     * @param int $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;

        return $this;
    }

    /**
     * Get data set on the node
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set arbitrary data on the node
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Returns true if this node is a root node
     *
     * @return bool
     */
    public function isRoot()
    {
        return (null === $this->parent);
    }
}
