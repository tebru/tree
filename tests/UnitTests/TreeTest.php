<?php

namespace Tebru\Test\Tree;

use PHPUnit_Framework_TestCase;
use Tebru\Tree\Node;
use Tebru\Tree\Storage\NodeArrayCollection;
use Tebru\Tree\Tree;

class TreeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tree $collection
     */
    private $tree;

    protected function setUp()
    {
        parent::setUp();

        $this->tree = new Tree(new NodeArrayCollection());
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($this->tree);
    }

    public function testCanCreateNode()
    {
        $this->tree->createNode('node');
        $node = $this->tree->findNode('node');

        $this->assertTrue($node instanceof Node);
    }

    public function testCreatedNodeHasRootParent()
    {
        $this->tree->createNode('node');
        $node = $this->tree->findNode('node');

        $this->assertTrue($node->getParent()->isRoot());
    }

    public function testCreateNodeParentHasChild()
    {
        $this->tree->createNode('node');
        $node = $this->tree->findNode('node');

        $this->assertTrue($node === $node->getParent()->getChildren()[0]);
    }

    public function testCreateNodeChild()
    {
        $this->tree->createNode('node1');
        $this->tree->createNode('node2', 'node1');

        $node1 = $this->tree->findNode('node1');
        $node2 = $this->tree->findNode('node2');

        $this->assertTrue($node1 === $node2->getParent());
        $this->assertTrue($node2 === $node1->getChildren()[0]);
    }

    public function testCreateNodeWithData()
    {
        $this->tree->createNode('node', null, 'foo');
        $node = $this->tree->findNode('node');

        $this->assertTrue('foo' === $node->getData());
    }

    public function testCreateNodePosition()
    {
        $this->tree->createNode('node1', null, null, 0);
        $this->tree->createNode('node2', null, null, 0);

        $root = $this->tree->findNode(Tree::ROOT_NODE_ID);
        $node1 = $this->tree->findNode('node1');
        $node2 = $this->tree->findNode('node2');


        $this->assertTrue($node2 === $root->getChildren()[0]);
        $this->assertTrue($node1 === $root->getChildren()[1]);
    }

    /**
     * @expectedException \Tebru\Tree\Exception\DuplicateNodeException
     */
    public function testDuplicateNodeIdWillThrowException()
    {
        $this->tree->createNode('node');
        $this->tree->createNode('node');
    }

    public function testRemoveNode()
    {
        $this->tree->createNode('node');
        $this->tree->removeNode('node');

        $this->assertFalse($this->tree->nodeExists('node'));
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testRemoveThrowsNotFound()
    {
        $this->tree->createNode('node');
        $this->tree->removeNode('foo');
    }

    /**
     * @expectedException \Tebru\Tree\Exception\OperationNotAllowedException
     */
    public function testRemoveThrowsNotAllowed()
    {
        $this->tree->removeNode(Tree::ROOT_NODE_ID);
    }

    public function testMoveNode()
    {
        $this->tree->createNode('node1');
        $this->tree->createNode('node2');
        $this->tree->createNode('node3', 'node1');
        $this->tree->moveNode('node3', 'node2');

        $node1 = $this->tree->findNode('node1');
        $node2 = $this->tree->findNode('node2');
        $node3 = $this->tree->findNode('node3');

        $this->assertTrue(0 === $node1->getChildren()->count());
        $this->assertTrue($node3 === $node2->getChildren()[0]);
        $this->assertTrue($node2 === $node3->getParent());
    }

    /**
     * @expectedException \Tebru\Tree\Exception\NodeNotFoundException
     */
    public function testMoveNodeThrowsNotFound()
    {
        $this->tree->moveNode('foo', 'bar');
    }

    /**
     * @expectedException \Tebru\Tree\Exception\OperationNotAllowedException
     */
    public function testMoveNodeThrowsNotAllowed()
    {
        $this->tree->moveNode('root', 'bar');
    }
}
