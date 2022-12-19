<?php
namespace Elgg\Assets;

use CssCrush\Process;

/**
 * Css Crush Processor
 *
 * @internal
 */
class CssCrushProcess extends Process {
	
	protected $iniOriginal = [];
	
	/**
	 * {@inheritDoc}
	 *
	 * Added check for already sufficient ini value
	 */
	public function preCompile() {
		if (ini_get('pcre.backtrack_limit') < 1000000) {
			$this->iniOriginal['pcre.backtrack_limit'] = ini_get('pcre.backtrack_limit');
			ini_set('pcre.backtrack_limit', 1000000);
		}
		
		if (ini_get('pcre.jit')) {
			$this->iniOriginal['pcre.jit'] = ini_get('pcre.jit');
			ini_set('pcre.jit', 0);
		}
		
		$current_limit = elgg_get_ini_setting_in_bytes('memory_limit');
		if (($current_limit > 0) && ($current_limit < (128 * 1024 * 1024))) {
			$this->iniOriginal['memory_limit'] = ini_get('memory_limit');
			ini_set('memory_limit', '128M');
		}
		
		$this->filterPlugins();
		$this->filterAliases();
		
		$this->functions->setPattern(true);
		
		$this->stat['compile_start_time'] = microtime(true);
	}
}
