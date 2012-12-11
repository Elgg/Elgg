<?php

/**
 * An object that implements this interface can be resolved to a value at a later time. Since the
 * container is passed in, the object can pull other values from the container to resolve the
 * value.
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
