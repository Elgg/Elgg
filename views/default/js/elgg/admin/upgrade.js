
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	var upgrades = $('.elgg-upgrade');
	var upgrade;
	var className;
	var progressbar;
	var upgradeStartTime;
	var timer;
	var counter;
	var percent;
	var errorMessages = [];
	var offset = 0;
	var numSuccess;
	var numError;
	var numProcessed = 0;
	var total;
	var messages = [];
	var messageList;

	/**
	 * Initializes the upgrade page
	 *
	 * Makes the upgrade button visible and adds a progress bar
	 * to each upgrade.
	 */
	init = function() {
		$('#upgrade-run').removeClass('hidden').click(run);

		upgrades.each(function(key, value) {
			// Initialize progressbar
			$(value).find('.elgg-progressbar').progressbar();
		});
	};

	/**
	 * Runs all individual upgrades one at a time
	 *
	 * @param {Object} e Event object
	 */
	var run = function(e) {
		e.preventDefault();

		// Replace button with spinner when upgrade starts
		$('#upgrade-run').addClass('hidden');
		spinner.start();

		upgrade = upgrades.first();
		runUpgrade();

		return false;
	};

	/**
	 * Takes care of processing a single upgrade in multiple batches
	 */
	var runUpgrade = function() {
		upgrade = $(upgrade);
		progressbar = upgrade.find('.elgg-progressbar');
		className = upgrade.attr('data-class');
		counter = upgrade.find('.upgrade-counter');
		percent = upgrade.find('.upgrade-percent');
		timer = upgrade.find('.upgrade-timer');
		messageList = upgrade.find('.upgrade-messages');

		// The total amount of items to be upgraded
		total = $(upgrade).attr('data-total');

		// Initialize progressbar
		$(upgrade).find('.elgg-progressbar').progressbar({
			value: 0,
			max: total
		});

		upgradeStartTime = new Date().getTime();

		// Start upgrade from offset 0
		processBatch(0);
	};

	/**
	 * Takes care of upgrading a single batch of items
	 */
	var processBatch = function() {
		// TODO Remove after debugging
		console.log(className);

		var options = {
			data: {
				class_name: className,
				offset: offset
			},
			dataType: 'json'
		};

		options.data = elgg.security.addToken(options.data);

		var upgradeCount = $('#upgrade-count');

		options.success = function(json) {
			// Append possible errors after the progressbar
			if (json.system_messages.error.length) {
				// Display only the errors that haven't already been shown
				$(json.system_messages.error).each(function(key, message) {
					if ($.inArray(message, errorMessages) === -1) {
						var msg = '<li class="elgg-message elgg-state-error">' + message + '</li>';
						messageList.append(msg);
						messages.push(message);
					}
				});
			}

			numSuccess = parseInt(json.output.numSuccess);
			numError = parseInt(json.output.numErrors);
			offset = parseInt(json.output.nextOffset);

			numProcessed += (numSuccess + numError);

			console.log('numProcessed: ' + numProcessed + ', numSuccess: ' + numSuccess + ', numError: ' + numError + ', next offset: ' + offset);

			// Increase success statistics
			counter.text(numProcessed + '/' + total);

			// Increase the progress bar
			progressbar.progressbar({ value: numProcessed });

			updateCounter();

			var percentage = 0;
			if (numProcessed < total) {
				percentage = parseInt(numProcessed * 100 / total);

				// Increase percentage
				percent.html(percentage + '%');

				/**
				 * Start next upgrade call. Offset is the total amount of erros so far.
				 * This prevents faulty items from causing the same error again.
				 */
				processBatch();
			} else {
				if (numError > 0) {
					// Upgrade finished with errors. Give instructions on how to proceed.
					elgg.register_error(elgg.echo('upgrade:finished_with_errors'));
				} else {
					// Upgrade is finished. Make one more call to mark it complete.
					elgg.action('action/admin/upgrade', {
						'upgrade_completed': 1,
						class_name: className,
					});
					elgg.system_message(elgg.echo('upgrade:finished'));
				}

				// Increase percentage
				percent.html('100%');

				// Reset all counters
				numSuccess = numErrors = numProcessed = offset = percentage = 0;

				// Get next upgrade
				upgrade = upgrade.next();

				if (upgrade.length) {
					// Continue to next upgrade
					runUpgrade();
				} else {
					spinner.stop();
					$('#upgrade-finished').removeClass('hidden');
				}
			}
		};

		// We use post() instead of action() so we can catch error messages
		// and display them manually underneath the upgrade view.
		return elgg.post('action/admin/upgrade', options);
	};

	/**
	 * Displays estimated amount of time needed for a single upgrade to finish
	 */
	var updateCounter = function() {
		now = new Date().getTime();

		// How many milliseconds ago the last batch was started
		var difference = Math.round((now - upgradeStartTime) / 1000);

		var unProcessed = total - numProcessed;

		var timeLeft = Math.round((difference / numProcessed) * unProcessed);

		if (timeLeft < 60) {
			var hours = '00';
			var minutes = '00';
			var seconds = formatDigits(timeLeft);
		} else {
			if (timeLeft < 3600) {
				var hours = '00';
			} else {
				var hours = formatDigits(minutes / 3600);
			}

			var minutes = formatDigits(timeLeft / 60);
			var seconds = formatDigits(timeLeft - (minutes * 60));
		}

		var value = hours + ':' + minutes + ':' + seconds;

		timer.html(value);
	};

	/**
	 * Rounds hours, minutes or seconds and adds a leading zero if necessary
	 *
	 * @param {String} time
	 * @return {String} time
	 */
	var formatDigits = function(time) {
		time = Math.round(time);

		if (time < 1) {
			return '00';
		}

		if (time < 9) {
			return '0' + time;
		}

		return time;
	};

	return init();

});
