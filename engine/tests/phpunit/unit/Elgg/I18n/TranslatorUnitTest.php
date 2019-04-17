<?php

namespace Elgg\I18n;

use Psr\Log\LogLevel;

/**
 * @group Translator
 * @group UnitTests
 */
class TranslatorUnitTest extends \Elgg\UnitTestCase {

	public $key = '__elgg_php_unit:test_key';

	/**
	 * @var Translator
	 */
	public $translator;

	public function up() {
		$config = elgg()->config;
		$localeService = elgg()->locale;
		
		$this->translator = new Translator($config, $localeService);
		$this->translator->loadTranslations('en');

		$this->translator->addTranslation('en', [$this->key => 'Dummy']);
		$this->translator->addTranslation('es', [$this->key => 'Estúpido']);

		_elgg_services()->setValue('translator', $this->translator);
	}

	public function down() {

	}

	public function testSetLanguageFromGetParameter() {
		_elgg_services()->request->setParam('hl', 'es');

		$this->assertEquals('es', $this->translator->detectLanguage());

		_elgg_services()->request->setParam('hl', null);
	}

	public function testCheckLanguageKeyExists() {
		$this->assertTrue($this->translator->languageKeyExists($this->key));

		_elgg_services()->logger->disable();
		$this->assertFalse($this->translator->languageKeyExists('__elgg_php_unit:test_key:missing'));
		_elgg_services()->logger->enable();
	}

	public function testCanTranslate() {
		$this->assertEquals('Dummy', $this->translator->translate($this->key));
		$this->assertEquals('Estúpido', $this->translator->translate($this->key, [], 'es'));
	}

	public function testUsesSprintfArguments() {
		$this->translator->addTranslation('en', [$this->key => 'Dummy %s']);
		$this->assertEquals('Dummy %s', $this->translator->translate($this->key));
		$this->assertEquals('Dummy 1', $this->translator->translate($this->key, [1]));

		$this->translator->addTranslation('en', [$this->key => 'Dummy %2$s %1$s']);
		$this->assertEquals('Dummy 2 1', $this->translator->translate($this->key, [1, 2]));
	}

	public function testFallsBackToEnglish() {
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy A']);
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy A', $this->translator->translate("{$this->key}a", [], 'es'));
		_elgg_services()->logger->enable();
	}
	
	public function testFallsBackToSiteLanguage() {
		// set site language
		_elgg_services()->config->language = 'nl';
		
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy EN']);
		$this->translator->addTranslation('nl', ["{$this->key}a" => 'Dummy NL']);
		
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy NL', $this->translator->translate("{$this->key}a", [], 'es'));
		_elgg_services()->logger->enable();
	}
	
