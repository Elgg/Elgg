/**
 * Elgg developers tool JavaScript
 */
define(function(require) {
	var $ = require('jquery'); require('jquery.form'); require('jquery.jstree');

	/**
	 * Submit the inspect form through Ajax
	 *
	 * @param {Object} event
	 */
	function inspectSubmit(event) {
	
		$("#developers-inspect-results").hide();
		$("#developers-ajax-loader").show();
		
		$(this).ajaxSubmit({
			dataType: 'json',
			success: handleSuccess
		});
	
		event.preventDefault();
	};
	
	function handleSuccess(response) {
		if (!response) {
			return;
		}
		
		$("#developers-inspect-results").html(response.output);
		$("#developers-inspect-results").jstree({
			"plugins" : [ "themes", "html_data" ],
			"themes" : {"icons" : false}
		}).bind("loaded.jstree", function() {
			$("#developers-inspect-results").fadeIn();
			$("#developers-ajax-loader").hide();
		});
	}

	$('.developers-form-inspect').live('submit', inspectSubmit);

	return {
		inspectSubmit: inspectSubmit
	};
});
