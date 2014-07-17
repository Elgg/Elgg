<?php

namespace Elgg\Views;

use Elgg\Structs\GetterMap;

/**
 * A PathRegistry decorator that will use a viewtype's fallback to try to find a
 * valid path for the specified view.
 * 
 * E.g. if the "mobile" viewtype falls back to "default", then if there is no
 * path found in the "mobile" viewtype for the given view, this class will check
 * for a valid path in the "default" viewtype.
 * 
 * @since 2.0.0
 * @access private
 */
class ViewtypeFallbackPathRegistry implements PathRegistry {
	/** @var PathRegistry */
	private $delegate;
	
	/**
	 * Constructor
	 * 
	 * @param PathRegistry $delegate Implementation to defer to when necessary
	 */
	public function __construct(PathRegistry $delegate) {
		$this->delegate = $delegate;
	}
	
	/** @inheritDoc */
	public function getViewtypes() {
		return $this->delegate->getViewtypes();
	}
	
	/** @inheritDoc */
	public function forView(/*string*/ $view) {
		$viewPaths = $this->delegate->forView($view);
		
		return new GetterMap($this->getViewtypes(), function(Viewtype $viewtype) use ($viewPaths) {
			while ($viewtype) {
				$file = $viewPaths->get($viewtype);
				if ($file) {
					return $file;
				}
				
				$viewtype = $viewtype->getFallback();
			}
			
			return null;
		});
	}
}
