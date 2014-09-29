<?php
/**
 * File NodeDoublyLinkedListCollection.php
 */

namespace Tebru\Tree\Storage;

use SplDoublyLinkedList;
use Tebru\Tree\Exception\NodeNotFoundException;
use Tebru\Tree\Node;
use Tebru\Tree\NodeStorage;

/**
 * Class NodeDoublyLinkedListCollection
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NodeDoublyLinkedListCollection implements NodeStorage
{
    /**
     * @var SplDoublyLinkedList $nodes
     */
    private $nodes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodes = new SplDoublyLinkedList();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($nodeId)
    {
        try {
            $this->find($nodeId);

            return true;
        } catch (NodeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     * @throws NodeNotFoundException If node could not be found
     */
    public function find($nodeId)
    {
        /** @var Node $node */
        foreach ($this->nodes as $node) {
            if ($node->getId() === $nodeId) {
                return $node;
            }
        }

        throw new NodeNotFoundException('Could not find node with id ' . "'$nodeId'");
    }

    /**
     * {@inheritdoc}
     */
    public function add(Node $node, $index = null)
    {
        if (null === $index) {
            $index = sizeof($this->nodes);
        }

        $this->nodes->add($index, $node);
    }

    /**
     * {@inheritdoc}
     * @throws NodeNotFoundException If node could not be found
     */
    public function remove($nodeId)
    {
        /** @var Node $node */
        foreach ($this->nodes as $index => $node) {
            if ($node->getId() === $nodeId) {
                $this->nodes->offsetUnset($index);

                return null;
            }
        }

        throw new NodeNotFoundException('Could not find node with id ' . "'$nodeId'");
    }

    /**
     * Return the nodes
     *
     * @return SplDoublyLinkedList
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
