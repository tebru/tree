[![Build Status](https://travis-ci.org/tebru/tree.svg?branch=master)](https://travis-ci.org/tebru/tree)

# Tree Data Structure
This library aims to provide an object oriented way to create a tree data structure. Each node has a user defined unique id by which it is referenced.

## Installing the project
Run `composer require tebru/tree:1.0.*`

## Creating the tree
Creating a tree is really easy.  All you have to do is instantiate it:
`$tree = new Tree();`
This will create the tree object and create a root node to act upon.

### Create a node
There are many options for creating a node.

- Creating a node appended to the root node can be done like `$tree->createNode('node');`
- If you want to add this node to a different node you can do so like `$tree->createNode('node2', 'node');`
- You have the option to add arbitrary data to a node by doing `$tree->createNode('a_category', null, 'Foo Category');`
- Finally, you can specify the position your node should appear in the parent's children like `$tree->createNode('node', null, null, 10);`
    - You may not specify an position for an index that does not already exist
    - Setting the position will bump all existing nodes back 1 space starting with the node at the specified index

### Remove a node
Just pass in the node id:
`$tree->removeNode('node');`
This will remove that node and all of its children from the tree.

### Move a node
This is also easy.  Just specify the node id of the node you wish to move and the node id of its new parent:
`$tree->moveNode('node', 'newNodeId');`

## Testing
Run tests by running `phpunit` from the root directory.

## Versioning
This project uses semantic versioning [http://semver.org] for versioning.