<?php
/**
 * File DuplicateNodeException.php
 */

namespace Tebru\Tree\Exception;

/**
 * Class DuplicateNodeException
 *
 * Throw this exception when a node with an id of a node that already exists
 * is trying to be added.
 *
 * @author Nate Brunette <n@tebru.net>
 * @package Tebru\Tree\Exception
 */
class DuplicateNodeException extends \RuntimeException
{
}
