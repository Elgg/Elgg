<?php
namespace Elgg\Cache;

use Elgg\Includer;
use Elgg\ViewsService;
use Elgg\Filesystem\Directory\Local;
use Elgg\Config;

class ViewCacher {

	/**
	 * @var ViewsService
	 */
	private $views;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param ViewsService $views  Views
	 * @param Config       $config Config
	 */
	public function __construct(ViewsService $views, Config $config) {
		$this->views = $views;
		$this->config = $config;
	}

	public function registerCoreViews() {
		if (!$this->config->system_cache_loaded) {
			// Core view files in /views
			$this->views->registerPluginViews(Local::projectRoot()->getPath());

			// Core view definitions in /engine/views.php
			$file = $this->config->elgg_root . 'engine/views.php';
			if (is_file($file)) {
				$spec = Includer::includeFile($file);
				if (is_array($spec)) {
					$this->views->mergeViewsSpec($spec);
				}
			}
		}
	}
}
