<?php

/**
 * Elgg Regression Tests -- GitHub Bugfixes
 * Any bugfixes from GitHub that require testing belong here.
 *
 * @package    Elgg
 * @subpackage Test
 */
class ElggCoreRegressionBugsTest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	/**
	 * #1558
	 */
	public function testElggObjectDeleteAnnotations() {
		$this->entity = new \ElggObject();
		$this->entity->subtype = $this->getRandomSubtype();
		$guid = $this->entity->save();

		$this->entity->annotate('test', 'hello', ACCESS_PUBLIC);

		$this->entity->deleteAnnotations('does not exist');

		$num = $this->entity->countAnnotations('test');

		//$this->assertIdentical($num, 1);
		$this->assertEqual($num, 1);

		// clean up
		$this->entity->delete();
	}

	/**
	 * #2063 - get_resized_image_from_existing_file() fails asked for image larger than selection and not scaling an
	 * image up Test get_image_resize_parameters().
	 */
	public function testElggResizeImage() {
		$orig_width = 100;
		$orig_height = 150;

		// test against selection > max
		$options = [
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => true,
			'upscale' => false,

			'x1' => 25,
			'y1' => 75,
			'x2' => 100,
			'y2' => 150
		];

		_elgg_services()->logger->disable();
		// should get back the same x/y offset == x1, y1 and an image of 50x50
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);
		_elgg_services()->logger->enable();

		$this->assertEqual($params['newwidth'], $options['maxwidth']);
		$this->assertEqual($params['newheight'], $options['maxheight']);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);

		// test against selection < max
		$options = [
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => true,
			'upscale' => false,

			'x1' => 75,
			'y1' => 125,
			'x2' => 100,
			'y2' => 150
		];

		_elgg_services()->logger->disable();
		// should get back the same x/y offset == x1, y1 and an image of 25x25 because no upscale
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);
		_elgg_services()->logger->enable();

		$this->assertEqual($params['newwidth'], 25);
		$this->assertEqual($params['newheight'], 25);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);
	}

	// #3722 Check canEdit() works for contains regardless of groups
	function test_can_write_to_container() {

		// @todo: I am not sure what's wrong with this test and why it passes on some builds
		// and fails on others

		$this->skip("The test is fragile and needs some attention");

		return;

		$user = $this->createUser();

		$owner = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$group_owner = $this->createUser();
		$group = $this->createGroup([
			'owner_guid' => $group_owner->guid,
		]);

		$logged_in = _elgg_services()->session->getLoggedInUser();

		_elgg_services()->session->removeLoggedInUser();

		_elgg_services()->logger->disable();

		$this->assertFalse($object->canWriteToContainer($user->guid));

		// register hook to allow access
		$callback = function ($hook, $type, $value, $params) use ($user) {
			if ($params['user']->guid == $user->guid) {
				return true;
			}
		};

		elgg_register_plugin_hook_handler('container_permissions_check', 'all', $callback, 600);
		$this->assertTrue($object->canWriteToContainer($user->guid));
		elgg_unregister_plugin_hook_handler('container_permissions_check', 'all', $callback);
		$this->assertFalse($group->canWriteToContainer($user->guid));

		$group->join($user);
		$this->assertTrue($group->canWriteToContainer($user->guid));

		_elgg_services()->logger->enable();

		_elgg_services()->session->setLoggedInUser($logged_in);

		$user->delete();
		$owner->delete();
		$group_owner->delete();

	}

	/**
	 * https://github.com/elgg/elgg/issues/3210 - Don't remove -s in friendly titles
	 * https://github.com/elgg/elgg/issues/2276 - improve char encoding
	 */
	public function test_friendly_title() {
		$cases = [
			// acid test
			"B&N > Amazon, OK? <bold> 'hey!' $34"
			=> "bn-amazon-ok-bold-hey-34",

			// hyphen, underscore and ASCII whitespace replaced by separator,
			// other non-alphanumeric ASCII removed
			"a-a_a a\na\ra\ta\va!a\"a#a\$a%aa'a(a)a*a+a,a.a/a:a;a=a?a@a[a\\a]a^a`a{a|a}a~a"
			=> "a-a-a-a-a-a-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",

			// separators trimmed
			"-_ hello _-"
			=> "hello",

			// accents removed, lower case, other multibyte chars are URL encoded
			"I\xC3\xB1t\xC3\xABrn\xC3\xA2ti\xC3\xB4n\xC3\xA0liz\xC3\xA6ti\xC3\xB8n, AND \xE6\x97\xA5\xE6\x9C\xAC\xE8\xAA\x9E"
			// Iñtërnâtiônàlizætiøn, AND 日本語
			=> 'internationalizaetion-and-%E6%97%A5%E6%9C%AC%E8%AA%9E',
		];

		// where available, string is converted to NFC before transliteration
		if (\Elgg\Translit::hasNormalizerSupport()) {
			$form_d = "A\xCC\x8A"; // A followed by 'COMBINING RING ABOVE' (U+030A)
			$cases[$form_d] = "a";
		}

		foreach ($cases as $case => $expected) {
			$friendly_title = elgg_get_friendly_title($case);
			$this->assertIdentical($expected, $friendly_title);
		}
	}

	/**
	 * Test #5369 -- parse_urls()
	 * https://github.com/Elgg/Elgg/issues/5369
	 */
	public function test_parse_urls() {

		$cases = [
			'no.link.here' =>
				'no.link.here',
			'simple link http://example.org test' =>
				'simple link <a href="http://example.org" rel="nofollow">http://example.org</a> test',
			'non-ascii http://ñew.org/ test' =>
				'non-ascii <a href="http://ñew.org/" rel="nofollow">http://ñew.org/</a> test',

			// section 2.1
			'percent encoded http://example.org/a%20b test' =>
				'percent encoded <a href="http://example.org/a%20b" rel="nofollow">http://example.org/a%20b</a> test',
			// section 2.2: skipping single quote and parenthese
			'reserved characters http://example.org/:/?#[]@!$&*+,;= test' =>
				'reserved characters <a href="http://example.org/:/?#[]@!$&*+,;=" rel="nofollow">http://example.org/:/?#[]@!$&*+,;=</a> test',
			// section 2.3
			'unreserved characters http://example.org/a1-._~ test' =>
				'unreserved characters <a href="http://example.org/a1-._~" rel="nofollow">http://example.org/a1-._~</a> test',

			'parameters http://example.org/?val[]=1&val[]=2 test' =>
				'parameters <a href="http://example.org/?val[]=1&val[]=2" rel="nofollow">http://example.org/?val[]=1&val[]=2</a> test',
			'port http://example.org:80/ test' =>
				'port <a href="http://example.org:80/" rel="nofollow">http://example.org:80/</a> test',

			'parentheses (http://www.google.com) test' =>
				'parentheses (<a href="http://www.google.com" rel="nofollow">http://www.google.com</a>) test',
			'comma http://elgg.org, test' =>
				'comma <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>, test',
			'period http://elgg.org. test' =>
				'period <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>. test',
			'exclamation http://elgg.org! test' =>
				'exclamation <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>! test',

			'already anchor <a href="http://twitter.com/">twitter</a> test' =>
				'already anchor <a href="http://twitter.com/">twitter</a> test',

			'ssl https://example.org/ test' =>
				'ssl <a href="https://example.org/" rel="nofollow">https://example.org/</a> test',
			'ftp ftp://example.org/ test' =>
				'ftp <a href="ftp://example.org/" rel="nofollow">ftp://example.org/</a> test',

			'web archive anchor <a href="http://web.archive.org/web/20000229040250/http://www.google.com/">google</a>' =>
				'web archive anchor <a href="http://web.archive.org/web/20000229040250/http://www.google.com/">google</a>',

			'single quotes already anchor <a href=\'http://www.yahoo.com\'>yahoo</a>' =>
				'single quotes already anchor <a href=\'http://www.yahoo.com\'>yahoo</a>',

			'unquoted already anchor <a href=http://www.yahoo.com>yahoo</a>' =>
				'unquoted already anchor <a href=http://www.yahoo.com>yahoo</a>',

			'parens in uri http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx' =>
				'parens in uri <a href="http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx" rel="nofollow">http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx</a>'
		];
		foreach ($cases as $input => $output) {
			$this->assertEqual($output, parse_urls($input));
		}
	}

	/**
	 * Test #10398 -- elgg_parse_emails()
	 * https://github.com/Elgg/Elgg/pull/10398
	 */
	public function test_elgg_parse_emails() {
		$cases = [
			'no.email.here' =>
				'no.email.here',
			'simple email mail@test.com test' =>
				'simple email <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test',
			'simple paragraph <p>mail@test.com</p>' =>
				'simple paragraph <p><a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a></p>',
			'multiple matches mail@test.com test mail@test.com test' =>
				'multiple matches <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test',
			'invalid email 1 @invalid.com test' =>
				'invalid email 1 @invalid.com test',
			'invalid email 2 mail@invalid. test' =>
				'invalid email 2 mail@invalid. test',
			'no double parsing <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test' =>
				'no double parsing <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test',
			'no double parsing 2 <a href="#">mail@test.com</a> test' =>
				'no double parsing 2 <a href="#">mail@test.com</a> test',
			'no double parsing 3 <a href="#">with a lot of text - mail@test.com - around it</a> test' =>
				'no double parsing 3 <a href="#">with a lot of text - mail@test.com - around it</a> test',
		];
		foreach ($cases as $input => $output) {
			$this->assertEqual($output, elgg_parse_emails($input));
		}
	}

	/**
	 * Ensure additional select columns do not end up in entity attributes.
	 *
	 * https://github.com/Elgg/Elgg/issues/5538
	 */
	public function test_extra_columns_dont_appear_in_attributes() {

		$entities = [];

		$types = \Elgg\Config::getEntityTypes();

		foreach ($types as $type) {
			switch ($type) {
				case 'user' :
					$entities[] = $this->createUser();
					break;
				case 'group' :
					$entities[] = $this->createGroup();
					break;
				case 'object' :
					$entities[] = $this->createObject();
			}

			$entities = elgg_get_entities([
				'type' => $type,
				'selects' => ['1 as _nonexistent_test_column'],
				'limit' => 1,
			]);

			if (!$this->assertTrue($entities, "Query for '$type' did not return an entity.")) {
				continue;
			}
			$entity = $entities[0];
			$this->assertNull($entity->_nonexistent_test_column, "Additional select columns are leaking to attributes for '$type'");
		}

		foreach ($entities as $entity) {
			$entity->delete();
		}
	}

	/**
	 * Ensure that \ElggBatch doesn't go into infinite loop when disabling annotations recursively when show hidden is
	 * enabled.
	 *
	 * https://github.com/Elgg/Elgg/issues/5952
	 */
	public function test_disabling_annotations_infinite_loop() {

		//let's have some entity
		$group = new \ElggGroup();
		$group->subtype = $this->getRandomSubtype();
		$group->name = 'test_group';
		$group->access_id = ACCESS_PUBLIC;
		$this->assertTrue($group->save() !== false);

		$total = 51;
		//add some annotations
		for ($cnt = 0; $cnt < $total; $cnt++) {
			$group->annotate('test_annotation', 'value_' . $total);
		}

		//disable them
		$show_hidden = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$options = [
			'guid' => $group->guid,
			'limit' => $total,
			//using strict limit to avoid real infinite loop and just see \ElggBatch limiting on it before finishing the work
		];
		elgg_disable_annotations($options);
		access_show_hidden_entities($show_hidden);

		//confirm all being disabled
		$annotations = $group->getAnnotations([
			'limit' => $total,
		]);
		foreach ($annotations as $annotation) {
			$this->assertTrue($annotation->enabled == 'no');
		}

		//delete group and annotations
		$group->delete();
	}

	public function test_ElggXMLElement_does_not_load_external_entities() {
		$elLast = libxml_disable_entity_loader(false);

		// build payload that should trigger loading of external entity
		$payload = file_get_contents($this->normalizeTestFilePath('xxe/request.xml'));
		$path = realpath($this->normalizeTestFilePath('xxe/external_entity.txt'));
		$path = str_replace('\\', '/', $path);
		if ($path[0] != '/') {
			$path = '/' . $path;
		}
		$path = 'file://' . $path;
		$payload = sprintf($payload, $path);

		// make sure we can actually this in this environment
		$element = new SimpleXMLElement($payload);
		$can_load_entity = preg_match('/secret/', (string) $element->methodName);

		$this->skipUnless($can_load_entity, "XXE vulnerability cannot be tested on this system");

		if ($can_load_entity) {
			$this->expectError("SimpleXMLElement::__construct(): I/O warning : failed to load external entity &quot;" . $path . "&quot;");
			$el = new \ElggXMLElement($payload);
			$chidren = $el->getChildren();
			$content = $chidren[0]->getContent();
			$this->assertNoPattern('/secret/', $content);
		}

		libxml_disable_entity_loader($elLast);
	}

	public function test_update_handlers_can_change_attributes() {
		$object = new \ElggObject();
		$object->subtype = 'issue6225';
		$object->access_id = ACCESS_PUBLIC;
		$object->save();
		$guid = $object->guid;

		elgg_register_event_handler('update', 'object', [
			'\ElggCoreRegressionBugsTest',
			'handleUpdateForIssue6225test'
		]);

		$object->save();

		elgg_unregister_event_handler('update', 'object', [
			'\ElggCoreRegressionBugsTest',
			'handleUpdateForIssue6225test'
		]);

		$object->invalidateCache();

		$object = get_entity($guid);
		$this->assertEqual($object->access_id, ACCESS_PRIVATE);

		$object->delete();
	}

	public static function handleUpdateForIssue6225test($event, $type, \ElggObject $object) {
		$object->access_id = ACCESS_PRIVATE;
	}

	/**
	 * elgg_admin_sort_page_menu() should not expect that the supplied menu has a certain hierarchy
	 *
	 * https://github.com/Elgg/Elgg/issues/6379
	 */
	function test_admin_sort_page_menu() {

		elgg_push_context('admin');

		elgg_register_plugin_hook_handler('prepare', 'menu:page', 'elgg_admin_sort_page_menu');
		$result = elgg_trigger_plugin_hook('prepare', 'menu:page', [], []);
		$this->assertTrue(is_array($result), "Admin page menu fails to prepare for viewing");

		elgg_pop_context();
	}

	/**
	 * Tests get_entity_statistics() without owner
	 * @covers get_entity_statistics()
	 */
	function test_global_get_entity_statistics() {

		$subtype = 'issue7845' . rand(0, 100);

		$object = new \ElggObject();
		$object->subtype = $subtype;
		$object->save();

		$stats = get_entity_statistics();

		$this->assertEqual($stats['object'][$subtype], 1);

		$object->delete();
	}

	/**
	 * Tests get_entity_statistics() with an owner
	 * @covers get_entity_statistics()
	 */
	function test_owned_get_entity_statistics() {

		$user = $this->createUser();

		$subtype = 'issue7845' . rand(0, 100);

		$object = $this->createObject([
			'subtype' => $subtype,
			'owner_guid' => $user->guid,
		]);

		$stats = get_entity_statistics($user->guid);

		$this->assertEqual($stats['object'][$subtype], 1);

		$user->delete();
		$object->delete();
	}

}