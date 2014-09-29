<?php
/**
 * File NodeDoublyLinkedListCollectionTest.php 
 */

namespace Tebru\Test\Tree;

use PHPUnit_Framework_TestCase;
use SplDoublyLinkedList;
use Tebru\Tree\Node;
use Tebru\Tree\Storage\NodeDoublyLinkedListCollection;

/**
 * Class NodeDoublyLinkedListCollectionTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NodeDoublyLinkedListCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var NodeDoublyLinkedListCollection $collection
     */
    private $collection;

    protected function setUp()
    {
        parent::setUp();

        $this->collection = new NodeDoublyLinkedListCollection();
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($this->collection);
    }


    public function testAddNode()
    {
        $node1 = new Node('node1');
        $node2 = new Node('node2');
        $this->collection->add($node1);
        $this->collection->add($node2);
        $this->assertSame($node1, $this->collection->getNodes()->offsetGet(0));
        $this->assertSame($node2, $this->collection->getNodes()->offsetGet(1));
    }

    public function testAddNodeAtIndex()
    {
        $node1 = new Node('node1');
        $node2 = new Node('node2');
        $this->collection->add($node1);
        $this->collection->add($node2, 0);
        $this->assertSame($node1, $this->collection->getNodes()->offsetGet(1));
        $this->assertSame($node2, $this->collection->getNodes()->offsetGet(0));
    }

    /**
     * @expectedException \OutOfRangeException
     *
     */
    public function testAddNodeAtNonIndex()
    {
        $this->collection->add(new Node('node'), 1);
    }

    public function testRemoveNode()
    {
        $this->collection->add(new Node('node1'));
        $this->collection->add(new Node('node2'));
        $this->collection->remove('node1', 0);
        $this->assertEquals(1, count($this->collection->getNodes()));
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testRemoveOutOfBounds()
    {
        $this->collection->remove('node');
    }

    public function testFind()
    {
        $node1 = new Node('node1');
        $node2 = new Node('node2');
        $this->collection->add($node1);
        $this->collection->add($node2);
        $this->assertSame($node1, $this->collection->find('node1'));
        $this->assertSame($node2, $this->collection->find('node2'));
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testFindOutOfBounds()
    {
        $this->collection->add(new Node('node'));
        $this->collection->find('node2');
    }

    public function testExists()
    {
        $this->collection->add(new Node('node1'));
        $this->collection->add(new Node('node2'));
        $this->assertEquals(true, $this->collection->exists('node1'));
        $this->assertEquals(true, $this->collection->exists('node2'));
    }

    public function testNotExists()
    {
        $this->collection->add(new Node('node'));
        $this->assertEquals(false, $this->collection->exists('node2'));
    }

    public function testGetNodes()
    {
        $this->assertTrue($this->collection->getNodes() instanceof SplDoublyLinkedList);
    }

    public function testCanIterate()
    {
        $this->collection->add(new Node('node1'));
        $this->collection->add(new Node('node2'));

        foreach ($this->collection as $node) {
            $this->assertTrue($node instanceof Node);
        }
    }
}
