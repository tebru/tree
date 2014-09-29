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
     *
     * @return $this
     */
    public function add(Node $node);

    /**
     * Remove the node by its id
     *
     * @param $nodeId
     *
     * @return $this
     * @throws NodeNotFoundException If node could not be found
     */
    public function remove($nodeId);

    /**
     * Return the node collection as an array
     *
     * @return array
     */
    public function toArray();

} 
