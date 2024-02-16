/**
 * Helper module to call API calls with javascript
 */

import 'jquery';
import elgg from 'elgg';
import Ajax from 'elgg/Ajax';
	
export default {
		
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
