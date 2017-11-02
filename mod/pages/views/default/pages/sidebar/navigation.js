define(function (require) {

	var $ = require('jquery');
	require('jquery.treeview');

	$(".pages-nav").treeview({
		persist: "location",
		collapsed: true,
		unique: true
	});

	// if on a history page, we need to manually select the correct menu item
	// code taken from the jquery.treeview library
	var current = $('.pages-nav').find('.elgg-state-selected > a');
	var items = current.addClass("selected").parents("ul, li").add(current.next()).show();
	var CLASSES = $.treeview.classes;
	items.filter("li")
		.swapClass(CLASSES.collapsable, CLASSES.expandable)
		.swapClass(CLASSES.lastCollapsable, CLASSES.lastExpandable)
		.find(">.hitarea")
		.swapClass(CLASSES.collapsableHitarea, CLASSES.expandableHitarea)
		.swapClass(CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea);

});
