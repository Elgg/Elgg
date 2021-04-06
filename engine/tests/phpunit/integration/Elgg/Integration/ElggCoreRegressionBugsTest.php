<?php

namespace Elgg\Integration;

/**
 * Elgg Regression Tests -- GitHub Bugfixes
 * Any bugfixes from GitHub that require testing belong here.
 *
 * @group IntegrationTests
 */
class ElggCoreRegressionBugsTest extends \Elgg\IntegrationTestCase {

	public function up() {
		$this->ia = elgg()->session->setIgnoreAccess(true);
	}

	public function down() {
		elgg()->session->setIgnoreAccess($this->ia);
	}

	/**
	 * @see https://github.com/Elgg/Elgg/issues/1558
	 */
	public function testElggObjectDeleteAnnotations() {
		$entity = $this->createObject();
		
		$entity->annotate('test', 'hello', ACCESS_PUBLIC);

		$entity->deleteAnnotations('does not exist');

		$this->assertEquals(1, $entity->countAnnotations('test'));

		// clean up
		$entity->delete();
	}

	/**
	 * Check canEdit() works for contains regardless of groups
	 *
	 * @see https://github.com/Elgg/Elgg/issues/3722
	 */
	function testCanWriteToContainer() {
		$user = $this->createUser();
		$owner = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $owner->guid, // make sure this is a different user
		]);
		$group = $this->createGroup([
			'owner_guid' => $owner->guid, // make sure this is a different user
		]);

		// disable access overrides because we're admin.
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($user, $object, $group) {
			$this->assertFalse($object->canWriteToContainer($user->guid, 'object', 'foo'));
	
			// register hook to allow access
			$handler = function (\Elgg\Hook $hook) use ($user) {
				$hook_user = $hook->getUserParam();
				if ($hook_user->guid === $user->guid) {
					return true;
				}
			};
	
			elgg_register_plugin_hook_handler('container_permissions_check', 'object', $handler, 600);
			$this->assertTrue($object->canWriteToContainer($user->guid, 'object', 'foo'));
			elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', $handler);
	
			$this->assertFalse($group->canWriteToContainer($user->guid, $object->getType(), $object->getSubtype()));
			$group->join($user);
			$this->assertTrue($group->canWriteToContainer($user->guid, $object->getType(), $object->getSubtype()));
		});
		
		$user->delete();
		$owner->delete();
		$object->delete();
		$group->delete();
	}

	/**
	 * @see https://github.com/elgg/elgg/issues/3210 - Don't remove -s in friendly titles
	 * @see https://github.com/elgg/elgg/issues/2276 - improve char encoding
	 *
	 * @dataProvider friendlyTitleProvider
	 */
	public function testFriendlyTitle($input, $expected) {
		$actual = elgg_get_friendly_title($input);
		$this->assertEquals($expected, $actual);
	}
	
	public function friendlyTitleProvider() {
		$cases = [
			// acid test
			["B&N > Amazon, OK? <bold> 'hey!' $34", "bn-amazon-ok-bold-hey-34"],
			
			// hyphen, underscore and ASCII whitespace replaced by separator,
			// other non-alphanumeric ASCII removed
			["a-a_a a\na\ra\ta\va!a\"a#a\$a%aa'a(a)a*a+a,a.a/a:a;a=a?a@a[a\\a]a^a`a{a|a}a~a", "a-a-a-a-a-a-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"],
			
			// separators trimmed
			["-_ hello _-", "hello"],
			
			// accents removed, lower case, other multibyte chars are URL encoded
			// Iñtërnâtiônàlizætiøn, AND 日本語
			["I\xC3\xB1t\xC3\xABrn\xC3\xA2ti\xC3\xB4n\xC3\xA0liz\xC3\xA6ti\xC3\xB8n, AND \xE6\x97\xA5\xE6\x9C\xAC\xE8\xAA\x9E", 'internationalizaetion-and-%E6%97%A5%E6%9C%AC%E8%AA%9E'],
		];
		
		if (\Elgg\Translit::hasNormalizerSupport()) {
			$cases[] = ["A\xCC\x8A", "a"]; // A followed by 'COMBINING RING ABOVE' (U+030A)
		}
		
		return $cases;
	}

	/**
	 * Test #5369 -- parse_urls()
	 * @see https://github.com/Elgg/Elgg/issues/5369
	 *
	 * @dataProvider parseUrlsProvider
	 */
	public function testParseUrls($input, $expected) {
		$this->assertEquals($expected, parse_urls($input));
	}
	
	public function parseUrlsProvider() {
		return [
			['no.link.here', 'no.link.here'],
			['simple link http://example.org test', 'simple link <a href="http://example.org" rel="nofollow">http://example.org</a> test'],
			['non-ascii http://ñew.org/ test', 'non-ascii <a href="http://ñew.org/" rel="nofollow">http://ñew.org/</a> test'],
			// section 2.1
			['percent encoded http://example.org/a%20b test', 'percent encoded <a href="http://example.org/a%20b" rel="nofollow">http://example.org/a%20b</a> test'],
			// section 2.2: skipping single quote and parenthese
			['reserved characters http://example.org/:/?#[]@!$&*+,;= test', 'reserved characters <a href="http://example.org/:/?#[]@!$&*+,;=" rel="nofollow">http://example.org/:/?#[]@!$&*+,;=</a> test'],
			// section 2.3
			['unreserved characters http://example.org/a1-._~ test', 'unreserved characters <a href="http://example.org/a1-._~" rel="nofollow">http://example.org/a1-._~</a> test'],
			
			['parameters http://example.org/?val[]=1&val[]=2 test', 'parameters <a href="http://example.org/?val[]=1&val[]=2" rel="nofollow">http://example.org/?val[]=1&val[]=2</a> test'],
			['port http://example.org:80/ test', 'port <a href="http://example.org:80/" rel="nofollow">http://example.org:80/</a> test'],
			
			['parentheses (http://www.google.com) test', 'parentheses (<a href="http://www.google.com" rel="nofollow">http://www.google.com</a>) test'],
			['comma http://elgg.org, test', 'comma <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>, test'],
			['period http://elgg.org. test', 'period <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>. test'],
			['exclamation http://elgg.org! test', 'exclamation <a href="http://elgg.org" rel="nofollow">http://elgg.org</a>! test'],
			
			['already anchor <a href="http://twitter.com/">twitter</a> test', 'already anchor <a href="http://twitter.com/">twitter</a> test'],
			
			['ssl https://example.org/ test', 'ssl <a href="https://example.org/" rel="nofollow">https://example.org/</a> test'],
			['ftp ftp://example.org/ test', 'ftp <a href="ftp://example.org/" rel="nofollow">ftp://example.org/</a> test'],
			
			['web archive anchor <a href="http://web.archive.org/web/20000229040250/http://www.google.com/">google</a>', 'web archive anchor <a href="http://web.archive.org/web/20000229040250/http://www.google.com/">google</a>'],
			
			['single quotes already anchor <a href=\'http://www.yahoo.com\'>yahoo</a>', 'single quotes already anchor <a href=\'http://www.yahoo.com\'>yahoo</a>'],
			
			['unquoted already anchor <a href=http://www.yahoo.com>yahoo</a>', 'unquoted already anchor <a href=http://www.yahoo.com>yahoo</a>'],
			
			['parens in uri http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx', 'parens in uri <a href="http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx" rel="nofollow">http://thedailywtf.com/Articles/A-(Long-Overdue)-BuildMaster-Introduction.aspx</a>'],
		];
	}

	/**
	 * Test #10398 -- elgg_parse_emails()
	 * @see https://github.com/Elgg/Elgg/pull/10398
	 *
	 * @dataProvider elggParseEmailsProvider
	 */
	public function testElggParseEmails($input, $expected) {
		$this->assertEquals($expected, elgg_parse_emails($input));
	}
	
	public function elggParseEmailsProvider() {
		return [
			['no.email.here', 'no.email.here'],
			['simple email mail@test.com test', 'simple email <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test'],
			['simple paragraph <p>mail@test.com</p>', 'simple paragraph <p><a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a></p>'],
			['multiple matches mail@test.com test mail@test.com test', 'multiple matches <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test'],
			['invalid email 1 @invalid.com test', 'invalid email 1 @invalid.com test'],
			['invalid email 2 mail@invalid. test', 'invalid email 2 mail@invalid. test'],
			['no double parsing <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test', 'no double parsing <a href="mailto:mail@test.com" rel="nofollow">mail@test.com</a> test'],
			['no double parsing 2 <a href="#">mail@test.com</a> test', 'no double parsing 2 <a href="#">mail@test.com</a> test'],
			['no double parsing 3 <a href="#">with a lot of text - mail@test.com - around it</a> test', 'no double parsing 3 <a href="#">with a lot of text - mail@test.com - around it</a> test'],
		];
	}

	/**
	 * Ensure additional select columns do not end up in entity attributes.
	 *
	 * @see https://github.com/Elgg/Elgg/issues/5538
	 *
	 * @dataProvider extraColumnsDontAppearInAttributesProvider
	 */
	public function testExtraColumnsDontAppearInAttributes($type) {
		$seed_entity = false;
		if ($type !== 'site') {
			// make sure the entity type exists in DB
			$seed_entity = $this->createOne($type);
	
			// entity cache interferes with our test
			$seed_entity->invalidateCache();
		}

		$entities = elgg_get_entities([
			'type' => $type,
			'selects' => ['1 as _nonexistent_test_column'],
			'limit' => 1,
		]);

		$this->assertNotEmpty($entities, "Query for '$type' did not return an entity.");
		
		$entity = $entities[0];
		$this->assertNull($entity->_nonexistent_test_column, "Additional select columns are leaking to attributes for '$type'");
		
		// cleanup
		if ($seed_entity) {
			$seed_entity->delete();
		}
	}
	
	public function extraColumnsDontAppearInAttributesProvider() {
		return [
			['site'],
			['user'],
			['group'],
			['object'],
		];
	}

	/**
	 * Ensure that \ElggBatch doesn't go into infinite loop when disabling annotations recursively when show hidden is
	 * enabled.
	 *
	 * @see https://github.com/Elgg/Elgg/issues/5952
	 */
	public function testDisablingAnnotationsInfiniteLoop() {
		// let's have some entity
		$group = $this->createGroup();

		$total = 51;
		// add some annotations
		for ($cnt = 0; $cnt < $total; $cnt++) {
			$group->annotate('test_annotation', 'value_' . $total);
		}

		// disable them
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($group, $total) {
			elgg_disable_annotations([
				'guid' => $group->guid,
				'limit' => $total, //using strict limit to avoid real infinite loop and just see \ElggBatch limiting on it before finishing the work
			]);
		});
		
		// confirm all being disabled
		$annotations = $group->getAnnotations([
			'limit' => $total,
		]);
		foreach ($annotations as $annotation) {
			$this->assertEquals('no', $annotation->enabled);
		}

		// delete group and annotations
		$group->delete();
	}

	/**
	 * @see https://github.com/Elgg/Elgg/issues/6225
	 */
	public function testUpdateHandlersCanChangeAttributes() {
		$object = $this->createObject([
			'subtype' => 'issue6225',
			'access_id' => ACCESS_PUBLIC,
		]);
		$guid = $object->guid;

		elgg_register_event_handler('update', 'object', [
			self::class,
			'handleUpdateForIssue6225test'
		]);

		$object->save();

		elgg_unregister_event_handler('update', 'object', [
			self::class,
			'handleUpdateForIssue6225test'
		]);

		$object->invalidateCache();

		$object = get_entity($guid);
		$this->assertEquals(ACCESS_PRIVATE, $object->access_id);

		$object->delete();
	}

	public static function handleUpdateForIssue6225test(\Elgg\Event $event) {
		$object = $event->getObject();
		$object->access_id = ACCESS_PRIVATE;
	}

	/**
	 * Tests get_entity_statistics() without owner
	 *
	 * @see https://github.com/Elgg/Elgg/pull/7845
	 */
	function testGlobalGetEntityStatistics() {

		$subtype = 'issue7845' . rand(0, 100);

		$object = $this->createObject([
			'subtype' => $subtype,
		]);

		$stats = get_entity_statistics();

		$this->assertEquals(1, $stats['object'][$subtype]);

		$object->delete();
	}

	/**
	 * Tests get_entity_statistics() with an owner
	 *
	 * @see https://github.com/Elgg/Elgg/pull/7845
	 */
	function testOwnedGetEntityStatistics() {

		$user = $this->createOne('user');

		$subtype = 'issue7845' . rand(0, 100);

		$this->createObject([
			'subtype' => $subtype,
			'owner_guid' => $user->guid,
		]);

		$stats = get_entity_statistics($user->guid);

		$this->assertEquals(1, $stats['object'][$subtype]);

		$user->delete();
	}

}
