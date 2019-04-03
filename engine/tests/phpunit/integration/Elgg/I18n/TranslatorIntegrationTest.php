<?php

namespace Elgg\I18n;

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group Translator
 */
class TranslatorIntegrationTest extends IntegrationTestCase {

	/**
	 * @var Translator
	 */
	protected $translator;
	
	public function up() {
		$this->translator = elgg()->translator;
	}

	public function down() {

	}

	public function testTranslationsGetSavedInCache() {
		$cache = elgg_get_system_cache();
		$cache->delete('en.lang');
		
		$this->assertEmpty($cache->load('en.lang'));
		
		$this->translator->loadTranslations('en');
		
		$this->assertNotEmpty($cache->load('en.lang'));
	}
}
