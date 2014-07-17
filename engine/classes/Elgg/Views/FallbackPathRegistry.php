<?php

namespace Elgg\Views;

use Elgg\Filesystem\Directory;
use Elgg\Structs\ArrayCollection;
use Elgg\Structs\Collection;
use Elgg\Structs\GetterMap;

/**
 * Takes a Collection<PathRegistry> and tries all items to find
 * a valid path for the specified view.
 * 
 * This class makes it possible for multiple plugins to register views.
 * 
 * @since 2.0.0
 * @access private
 */
class FallbackPathRegistry implements PathRegistry {
	/** @var Collection<PathRegistry> */
	private $registries;
	
	/** @var ViewtypeRegistry */
	private $viewtypes;
	
	/**
	 * Constructor
	 * 
	 * @param Collection<PathRegistry> $registries The list of registries in fallback order.
	 * @param ViewtypeRegistry         $viewtypes  The available system viewtypes.
	 */
	public function __construct(Collection/*<PathRegistry>*/ $registries, ViewtypeRegistry $viewtypes) {
		$this->registries = $registries;
		$this->viewtypes = $viewtypes;
	}
	
	/** @inheritDoc */
	public function getViewtypes() {
		$viewtypesLists = $this->registries->map(function(PathRegistry $viewFiles) {
			return $viewFiles->getViewtypes();
		});
		
		// TODO(ewinslow): Implement flatten and/or flatMap
		$allViewtypes = [];
		foreach ($viewtypesLists as $viewtypeList) {
			foreach ($viewtypeList as $viewtype) {
				$allViewtypes[] = $viewtype;
			}
		}
		
		return (new ArrayCollection($allViewtypes))->unique();
	}
	
	/** @inheritDoc */
	public function forView(/*string*/ $view) {
		return new GetterMap($this->getViewtypes(), function(Viewtype $viewtype) use ($view) {
			foreach ($this->registries as $pathRegistry) {
				$file = $pathRegistry->forView($view)->get($viewtype);
				if ($file) {
					return $file;
				}
			}
			
			return null;
		});
	}
	
	/**
	 * Simpler construction for clients by encapsulating the `new ArrayCollection()` call.
	 * 
	 * @param PathRegistry[]   $registries List of PathRegistry instances to fall back to.
	 * @param ViewtypeRegistry $viewtypes  The available viewtypes...
	 * 
	 * @return self
	 */
	public static function fromArray(array $registries = [], ViewtypeRegistry $viewtypes) {
		return new self(new ArrayCollection($registries), $viewtypes);
	}
}