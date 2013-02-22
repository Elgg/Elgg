<?php
/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */
?>
//<script>
elgg.provide('elgg.admin');
elgg.provide('elgg.admin.massMailout');

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

	// ajax mass mailout submit
	$('.elgg-form-admin-mass-mailout-send').submit(elgg.admin.massMailout.submit);
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
	var orderArr = $('#elgg-profile-fields').sortable('toArray');
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

/**
 * Fires the ajax actions to send emails.
 * 
 * @param {Object} request
 * @return void
 */
elgg.admin.massMailout.send = function(request) {
	elgg.action('admin/mass_mailout/send', {
		data: request,
		success: function(response) {
			$('#progressbar').progressbar('value', response.output.sent * 100 / response.output.total);
			if (response.output.sent < response.output.total) {
				request.offset += request.limit; 
				elgg.admin.massMailout.send(request);
			}
		}
	});
};

/**
 * Display the mass mailout progress bar
 * 
 * @return void
 */
elgg.admin.massMailout.progressBar = function() {
	$('#progressbar').progressbar({
		value: false,
		change: function() {
			$('.progress-label', this).text( parseInt($(this).progressbar( "value" )) + "%" );
		},
		complete: function() {
			$('.progress-label', this).text( elgg.echo('admin:mass_mailout:success') );
		}
	}).fadeIn();
}

/**
 * Event handler for mass mailout form submit.
 * 
 * @param {Object} e  Event object.
 * @return void
 */
elgg.admin.massMailout.submit = function(e) {
	var subject = $(this).find('[name="subject"]').val();
	var message = $(this).find('[name="message"]').val();

	if (!subject || !message) {
		elgg.register_error(elgg.echo('admin:mass_mailout:inputs'));
		e.preventDefault();
		return;
	}

	elgg.admin.massMailout.send({
		subject: subject,
		message: message,
		offset: 0,
		limit: 10
	});
	$(this).fadeOut();
	elgg.admin.massMailout.progressBar();
	e.preventDefault();
};

elgg.register_hook_handler('init', 'system', elgg.admin.init, 1000);
