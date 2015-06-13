<?php
namespace Elgg\CkeditorPlugin;

use Elgg\LanguagePacks\Config;

class Hooks {

	/**
	 * Alter language packs
	 *
	 * @param string $hook   "config"
	 * @param string $type   "elgg/echo:packs"
	 * @param Config $packs  Language packs config
	 * @param null   $params Hook params
	 *
	 * @return mixed
	 */
	public static function alterLanguagePacks($hook, $type, Config $packs, $params) {
		$packs->addKeys([
			'ckeditor:visual',
			'ckeditor:html',
		]);
	}
}
