/**
 * Elgg developers tool JavaScript
 */
define(function(require) {
	var $ = require('jquery'); require('jquery.form'); require('jquery.jstree');

	/**
	 * Submit the inspect form through Ajax
	 *
	 * Requires the jQuery Form Plugin.
	 *
	 * @param {Object} event
	 */
	var inspectSubmit = function(event) {

		$("#developers-inspect-results").hide();
		$("#developers-ajax-loader").show();

		$(this).ajaxSubmit({
			dataType : 'json',
			success  : function(response) {
				if (response) {
					$("#developers-inspect-results").html(response.output);
					$("#developers-inspect-results").jstree({
						"plugins" : [ "themes", "html_data" ],
						"themes" : {"icons" : false}
					}).bind("loaded.jstree", function() {
						$("#developers-inspect-results").fadeIn();
						$("#developers-ajax-loader").hide();
					});
				}
			}
		});

		event.preventDefault();
	};

	$('.developers-form-inspect').live('submit', inspectSubmit);

	return {
		inspectSubmit: inspectSubmit
	};
});
