define(function(require) {

	var elgg = require('elgg');
	var echo = require('elgg/echo');

	var translations = {
		'en': {
			'river:owner': 'Activity of %s',
			'welcome:user': 'Welcome %s'
		},
		'es': {
			'river:owner': 'Actividad de %s',
			'welcome:user': 'Bienvenido %s'
		}
	};

	var original_echo = elgg.echo;

	function mock_echo(key, argv, language) {
		if (elgg.isString(argv)) {
			language = argv;
			argv = [];
		}

		var dlang = 'en',
			map;

		language = language || dlang;
		argv = argv || [];

		map = translations[language] || translations[dlang];
		if (map && map[key]) {
			return vsprintf(map[key], argv);
		}

		return key;
	}

	describe("elgg/echo", function() {

		beforeEach(function () {
			elgg.echo = mock_echo;
		});

		afterEach(function () {
			elgg.echo = original_echo;
		});

		it("has a working mock in this test", function() {
			expect(elgg.echo('river:owner')).toBe('Activity of undefined');
			expect(elgg.echo('river:owner', 'es')).toBe('Actividad de undefined');
			expect(elgg.echo('river:owner', ['Bob'])).toBe('Activity of Bob');
			expect(elgg.echo('welcome:user', ['Bob'], 'es')).toBe('Bienvenido Bob');
		});

		it("calls all callbacks async", function() {
			var check;
			echo(['river:owner'], function (activity) {
				check = 1;
			});
			echo(function (echo) {
				check = 1;
			});

			expect(check).toBe(undefined);
		});

		it("can accept explicit array of keys", function(done) {
			echo(['river:owner', 'welcome:user'], function (activity, welcome) {
				expect(activity(['Bob'])).toBe('Activity of Bob');
				expect(welcome(['Bob'], 'es')).toBe('Bienvenido Bob');
				done();
			});
		});

		it("translators use 'undefined' for missing args", function(done) {
			echo(['river:owner'], function (activity) {
				expect(activity()).toBe('Activity of undefined');
				done();
			});
		});

		it("translators interpret string args as language", function(done) {
			var count = 0;

			echo(['river:owner'], function (activity) {
				expect(activity('es')).toBe('Actividad de undefined');
				count++;
				(count > 1) && done();
			});

			echo(function (echo) {
				expect(echo('river:owner', 'es')).toBe('Actividad de undefined');
				count++;
				(count > 1) && done();
			});
		});

		it("can sniff keys in callback", function(done) {
			echo(function (echo) {
				expect(echo('river:owner', ['Bob'])).toBe('Activity of Bob');
				expect(echo('welcome:user', ['Bob'], 'es')).toBe('Bienvenido Bob');
				done();
			});
		});

		it("cannot sniff string expressions", function() {
			echo(function (echo) {
				expect(function () {
					echo('river' + 'owner');
				}).toThrow();
			});
		});
	});
});
