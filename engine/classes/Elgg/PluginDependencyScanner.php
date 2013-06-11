<?php
/**
 * Computes some dependencies between plugins and gives info aboud resolving problems when possible. 
 *
 * @access private
 * @package Elgg.Core
 */
class Elgg_PluginDependencyScanner {
	
	/**
	 * @var Elgg_Graph
	 */
	protected $dependencyGraph;
	
	/**
	 * @var string
	 */
	const DEP_REQUIRES = 'requires';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dependencyGraph = new Elgg_Graph();
	}
	
	/**
	 * Performs topological sotr of given plugins/packages to determine save order of enabling them in single action
	 * 
	 * @param ElggPlugin[]|ElggPluginPackage[] $plugins Plugins or packages to be sorted
	 * @return false|ElggPlugin[]|ElggPluginPackage[]
	 */
	public function scanPlugins($plugins) {
		
		$resultPlugins = array();
		foreach ($plugins as $key => $plugin) {
			if (($plugin instanceof ElggPlugin) || ($plugin instanceof ElggPluginPackage)) {
				$plugins[$key] = $plugin->getManifest();
				$resultPlugins[$plugin->getId()] = $plugin;
			}
		}
		
		$result = $this->scanManifests($plugins);
		
		if ($result === false) {
			//TODO consider something else
			return false;
		}
		
		return array_merge(array_flip($result), $resultPlugins);
	}
	
	/**
	 * Returns plugins cycle if exists
	 * 
	 * @return array|false returns array of names of vertices making the cycle or false if graph is acyclic
	 */
	public function getCycle() {
		return $this->dependencyGraph->getCycle();
	}
	
	/**
	 * SOrts manifests topologizally by "requires plugin" dependency
	 * 
	 * @param ElggPluginManifest[] $manifests List of manifests to sort
	 * @return false|string[]
	 */
	protected function scanManifests($manifests) {

		$mt = microtime(true);
		
		// Affects enabling order:
		//catch dependencies on $pluginsToEnable:
		// - priority of plugin
		// - requires plugin
		//catch dependencies on $pluginsEnabled:
		// - priority of plugin
		
		foreach ($manifests as $manifest) {
			if ($manifest instanceof ElggPluginManifest) {
				$requires = $manifest->getRequires();
				$conflicts = $manifest->getConflicts();
				$provides = $manifest->getProvides();
				$this->dependencyGraph->addVertex($manifest->getPluginID());
				foreach ($requires as $rule) {
					if ($rule['type'] == 'plugin') {
						//edge A->B in topological sort means "A is required by B"
						$this->dependencyGraph->addEdge($rule['name'], $manifest->getPluginID(), self::DEP_REQUIRES);
					}
				}
			}
		}
		// var_dump(microtime(true) - $mt);
		// var_dump($this->dependencyGraph->getVertices());
		// var_dump($this->dependencyGraph->getEdges());
		// var_dump($this->dependencyGraph->getCycle());
		
		return $this->dependencyGraph->topologicalSort(true);
	}
}