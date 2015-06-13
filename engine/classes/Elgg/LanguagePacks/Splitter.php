<?php
namespace Elgg\LanguagePacks;

class Splitter {

	private $translations;
	private $config;

	public function __construct(array $translations, array $packs_config) {
		$this->translations = $translations;
		$this->config = $packs_config;
	}

	public function getPack($pack = '') {
		$translations = $this->translations;
		if (empty($this->config[$pack])) {
			throw new \InvalidArgumentException("Pack '$pack' is not configured");
		}

		$out = [];
		foreach ($this->config[$pack]['keys'] as $key) {
			if (isset($translations[$key])) {
				$out[$key] = $translations[$key];
				unset($translations[$key]);
			}
		}
		foreach ($this->config[$pack]['patterns'] as $pattern) {
			$regex = "/{$pattern[0]}/{$pattern[1]}";
			foreach ($translations as $key => $msg) {
				if (preg_match($regex, $key)) {
					$out[$key] = $msg;
					unset($translations[$key]);
				}
			}
		}
		return $out;
	}
}
