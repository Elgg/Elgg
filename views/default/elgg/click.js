/**
 * Captures clicks that occur before event listeners can be registered, and allows "replay"ing them.
 *
 * E.g. if an element has data-elgg-click="foo", then clicks will be silently captured until
 * a module calls replay('foo') or takeOver('foo').
 */
define(function (require) {

	var $ = require('jquery');

	/**
	 * Return and discard events captured under a name, and don't allow this module to
	 * collect further events.
	 *
	 * Each object returned will have the key:
	 *    "target" : {Element} the element clicked
	 *
	 * @param {string} name Name clicks are associated with
	 * @returns {object[]}
	 */
	function dumpEvents(name) {
		if (name in elgg_clicks.clicks) {
			var ret = elgg_clicks.clicks[name];
			delete elgg_clicks.clicks[name];
			return ret;
		}
		return [];
	}

	function stop(name) {
		elgg_click.stops[name] = 1;
	}

	return {

		/**
		 * Return and the captured events and stop listening for more.
		 *
		 * Each object returned will have the key:
		 *    "target" : {Element} the element clicked
		 *
		 * @param {string} name Name clicks are associated with
		 * @returns {object[]}
		 */
		takeOver: function (name) {
			stop(name);
			return dumpEvents(name);
		},

		/**
		 * Replay the stored clicks and stop listening for more.
		 *
		 * @param {string} name Name clicks are associated with
		 */
		replay: function (name) {
			stop(name);
			$.each(dumpEvents(name), function (k, v) {
				$(v.target).trigger('click');
			});
		}
	};
});
