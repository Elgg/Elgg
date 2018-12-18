define(function(require) {
	
	var elgg = require('elgg');
	var $ = require('jquery');
	require('jquery-mockjax');
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax();

	$.mockjaxSettings.responseTime = 10;

	describe("elgg/ajax", function() {
		var captured_hook,
			root = elgg.get_site_url();

		beforeEach(function() {
			captured_hook = null;

			$.mockjaxSettings.logging = false;
			$.mockjax.clear();

			elgg.config.hooks = {};

			// note, "all" type > always higher priority than specific types
			elgg.register_hook_handler(Ajax.REQUEST_DATA_HOOK, 'all', function (h, t, p, v) {
				captured_hook = {
					t: t,
					p: p,
					v: v
				};
			});
		});

		it("passes unwrapped value to both success and deferred", function(done) {
			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				responseText: {
					value: 1
				}
			});

			var def1 = $.Deferred(),
				def2 = $.Deferred();

			ajax.path('foo', {
				success: function (val) {
					expect(val).toBe(1);
					def1.resolve();
				}
			}).done(function (val) {
				expect(val).toBe(1);
				def2.resolve();
			});

			$.when(def1, def2).then(done);
		});

		it("allows filtering response wrapper by hook, called only once", function(done) {
			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				responseText: {
					value: 1,
					foo: 2
				}
			});

			var hook_calls = 0;

			elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'path:foo', function (h, t, p, v) {
				hook_calls++;
				expect(v).toEqual({
					value: 1,
					foo: 2,
					status: 0
				});
				expect(p.options.url).toBe('foo');
				v.value = 3;
				return v;
			});

			var def1 = $.Deferred(),
				def2 = $.Deferred();

			ajax.path('foo', {
				success: function (val) {
					expect(val).toBe(3);
					def1.resolve();
				}
			}).done(function (val) {
				expect(val).toBe(3);
				def2.resolve();
			});

			$.when(def1, def2).then(function () {
				expect(hook_calls).toBe(1);
				done();
			});
		});

		$.each(['path', 'action', 'form', 'view'], function (i, method) {
			it("method " + method + "() sends special header", function() {
				ajax[method]('foo');
				expect(ajax._ajax_options.headers).toEqual({ 'X-Elgg-Ajax-API' : '2' });
			});

			it("method " + method + "() uses dataType json", function() {
				ajax[method]('foo');
				expect(ajax._ajax_options.dataType).toEqual('json');
			});
		});

		it("action() defaults to POST", function() {
			ajax.action('foo');
			expect(ajax._ajax_options.method).toEqual('POST');
		});

		it("path(): non-empty object data changes default to POST", function() {
			ajax.path('foo', {
				data: {bar: 'bar'}
			});
			expect(ajax._ajax_options.method).toEqual('POST');
		});

		it("path(): non-empty string data changes default to POST", function() {
			ajax.path('foo', {
				data: '?bar=bar'
			});
			expect(ajax._ajax_options.method).toEqual('POST');
		});

		$.each(['form', 'view'], function (i, method) {
			it(method + "(): non-empty object data left as GET", function() {
				ajax[method]('foo', {
					data: {bar: 'bar'}
				});
				expect(ajax._ajax_options.method).toEqual('GET');
			});
		});

		$.each(['path', 'form', 'view'], function (i, method) {

			it(method + "() defaults to GET", function() {
				ajax[method]('foo');
				expect(ajax._ajax_options.method).toEqual('GET');
			});

			it(method + "(): empty string data leaves default as GET", function() {
				ajax[method]('foo', {
					data: ''
				});
				expect(ajax._ajax_options.method).toEqual('GET');
			});

			it(method + "(): empty object data leaves default as GET", function() {
				ajax[method]('foo', {
					data: {}
				});
				expect(ajax._ajax_options.method).toEqual('GET');
			});
		});

		it("allows altering value via hook", function(done) {
			elgg.register_hook_handler(Ajax.REQUEST_DATA_HOOK, 'path:foo/bar', function (h, t, p, v) {
				v.arg3 = 3;
				return v;
			}, 900);

			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo/bar/?arg1=1"),
				responseText: {
					value: 1,
					foo: 2
				}
			});

			ajax.path('/foo/bar/?arg1=1#target', {
				data: {arg2: 2}
			}).done(function () {
				expect(captured_hook.v).toEqual({arg2: 2, arg3: 3});
				expect(captured_hook.p.options.data).toEqual({arg2: 2, arg3: 3});
				done();
			});

			expect(ajax._ajax_options.data).toEqual({
				arg2: 2,
				arg3: 3
			});
		});

		it("normalizes argument paths/URLs", function() {
			ajax.path('/foo/bar/?arg1=1#target');
			expect(ajax._fetch_args.hook_type).toEqual('path:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'foo/bar/?arg1=1');

			ajax.path(root + 'foo/bar/?arg1=1#target');
			expect(ajax._fetch_args.hook_type).toEqual('path:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'foo/bar/?arg1=1');

			ajax.action('/foo/bar/?arg1=1#target');
			expect(ajax._fetch_args.hook_type).toEqual('action:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'action/foo/bar/?arg1=1');

			ajax.action(root + 'action/foo/bar/?arg1=1#target');
			expect(ajax._fetch_args.hook_type).toEqual('action:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'action/foo/bar/?arg1=1');

			ajax.view('foo/bar?arg1=1');
			expect(ajax._fetch_args.hook_type).toEqual('view:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'ajax/view/foo/bar?arg1=1');

			ajax.form('/foo/bar/?arg1=1#target');
			expect(ajax._fetch_args.hook_type).toEqual('form:foo/bar');
			expect(ajax._fetch_args.options.url).toEqual(root + 'ajax/form/foo/bar/?arg1=1');
		});

		it("refuses to accept external URLs", function() {
			expect(function () {
				ajax.action('http://other.com/action/foo');
			}).toThrowError();

			expect(function () {
				ajax.path('http://other.com/foo');
			}).toThrowError();
		});

		it("form() and view() refuse to accept any URL", function() {
			expect(function () {
				ajax.view(root + 'ajax/view/foo');
			}).toThrowError();

			expect(function () {
				ajax.form(root + 'action/foo');
			}).toThrowError();
		});
		
		it("adds CSRF tokens to action data", function() {
			var ts = elgg.security.token.__elgg_ts;

			ajax.action('foo');
			expect(ajax._ajax_options.data.__elgg_ts).toBe(ts);

			ajax.action('foo', {
				data: "?arg1=1"
			});
			expect(ajax._ajax_options.data).toContain('__elgg_ts=' + ts);
		});

		it("does not add tokens if already in action URL", function() {
			var ts = elgg.security.token.__elgg_ts;

			var url = elgg.security.addToken(root + 'action/foo');

			ajax.action(url);
			expect(ajax._ajax_options.data.__elgg_ts).toBe(undefined);
		});

		it("path() accepts empty argument for fetching home page", function() {
			ajax.path("");
		});

		$.each(['action', 'form', 'view'], function (i, method) {
			it(method + "() does not accept empty argument", function () {
				expect(function () {
					ajax[method]('');
				}).toThrowError();
			});
		});

		it("handles server-sent messages and dependencies", function(done) {
			var tmp_system_message = elgg.system_message;
			var tmp_register_error = elgg.register_error;
			var tmp_require = Ajax._require;
			var captured = {};

			elgg.system_message = function (arg) {
				captured.msg = arg;
			};
			elgg.register_error = function (arg) {
				captured.error = arg;
			};
			Ajax._require = function (arg) {
				captured.deps = arg;
			};

			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				responseText: {
					value: 1,
					_elgg_msgs: {
						error: ['fail'],
						success: ['yay']
					},
					_elgg_deps: ['foo']
				}
			});

			ajax.path('foo').done(function () {
				expect(captured).toEqual({
					msg: ['yay'],
					error: ['fail'],
					deps: ['foo']
				});

				elgg.system_message = tmp_system_message;
				elgg.register_error = tmp_register_error;
				Ajax._require = tmp_require;

				done();
			});
		});

		it("error handler still handles server-sent messages and dependencies", function (done) {
			var tmp_system_message = elgg.system_message;
			var tmp_register_error = elgg.register_error;
			var tmp_require = Ajax._require;
			var captured = {};

			elgg.system_message = function (arg) {
				captured.msg = arg;
			};
			elgg.register_error = function (arg) {
				captured.error = arg;
			};
			Ajax._require = function (arg) {
				captured.deps = arg;
			};

			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				status: 500,
				responseText: {
					value: null,
					_elgg_msgs: {
						error: ['fail'],
						success: ['yay']
					},
					_elgg_deps: ['foo']
				}
			});

			ajax.path('foo').fail(function () {
				expect(captured).toEqual({
					msg: ['yay'],
					error: ['fail'],
					deps: ['foo']
				});

				elgg.system_message = tmp_system_message;
				elgg.register_error = tmp_register_error;
				Ajax._require = tmp_require;

				done();
			});
		});

		it("outputs the generic error if no server-sent message", function (done) {
			var tmp_register_error = elgg.register_error;
			var captured = {};

			elgg.register_error = function (arg) {
				captured.error = arg;
			};

			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				status: 500,
				responseText: {}
			});

			ajax.path('foo').fail(function () {
				expect(captured).toEqual({
					error: elgg.echo('ajax:error')
				});

				elgg.register_error = tmp_register_error;

				done();
			});
		});

		it("outputs the error message of the non-200 response", function (done) {
			var tmp_register_error = elgg.register_error;
			var captured = {};

			elgg.register_error = function (arg) {
				captured.error = arg;
			};

			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				status: 500,
				responseText: {
					error: 'Server throws'
				}
			});

			ajax.path('foo').fail(function () {
				expect(captured).toEqual({
					error: 'Server throws'
				});

				elgg.register_error = tmp_register_error;

				done();
			});
		});

		it("copies data to jqXHR.AjaxData", function (done) {
			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("foo"),
				responseText: {
					value: 1,
					other: 2
				}
			});

			ajax.path('foo').done(function (value, textStatus, jqXHR) {
				expect(jqXHR.AjaxData).toEqual({
					value: 1,
					other: 2,
					status: 0
				});
				done();
			});
		});

		it("sets jqXHR.AjaxData.status to 0 or -1 depending on presence of server error", function (done) {
			//$.mockjaxSettings.logging = true;
			$.mockjax({
				url: elgg.normalize_url("good"),
				responseText: {
					value: 1
				}
			});
			$.mockjax({
				url: elgg.normalize_url("bad"),
				responseText: {
					value: 1,
					_elgg_msgs: {
						error: ['fail']
					}
				}
			});

			ajax.path('good').done(function (value, textStatus, jqXHR) {
				expect(jqXHR.AjaxData.status).toBe(0);

				ajax.path('bad').done(function (value, textStatus, jqXHR) {
					expect(jqXHR.AjaxData.status).toBe(-1);
					done();
				});
			});
		});

		describe("ajax.objectify", function() {

			var $form = $("<form id=\"form\" action=\"formaction\">" +
					"<label for=\"action\" id=\"label-for\">Action:<\/label>" +
					"<input type=\"text\" name=\"action\" value=\"Test\" id=\"text1\" maxlength=\"30\"\/>" +
					"<input type=\"text\" name=\"text2\" value=\"Test\" id=\"text2\" disabled=\"disabled\"\/>" +
					"<input type=\"radio\" name=\"radio1\" id=\"radio1\" value=\"on\"\/>" +
					"<input type=\"radio\" name=\"radio2\" id=\"radio2\" checked=\"checked\"\/>" +
					"<input type=\"checkbox\" name=\"check\" id=\"check1\" checked=\"checked\"\/>" +
					"<input type=\"checkbox\" id=\"check2\" value=\"on\"\/>" +
					"<input type=\"hidden\" name=\"hidden\" id=\"hidden1\"\/>" +
					"<input type=\"text\" style=\"display:none;\" name=\"foo[bar]\" value=\"baz\" id=\"hidden2\"\/>" +
					"<input type=\"text\" id=\"name\" name=\"name\" value=\"name\" \/>" +
					"<input type=\"search\" id=\"search\" name=\"search\" value=\"search\" \/>" +
					"<button id=\"button\" name=\"button\" type=\"button\">Button<\/button>" +
					"<button id=\"button2\" name=\"button2\" type=\"button\" value=\"button_value\">Button 2<\/button>" +
					"<button id=\"submit\" name=\"submit\" type=\"submit\" value=\"submit_value\">Submit<\/button>" +
					"<textarea id=\"area1\" maxlength=\"30\">foobar<\/textarea>" +
					"<select name=\"select1\" id=\"select1\">" +
						"<option id=\"option1a\" class=\"emptyopt\" value=\"\">Nothing<\/option>" +
						"<option id=\"option1b\" value=\"1\">1<\/option>" +
						"<option id=\"option1c\" value=\"2\">2<\/option>" +
						"<option id=\"option1d\" value=\"3\">3<\/option>" +
					"<\/select>" +
					"<select name=\"select2\" id=\"select2\">" +
						"<option id=\"option2a\" class=\"emptyopt\" value=\"\">Nothing<\/option>" +
						"<option id=\"option2b\" value=\"1\">1<\/option>" +
						"<option id=\"option2c\" value=\"2\">2<\/option>" +
						"<option id=\"option2d\" selected=\"selected\" value=\"3\">3<\/option>" +
					"<\/select>" +
					"<select name=\"select3\" id=\"select3\" multiple=\"multiple\">" +
						"<option id=\"option3a\" class=\"emptyopt\" value=\"\">Nothing<\/option>" +
						"<option id=\"option3b\" selected=\"selected\" value=\"1\">1<\/option>" +
						"<option id=\"option3c\" selected=\"selected\" value=\"2\">2<\/option>" +
						"<option id=\"option3d\" value=\"3\">3<\/option>" +
						"<option id=\"option3e\">no value<\/option>" +
					"<\/select>" +
					"<select name=\"select4\" id=\"select4\" multiple=\"multiple\">" +
						"<optgroup disabled=\"disabled\">" +
							"<option id=\"option4a\" class=\"emptyopt\" value=\"\">Nothing<\/option>" +
							"<option id=\"option4b\" disabled=\"disabled\" selected=\"selected\" value=\"1\">1<\/option>" +
							"<option id=\"option4c\" selected=\"selected\" value=\"2\">2<\/option>" +
						"<\/optgroup>" +
						"<option selected=\"selected\" disabled=\"disabled\" id=\"option4d\" value=\"3\">3<\/option>" +
						"<option id=\"option4e\">no value<\/option>" +
					"<\/select>" +
					"<select name=\"select5\" id=\"select5\">" +
						"<option id=\"option5a\" value=\"3\">1<\/option>" +
						"<option id=\"option5b\" value=\"2\">2<\/option>" +
						"<option id=\"option5c\" value=\"1\" data-attr=\"\">3<\/option>" +
					"<\/select>" +
					"<object id=\"object1\" codebase=\"stupid\">" +
						"<param name=\"p1\" value=\"x1\" \/>" +
						"<param name=\"p2\" value=\"x2\" \/>" +
					"<\/object>" +
					"<span id=\"\u53F0\u5317Ta\u0301ibe\u030Ci\"><\/span>" +
					"<span id=\"\u53F0\u5317\" lang=\"\u4E2D\u6587\"><\/span>" +
					"<span id=\"utf8class1\" class=\"\u53F0\u5317Ta\u0301ibe\u030Ci \u53F0\u5317\"><\/span>" +
					"<span id=\"utf8class2\" class=\"\u53F0\u5317\"><\/span>" +
					"<span id=\"foo:bar\" class=\"foo:bar\"><\/span>" +
					"<span id=\"test.foo[5]bar\" class=\"test.foo[5]bar\"><\/span>" +
					"<foo_bar id=\"foobar\">test element<\/foo_bar>" +
				"<\/form>");

			it("objectifies a form into a FormData object", function() {
				var obj = ajax.objectify($form);

				expect(obj instanceof FormData).toBeTruthy();

				expect(obj.get('action')).toBe('Test');
				expect(obj.get('text2')).toBe(null); // disabled
				expect(obj.get('radio1')).toBe(null); // not checked
				expect(obj.get('radio2')).toBe('on');
				expect(obj.get('check')).toBe('on');
				expect(obj.get('hidden')).toBe('');
				expect(obj.get('name')).toBe('name');
				expect(obj.get('search')).toBe('search');
				expect(obj.get('foo[bar]')).toBe('baz');
				expect(obj.get('button')).toBe(null); // buttons don't get set
				expect(obj.get('button2')).toBe(null); // buttons don't get set
				expect(obj.get('submit')).toBe(null); // buttons don't get set, only when actualy set https://html.spec.whatwg.org/multipage/form-control-infrastructure.html#constructing-form-data-set
				expect(obj.get('select1')).toBe('');
				expect(obj.get('select2')).toBe('3');
				expect(obj.getAll('select3')).toEqual(['1', '2']);

			});
		});
	});
});
