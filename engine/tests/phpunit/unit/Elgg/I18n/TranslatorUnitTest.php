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
		$this->translator = new Translator(_elgg_services()->config, _elgg_services()->locale);
		$this->translator->loadTranslations('en');

		$this->translator->addTranslation('en', [$this->key => 'Dummy']);
		$this->translator->addTranslation('es', [$this->key => 'Estúpido']);

		_elgg_services()->set('translator', $this->translator);
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
	}
	
	public function testFallsBackToSiteLanguage() {
		// set site language
		_elgg_services()->config->language = 'nl';
		
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy EN']);
		$this->translator->addTranslation('nl', ["{$this->key}a" => 'Dummy NL']);
		
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy NL', $this->translator->translate("{$this->key}a", [], 'es'));
	}
	
	public function testFallsBackToSiteLanguageLoggedIn() {
		// set site language
		_elgg_services()->config->language = 'nl';
		
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy EN']);
		$this->translator->addTranslation('nl', ["{$this->key}a" => 'Dummy NL']);
		
		$user = $this->createUser([
			'language' => 'de',
		]);
		
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertEquals('de', $this->translator->detectLanguage());
		
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy NL', $this->translator->translate("{$this->key}a"));
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
		_elgg_services()->reset('session_manager');
		$this->translator->setCurrentLanguage(null);

		$language = 'ab';

		$user = $this->createUser([
			'language' => $language,
		]);
		
		$this->assertEquals('en', $this->translator->getCurrentLanguage());

		_elgg_services()->session_manager->setLoggedInUser($user);

		$this->assertEquals($language, $this->translator->getCurrentLanguage());
	}

	public function testCanDetectCurrentLanguageFromConfig() {
		$this->translator->setCurrentLanguage(null);
		elgg_set_config('language', 'af');
		$this->assertEquals('af', $this->translator->getCurrentLanguage());
	}

	public function testCanOverrideExistingTranslation() {

		$this->assertEquals('Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'en'));
		$this->assertEquals('__elgg_php_unit:test_key2', $this->translator->translate('__elgg_php_unit:test_key2', [], 'en'));

		$this->assertEquals('Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'fr'));
		$this->assertEquals('__elgg_php_unit:test_key2', $this->translator->translate('__elgg_php_unit:test_key2', [], 'fr'));

		$this->translator->addTranslation('en', [
			'__elgg_php_unit:test_key' => 'Not So Dummy',
			'__elgg_php_unit:test_key2' => 'Still Dummy',
		]);

		$this->translator->addTranslation('fr', [
			'__elgg_php_unit:test_key' => 'Карамель',
			'__elgg_php_unit:test_key2' => 'Карамель в шоколаде',
		]);

		$this->assertEquals('Not So Dummy', $this->translator->translate('__elgg_php_unit:test_key', [], 'en'));
		$this->assertEquals('Still Dummy', $this->translator->translate('__elgg_php_unit:test_key2', [], 'en'));

		$this->assertEquals('Карамель', $this->translator->translate('__elgg_php_unit:test_key', [], 'fr'));
		$this->assertEquals('Карамель в шоколаде', $this->translator->translate('__elgg_php_unit:test_key2', [], 'fr'));

		$this->assertFalse($this->translator->addTranslation('en', []));
	}

	public function testCanLoadPluginTranslations() {

		$app = $this->createApplication();

		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);

		$plugin = \ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));

		$app->internal_services->config->boot_cache_ttl = 0;

		$plugin->activate();

		$this->assertEquals('Loaded', $app->internal_services->translator->translate('tests:languages:loaded'));
	}

	public function testCanLoadPluginTranslationsWithCacheDisabled() {

		$app = $this->createApplication([
			'system_cache_enabled' => false,
		]);

		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);

		$plugin = \ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));

		$app->internal_services->config->boot_cache_ttl = 0;

		$plugin->activate();

		$this->assertEquals('Loaded', $app->internal_services->translator->translate('tests:languages:loaded'));
	}
	
	public function testCanGetAllowedLanguages() {
		$app = $this->createApplication();
		$app->internal_services->config->language = 'fr';
		$app->internal_services->config->allowed_languages = 'nl';
		
		$allowed = $app->internal_services->translator->getAllowedLanguages();
		$this->assertTrue(in_array('en', $allowed));
		$this->assertTrue(in_array('nl', $allowed));
		$this->assertTrue(in_array('fr', $allowed));
		$this->assertCount(3, $allowed);
	}
	
	public function testCanNotTranslateUnallowedLanguages() {
		$app = $this->createApplication();
		$app->internal_services->config->language = 'fr';
		$app->internal_services->config->allowed_languages = 'nl';
		
		$app->internal_services->translator->addTranslation('en', ["{$this->key}a" => 'Dummy EN']);
		$app->internal_services->translator->addTranslation('nl', ["{$this->key}a" => 'Dummy NL']);
		$app->internal_services->translator->addTranslation('fr', ["{$this->key}a" => 'Dummy FR']);
		$app->internal_services->translator->addTranslation('de', ["{$this->key}a" => 'Dummy DE']); // not allowed
		
		$this->assertEquals('Dummy EN', $app->internal_services->translator->translate("{$this->key}a", [], 'en'));
		$this->assertEquals('Dummy NL', $app->internal_services->translator->translate("{$this->key}a", [], 'nl'));
		$this->assertEquals('Dummy FR', $app->internal_services->translator->translate("{$this->key}a", [], 'fr'));
		$this->assertEquals('Dummy FR', $app->internal_services->translator->translate("{$this->key}a", [], 'de'));
	}
	
	public function testTranslationArgumentIssues() {
		$this->translator->addTranslation('en', [
			'translation:arguments:test' => 'Hello %s, just testing %s',
		]);
		
		// suppressing vsprintf error, because that's what we're testing
		$this->assertEquals('translation:arguments:test', @$this->translator->translate('translation:arguments:test', ['Foo'], 'en'));
		
		$this->assertEquals('Hello Foo, just testing Bar', @$this->translator->translate('translation:arguments:test', ['Foo', 'Bar'], 'en'));
		
		$this->assertEquals('Hello Foo, just testing Bar', @$this->translator->translate('translation:arguments:test', ['Foo', 'Bar', 'Something'], 'en'));
	}
}
