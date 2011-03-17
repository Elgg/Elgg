<?php
/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */
?>
//<script>
elgg.provide('elgg.admin');

elgg.admin.init = function () {

	// system messages do not fade in admin area, instead slide up when clicked
	$('.elgg-system-messages li').stop(true);
	$('.elgg-system-messages li').die('click');
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().slideUp('medium');
	});

	// plugin screenshot modal
	$('.elgg-plugin-screenshot a').click(elgg.admin.displayPluginScreenshot);

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
	$(".elgg-state-editable").editable(elgg.admin.editProfileField, {
		type:   'text',
		onblur: 'submit',
		width:  '300px',
		height: 'none',
		style:  'display:inline;'
	});

	// draggable profile field reordering.
	$('#sortable_profile_fields').sortable({
		items: 'li',
		handle: 'span.elgg-state-draggable',
		stop: elgg.admin.moveProfileField
	});

	// admin notices delete ajax
	$('a.elgg-admin-notice').click(elgg.admin.deleteNotice);
}

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
 * Display a plugin screenshot.
 *
 * @param {Object} e The event object.
 * @return void
 */
elgg.admin.displayPluginScreenshot = function(e) {
	e.preventDefault();
	var lb = $('.elgg-plugin-screenshot-lightbox');

	if (lb.length < 1) {
		$('body').append('<div class="elgg-plugin-screenshot-lightbox"></div>');
		lb = $('.elgg-plugin-screenshot-lightbox');

		lb.click(function() {
			lb.hide();
		});

		$(document).click(function(e) {
			var target = $(e.target);
			if (target.is('a') && target.hasClass('elgg-plugin-screenshot-lightbox')) {
				lb.hide();
				e.preventDefault();
			}
		});
	}

	var html = '<img class="pas" src="' + $(this).attr('href') + '">';
	var desc = $(this).find('img').attr('alt');

	if (desc) {
		html = '<h2 class="pam">' + desc + '</h2>' + html;
	}

	lb.html(html);

	top_pos = $(window).scrollTop() + 10 + 'px';
	left_pos = $(window).scrollLeft() + 5 + 'px';

	lb.css('top', top_pos).css('left', left_pos).show();
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
}

/**
 * Save the plugin profile order after a move event.
 *
 * @param {Object} e  Event object.
 * @param {Object} ui jQueryUI object
 * @return void
 */
elgg.admin.moveProfileField = function(e, ui) {
	var orderArr = $('#sortable_profile_fields').sortable('toArray');
	var orderStr = orderArr.join(',');

	elgg.action('profile/fields/reorder', {
		fieldorder: orderStr
	});
}

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
}

elgg.register_hook_handler('init', 'system', elgg.admin.init, 1000);