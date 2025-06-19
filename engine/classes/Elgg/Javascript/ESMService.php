<?php

namespace Elgg\Javascript;

use Elgg\Assets\ExternalFiles;
use Elgg\Cache\SimpleCache;
use Elgg\Config;
use Elgg\ViewsService;

/**
 * Keeps track of ES modules
 *
 * @since 6.0
 * @internal
 */
class ESMService {
	
	protected array $imports = [];
	
	protected array $runtime_modules = [];

	/**
	 * Constructor
	 *
	 * @param ViewsService  $views         Views service
	 * @param SimpleCache   $cache         Simple cache
	 * @param ExternalFiles $externalFiles External files
	 * @param Config        $config        Config
	 */
	public function __construct(
		protected ViewsService $views,
		protected SimpleCache $cache,
		protected ExternalFiles $externalFiles,
		protected Config $config
	) {
	}
	
	/**
	 * Returns the importmap data
	 *
	 * @return array
	 */
	public function getImportMapData(): array {
		$modules = $this->views->getESModules();
		$imports = [];
		$result = [];
		
		foreach ($modules as $name) {
			$short_name = str_replace('.mjs', '', $name);
			$imports[$short_name] = $this->cache->getUrl($name);
		}
		
		$result['imports'] = array_merge($imports, $this->runtime_modules);
		
		if (!$this->config->subresource_integrity_enabled) {
			return $result;
		}
		
		foreach ($result['imports'] as $name => $import) {
			$integrity = $this->externalFiles->getSubResourceIntegrity('js', "{$name}.mjs");
			// fallback for runtime modules
			$integrity = $integrity ?? $this->externalFiles->getSubResourceIntegrity('js', $name);
			
			if (!empty($integrity)) {
				$result['integrity'][$import] = $integrity;
			}
		}
		
		return $result;
	}
	
	/**
	 * Registers a module to the import map
	 *
	 * @param string $name name of the module
	 * @param string $href location from where to download the module (usually a simplecache location)
	 *
	 * @return void
	 */
	public function register(string $name, string $href): void {
		$this->runtime_modules[$name] = $href;
	}
	
	/**
	 * Request a module to be loaded on the page
	 *
	 * @param string $name name of the module
	 *
	 * @return void
	 */
	public function import(string $name): void {
		$this->imports[$name] = true;
	}
	
	/**
	 * Returns all modules that requested to be loaded
	 *
	 * @return array
	 */
	public function getImports(): array {
		return array_keys($this->imports);
	}
}
