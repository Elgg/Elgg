ElggLanguagesTest = TestCase("ElggLanguagesTest");

ElggLanguagesTest.prototype.setUp = function() {
	this.ajax = $.ajax;
	
	//Immediately execute some dummy "returned" javascript instead of sending
	//an actual ajax request
	$.ajax = function(settings) {
		var lang = settings.data.js.split('/')[1];
		elgg.config.translations[lang] = {'language':lang};
	};
};

ElggLanguagesTest.prototype.tearDown = function() {
	$.ajax = this.ajax;
	
	//clear translations
	elgg.config.translations['en'] = undefined;
	elgg.config.translations['aa'] = undefined;
};

ElggLanguagesTest.prototype.testLoadTranslations = function() {
	assertUndefined(elgg.config.translations['en']);
	assertUndefined(elgg.config.translations['aa']);
	
	elgg.reload_all_translations();
	elgg.reload_all_translations('aa');
	
	assertNotUndefined(elgg.config.translations['en']['language']);
	assertNotUndefined(elgg.config.translations['aa']['language']);
};

ElggLanguagesTest.prototype.testElggEchoTranslates = function() {
	elgg.reload_all_translations('en');
	elgg.reload_all_translations('aa');
	
	assertEquals('en', elgg.echo('language'));
	assertEquals('aa', elgg.echo('language', 'aa'));
};

ElggLanguagesTest.prototype.testElggEchoFallsBackToDefaultLanguage = function() {
	elgg.reload_all_translations('en');
	assertEquals('en', elgg.echo('language', 'aa'));
};

