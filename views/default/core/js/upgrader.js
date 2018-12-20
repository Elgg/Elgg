/**
 * Javascript that takes care of running batch upgrades
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since 3.0.0
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');
	var popup = require('elgg/popup');

	var UNKNOWN_COUNT = -1;
	var upgrades = $('.elgg-item-object-elgg_upgrade');
	var upgrade;
	var guid;
	var progressbar;
	var upgradeStartTime;
	var timer;
	var counter;
	var percent;
	var errorCounter;
	var errorMessages = [];
	var numSuccess;
	var numError;
	var numProcessed = 0;
	var total;
	var messages = [];
	var messageList;
	var percentage = 0;

	// used to fake the progressbar if we don't know the total
	var FAKE_TOTAL = 1000000;
	var fakeDone = 0;

	/**
	 * Initializes the upgrade page
	 *
	 * Makes the upgrade button visible and adds a progress bar
	 * to each upgrade.
	 */
	function init() {
		// Display the button only if there are pending upgrades
		if ($('.elgg-item-object-elgg_upgrade').length) {
			$('#elgg-upgrades-run').removeClass('hidden').click(run);
		}
		
		$(document).on('click', '.elgg-menu-item-run-upgrade > a', runSingle);

		upgrades.each(function(key, value) {
			// Initialize progressbar
			$(value).find('.elgg-progressbar').progressbar();
		});
	}

	/**
	 * Runs all individual upgrades one at a time
	 *
	 * @param {Object} e Event object
	 */
	function run(e) {
		e.preventDefault();

		// Replace button with spinner when upgrade starts
		$('#elgg-upgrades-run').addClass('hidden');
		spinner.start();

		upgrade = upgrades.first();
		runUpgrade();

		return false;
	}
	
	/**
	 * Run a single upgrade
	 *
	 * @param {Object} e Event object
	 */
	function runSingle(e) {
		var guid = $(this).data().guid;
		
		if (!$('#elgg-object-' + guid).length) {
			return;
		}
		
		spinner.start();
		upgrade = $('#elgg-object-' + guid);
		
		runUpgrade(false);
		
		popup.close();
	}

	/**
	 * Takes care of processing a single upgrade in multiple batches
	 *
	 * @param bool advanceToNextWhenDone when the upgrade is done shoul it continue to the next available
	 */
	function runUpgrade(advanceToNextWhenDone) {
		
		if (typeof advanceToNextWhenDone !== 'boolean') {
			// IE doesn't support default function params
			advanceToNextWhenDone = true;
		}
		
		upgrade = $(upgrade);
		progressbar = upgrade.find('.elgg-progressbar');
		counter = upgrade.find('.upgrade-counter');
		percent = upgrade.find('.upgrade-percent');
		timer = upgrade.find('.upgrade-timer');
		messageList = upgrade.find('.upgrade-messages');
		errorCounter = upgrade.find('.upgrade-error-counter');
		data = upgrade.find('.upgrade-data');

		// The total amount of items to be upgraded
		total = data.attr('data-total');

		// Get the GUID from the element id: elgg-object-123
		guid = upgrade.attr('id').replace('elgg-object-', '');

		// Initialize progressbar
		$(upgrade).find('.elgg-progressbar').progressbar({
			value: 0,
			max: (total == UNKNOWN_COUNT) ? FAKE_TOTAL : total
		});

		upgradeStartTime = new Date().getTime();
		percentage = 0;

		processBatch(advanceToNextWhenDone);
	}

	/**
	 * Takes care of upgrading a single batch of items
	 *
	 * @param bool advanceToNextWhenDone when the upgrade is done shoul it continue to the next available
	 */
	function processBatch(advanceToNextWhenDone) {
		
		if (typeof advanceToNextWhenDone !== 'boolean') {
			// IE doesn't support default function params
			advanceToNextWhenDone = true;
		}
		
		var options = {
			data: {guid: guid},
			dataType: 'json'
		};

		options.data = elgg.security.addToken(options.data);

		options.success = function(json) {
			// Append possible errors after the progressbar
			if (json.system_messages.error.length) {
				// Display only the errors that haven't already been shown
				$(json.system_messages.error).each(function(key, message) {
					if ($.inArray(message, errorMessages) === -1) {
						var msg = '<li>' + message + '</li>';
						messageList.append(msg);
						messages.push(message);
					}
				});
			}

			$(json.output.errors).each(function(key, message) {
				var msg = '<li>' + message + '</li>';
				messageList.append(msg);
				messages.push(message);
			});

			numSuccess = parseInt(json.output.numSuccess);
			numError = parseInt(json.output.numErrors);

			numProcessed += (numSuccess + numError);

			// Increase success statistics
			if (total == UNKNOWN_COUNT) {
				counter.text(numProcessed + '/???');
			} else {
				counter.text(numProcessed + '/' + total);
			}

			// Increase the progress bar
			if (total == UNKNOWN_COUNT) {
				fakeDone = Math.round(FAKE_TOTAL - (FAKE_TOTAL - fakeDone) / 2);
				progressbar.progressbar({value: fakeDone});
			} else {
				percentage = parseInt(numProcessed * 100 / total);
				progressbar.progressbar({value: numProcessed});
			}

			if (numError > 0) {
				errorCounter
					.text(elgg.echo('upgrade:error_count', [messages.length]))
					.css('color', 'red');
			}

			updateCounter();

			var done;
			if (total == UNKNOWN_COUNT) {
				done = json.output.isComplete;
			} else {
				done = numProcessed >= total;
			}

			if (done) {
				if (numError > 0) {
					// Upgrade finished with errors. Give instructions on how to proceed.
					elgg.register_error(elgg.echo('upgrade:finished_with_errors'));
				}

				if (total == UNKNOWN_COUNT) {
					counter.text(numProcessed + '/' + numProcessed);
					progressbar.progressbar({value: FAKE_TOTAL});
				}

				// Increase percentage
				percent.html('100%');

				// Reset all counters
				numSuccess = numError = numProcessed = percentage = 0;
				messages = [];

				if (advanceToNextWhenDone) {
					// Get next upgrade
					upgrade = upgrade.next();
				} else {
					upgrade = '';
				}

				if (upgrade.length) {
					// Continue to next upgrade
					runUpgrade(advanceToNextWhenDone);
				} else {
					spinner.stop();
					$('#upgrade-finished').removeClass('hidden');
				}
				
				return;
			}

			// carry on...
			if (total != UNKNOWN_COUNT) {
				percentage = parseInt(numProcessed * 100 / total);
				// Increase percentage
				percent.html(percentage + '%');
			}

			// Start next upgrade call
			processBatch(advanceToNextWhenDone);
		};

		// We use post() instead of action() so we can catch error messages
		// and display them manually underneath the upgrade view.
		return elgg.post('action/admin/upgrade', options);
	}

	/**
	 * Displays estimated amount of time needed for a single upgrade to finish
	 */
	function updateCounter() {
		var now = new Date().getTime();

		// How many milliseconds ago the last batch was started
		var difference = (now - upgradeStartTime) / 1000;

		// How many items are waiting to be processed
		var unProcessed = total - numProcessed;

		var timeLeft = Math.round((difference / numProcessed) * unProcessed);

		if (timeLeft < 60) {
			var hours = '00';
			var minutes = '00';
			var seconds = timeLeft;
		} else {
			if (timeLeft < 3600) {
				var minutes = Math.floor(timeLeft / 60);
				var seconds = timeLeft % 60;
				var hours = '00';
			} else {
				var hours = Math.floor(timeLeft / 3600);
				var timeLeft = timeLeft % 3600;
				var minutes = Math.floor(timeLeft / 60);
				var seconds = timeLeft % 60;
			}
		}

		hours = formatDigits(hours);
		minutes = formatDigits(minutes);
		seconds = formatDigits(seconds);

		var value = hours + ':' + minutes + ':' + seconds;

		timer.html(value);
	}

	/**
	 * Rounds hours, minutes or seconds and adds a leading zero if necessary
	 *
	 * @param {String} time
	 * @return {String} time
	 */
	function formatDigits(time) {
		time = Math.floor(time);

		if (time < 1) {
			return '00';
		}

		if (time < 10) {
			return '0' + time;
		}

		return time;
	}

	init();
});
