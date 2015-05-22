<?php
namespace Elgg\LanguagePacks;

/**
 * Language pack configurator
 *
 * @since 2.0.0
 */
class Config {

	const DEFAULT_PACK = 'default';

	private $config = [];

	/**
	 * Is the given name valid?
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isValidName($name) {
		return (bool)preg_match('~^[a-zA-Z]+$~', $name);
	}

	/**
	 * Add keys to a pack
	 *
	 * @param string[] $keys Language keys to include in the pack
	 * @param string   $pack Pack name
	 *
	 * @return self
	 */
	public function addKeys(array $keys, $pack = self::DEFAULT_PACK) {
		if (!is_string($pack)) {
			throw new \InvalidArgumentException('Pack must be a string');
		}
		if (!self::isValidName($pack)) {
			throw new \InvalidArgumentException('Pack names may only contain [a-zA-Z].');
		}
		foreach ($keys as $key) {
			$this->config[$pack]['keys'][(string)$key] = true;
		}

		return $this;
	}

	/**
	 * Add RegExp pattern to a pack
	 *
	 * @param string $pattern        Language key pattern to include in the pack (first arg of RegExp)
	 * @param bool   $case_sensitive Should the match be case-sensitive?
	 * @param string $pack           Pack name
	 *
	 * @return self
	 */
	public function addPattern($pattern, $case_sensitive = true, $pack = self::DEFAULT_PACK) {
		if (!is_string($pack) || !is_string($pattern)) {
			throw new \InvalidArgumentException('Pack and pattern must be strings');
		}
		if (!self::isValidName($pack)) {
			throw new \InvalidArgumentException('Pack names may only contain [a-zA-Z].');
		}
		$this->config[$pack]['patterns'][] = [$pattern, $case_sensitive ? 'i' : ''];

		return $this;
	}

	/**
	 * Get elgg/echo/config module definition
	 *
	 * @return array
	 */
	public function getConfig() {
		$packs = [];
		foreach ($this->config as $pack => $definition) {
			$definition = array_merge([
				'keys' => [],
				'patterns' => [],
			], $definition);

			$packs[$pack]['keys'] = array_keys($definition['keys']);
			$packs[$pack]['patterns'] = $definition['patterns'];
		}
		return $packs;
	}
}
