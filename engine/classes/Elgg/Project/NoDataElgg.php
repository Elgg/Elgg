<?php
namespace Elgg\Project;

use ElggPluginPackage;
use ElggPlugin;
use Elgg\Config;
use Elgg\Application;

/**
 * Use Elgg without DB or dataroot.
 *
 * @access private EXPERIMENTAL. DO NOT USE
 */
class NoDataElgg {

	/**
	 * @var object
	 */
	private $spec;

	/**
	 * @var bool
	 */
	private $is_setup = false;

	/**
	 * Constructor
	 *
	 * @param \stdClass $spec Spec
	 *    __DIR__          : Path of the document root for your app
	 *    symfony_session  : Symfony Session
	 *    site_secret      : Site key. "z" + 31 base 64 chars
	 *    site_name        : Name for site
	 *    site_description : Tagline for site
	 *    wwwroot          : Optional base URL
	 *    config           : Optional Elgg\Config object
	 */
	public function __construct(\stdClass $spec) {
		$spec->__DIR__ = rtrim($spec->__DIR__, '/\\');
		$this->spec = $spec;
	}

	/**
	 * Make most Elgg APIs available (but anything touching data will fail).
	 *
	 * If not called yet, this is called by run().
	 *
	 * @return void
	 */
	public function setup() {
		if ($this->is_setup) {
			return;
		}

		$spec = $this->spec;

		if (empty($spec->config)) {
			$spec->config = new Config();
		}

		$spec->config->__site_secret__ = $spec->site_secret;
		$spec->config->dataroot = '/fake/path';

		if (empty($spec->config->wwwroot) && !empty($spec->wwwroot)) {
			$spec->config->wwwroot = $spec->wwwroot;
		}
		$spec->config->simplecache_enabled = false;

		$services = new \Elgg\Di\ServiceProvider($spec->config);
		$services->setValue('session', new \ElggSession($spec->symfony_session));
		$services->setValue('db', null);

		$app = Application::factory([
			'service_provider' => $services,
		]);
		$app->loadCore();

		// can't be created until core loaded
		$site = new \ElggSite();
		$site->name = $spec->site_name;
		$site->description = $spec->site_description;
		$spec->config->site = $site;
		$spec->config->sitename = $spec->site_name;
		$spec->config->sitedescription = $spec->site_description;

		$app->bootCore();

		// load local translations
		register_translations($spec->__DIR__ . '/languages');

		// read elgg-plugin.php
		$static_config = [];
		$static_file = $spec->__DIR__ . '/' . ElggPluginPackage::STATIC_CONFIG_FILENAME;
		if (is_file($static_file) && is_readable($static_file)) {
			$static_config = (require $static_file);
		}

		// add local views and views.php
		if (!empty($static_config['views'])) {
			$services->views->mergeViewsSpec($static_file['views']);
		}
		$services->views->registerPluginViews($spec->__DIR__);

		// add local static actions
		if (!empty($static_config['actions'])) {
			ElggPlugin::addActionsFromStaticConfig($static_config['actions'], $spec->__DIR__);
		}

		$this->is_setup = true;
	}

	/**
	 * Handle the request
	 *
	 * To support CLI server use, have your script return this method's return value.
	 *
	 * @return bool
	 */
	public function run() {
		if (!$this->is_setup) {
			$this->setup();
		}

		$request = _elgg_services()->request;

		if ($request->isCliServer() && $request->isCliServable($this->spec->__DIR__)) {
			return false;
		}

		if (0 === strpos($request->getElggPath(), '/cache')) {
			_elgg_services()->cacheHandler->handleRequest($request)->prepare($request)->send();
			return true;
		}

		// TODO use formal Response object instead
		// This is to set the charset to UTF-8.
		header("Content-Type: text/html;charset=utf-8", true);

		if (!_elgg_services()->router->route($request)) {
			forward('', '404');
		}
		return true;
	}
}
