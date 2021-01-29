/**
 * Helper module to call API calls with javascript
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {
	
	var webservices = {
			
		/**
		 * Execute an API method
		 *
		 * @example Usage:
		 * <pre>
		 * webservices.executeMethod('system.api.list', {
		 *     success: function(data) {
		 *         console.log(data);
		 *     }
		 * });
		 * </pre>
		 *
		 * @param {String} method The API method to be called
		 * @param {Object} options {@link jQuery#ajax}
		 * @return {jqXHR}
		 */
		executeMethod: function(method, options) {
			elgg.assertTypeOf('string', method);
			
			var defaults = {
				dataType: 'json',
				data: {}
			};

			options = $.extend(defaults, options);

			options.url = 'services/api/rest/' + options.dataType + '/';
			options.data.method = method;
			
			var ajax = new Ajax(false);
			return ajax.fetch(options);
		}
	}

	return webservices;
});
