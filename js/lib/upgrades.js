/**
 * Provides functions for site upgrades performed through AJAX
 */

elgg.provide('elgg.upgrades');

elgg.upgrades.init = function () {
	$('#comment-upgrade-run').click(elgg.upgrades.upgradeComments);
};

/**
 * Initializes the comment upgrade feature
 *
 * @param {Object} e Event object
 */
elgg.upgrades.upgradeComments = function(e) {
	e.preventDefault();

	// The total amount of comments to be upgraded
	var total = $('#comment-upgrade-total').text();

	// Initialize progressbar
	$('.elgg-progressbar').progressbar({
		value: 0,
		max: total
	});

	// Replace button with spinner when upgrade starts
	$('#comment-upgrade-run').addClass('hidden');
	$('#comment-upgrade-spinner').removeClass('hidden');

	// Start comment upgrade from offset 0
	elgg.upgrades.upgradeCommentBatch(0);
};

/**
 * Fires the ajax action to upgrade a batch of comments.
 *
 * @param {Number} offset  The next upgrade offset
 */
elgg.upgrades.upgradeCommentBatch = function(offset) {
	var options = {
			data: {
				offset: offset
			},
			dataType: 'json'
		},
		$upgradeCount = $('#comment-upgrade-count');

	options.data = elgg.security.addToken(options.data);

	options.success = function(json) {
		// Append possible errors after the progressbar
		if (json.system_messages.error.length) {
			var msg = '<li class="elgg-message elgg-state-error">' + json.system_messages.error + '</li>';
			$('#comment-upgrade-messages').append(msg);
		}

		// Increase success statistics
		var numSuccess = $('#comment-upgrade-success-count');
		var successCount = parseInt(numSuccess.text()) + json.output.numSuccess;
		numSuccess.text(successCount);

		// Increase error statistics
		var numErrors = $('#comment-upgrade-error-count');
		var newOffset = parseInt(numErrors.text()) + json.output.numErrors;
		numErrors.text(newOffset);

		// Increase total amount of processed comments
		var numProcessed = parseInt($upgradeCount.text()) + json.output.numSuccess + json.output.numErrors;
		$upgradeCount.text(numProcessed);

		// Increase percentage
		var total = $('#comment-upgrade-total').text();
		var percent = parseInt(numProcessed * 100 / total);

		// Increase the progress bar
		$('.elgg-progressbar').progressbar({ value: numProcessed });

		if (numProcessed < total) {
			/**
			 * Start next upgrade call. Offset is the total amount of erros so far.
			 * This prevents faulty comments from causing the same error again.
			 */
			elgg.upgrades.upgradeCommentBatch(newOffset);
		} else {
			// Upgrade is finished
			elgg.system_message(elgg.echo('upgrade:comments:finished'));

			$('#comment-upgrade-spinner').addClass('hidden');

			percent = '100';
		}

		$('#comment-upgrade-counter').text(percent + '%');
	};

	// We use post() instead of action() to get better control over error messages
	return elgg.post('action/admin/upgrades/upgrade_comments', options);
};

elgg.register_hook_handler('init', 'system', elgg.upgrades.init);
