<?php
namespace Elgg\LanguagePacks;

use Elgg\PluginHooksService;

/**
 * @access private
 * @since 2.0.0
 */
class Service {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	public function __construct(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Build the definition of the elgg/echo/config module
	 *
	 * @return array
	 */
	public function buildConfig() {
		$packs = $this->hooks->trigger('config', 'elgg/echo:packs', null, $this->getDefaultConfig());
		if (!$packs instanceof Config) {
			throw new \RuntimeException('The [config, elgg/echo:packs] hook must return a ' . Config::class);
		}

		return $packs->getConfig();
	}

	/**
	 * @return Config
	 */
	private function getDefaultConfig() {
		$packs = new Config();
		$packs->addKeys([
			'js:lightbox:current',
			'previous',
			'next',
			'close',
			'hide',
			'error',
			'error:default',
			'ajax:error',
			'confirm',
			'question:areyousure',
			'access:comments:change',
			'deleteconfirm',
			'reportedcontent:refresh',
		]);

		return $packs;
	}
}
