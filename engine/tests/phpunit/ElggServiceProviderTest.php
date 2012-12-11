<?php

class ElggServiceProviderTest extends PHPUnit_Framework_TestCase {

	public function testSharedMetadataCache() {
		$sp = new Elgg_ServiceProvider();

		$obj1 = $sp->metadataCache;
		$obj2 = $sp->metadataCache;
		$this->assertInstanceOf('ElggVolatileMetadataCache', $obj1);
		$this->assertSame($obj1, $obj2);
	}
}
