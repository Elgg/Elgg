/**
 * Provides functions for site upgrades performed through XHR
 *
 * @access private
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	// The already displayed messages are saved here
	var errorMessages = [];

	/**
	 * Initializes the XHR upgrade feature
	 *
	 * @param {Object} e Event object
	 */
	var run = function(e) {
		e.preventDefault();

		// The total amount of items to be upgraded
		var total = $('#upgrade-total').text();

		// Initialize progressbar
		$('.elgg-progressbar').progressbar({
			value: 0,
			max: total
		});

		// Replace button with spinner when upgrade starts
		$('#upgrade-run').addClass('hidden');
		$('#upgrade-spinner').removeClass('hidden');

		// Start upgrade from offset 0
		upgradeBatch(0);
	};

	/**
	 * Fires the ajax action to upgrade a batch of items.
	 *
	 * @param {Number} offset  The next upgrade offset
	 */
	var upgradeBatch = function(offset) {
		var options = {
			data: {
				offset: offset
			},
			dataType: 'json'
		};

		options.data = elgg.security.addToken(options.data);

		var upgradeCount = $('#upgrade-count');
		var action = $('#upgrade-run').attr('href');

		options.success = function(json) {
			// Append possible errors after the progressbar
			if (json.system_messages.error.length) {
				// Display only the errors that haven't already been shown
				$(json.system_messages.error).each(function(key, message) {
					if (jQuery.inArray(message, errorMessages) === -1) {
						var msg = '<li class="elgg-message elgg-state-error">' + message + '</li>';
						$('#upgrade-messages').append(msg);

						// Add this error to the displayed errors
						errorMessages.push(message);
					}
				});
			}

			// Increase success statistics
			var numSuccess = $('#upgrade-success-count');
			var successCount = parseInt(numSuccess.text()) + json.output.numSuccess;
			numSuccess.text(successCount);

			// Increase error statistics
			var numErrors = $('#upgrade-error-count');
			var errorCount = parseInt(numErrors.text()) + json.output.numErrors;
			numErrors.text(errorCount);

			// Increase total amount of processed items
			var numProcessed = successCount + errorCount;
			upgradeCount.text(numProcessed);

			// Increase the progress bar
			$('.elgg-progressbar').progressbar({ value: numProcessed });
			var total = $('#upgrade-total').text();

			var percent = 100;
			if (numProcessed < total) {
				percent = parseInt(numProcessed * 100 / total);

				/**
				 * Start next upgrade call. Offset is the total amount of erros so far.
				 * This prevents faulty items from causing the same error again.
				 */
				upgradeBatch(errorCount);
			} else {
				$('#upgrade-spinner').addClass('hidden');

				if (errorCount > 0) {
					// Upgrade finished with errors. Give instructions on how to proceed.
					elgg.register_error(elgg.echo('upgrade:finished_with_errors'));
				} else {
					// Upgrade is finished. Make one more call to mark it complete.
					elgg.action(action, {'upgrade_completed': 1});
					elgg.system_message(elgg.echo('upgrade:finished'));
				}
			}

			// Increase percentage
			$('#upgrade-counter').text(percent + '%');
		};

		// We use post() instead of action() so we can catch error messages
		// and display them manually underneath the upgrade view.
		return elgg.post(action, options);
	};

	$('#upgrade-run').click(run);
});

