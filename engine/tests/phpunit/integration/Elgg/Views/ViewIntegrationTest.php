<?php
/**
 *
 */

namespace Elgg\Integration\Views;

use Elgg\IntegrationTestCase;
use Elgg\Logger;
use Elgg\TestSeeder;
use InvalidArgumentException;
use InvalidParameterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo  : Update views that throw notices and warning
 *        The goal of this test to catch syntax errors and make sure that views are in existence
 *        Ultimately, we would want to review all views and make sure we fix problems
 *
 * @group ViewsService
 * @group IntegrationTests
 */
class ViewIntegrationTest extends IntegrationTestCase {

	public static $_view_error_log = [];
	public static $_random_output;
	public static $_default_view_vars;
	public static $_cleanup;

	public static function getResettableServices() {
		$services = parent::getResettableServices();

		$services[] = 'router';
		$services[] = 'responseFactory';

		return $services;
	}

	public static function setUpBeforeClass() {
		self::createApplication();
		
		parent::setUpBeforeClass();

		// This test produces too many errors
		\PHPUnit_Framework_Error_Notice::$enabled = false;
		\PHPUnit_Framework_Error_Warning::$enabled = false;

		set_error_handler(function ($errno, $errstr, $errfile, $errline) {
			throw new ViewIntegrationTestException($errstr . " on line " . $errline . " in file " . $errfile);
		});

		register_shutdown_function(function () {
			if (!empty(ViewIntegrationTest::$_view_error_log)) {
				foreach (ViewIntegrationTest::$_view_error_log as $error) {
					_elgg_services()->logger->log($error['message'], $error['level']);
				}
			}
		});

		self::$_random_output = _elgg_services()->crypto->getRandomBytes(20);

		$seeder = new TestSeeder();

		$user = $seeder->createUser();
		$friend = $seeder->createUser();
		$user->addFriend($friend->guid);

		$relationship = check_entity_relationship($user->guid, 'friend', $friend->guid);

		$object = $seeder->createObject([
			'owner_guid' => $user->guid,
		]);

		$seeder->createLikes($object, 1);

		$annotations = $object->getAnnotations([
			'name' => 'likes',
			'limit' => 1
		]);

		$plugins = elgg_get_plugins('active');
		$plugin = array_shift($plugins);

		$group = $seeder->createGroup([
			'owner_guid' => $user->guid,
		]);

		$comment = $seeder->createObject([
			'subtype' => 'comment',
			'container_guid' => $object->guid,
			'owner_guid' => $user->guid,
		]);

		$widget = elgg_create_widget($user->guid, 'online_users', 'dashboard');

		$object->foo = 'bar';
		$metadata = elgg_get_metadata([
			'guids' => $object->guid,
			'metadata_names' => 'foo',
		]);

		$river = elgg_get_river([
			'limit' => 1,
		]);

		$upgrade = new \ElggUpgrade();
		$upgrade->id = 'abc';
		$upgrade->class = '\UncallableClass';
		$upgrade->title = 'foo';
		$upgrade->description = 'bar';
		$upgrade->__faker = true;
		$upgrade->save();

		self::$_default_view_vars = [
			'site' => elgg_get_site_entity(),
			'entity' => $object,
			'object' => $object,
			'item' => $object,
			'guid' => $object->guid,
			'plugin' => $plugin,
			'plugin_id' => $plugin->getID(),
			'user' => $user,
			'group' => $group,
			'comment' => $comment,
			'widget' => $widget,
			'annotation' => array_shift($annotations),
			'metadata' => array_shift($metadata),
			'river' => array_shift($river),
			'relationship' => $relationship,
			'elgg_upgrade' => $upgrade,
		];
	}

	public static function tearDownAfterClass() {

		\PHPUnit_Framework_Error_Notice::$enabled = false;
		\PHPUnit_Framework_Error_Warning::$enabled = true;

		restore_error_handler();

		foreach (self::$_default_view_vars as $item) {
			if (is_object($item) && !empty($item->__faker)) {
				$item->delete();
			}
		}

		parent::tearDownAfterClass();

		// Let other tests have a clean application
		self::createApplication();
	}