	public function testFallsBackToSiteLanguageLoggedIn() {
		// set site language
		_elgg_services()->config->language = 'nl';
		
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy EN']);
		$this->translator->addTranslation('nl', ["{$this->key}a" => 'Dummy NL']);
		
		$user = $this->createUser([
			'language' => 'de',
		]);
		
		_elgg_services()->session->setLoggedInUser($user);
		
		$this->assertEquals('de', $this->translator->detectLanguage());
		
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy NL', $this->translator->translate("{$this->key}a"));
		_elgg_services()->logger->enable();
		
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testIssuesNoticeOnMissingKey() {
		// key is missing from all checked translations
		$logger = _elgg_services()->logger;
		
		$logger->disable();

		$this->assertEquals("{$this->key}b", $this->translator->translate("{$this->key}b"));
		
		$logged = $logger->enable();
		
		// expecting translation about missing key
		$this->assertEquals(1, count($logged));

		$message = "Missing English translation for \"{$this->key}b\" language key";
		$this->assertEquals($message, $logged[0]['message']);
		$this->assertEquals(LogLevel::NOTICE, $logged[0]['level']);

		// has fallback key
		$this->translator->addTranslation('en', ["{$this->key}b" => 'Dummy']);

		$logger->disable();
		$this->assertEquals('Dummy', $this->translator->translate("{$this->key}b", [], 'es'));
		$logged = $logger->enable();

		$this->assertEquals([
			[
				'message' => "Missing es translation for \"{$this->key}b\" language key",
				'level' => LogLevel::INFO,
			]
		], $logged);
	}

	public function testDoesNotProcessArgsOnKey() {
		_elgg_services()->logger->disable();
		$this->assertEquals('nonexistent:%s', $this->translator->translate('nonexistent:%s', [1]));
		_elgg_services()->logger->enable();
	}

	public function testCanSetCurrentLanguage() {
		$this->translator->setCurrentLanguage(null);
		$this->assertEquals('en', $this->translator->getCurrentLanguage());

		$this->translator->setCurrentLanguage('lang');
		$this->assertEquals('lang', $this->translator->getCurrentLanguage());
	}

	public function testCanDetectCurrentLanguageFromInput() {
		$this->translator->setCurrentLanguage(null);
		_elgg_services()->request->setParam('hl', 'aa');
		$this->assertEquals('aa', $this->translator->getCurrentLanguage());
	}

	public function testCanDetectCurrentLanguageFromUserSettings() {

		$this->translator->setCurrentLanguage(null);

		$language = 'ab';

		$ia = elgg_set_ignore_access(true);
		$user = $this->createUser([], [
			'language' => $language,
		]);

		elgg_set_ignore_access($ia);

		$this->assertEquals('en', $this->translator->getCurrentLanguage());

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals($language, $this->translator->getCurrentLanguage());

		_elgg_services()->session->removeLoggedInUser();

		$user->delete();
	}

	public function testCanDetectCurrentLanguageFromConfig() {
		$this->translator->setCurrentLanguage(null);
		elgg_set_config('language', 'af');
		$this->assertEquals('af', $this->translator->getCurrentLanguage());
	}

	public function testCanOverrideExistingTranslation() {

		$this->assertEquals('Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'en'));
		$this->assertEquals('__elgg_php_unit:test_key2', $this->translator->translate('__elgg_php_unit:test_key2', [], 'en'));

		$this->assertEquals('Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'new_lang_code'));
		$this->assertEquals('__elgg_php_unit:test_key2', $this->translator->translate('__elgg_php_unit:test_key2', [], 'new_lang_code'));

		$this->translator->addTranslation('en', [
			'__elgg_php_unit:test_key' => 'Not So Dummy',
			'__elgg_php_unit:test_key2' => 'Still Dummy',
		]);

		$this->translator->addTranslation('new_lang_code', [
			'__elgg_php_unit:test_key' => 'Карамель',
			'__elgg_php_unit:test_key2' => 'Карамель в шоколаде',
		]);

		$this->assertEquals('Not So Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'en'));
		$this->assertEquals('Still Dummy', $this->translator->translate('__elgg_php_unit:test_key2', [], 'en'));

		$this->assertEquals('Карамель', $this->translator->translate('__elgg_php_unit:test_key', [], 'new_lang_code'));
		$this->assertEquals('Карамель в шоколаде', $this->translator->translate('__elgg_php_unit:test_key2', [], 'new_lang_code'));

		$this->assertFalse($this->translator->addTranslation('en', []));
	}

	public function testCanLoadPluginTranslations() {

		$app = $this->createApplication();

		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);

		$plugin = \ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));

		$app->_services->config->boot_cache_ttl = 0;
		$app->_services->plugins->addTestingPlugin($plugin);

		$plugin->activate();

		$this->assertEquals('Loaded', $app->_services->translator->translate('tests:languages:loaded'));
	}

	public function testCanLoadPluginTranslationsWithCacheDisabled() {

		$app = $this->createApplication([
			'system_cache_enabled' => false,
		]);

		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);

		$plugin = \ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));

		$app->_services->config->boot_cache_ttl = 0;
		$app->_services->plugins->addTestingPlugin($plugin);

		$plugin->activate();

		$this->assertEquals('Loaded', $app->_services->translator->translate('tests:languages:loaded'));
	}
}
