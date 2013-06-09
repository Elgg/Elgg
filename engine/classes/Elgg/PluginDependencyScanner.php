<?php
class Elgg_PluginDependencyScanner {
	
	/**
	 * @var Elgg_Graph
	 */
	protected $dependencyGraph;
	
	const DEP_REQUIRES = 'requires';
	
	public function __construct() {
		$this->dependencyGraph = new Elgg_Graph();
	}
	
	public function scanPlugins($pluginsToEnable, $pluginsEnabled = null) {
		
		foreach ($pluginsToEnable as $key => $plugin) {
			if ($plugin instanceof ElggPlugin) {
				$pluginsToEnable[$key] = $plugin->getManifest();
			}
		}
		
		return $this->scanManifests($pluginsToEnable, $pluginsEnabled);
	}
	
	public function scanPluginPackages($packagesToEnable, $packagesEnabled = null) {
		
		foreach ($packagesToEnable as $key => $package) {
			if ($package instanceof ElggPluginPackage) {
				$packagesToEnable[$key] = $package->getManifest();
			}
		}
		
		return $this->scanManifests($packagesToEnable, $packagesEnabled);
	}
	
	protected function scanManifests($manifestsToEnable, $manifestsEnabled = null) {

		$mt = microtime(true);
// 		var_dump(count($pluginsToEnable));
		
		// Affects enabling order:
		//catch dependencies on $pluginsToEnable:
		// - priority of plugin
		// - requires plugin
		//catch dependencies on $pluginsEnabled:
		// - priority of plugin
		
// 		shuffle($pluginsToEnable);
		
		foreach ($manifestsToEnable as $manifest) {
			if ($manifest instanceof ElggPluginManifest) {
				$requires = $manifest->getRequires();
				$conflicts = $manifest->getConflicts();
				$provides = $manifest->getProvides();
				$this->dependencyGraph->addVertice($manifest->getPluginID());
// 				var_dump($manifest->getPluginID());
				foreach ($requires as $rule) {
					if ($rule['type'] == 'plugin') {
						//edge A->B in topological sort means "A is required by B"
						$this->dependencyGraph->addEdge($rule['name'], $manifest->getPluginID(), self::DEP_REQUIRES);
// 						$this->dependencyGraph->addEdge($manifest->getPluginID(), $rule['name'], self::DEP_REQUIRES);
// 						var_dump($rule);
					}
				}
			}
		}
// 		var_dump(microtime(true) - $mt);
// 		var_dump($this->dependencyGraph->getVertices());
// 		var_dump($this->dependencyGraph->getEdges());
// 		var_dump($this->dependencyGraph->getCycle());
		
		$result = $this->dependencyGraph->topologicalSort(true);
		return $result;
	}
	
	public function getPluginsEnablingOrder($plugins = null) {
		throw new NotImplementedException();
	}
	
}