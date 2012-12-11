<?php

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @access private
 *
 * @property-read ElggVolatileMetadataCache $metadataCache
 */
class Elgg_ServiceProvider extends Elgg_Di_Container {

	public function __construct() {
		$this->setService('metadataCache', 'ElggVolatileMetadataCache');
	}
}
