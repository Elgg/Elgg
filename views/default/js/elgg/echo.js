/**
 * Based on RequireJS 2.1.10 (MIT) Copyright (c) 2010-2014, The Dojo Foundation All Rights Reserved.
 */
define(function (require) {
	// TODO replace these dependencies https://github.com/Elgg/Elgg/issues/8345
	var elgg = require('elgg');
	var $ = require('jquery');

	var commentRegExp = /(\/\*([\s\S]*?)\*\/|([^:]|^)\/\/(.*)$)/mg,
		cjsEchoRegExp = /[^.]\s*echo\s*\(\s*["']([^'"\s]+)["']\s*[\),]/g,
		hasOwn = {}.hasOwnProperty;

	function explicit_keys(keys, callback) {
		var args = $.map(keys, function (key, idx) {
			return function (argv, language) {
				return elgg.echo(key, argv, language);
			};
		});

		callback.apply(null, args);
	}

	function sniff_keys(callback) {
		var keys = {};
		callback
			.toString()
			.replace(commentRegExp, '')
			.replace(cjsEchoRegExp, function (match, key) {
				keys[key] = true;
			});

		callback(function (key, argv, language) {
			if (!hasOwn.call(keys, key)) {
				throw new Error("The key '" + key + "' was not loaded. You must use simple string literals"
					+ " to declare your keys.");
			}

			return elgg.echo(key, argv, language);
		});
	}

	function async(keys, callback) {
		// don't allow devs to expect sync behavior
		if (typeof keys === 'function') {
			setTimeout(function () {
				sniff_keys(keys);
			}, 0);

		} else if ($.isArray(keys)) {
			setTimeout(function () {
				explicit_keys(keys, callback);
			}, 0);

		} else {
			// though bad args will throw sync
			throw new Error("First argument must be an array of keys or a function");
		}
	}

	return async;
});
