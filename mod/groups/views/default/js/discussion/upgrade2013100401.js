/**
 * Performed discussion reply upgrade through AJAX
 */

elgg.provide('elgg.discussion_upgrade');

elgg.discussion_upgrade.init = function () {
	$('#reply-upgrade-run').click(elgg.discussion_upgrade.upgradeReplies);
};

/**
 * Initializes the discussion reply upgrade feature
 *
 * @param {Object} e Event object
 */
elgg.discussion_upgrade.upgradeReplies = function(e) {
	e.preventDefault();

	// The total amount of discussion replies to be upgraded
	var total = $('#reply-upgrade-total').text();

	// Initialize progressbar
	$('.elgg-progressbar').progressbar({
		value: 0,
		max: total
	});

	// Replace button with spinner when upgrade starts
	$('#reply-upgrade-run').addClass('hidden');
	$('#reply-upgrade-spinner').removeClass('hidden');

	// Start discussion reply upgrade from offset 0
	elgg.discussion_upgrade.upgradeReplyBatch(0);
};

/**
 * Fires the ajax action to upgrade a batch of discussion replies.
 *
 * @param {Number} offset  The next upgrade offset
 */
elgg.discussion_upgrade.upgradeReplyBatch = function(offset) {
	var options = {
			data: {
				offset: offset
			},
			dataType: 'json'
		},
		$upgradeCount = $('#reply-upgrade-count');

	options.data = elgg.security.addToken(options.data);

	options.success = function(json) {
		// Append possible errors after the progressbar
		if (json.system_messages.error.length) {
			var msg = '<li class="elgg-message elgg-state-error">' + json.system_messages.error + '</li>';
			$('#reply-upgrade-messages').append(msg);
		}

		// Increase success statistics
		var numSuccess = $('#reply-upgrade-success-count');
		var successCount = parseInt(numSuccess.text()) + json.output.numSuccess;
		numSuccess.text(successCount);

		// Increase error statistics
		var numErrors = $('#reply-upgrade-error-count');
		var newOffset = parseInt(numErrors.text()) + json.output.numErrors;
		numErrors.text(newOffset);

		// Increase total amount of processed discussion replies
		var numProcessed = parseInt($upgradeCount.text()) + json.output.numSuccess + json.output.numErrors;
		$upgradeCount.text(numProcessed);

		// Increase percentage
		var total = $('#reply-upgrade-total').text();
		var percent = parseInt(numProcessed * 100 / total);

		// Increase the progress bar
		$('.elgg-progressbar').progressbar({ value: numProcessed });

		if (numProcessed < total) {
			/**
			 * Start next upgrade call. Offset is the total amount of erros so far.
			 * This prevents faulty discussion replies from causing the same error again.
			 */
			elgg.discussion_upgrade.upgradeReplyBatch(newOffset);
		} else {
			// Upgrade is finished
			elgg.system_message(elgg.echo('discussion:upgrade:replies:finished'));

			$('#reply-upgrade-spinner').addClass('hidden');

			percent = '100';
		}

		$('#reply-upgrade-counter').text(percent + '%');
	};

	// We use post() instead of action() to get better control over error messages
	return elgg.post('action/discussion/upgrade/2013100401', options);
};

elgg.register_hook_handler('init', 'system', elgg.discussion_upgrade.init);
