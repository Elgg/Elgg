<?php
namespace Elgg;

/**
 * Specifies a countable iterator, usually of result rows from a DB
 *
 * @since 2.3
 */
interface BatchResult extends \Countable, \Iterator {
}
