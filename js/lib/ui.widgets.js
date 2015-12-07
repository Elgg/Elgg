elgg.provide('elgg.ui.widgets');

elgg.ui.widgets = function() {
	elgg.deprecated_notice("elgg.ui.widgets object has been converted to 'elgg/widgets' AMD module", '2.1');
}

elgg.ui.widgets = {
	init: elgg.ui.widgets,
	add: elgg.ui.widgets,
	move: elgg.ui.widgets,
	remove: elgg.ui.widgets,
	collapseToggle: elgg.ui.widgets,
	saveSettings: elgg.ui.widgets,
	setMinHeight: elgg.ui.widgets,
};