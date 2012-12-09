<?php

/**
 * An object that implements this interface can be resolved to a value, and has access to the
 * container during resolution.
 *
 * @access private
 */
interface Elgg_Di_ResolvableInterface {

	/**
	 * @abstract
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 */
	public function resolveValue(Elgg_Di_Container $container);
}