	public function up() {
		_elgg_services()->logger->disable();

		// Let's run tests as admin to not have to deal with permissions
		// as they are not really important for view rendering
		$admin = $this->getAdmin();
		_elgg_services()->session->setLoggedInUser($admin);

		// Because some views still use forward(), we will use a page handler to avoid dealing with security exceptions
		elgg_register_page_handler('_view_renderer', [
			$this,
			'renderPage'
		]);
	}

	public function down() {

		_elgg_services()->session->removeLoggedInUser();

		while ($stack = _elgg_services()->logger->enable()) {

			foreach ($stack as $entry) {
				$message = "Test {$this->getName(false)} reported error {$this->getDataSetAsString()}: " . PHP_EOL . $entry['message'];
				self::$_view_error_log[] = [
					'message' => $message,
					'level' => $entry['level'],
				];
			}
		}

		elgg_unregister_page_handler('_view_renderer', [
			$this,
			'renderPage'
		]);
	}

	public function render($view, $viewtype, $vars) {
		$view_parts = explode('/', $view);
		$first_part = array_shift($view_parts);

		switch ($first_part) {
			case 'forms' :
				return elgg_view_form(implode('/', $view_parts), ['viewtype' => $viewtype], $vars);
				break;

			default :
				return elgg_view($view, $vars, $viewtype);
		}
	}

	public function renderPage() {

		elgg_set_page_owner_guid($this->getAdmin()->guid);

		// @todo: currently Input service is not aware of the Request being routed
		// Once #11177 is fixed, this can be uncommented, for now using concrete test values
		//$view = get_input('view');
		//$viewtype = get_input('viewtype');

		//$this->assertEquals($this->view, $view);
		//$this->assertEquals($this->viewtype, $viewtype);

		$view = $this->view;
		$viewtype = $this->viewtype;

		try {
			$this->assertTrue(_elgg_services()->views->viewHasHookHandlers($view));

			ob_start();
			$output = $this->render($view, $viewtype, []);
			$this->assertContains(self::$_random_output, $output);
			$buffer = ob_get_clean();
		} catch (\SecurityException $e) {
			_elgg_services()->logger->error("View \"$view\" calls forward() which is discouraged. Place the forwarding logic in the controller.");
		}

		$this->assertEmpty($buffer);

		elgg_set_page_owner_guid(0);

		return elgg_ok_response($output);
	}

	/**
	 * This tests both view existence #9714, as well as tries to catch any syntax errors in views
	 * It doesn't test actual view output
	 *
	 * Failing tests will be reported as risky, but won't actually fail the test
	 *
	 * @dataProvider viewsProvider
	 */
	public function testCanRenderView($view, $viewtype, $path, $is_simplecache_view) {

		if ($viewtype === 'php' || in_array($view, ['export/entity'])) {
			// @todo: Remove when #11173 is fixed
			$this->markTestSkipped("data_views plugin needs to be updated, it still calls removed methods");
		}

		$this->view = $view;
		$this->viewtype = $viewtype;

		$replace_view_vars = function ($hook, $type, $return, $params) {
			$view_vars = $this->prepareViewVars($type, $return);
			$view_vars['hook_view'] = $type;

			return $view_vars;
		};

		$replace_view = function ($hook, $type, $return, $params) {
			// Assert that the view_vars hook was called
			$this->assertEquals($type, $params['vars']['hook_view']);

			return self::$_random_output;
		};

		_elgg_services()->hooks->registerHandler('view_vars', $view, $replace_view_vars);
		_elgg_services()->hooks->registerHandler('view', $view, $replace_view);

		try {

			$this->assertTrue(_elgg_services()->views->isValidViewtype($viewtype));

			$this->assertEquals($path, _elgg_services()->views->findViewFile($view, $viewtype));
			$this->assertTrue(_elgg_services()->views->viewExists($view, $viewtype));

			$view_list = _elgg_services()->views->getViewList($view);
			$this->assertNotEmpty($view_list);
			$this->assertEquals(count($view_list) > 1, _elgg_services()->views->viewIsExtended($view));

			$this->assertEquals($is_simplecache_view, _elgg_services()->views->isCacheableView($view));

			$params = [
				'view' => $view,
				'viewtype' => $viewtype,
			];

			$request = $this->prepareHttpRequest('_view_renderer', 'GET', $params);

			// Response factory buffers the output on cli
			ob_start();
			_elgg_services()->router->route($request);
			$output = ob_get_clean();

			$response = _elgg_services()->responseFactory->getSentResponse();
			$this->assertInstanceOf(Response::class, $response);

			if ($response->isSuccessful()) {
				// Not using equal, because of views that are wrapping
				$this->assertContains(self::$_random_output, $output);
			} else {
				throw new ViewIntegrationTestException("Response from \"$view\" is not OK", $response->getStatusCode());
			}

		} catch (InvalidArgumentException $e) {

			// Some views throw InvalidArgumentException when an owner or container can not be loaded
			// These should really be changed to HTTP exceptions
			throw new ViewIntegrationTestException($e->getMessage(), $e->getCode(), $e);

		} catch (InvalidParameterException $e) {

			// Some views throw InvalidArgumentException when an owner or container can not be loaded
			// These should really be changed to HTTP exceptions
			throw new ViewIntegrationTestException($e->getMessage(), $e->getCode(), $e);

		} catch (ViewIntegrationTestException $e) {

			$error = [
				"View \"$view\" can not be rendered due to an error: ",
				"{$e->getMessage()}",
			];

			self::$_view_error_log[] = [
				'message' => implode(PHP_EOL, $error),
				'level' => Logger::ERROR,
			];
		}

		_elgg_services()->hooks->unregisterHandler('view_vars', $view, $replace_view_vars);
		_elgg_services()->hooks->unregisterHandler('view', $view, $replace_view);
	}

