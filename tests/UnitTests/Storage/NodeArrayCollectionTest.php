<?php

namespace Tebru\Test\Tree;

use Tebru\Tree\Node;
use Tebru\Tree\Storage\NodeArrayCollection;
use Tebru\Tree\Tree;

class NodeCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NodeArrayCollection $collection
     */
    private $collection;

    protected function setUp()
    {
        $this->collection = new NodeArrayCollection();
    }

    protected function tearDown()
    {
        unset($this->collection);
    }

    public function testWillReturnArray()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        $nodes = $this->collection->toArray();
        $this->assertTrue(is_array($nodes));
    }

    public function testCanIterate()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        foreach ($this->collection as $collectionNode)
        {
            $this->assertTrue($node === $collectionNode);
        }
    }

    public function testNodeExists()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        $nodes = $this->collection->toArray();
        $this->assertTrue(isset($nodes[$node->getId()]));
    }

    public function testAddNode()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        $this->assertTrue($this->collection->exists($node->getId()));
    }

    public function testRemoveNode()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);
        $this->collection->remove($node->getId());

        $nodes = $this->collection->toArray();
        $this->assertTrue(empty($nodes));
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testRemoveNodeWillThrowException()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);
        $this->collection->remove('foo');
    }

    public function testFindNode()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        $this->assertTrue($node === $this->collection->find($node->getId()));
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testFindNodeWillThrowException()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);
        $this->collection->find('foo');
    }

    public function testExists()
    {
        $node = new Node(Tree::ROOT_NODE_ID);
        $this->collection->add($node);

        $this->assertTrue($this->collection->exists($node->getId()));
        $this->assertFalse($this->collection->exists('foo'));
    }
}
