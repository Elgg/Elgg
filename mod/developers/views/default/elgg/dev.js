/**
 * Elgg developers tool JavaScript
 */
define(function(require) {
	var $ = require('jquery'); require('jquery.jstree');

	var setupInspectTree = function() {
		
		$("#developers-inspect-results").jstree({
			"plugins" : [ "themes", "html_data" ],
			"themes" : {"icons" : false}
		}).bind("loaded.jstree", function() {
			$("#developers-inspect-results").fadeIn();				
		});
	};
	
	setupInspectTree();
});
