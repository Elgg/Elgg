<?php

namespace Elgg\Views;

use Elgg\Cache\Pool;
use Elgg\Structs\GetterMap;

/**
 * A PathRegistry decorator that will cache the results of another PathRegistry.
 * 
 * @since 2.0.0
 * @access private
 */
class CachedPathRegistry implements PathRegistry {
	/** @var PathRegistry */
	private $delegate;
	
	/** @var Pool */
	private $cache;
	
	/**
	 * Constructor
	 * 
	 * @param PathRegistry $delegate Implementation to defer to when necessary
	 */
	public function __construct(PathRegistry $delegate, Pool $cache) {
		$this->delegate = $delegate;
		$this->cache = $cache;
	}
	
	/** @inheritDoc */
	public function getViewtypes() {
		return $this->cache->get('viewtypes', function() {
			return $this->delegate->getViewtypes();
		});
	}
	
	/** @inheritDoc */
	public function forView(/*string*/ $view) {
		$viewPaths = $this->delegate->forView($view);
		
		return new GetterMap($this->getViewtypes(), function(Viewtype $viewtype) use ($viewPaths, $view) {
			return $this->cache->get("views/{$viewtype->getName()}/$view", function() use ($viewPaths, $viewtype) {
				return $viewPaths->get($viewtype);
			});
		});
	}
}
