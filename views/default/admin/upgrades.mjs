/**
 * JavaScript that takes care of running batch upgrades
 */

import 'jquery';
import Ajax from 'elgg/Ajax';
import spinner from 'elgg/spinner';
import popup from 'elgg/popup';
import system_messages from 'elgg/system_messages';
import i18n from 'elgg/i18n';

const UNKNOWN_COUNT = -1;
const upgrades = $('.elgg-item-object-elgg_upgrade');
let upgrade;
let guid;
let progressbar;
let upgradeStartTime;
let timer;
let counter;
let percent;
let errorCounter;
let errorMessages = [];
let numSuccess;
let numError;
let numProcessed = 0;
let total;
let messages = [];
let messageList;
let percentage = 0;

// used to fake the progressbar if we don't know the total
const FAKE_TOTAL = 1000000;
let fakeDone = 0;

/**
 * Runs all individual upgrades one at a time
 *
 * @param {Object} event Event object
 */
function run(event) {
	event.preventDefault();

	// Replace button with spinner when upgrade starts
	$('#elgg-upgrades-run').addClass('hidden');
	spinner.start();

	upgrade = upgrades.first();
	runUpgrade();

	return false;
}

/**
 * Run a single upgrade
 */
function runSingle() {
	const guid = $(this).data().guid;
	
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
 * @param {bool} advanceToNextWhenDone when the upgrade is done should it continue to the next available
 */
function runUpgrade(advanceToNextWhenDone) {
	if (typeof advanceToNextWhenDone !== 'boolean') {
		advanceToNextWhenDone = true;
	}
	
	upgrade = $(upgrade);
	progressbar = upgrade.find('.elgg-progressbar');
	counter = upgrade.find('.upgrade-counter');
	percent = upgrade.find('.upgrade-percent');
	timer = upgrade.find('.upgrade-timer');
	messageList = upgrade.find('.upgrade-messages');
	errorCounter = upgrade.find('.upgrade-error-counter');
	const data = upgrade.find('.upgrade-data');

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
 * @param {bool} advanceToNextWhenDone when the upgrade is done should it continue to the next available
 */
function processBatch(advanceToNextWhenDone) {
	if (typeof advanceToNextWhenDone !== 'boolean') {
		// IE doesn't support default function params
		advanceToNextWhenDone = true;
	}
	
	const options = {
		data: {
			guid: guid
		},
		error: function(result) {
			// Append possible errors after the progressbar
			$(result.errors).each(function(key, message) {
				const msg = '<li>' + message + '</li>';
				messageList.append(msg);
				messages.push(message);
			});
			
			spinner.stop();
		},
		success: function(result) {
			numSuccess = parseInt(result.numSuccess);
			numError = parseInt(result.numErrors);

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
				percentage = Math.round(numProcessed * 100 / total);
				progressbar.progressbar({value: numProcessed});
			}

			if (numError > 0) {
				errorCounter
					.text(i18n.echo('upgrade:error_count', [messages.length]))
					.css('color', 'red');
			}

			updateCounter();

			let done;
			if (total == UNKNOWN_COUNT || result.isComplete) {
				done = result.isComplete;
			} else {
				done = numProcessed >= total;
			}

			if (done) {
				if (numError > 0) {
					// Upgrade finished with errors. Give instructions on how to proceed.
					system_messages.error(i18n.echo('upgrade:finished_with_errors'));
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

				upgrade.fadeOut('slow', function () {
					$(this).remove();
				});

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
				}
				
				return;
			}

			// carry on...
			if (total != UNKNOWN_COUNT) {
				percentage = Math.round(numProcessed * 100 / total);
				// Increase percentage
				percent.html(percentage + '%');
			}

			// Start next upgrade call
			processBatch(advanceToNextWhenDone);
		}
	};

	const ajax = new Ajax(false);
	return ajax.action('admin/upgrade', options);
}

/**
 * Displays estimated amount of time needed for a single upgrade to finish
 */
function updateCounter() {
	const now = new Date().getTime();

	// How many milliseconds ago the last batch was started
	const difference = (now - upgradeStartTime) / 1000;

	// How many items are waiting to be processed
	const unProcessed = total - numProcessed;

	let timeLeft = Math.round((difference / numProcessed) * unProcessed);
	let hours = '00';
	let minutes = '00';
	let seconds;

	if (timeLeft < 60) {
		seconds = timeLeft;
	} else if (timeLeft < 3600) {
		minutes = Math.floor(timeLeft / 60);
		seconds = timeLeft % 60;
		hours = '00';
	} else {
		hours = Math.floor(timeLeft / 3600);
		timeLeft = timeLeft % 3600;
		minutes = Math.floor(timeLeft / 60);
		seconds = timeLeft % 60;
	}

	hours = formatDigits(hours);
	minutes = formatDigits(minutes);
	seconds = formatDigits(seconds);

	timer.html(hours + ':' + minutes + ':' + seconds);
}

/**
 * Rounds hours, minutes or seconds and adds a leading zero if necessary
 *
 * @param {String} time
 *
 * @return {String} time
 */
function formatDigits(time) {
	time = Math.floor(parseInt(time));

	if (time < 1) {
		return '00';
	}

	if (time < 10) {
		return '0' + time;
	}

	return time;
}

// Display the button only if there are pending upgrades
if ($('.elgg-item-object-elgg_upgrade').length) {
	$('#elgg-upgrades-run').removeClass('hidden').click(run);
}

$(document).on('click', '.elgg-menu-item-run-upgrade > a', runSingle);

upgrades.each(function(key, value) {
	// Initialize progressbar
	$(value).find('.elgg-progressbar').progressbar();
});