	public function viewsProvider() {

		self::createApplication();

		$provides = [];

		$data = _elgg_services()->views->getInspectorData();

		foreach ($data['locations'] as $viewtype => $views) {
			foreach ($views as $view => $path) {
				$provides[] = [
					$view,
					$viewtype,
					$path,
					elgg_extract($view, $data['simplecache'], false),
				];
			}
		}

		return $provides;
	}

	/**
	 * Allows passing variables to views for further testing
	 *
	 * @param string $view View name
	 * @param array  $vars View vars
	 *
	 * @return array
	 */
	public function prepareViewVars($view, $vars = []) {

		$vars = array_merge(self::$_default_view_vars, $vars);

		$parts = explode('/', $view);
		if (in_array($parts[0], [
			'icon',
			'forms'
		])) {
			array_shift($parts);
		}

		switch ($parts[0]) {
			case 'object' :
				$subtype = $parts[1];
				if (isset($vars[$subtype])) {
					$object = $vars[$subtype];
				} else {
					$object = $this->createObject([
						'subtype' => $subtype,
					]);
					self::$_default_view_vars[$subtype] = $object;
				}

				$vars['entity'] = $object;

				break;

			case 'site' :
				$vars['entity'] = $vars['site'];
				break;

			case 'user' :
			case 'profile' :
				$vars['entity'] = $vars['user'];

				break;

			case 'group' :
			case 'groups' :
				$vars['entity'] = $vars['group'];

				break;

			case 'widgets' :
				$vars['entity'] = $vars['widget'];
				break;

			case 'river' :
				$vars['item'] = $vars['river'];
				break;

			case 'page' :
				$defaults = [
					'content' => '',
					'title' => '',
					'filter' => '',
					'sidebar' => '',
					'description' => '',
					'body' => '',
				];
				$vars = array_merge($defaults, $vars);
				switch ($parts[1]) {
					case 'components' :
						switch ($parts[2]) {
							case 'list' :
							case 'gallery' :
							case 'table' :
								$vars['items'] = [
									$vars['object'],
									$vars['group'],
									$vars['user'],
								];
								break;
						}
						break;
				}
				break;


		}

		switch ($view) {

			case 'messages/exceptions/exception' :
			case 'messages/exceptions/admin_exception' :
				$vars = [
					'object' => new \Exception(''),
					'ts' => time(),
				];
				break;

			case 'core/ajax/edit_comment' :
				$vars['entity'] = $vars['comment'];
				$vars['guid'] = $vars['entity']->guid;

				break;

			case 'forms/plugins/settings/save' :
			case 'forms/plugins/usersettings/save' :
				$vars['entity'] = $vars['plugin'];

				break;
		}

		$vars['guid'] = $vars['entity']->guid;

		return $vars;
	}
}

class ViewIntegrationTestException extends \RuntimeException {

}