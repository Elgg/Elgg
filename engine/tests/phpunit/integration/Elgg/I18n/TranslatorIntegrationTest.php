<?php

namespace Elgg\I18n;

use Elgg\IntegrationTestCase;

class TranslatorIntegrationTest extends IntegrationTestCase {

	/**
	 * @var Translator
	 */
	protected $translator;
	
	public function up() {
		$this->translator = elgg()->translator;
	}
	
	public function testTranslationsGetSavedInCache() {
		$cache = _elgg_services()->systemCache;
		$cache->delete('en.lang');
		
		$this->assertEmpty($cache->load('en.lang'));
		
		$this->translator->loadTranslations('en');
		
		$this->assertNotEmpty($cache->load('en.lang'));
	}
}
