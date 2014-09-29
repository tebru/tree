<?php
/**
 * File NodeStorage.php 
 */

namespace Tebru\Tree;

use IteratorAggregate;
use Tebru\Tree\Exception\NodeNotFoundException;

/**
 * Interface NodeStorage
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface NodeStorage extends IteratorAggregate
{
    /**
     * Checks if a node exists with id
     *
     * @param string $nodeId
     *
     * @return bool
     */
    public function exists($nodeId);

    /**
     * Find a node by its id
     *
     * @param string $nodeId
     *
     * @return Node
     * @throws NodeNotFoundException If node could not be found
     */
    public function find($nodeId);

    /**
     * Add a node to the array
     *
     * Uses the node's id as a key for easy access
     *
     * @param Node $node
     * @param string|null $index
     *
     * @return $this
     */
    public function add(Node $node, $index = null);

    /**
     * Remove the node by its id
     *
     * @param string $nodeId
     *
     * @return $this
     * @throws NodeNotFoundException If node could not be found
     */
    public function remove($nodeId);

    /**
     * Return the nodes
     *
     * @return mixed
     */
    public function getNodes();

} 
