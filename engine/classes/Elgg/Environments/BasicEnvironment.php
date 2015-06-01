<?php
namespace Elgg\Environments;

use Elgg\Services\Config;
use Elgg\Services\Environment;

/**
 * Typical environment
 */
class BasicEnvironment implements Environment {

	const DEFAULT_NAME = 'default';
	const DEFAULT_IS_PROD = true;
	const CONFIG_KEY_FACTORY = 'elgg_env';

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $is_prod;

	/**
	 * Constructor
	 *
	 * @param string $name    Name of this instance
	 * @param bool   $is_prod Is this a production site?
	 */
	public function __construct($name, $is_prod) {
		$this->name = (string)$name;
		$this->is_prod = (bool)$is_prod;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isProd() {
		return $this->is_prod;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Build an environment from the application config
	 *
	 * @param Config $config The Config service
	 * @return BasicEnvironment
	 * @throws \ConfigurationException
	 */
	public static function factory(Config $config) {
		$factory = $config->getVolatile(self::CONFIG_KEY_FACTORY);
		if (!$factory) {
			// try after making sure settings are loaded
			$config->loadSettingsFile();
			$factory = $config->getVolatile(self::CONFIG_KEY_FACTORY);
		}
		if (!$factory) {
			$factory = [];
		}
		if (is_array($factory)) {
			$factory = array_merge([
				'name' => self::DEFAULT_NAME,
				'is_prod' => self::DEFAULT_IS_PROD,
			], $factory);
			return new self($factory['name'], $factory['is_prod']);
		}
		if ($factory instanceof \Closure) {
			$env = $factory();
		}
		if (empty($env) || !($env instanceof Environment)) {
			throw new \ConfigurationException('$CONFIG->' . self::CONFIG_KEY_FACTORY . ' must be an array or a Closure'
				. ' that returns an implementation of Elgg\Services\Environment');
		}
		return $env;
	}
}
