<?php

namespace Elgg\Di;

use DI\Definition\SelfResolvingDefinition;
use Psr\Container\ContainerInterface;
use DI\Definition\CacheableDefinition;
use DI\Scope;

/**
 * Cacheable PHP-DI definition that pulls from Elgg's DIC.
 *
 * We could just use Closures that call _elgg_services(), but with current PHPDI, they
 * can't be serialized for the cache. Even when PHPDI uses BetterReflection, unserializing
 * cached Closures may not be any faster than this.
 */
class PhpDiResolver implements SelfResolvingDefinition, CacheableDefinition {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $elgg_dic_key;

	/**
	 * Constructor
	 *
	 * @param string $name         Entry name. E.g. "Elgg\Menu\Service"
	 * @param string $elgg_dic_key Key on Elgg's DIC. E.g. "menus"
	 */
	public function __construct($name, $elgg_dic_key) {
		$this->name = $name;
		$this->elgg_dic_key = $elgg_dic_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getScope() {
		return Scope::SINGLETON;
	}


	/**
	 * {@inheritdoc}
	 */
	public function resolve(ContainerInterface $container) {
		return _elgg_services()->{$this->elgg_dic_key};
	}

	/**
	 * {@inheritdoc}
	 */
	public function isResolvable(ContainerInterface $container) {
		return true;
	}
}
