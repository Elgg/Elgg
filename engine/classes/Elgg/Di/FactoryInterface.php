<?php

/**
 * An object that implements this interface can create a value at a later time. Since the
 * container is passed in, the object can pull other values from the container if it needs them.
 *
 * @access private
 */
interface Elgg_Di_FactoryInterface {

	/**
	 * @abstract
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 */
	public function createValue(Elgg_Di_Container $container);
}
