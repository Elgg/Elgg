/**
 * Even thought this looks like an async require call, it in fact does not
 * issue an async call to load elgg/widgets, which  is inlined in elgg.js.
 * This makes sure elgg.ui.widgets methods are available for plugins to use,
 * once elgg module is loaded.
 * @deprecated 2.1
 */
require(['elgg', 'elgg/widgets'], function (elgg, widgets) {

	elgg.provide('elgg.ui.widgets');

	elgg.ui.widgets = {
		_notice: function() {
			elgg.deprecated_notice('Don\'t use elgg.ui.widgets directly. Use the AMD elgg/widgets module', '2.1');
		},
		init: function() {
			elgg.ui.widgets._notice();
			return widgets.init.apply(this, arguments);
		},
		add: function () {
			elgg.ui.widgets._notice();
			return widgets.add.apply(this, arguments);
		},
		move: function () {
			elgg.ui.widgets._notice();
			return widgets.move.apply(this, arguments);
		},
		remove: function () {
			elgg.ui.widgets._notice();
			return widgets.remove.apply(this, arguments);
		},
		collapseToggle: function() {
			elgg.ui.widgets._notice();
			return widgets.collapseToggle.apply(this, arguments);
		},
		setMinHeight: function() {
			elgg.ui.widgets._notice();
			return widgets.setMinHeight.apply(this, arguments);
		}
	};

	elgg.register_hook_handler('init', 'system', widgets.init);
});
