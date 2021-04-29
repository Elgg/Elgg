<?php

namespace Elgg\WebServices\Di;

use Elgg\Collections\Collection;
use Elgg\Collections\CollectionItemInterface;
use Elgg\Traits\Di\ServiceFacade;
use Elgg\WebServices\ApiMethod;

/**
 * Collection of ApiMethods
 *
 * @since 4.0
 */
class ApiRegistrationCollection extends Collection {
	
	use ServiceFacade;
	
	/**
	 * Constructor
	 *
	 * @param CollectionItemInterface[] $items      Items
	 * @param string                    $item_class Member class
	 *                                              Restrict members of the collection to instances of this class
	 */
	public function __construct($items = [], $item_class = null) {
		parent::__construct($items, ApiMethod::class);
	}
	
	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'webservices.api_collection';
	}
}
