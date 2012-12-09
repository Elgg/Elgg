<?php

/**
 * @access private
 *
 * @property-read ElggVolatileMetadataCache $metadataCache
 */
class Elgg_ServiceProvider extends Elgg_Di_Container {

	public function __construct() {
		parent::__construct(new Elgg_Di_Core());

		$this->set('metadataCache', new Elgg_Di_Factory('ElggVolatileMetadataCache'), true);
	}
}
