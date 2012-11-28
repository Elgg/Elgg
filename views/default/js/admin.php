<?php
/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */

if (0) { ?><script><?php }
?>
elgg.provide('elgg.admin');

elgg.admin.init = function () {

	// system messages do not fade in admin area, instead slide up when clicked
	$('.elgg-system-messages li').stop(true);
	$('.elgg-system-messages li').die('click');
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().slideUp('medium');
	});

	// draggable plugin reordering
	$('#elgg-plugin-list > ul').sortable({
		items:                'li:has(> .elgg-state-draggable)',
		handle:               '.elgg-head',
		forcePlaceholderSize: true,
		placeholder:          'elgg-widget-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 elgg.admin.movePlugin
	});

	// in-line editing for custom profile fields.
	// @note this requires jquery.jeditable plugin
	$(".elgg-state-editable").editable(elgg.admin.editProfileField, {
		type:   'text',
		onblur: 'submit',
		width:  '300px',
		height: 'none',
		style:  'display:inline;'
	});

	// draggable profile field reordering.
	$('#elgg-profile-fields').sortable({
		items: 'li',
		handle: 'span.elgg-state-draggable',
		stop: elgg.admin.moveProfileField
	});

	// admin notices delete ajax
	$('a.elgg-admin-notice').click(elgg.admin.deleteNotice);
};

/**
 * Save the plugin order after a move event.
 *
 * @param {Object} e  Event object.
 * @param {Object} ui jQueryUI object
 * @return void
 */
elgg.admin.movePlugin = function(e, ui) {
	// get guid from id like elgg-object-<guid>
	var pluginGuid = ui.item.attr('id');
	pluginGuid = pluginGuid.replace('elgg-object-', '');

	elgg.action('admin/plugins/set_priority', {
		data: {
				plugin_guid: pluginGuid,
				// we start at priority 1
				priority: ui.item.index() + 1
			}
	});
};

/**
 * In-line editing for custom profile fields
 *
 * @param string   value    The new value
 * @param {Object} settings The settings used for editable
 * @return void
 */
elgg.admin.editProfileField = function(value, settings) {
	var id = $(this).attr('id');
	id = id.replace('elgg-profile-field-', '');

	var data = {
		id:    id,
		label: value
	};

	elgg.action('profile/fields/edit', data);
	return value;
};

/**
 * Save the plugin profile order after a move event.
 *
 * @param {Object} e  Event object.
 * @param {Object} ui jQueryUI object
 * @return void
 */
elgg.admin.moveProfileField = function(e, ui) {
	var orderArr = $('#elgg-profile-fields').sortable('toArray');
	var orderStr = orderArr.join(',');

	elgg.action('profile/fields/reorder', {
		fieldorder: orderStr
	});
};

/**
 * Fires the ajax action to delete the admin notice then hides the notice.
 *
 * @return void
 */
elgg.admin.deleteNotice = function(e) {
	e.preventDefault();
	var $container = $(this).closest('p');
	
	elgg.action($(this).attr('href'), {
		success: function(json) {
			$container.slideUp('medium');
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.admin.init, 1000);