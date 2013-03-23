<?php
/**
 * Elgg Regression Tests -- Github Issues Bugfixes
 * Any bugfixes from Github that require testing belong here.
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreRegressionBugsTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		$this->ia = elgg_set_ignore_access(TRUE);
		parent::__construct();

		// all __construct() code should come after here
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		elgg_set_ignore_access($this->ia);
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * #1558
	 */
	public function testElggObjectDeleteAnnotations() {
		$this->entity = new ElggObject();
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
	 * #2063 - get_resized_image_from_existing_file() fails asked for image larger than selection and not scaling an image up
	 * Test get_image_resize_parameters().
	 */
	public function testElggResizeImage() {
		$orig_width = 100;
		$orig_height = 150;

		// test against selection > max
		$options = array(
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => TRUE,
			'upscale' => FALSE,

			'x1' => 25,
			'y1' => 75,
			'x2' => 100,
			'y2' => 150
		);

		// should get back the same x/y offset == x1, y1 and an image of 50x50
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);

		$this->assertEqual($params['newwidth'], $options['maxwidth']);
		$this->assertEqual($params['newheight'], $options['maxheight']);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);

		// test against selection < max
		$options = array(
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => TRUE,
			'upscale' => FALSE,

			'x1' => 75,
			'y1' => 125,
			'x2' => 100,
			'y2' => 150
		);

		// should get back the same x/y offset == x1, y1 and an image of 25x25 because no upscale
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);

		$this->assertEqual($params['newwidth'], 25);
		$this->assertEqual($params['newheight'], 25);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);
	}

	// #3722 Check canEdit() works for contains regardless of groups
	function test_can_write_to_container() {
		$user = new ElggUser();
		$user->username = 'test_user_' . rand();
		$user->name = 'test_user_name_' . rand();
		$user->email = 'test@user.net';
		$user->container_guid = 0;
		$user->owner_guid = 0;
		$user->save();

		$object = new ElggObject();
		$object->save();

		$group = new ElggGroup();
		$group->save();

		// disable access overrides because we're admin.
		$ia = elgg_set_ignore_access(false);

		$this->assertFalse(can_write_to_container($user->guid, $object->guid));

		global $elgg_test_user;
		$elgg_test_user = $user;

		// register hook to allow access
		function can_write_to_container_test_hook($hook, $type, $value, $params) {
			global $elgg_test_user;

			if ($params['user']->getGUID() == $elgg_test_user->getGUID()) {
				return true;
			}
		}

		elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'can_write_to_container_test_hook');
		$this->assertTrue(can_write_to_container($user->guid, $object->guid));
		elgg_unregister_plugin_hook_handler('container_permissions_check', 'all', 'can_write_to_container_test_hook');

		$this->assertFalse(can_write_to_container($user->guid, $group->guid));
		$group->join($user);
		$this->assertTrue(can_write_to_container($user->guid, $group->guid));

		elgg_set_ignore_access($ia);

		$user->delete();
		$object->delete();
		$group->delete();
	}

	function test_db_shutdown_links() {
		global $DB_DELAYED_QUERIES, $test_results;
		$DB_DELAYED_QUERIES = array();

		function test_delayed_results($results) {
			global $test_results;
			$test_results = $results;
		}

		$q = 'SELECT 1 as test';

		$links = array('read', 'write', get_db_link('read'), get_db_link('write'));

		foreach ($links as $link) {
			$DB_DELAYED_QUERIES = array();

			$result = execute_delayed_query($q, $link, 'test_delayed_results');

			$this->assertTrue($result, "Failed with link = $link");
			$this->assertEqual(count($DB_DELAYED_QUERIES), 1);
			$this->assertEqual($DB_DELAYED_QUERIES[0]['q'], $q);
			$this->assertEqual($DB_DELAYED_QUERIES[0]['l'], $link);
			$this->assertEqual($DB_DELAYED_QUERIES[0]['h'], 'test_delayed_results');

			db_delayedexecution_shutdown_hook();

			$num_rows = mysql_num_rows($test_results);
			$this->assertEqual($num_rows, 1);
			$row = mysql_fetch_assoc($test_results);
			$this->assertEqual($row['test'], 1);
		}

		// test bad case
		$DB_DELAYED_QUERIES = array();
		$result = execute_delayed_query($q, 'not_a_link', 'test_delayed_results');
		$this->assertFalse($result);
		$this->assertEqual(array(), $DB_DELAYED_QUERIES);
	}

	/**
	 * http://trac.elgg.org/ticket/3210 - Don't remove -s in friendly titles
	 * http://trac.elgg.org/ticket/2276 - improve char encoding
	 */
	public function test_friendly_title() {
		$cases = array(
			// hyphen, underscore and ASCII whitespace replaced by separator,
			// other non-alphanumeric ASCII removed
			"a-a_a a\na\ra\ta\va!a\"a#a\$a%a&a'a(a)a*a+a,a.a/a:a;a<a=a>a?a@a[a\\a]a^a`a{a|a}a~a"
			=> "a-a-a-a-a-a-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",

			// separators trimmed
			"-_ hello _-" => "hello",

			// accents removed, lower case, other multibyte chars are URL encoded
			"I\xC3\xB1t\xC3\xABrn\xC3\xA2ti\xC3\xB4n\xC3\xA0liz\xC3\xA6ti\xC3\xB8n, AND \xE6\x97\xA5\xE6\x9C\xAC\xE8\xAA\x9E"
				// Iñtërnâtiônàlizætiøn, AND 日本語
			=> 'internationalizaetion-and-%E6%97%A5%E6%9C%AC%E8%AA%9E',

			// some HTML entity replacements
			"Me &amp; You" => 'me-and-you',
		);

		// where available, string is converted to NFC before transliteration
		if (ElggTranslit::hasNormalizerSupport()) {
			$form_d = "A\xCC\x8A"; // A followed by 'COMBINING RING ABOVE' (U+030A)
			$cases[$form_d] = "a";
		}

		foreach ($cases as $case => $expected) {
			$friendly_title = elgg_get_friendly_title($case);
			$this->assertIdentical($expected, $friendly_title);
		}
	}

	/**
	 * Test #5244 -- parse_urls()
	 * https://github.com/Elgg/Elgg/issues/5244
	 */
	public function test_parse_urls() {

		$cases = array(
			"no.link.here",
			"but here yes, http://example.org, and there: https://ssl.example.org/works.",
			"news://leaks.example.net/, also",
			"ftp://ftp.example.net/will/support/ellipsis...",
			"and xmpp:foo@example.net! No kidding.",
			"multiline
http://foo.example.net
is ok",
			"but IRC link will break:irc://bla.example.org/#foo, not my fault.",
		);

		$this->assertEqual('no.link.here', parse_urls($cases[0]));
		$this->assertEqual('but here yes, <a class="elgg-link-http" href="http://example.org" rel="nofollow">example.org</a>, and there: <a class="elgg-link-https" href="https://ssl.example.org/works" rel="nofollow">ssl.example.org/works</a>.', parse_urls($cases[1]));
		$this->assertEqual('<a class="elgg-link-news" href="news://leaks.example.net/" rel="nofollow">leaks.example.net</a>, also', parse_urls($cases[2]));
		$this->assertEqual('<a class="elgg-link-ftp" href="ftp://ftp.example.net/will/support/ellipsis" rel="nofollow">ftp.example.net/will/support/ellipsis</a>...', parse_urls($cases[3]));
		$this->assertEqual('and <a class="elgg-link-xmpp" href="xmpp:foo@example.net" rel="nofollow">foo@example.net</a>! No kidding.', parse_urls($cases[4]));
		$this->assertEqual('multiline
<a class="elgg-link-http" href="http://foo.example.net" rel="nofollow">foo.example.net</a>
is ok.', $cases[5]);
		$this->assertEqual('but IRC link will break:<a class="elgg-link-irc" href="irc://bla.example.org/#foo" rel="nofollow">irc://bla.example.org/#foo</a>, not my fault.', parse_urls($cases[6]));

	}
}
