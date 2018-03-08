<?php
namespace Elgg\Cache;

use Elgg\Includer;
use Elgg\Project\Paths;
use Elgg\ViewsService;
use Elgg\Config;

/**
 * Handles caching of views in the system cache
 */
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

	/**
	 * Discover the core views if the system cache did not load
	 *
	 * @return void
	 */
	public function registerCoreViews() {
		if ($this->config->system_cache_loaded) {
			return;
		}
		
		// Core view files in /views
		$this->views->registerPluginViews(Paths::elgg());

		// Core view definitions in /engine/views.php
		$file = Paths::elgg() . 'engine/views.php';
		if (!is_file($file)) {
			return;
		}
		
		$spec = Includer::includeFile($file);
		if (is_array($spec)) {
			$this->views->mergeViewsSpec($spec);
		}
	}
}
