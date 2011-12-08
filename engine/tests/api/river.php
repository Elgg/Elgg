<?php
/**
 * Elgg Test river api
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreRiverAPITest extends ElggCoreUnitTest {

	public function testElggTypeSubtypeWhereSQL() {
		$types = array('object');
		$subtypes = array('blog');
		$result = elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog')))");

		$types = array('object');
		$subtypes = array('blog', 'file');
		$result = elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog') OR (rv.subtype = 'file')))");
	}
}
