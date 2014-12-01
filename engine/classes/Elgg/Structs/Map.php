<?php
namespace Elgg\Structs;

/**
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Associates a key with every value in the collection.
 * 
 * @package    Elgg.Core
 * @subpackage Structs
 * @since      1.10
 * 
 * @access private
 */
interface Map extends Collection {
	/** @return Set<string> */
	public function keys();
}