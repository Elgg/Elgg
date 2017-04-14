<?php
namespace Elgg\Project;

use Elgg\Database\SiteSecret;
use Elgg\Application\CacheHandler;
use ElggPluginPackage;
use ElggPlugin;

/**
 * Boot Elgg without DB or dataroot.
 *
 * @access private EXPERIMENTAL. DO NOT USE
 */
class NoDataBoot {

	/**
	 * Boot Elgg without a data directory or DB
	 *
	 * @param \stdClass $no_data_config Config data
	 * @return void
	 */
	public function boot(\stdClass $no_data_config) {

		$no_data_config->__DIR__ = rtrim($no_data_config->__DIR__, '/\\');

		$config = new \Elgg\Config(null, false);
		$sp = new \Elgg\Di\ServiceProvider($config);
		$app = new \Elgg\Application($sp);

		if (!$no_data_config->wwwroot) {
			$no_data_config->wwwroot = $sp->request->getSchemeAndHttpHost() . $sp->request->getBaseUrl() . '/';
		}
		$config->set('wwwroot', $no_data_config->wwwroot);
		$config->set('dataroot', '/fake/path');
		$config->set('cacheroot', '/fake/path');
		$config->set('language', 'en');
		$config->set('simplecache_enabled', false);
		$config->set('dbprefix', 'NOT_SET');
		$config->set('sitename',  $no_data_config->site_name);
		$config->set('sitedescription', $no_data_config->site_description);

		// don't load settings.php, check DB for configs/plugins, or boot
		$config->set('Database_none', true);
		$config->set('Config_file', false);
		$config->set('site_config_loaded', true);
		$config->set('boot_complete', true);

		// don't use DB session
		$sp->setValue('session', new \ElggSession($no_data_config->symfony_session));

		// use local key
		$secret = new SiteSecret($sp->configTable);
		$secret->setTestingSecret($no_data_config->site_secret);
		$sp->setValue('siteSecret', $secret);

		// define functions
		$app->loadCore();

		$sp->session->start();

		// core translations
		$sp->translator->loadTranslations();

		// load local translations
		register_translations($no_data_config->__DIR__ . '/languages');

		// read elgg-plugin.php
		$static_config = [];
		$static_file = $no_data_config->__DIR__ . '/' . ElggPluginPackage::STATIC_CONFIG_FILENAME;
		if (is_file($static_file) && is_readable($static_file)) {
			$static_config = (require $static_file);
		}

		// need a site entity for the request
		$site = new \ElggSite();
		$site->url = $config->getSiteUrl();
		$site->name = $no_data_config->site_name;
		$site->description = $no_data_config->site_description;
		$config->set('site', $site);

		// register all core views
		// elgg_views_boot() needs this
		$sp->views->view_path = \Elgg\Application::elggDir()->getPath('views');
		elgg_views_boot();

		// add local views and views.php
		if (!empty($static_config['views'])) {
			$sp->views->mergeViewsSpec($static_file['views']);
		}
		$sp->views->registerPluginViews($no_data_config->__DIR__);

		// add local static actions
		if (!empty($static_config['actions'])) {
			ElggPlugin::addActionsFromStaticConfig($static_config['actions'], $no_data_config->__DIR__);
		}

		// allow /cache to work
		$path = $sp->request->getPathInfo();
		if (0 === strpos($path, '/cache')) {
			(new CacheHandler($app, $config, $_SERVER))->handleRequest($path);
			exit;
		}
	}

	/**
	 * Start routing
	 *
	 * @return void
	 */
	public function route() {
		if (!_elgg_services()->router->route(_elgg_services()->request)) {
			forward('', '404');
		}
	}
}
