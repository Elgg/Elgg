/**
 * By default, this delivers messages from `elgg()->channels->sendMessage()` to the
 * client-side plugin hook ["channels:message", "<channel_name>"]
 */
define(function (require) {
	var elgg = require('elgg');
	var	$ = require('jquery');

	var URL = elgg.get_site_url() + 'channel-messages.php',

		delay = 10000,
		// when we receive a change, we decrease delay to minDelay and slowly increase it to targetDelay
		targetDelay = 60000,
		minDelay = 3000,
		waiting = false,
		stopped = true,

		/**
		 * @type {Channel[]}
		 */
		channels = {};

	function Channel(id, name, mac, last_message_id) {
		this.id = id;
		this.name = name;
		this.last_message_id = last_message_id;

		this.getRequestString = function () {
			return id + ',' + mac + ',' + this.last_message_id;
		};
	}

	function start() {
		if (stopped) {
			stopped = false;
			scheduleNextRequest();
		}
	}

	function throttleUp() {
		delay = minDelay;
	}

	function scheduleNextRequest() {
		setTimeout(makeRequest, delay);

		var delta = (targetDelay - delay);
		delay += delta * .1;
	}

	///**
	// * Set the maximum expected delay (in ms) between polling requests
	// *
	// * @param {Number} suggestedDelay (ms)
	// */
	//function suggestDelay(suggestedDelay) {
	//	suggestedDelay = Math.max(minDelay, suggestedDelay);
	//	targetDelay = Math.min(targetDelay, suggestedDelay);
	//}

	function makeRequest() {
		if (waiting) {
			setTimeout(makeRequest, 1000);
			return;
		}

		var data = {channels:[]};
		$.each(channels, function (i, channel) {
			data.channels.push(channel.getRequestString());
		});

		waiting = true;
		$.ajax({
			url: URL,
			data: data,
			method: 'POST',
			dataType: 'json'
		}).done(function (data) {
			waiting = false;
			var has_new_messages = false;

			$.each(data, function (i, obj) {
				if (!channels['id' + obj.id]) {
					return;
				}

				var channel = channels['id' + obj.id];
				$.each(obj.messages, function (i, msg) {
					if (msg[0] > channel.last_message_id) {
						var message = new Message(channel.name, msg[1]);
						elgg.trigger_hook('channels:message', channel.name, null, message);
						channel.last_message_id = msg[0];
						has_new_messages = true;
					}
				});
			});

			if (has_new_messages) {
				throttleUp();
			}

			if (!stopped) {
				scheduleNextRequest();
			}
		});
	}

	/**
	 * A message from a channel
	 *
	 * @param {String} channel
	 * @param {*} data
	 * @constructor
	 */
	function Message(channel, data) {
		/**
		 * @type {String} Channel name
		 */
		this.channel = channel;

		/**
		 * @type {*} Message data
		 */
		this.data = data;
	}

	if (elgg.data.elgg_channels) {
		// page has called setupChannel()
		$.each(elgg.data.elgg_channels.channels, function (i, obj) {
			channels["id" + obj.id] = new Channel(obj.id, obj.name, obj.mac, obj.last_message_id);
		});

		start();
	}

	elgg.register_hook_handler('channels:message', 'all', function (h, t, p, v) {
		console.log('New message:', v);
	});

	return {};
});
