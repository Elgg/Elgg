<?php
/**
 * Elgg developers tool JavaScript
 */
?>

elgg.provide('elgg.dev');

elgg.dev.init = function() {
	$('.developers-form-inspect').live('submit', elgg.dev.inspectSubmit);
};

/**
 * Submit the inspect form through Ajax
 *
 * Requires the jQuery Form Plugin.
 *
 * @param {Object} event
 */
elgg.dev.inspectSubmit = function(event) {

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

elgg.register_hook_handler('init', 'system', elgg.dev.init);