<?php

namespace Elgg\Mocks\I18n;

use Elgg\I18n\Translator as ElggTranslator;
use Elgg\Includer;

class Translator extends ElggTranslator {

	/**
	 * Load cached or include a language file by its path
	 *
	 * During unittests there is a performance gain using system cache
	 *
	 * @param string $path Path to file
	 * @return bool
	 * @internal
	 */
	protected function includeLanguageFile($path): bool {
		$cache_key = 'lang/' . sha1($path);
		$result = _elgg_services()->systemCache->load($cache_key);
		if (!isset($result)) {
			$result = includer::includeFile($path);
			_elgg_services()->systemCache->save($cache_key, $result);
		}
		
		if (is_array($result)) {
			$this->addTranslation(basename($path, '.php'), $result);
			return true;
		}

		return false;
	}
}
