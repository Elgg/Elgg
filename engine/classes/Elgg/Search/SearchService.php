<?php

namespace Elgg\Search;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use elgg_search instead.
 *
 * @access private
 * @since 3.0
 */
class SearchService {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Config             $config Config
	 * @param \Elgg\PluginHooksService $hooks  Hook registration service
	 * @param \Elgg\Logger             $logger Logger
	 */
	public function __construct(\Elgg\Config $config, \Elgg\PluginHooksService $hooks, \Elgg\Logger $logger) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->logger = $logger;
	}

	/**
	 * Processes a search request
	 *
	 * @param array $params Search parameters
	 *
	 * @return array with search results
	 */
	public function search(array $params = []) {
		if (elgg_extract('query', $params) === null) {
			return [
				'entities' => [],
				'count' => 0,
			];
		}
		
		// setting some default params to exist to prevent undefined index notices for common params in search hooks
		$default_params = [
			'sort' => '',
			'order' => '',
			'order_by' => null,
			'limit' => (int) get_input('limit', $this->config->get('default_limit')),
			'offset' => (int) get_input('offset', 0),
		];
		
		$params = array_merge($default_params, $params);
				
		// allow the search params to be adjusted
		$params = $this->hooks->trigger('params', 'search', $params, $params);
		
		$search_type = elgg_extract('search_type', $params);
		
		if ($search_type === 'entities') {
			$type = elgg_extract('type', $params, 'all');
			$subtype = elgg_extract('subtype', $params);
				
			
			if (!empty($subtype) && is_string($subtype)) {
				if ($this->hooks->hasHandler('search', "$type:$subtype")) {
					$type .= ":$subtype";
				}
			}
		} else {
			$type = $search_type;
		}
		
		/*
		 * Perform actual search
		 * This needs to return an array in the following format
		 *
		 * [
		 *     'entities' => \ElggEntity[],
		 *     'count' => int
		 * ]
		 */
		$result = $this->hooks->trigger('search', $type, $params);
		
		if (isset($result['entities'])) {
			foreach ($result['entities'] as $entity) {
				// add the volatile data for why these entities have been returned.
				// hook does not need to return anything as the entities as memory objects
				elgg_trigger_plugin_hook('format', 'search', $params, $entity);
			}
		}
		
		$result = $this->hooks->trigger('prepare', 'search', $params, $result);
		
		return $result;
	}
}
