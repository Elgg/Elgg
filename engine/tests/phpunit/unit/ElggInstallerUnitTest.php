<?php

use Elgg\Application;
use Elgg\Config;
use Elgg\Mocks\Di\MockServiceProvider;
use Elgg\Project\Paths;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Installer
 */
class ElggInstallerUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var MockObject
	 */
	private $mock;

	/**
	 * @var Application
	 */
	private $app;

	private $settings_path_backup;

	public function up() {

		$this->settings_path_backup = null;
		if (isset($_ENV['ELGG_SETTINGS_FILE'])) {
			$this->settings_path_backup = $_ENV['ELGG_SETTINGS_FILE'];
		}

		$_ENV['ELGG_SETTINGS_FILE'] = $this->normalizeTestFilePath('installer/settings.php');

		$this->mock = $this->getMockBuilder(ElggInstaller::class)
			->onlyMethods([
				'getApp',
				'checkRewriteRules',
				'createSessionFromFile',
				'createSessionFromDatabase',
			])
			->getMock();

		$this->mock->method('getApp')
			->will($this->returnCallback([$this, 'getApp']));

		$this->mock->method('checkRewriteRules')
			->will($this->returnCallback([$this, 'checkRewriteRules']));

		$this->mock->method('createSessionFromFile')
			->will($this->returnCallback(function () {
				$this->getApp()->_services->setValue('session', ElggSession::getMock());
			}));

		$this->mock->method('createSessionFromDatabase')
			->will($this->returnCallback(function () {
				$this->getApp()->_services->setValue('session', ElggSession::getMock());
			}));
	}

	public function down() {
		if (is_file($this->normalizeTestFilePath('installer/settings.php'))) {
			unlink($this->normalizeTestFilePath('installer/settings.php'));
		}

		$_ENV['ELGG_SETTINGS_FILE'] = $this->settings_path_backup;
	}

	public function getApp() {
		if ($this->app) {
			return $this->app;
		}

		Application::setInstance(null);

		$config = new Config();
		$config->elgg_config_locks = false;
		$config->installer_running = true;
		$config->dbencoding = 'utf8mb4';
		$config->boot_cache_ttl = 0;
		$config->system_cache_enabled = false;
		$config->simplecache_enabled = false;
		$config->lastcache = time();
		$config->wwwroot = getenv('ELGG_WWWROOT') ? : 'http://localhost/';

		$services = new MockServiceProvider($config);

		$services->setValue('session', \ElggSession::getMock());
		$services->systemMessages;

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $services,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
		]);

		Application::setInstance($app);
		$app->loadCore();
		$this->app = $app;

		$this->app->_services->views->setViewtype('installation');
		$this->app->_services->views->registerPluginViews(Paths::elgg());
		$this->app->_services->translator->registerTranslations(Paths::elgg() . "install/languages/", true);

		return $this->app;

	}

	public function checkRewriteRules(&$report) {
		$report['rewrite'] = [
			[
				'severity' => 'success',
				'message' => elgg_echo('install:check:rewrite:success'),
			]
		];

		return $report;
	}

	public function createSettingsFile() {
		$template = Application::elggDir()->getContents("elgg-config/settings.example.php");

		$params = [
			'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 'c_i_elgg_',
			'dbname' => getenv('ELGG_DB_NAME') ? : '',
			'dbuser' => getenv('ELGG_DB_USER') ? : '',
			'dbpassword' => getenv('ELGG_DB_PASS') ? : '',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ? : 3306,
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',
			'dataroot' => \Elgg\Project\Paths::sanitize(Paths::elgg() . 'engine/tests/test_files/dataroot/'),
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'timezone' => 'UTC',
			'cacheroot' => \Elgg\Project\Paths::sanitize(Paths::elgg() . 'engine/tests/test_files/cacheroot/'),
			'assetroot' => \Elgg\Project\Paths::sanitize(Paths::elgg() . 'engine/tests/test_files/assetroot/'),
		];

		foreach ($params as $k => $v) {
			$template = str_replace("{{" . $k . "}}", $v, $template);
		}

		file_put_contents(Config::resolvePath(), $template);
	}

	public function testWelcome() {

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);

		$vars = [];
		$vars['next_step'] = 'requirements';

		$title = elgg_echo("install:welcome");
		$body = elgg_view("install/pages/welcome", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => 'welcome',
				'steps' => [
					'welcome',
					'requirements',
					'database',
					'settings',
					'admin',
					'complete',
				],
			]
		);

		$this->assertEquals($response->getContent(), $output);
	}

	public function testRequirements() {

		$this->getApp()->_services->request->setParam('step', 'requirements');

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);

		$vars = [];
		$vars['report'] = [
			'php' => [
				[
					'severity' => 'success',
					'message' => elgg_echo('install:check:php:success')
				]
			],
			'rewrite' => [
				[
					'severity' => 'success',
					'message' => elgg_echo('install:check:rewrite:success'),
				]
			],
			'database' => [
				[
					'severity' => 'notice',
					'message' => elgg_echo('install:check:database')
				],
			]

		];

		$vars['num_failures'] = 0;
		$vars['num_warnings'] = 0;
		$vars['next_step'] = 'database';

		$title = elgg_echo("install:requirements");
		$body = elgg_view("install/pages/requirements", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => 'requirements',
				'steps' => [
					'welcome',
					'requirements',
					'database',
					'settings',
					'admin',
					'complete',
				],
			]
		);

		$this->assertEquals($response->getContent(), $output);
	}

	public function testDatabase() {

		$this->getApp()->_services->request->setParam('step', 'database');

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);

		$vars = [];
		$vars['variables'] = [
			'dbuser' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'dbpassword' => [
				'type' => 'password',
				'value' => '',
				'required' => false,
			],
			'dbname' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'dbhost' => [
				'type' => 'text',
				'value' => 'localhost',
				'required' => true,
			],
			'dbport' => [
				'type' => 'number',
				'value' => 3306,
				'required' => true,
				'min' => 0,
				'max' => 65535,
			],
			'dbprefix' => [
				'type' => 'text',
				'value' => 'elgg_',
				'required' => false,
			],
			'dataroot' => [
				'type' => 'text',
				'value' => '',
				'required' => true,
			],
			'wwwroot' => [
				'type' => 'url',
				'value' => $this->getApp()->_services->config->wwwroot,
				'required' => true,
			],
			'timezone' => [
				'type' => 'dropdown',
				'value' => 'UTC',
				'options' => \DateTimeZone::listIdentifiers(),
				'required' => true
			]
		];

		$vars['next_step'] = 'settings';

		$title = elgg_echo("install:database");
		$body = elgg_view("install/pages/database", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => 'database',
				'steps' => [
					'welcome',
					'requirements',
					'database',
					'settings',
					'admin',
					'complete',
				],
			]
		);

		$this->assertEquals($response->getContent(), $output);
	}

	public function testDatabaseAction() {

		$dataroot = dirname(Paths::elgg()) . '/_installer_testing_dataroot/';
		elgg_delete_directory($dataroot);

		mkdir($dataroot);

		$request = $this->prepareHttpRequest('install.php?step=database', 'POST', [
			'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 'c_i_elgg_',
			'dbname' => getenv('ELGG_DB_NAME') ? : '',
			'dbuser' => getenv('ELGG_DB_USER') ? : '',
			'dbpassword' => getenv('ELGG_DB_PASS') ? : '',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ? : 3306,
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',
			'dataroot' => $dataroot,
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'timezone' => 'UTC',
		]);

		$this->getApp()->_services->setValue('request', $request);

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('install.php?step=settings'), $response->getForwardURL());

		elgg_delete_directory($dataroot);
	}
	
	public function testDatabaseActionWithEmptyPrefix() {

		$dataroot = dirname(Paths::elgg()) . '/_installer_testing_dataroot/';
		elgg_delete_directory($dataroot);

		mkdir($dataroot);

		$request = $this->prepareHttpRequest('install.php?step=database', 'POST', [
			'dbprefix' => '',
			'dbname' => getenv('ELGG_DB_NAME') ? : '',
			'dbuser' => getenv('ELGG_DB_USER') ? : '',
			'dbpassword' => getenv('ELGG_DB_PASS') ? : '',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ? : 3306,
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',
			'dataroot' => $dataroot,
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'timezone' => 'UTC',
		]);

		$this->getApp()->_services->setValue('request', $request);

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('install.php?step=settings'), $response->getForwardURL());

		elgg_delete_directory($dataroot);
	}

	public function testSettings() {

		$db = $this->getApp()->_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => 'SHOW TABLES',
			'results' => [
				(object) ["{$db->prefix}config"],
			],
		]);

		$this->createSettingsFile();

		$this->getApp()->_services->request->setParam('step', 'settings');

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);

		$vars = [];
		$vars['variables'] = [
			'sitename' => [
				'type' => 'text',
				'value' => 'My New Community',
				'required' => true,
			],
			'siteemail' => [
				'type' => 'email',
				'value' => '',
				'required' => false,
			],
			'siteaccess' => [
				'type' => 'access',
				'value' => ACCESS_PUBLIC,
				'required' => true,
			],
		];

		$vars['next_step'] = 'admin';

		$title = elgg_echo("install:settings");
		$body = elgg_view("install/pages/settings", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => 'settings',
				'steps' => [
					'welcome',
					'requirements',
					'database',
					'settings',
					'admin',
					'complete',
				],
			]
		);

		$this->assertEquals($response->getContent(), $output);
	}

	public function testSettingsAction() {

		$db = $this->getApp()->_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => 'SHOW TABLES',
			'results' => [
				(object) ["{$db->prefix}config"],
			],
		]);

		$this->createSettingsFile();

		$this->getApp()->_services->request->setParam('step', 'settings');

		$request = $this->prepareHttpRequest('install.php?step=settings', 'POST', [
			'sitename' => 'Test Site',
			'siteemail' => 'no-reply@example.com',
			'siteaccess' => ACCESS_PUBLIC,
		]);

		$this->getApp()->_services->setValue('request', $request);

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('install.php?step=admin'), $response->getForwardURL());
	}

	public function testAdmin() {

		$db = $this->getApp()->_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => 'SHOW TABLES',
			'results' => [
				(object) ["{$db->prefix}config"],
			],
		]);

		$this->createSettingsFile();

		$this->createSite();

		$this->getApp()->_services->request->setParam('step', 'admin');

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);

		$vars = [
			'variables' => [
				'displayname' => [
					'type' => 'text',
					'value' => '',
					'required' => true,
				],
				'email' => [
					'type' => 'email',
					'value' => '',
					'required' => true,
				],
				'username' => [
					'type' => 'text',
					'value' => '',
					'required' => true,
				],
				'password1' => [
					'type' => 'password',
					'value' => '',
					'required' => true,
					'pattern' => '.{6,}',
				],
				'password2' => [
					'type' => 'password',
					'value' => '',
					'required' => true,
				],
			],
			'next_step' => 'complete',
		];

		$title = elgg_echo("install:admin");
		$body = elgg_view("install/pages/admin", $vars);

		$output = elgg_view_page(
			$title,
			$body,
			'default',
			[
				'step' => 'admin',
				'steps' => [
					'welcome',
					'requirements',
					'database',
					'settings',
					'admin',
					'complete',
				],
			]
		);

		$this->assertEquals($response->getContent(), $output);
	}

	public function testAdminAction() {

		$db = $this->getApp()->_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => 'SHOW TABLES',
			'results' => [
				(object) ["{$db->prefix}config"],
			],
		]);

		$this->createSettingsFile();

		$this->createSite();

		$this->getApp()->_services->request->setParam('step', 'admin');

		$request = $this->prepareHttpRequest('install.php?step=admin', 'POST', [
			'displayname' => 'admin user',
			'email' => 'admin@example.com',
			'username' => 'admin',
			'password1' => '12345678',
			'password2' => '12345678',
		]);

		$this->getApp()->_services->setValue('request', $request);

		$mock = $this->mock;
		/* @var $mock ElggInstaller */

		$response = $mock->run();

		$this->assertInstanceOf(\Elgg\Http\RedirectResponse::class, $response);
		$this->assertEquals(elgg_normalize_url('install.php?step=complete'), $response->getForwardURL());

		$this->assertInstanceOf(ElggUser::class, elgg_get_logged_in_user_entity());

		$this->getApp()->_services->session->removeLoggedInUser();
	}

	public function testBatchInstall() {

		$db = $this->getApp()->_services->db;
		/* @var $db \Elgg\Mocks\Database */

		$db->addQuerySpec([
			'sql' => 'SHOW TABLES',
			'results' => [
				(object) ["{$db->prefix}config"],
			],
		]);

		$this->mock->batchInstall([
			// database settings
			'dbuser' => getenv('ELGG_DB_USER') ? : 'c_i_elgg_dbuser',
			'dbpassword' => getenv('ELGG_DB_PASS') ? : 'c_i_elgg_dbpwd',
			'dbname' => getenv('ELGG_DB_NAME') ? : 'c_i_elgg_dbname',
			'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 'c_i_elgg_',
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ? : '3306',

			// site settings
			'sitename' => 'Elgg CI Site',
			'siteemail' => 'no_reply@ci.elgg.org',
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'dataroot' => getenv('HOME') . '/engine/tests/test_files/dataroot/',

			// admin account
			'displayname' => 'Administrator',
			'email' => 'admin@ci.elgg.org',
			'username' => 'admin',
			'password' => 'fancypassword',

			// timezone
			'timezone' => 'UTC',
		]);

		$this->assertNull($this->getApp()->_services->config->installer_running);
		$this->assertIsInt($this->getApp()->_services->config->installed);
	}
}
