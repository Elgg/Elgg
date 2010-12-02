<?php echo $vars['body']; ?>

<script type="text/javascript">
$(document).ready(function(){
	var updates = new activityUpdateChecker(10000);
	updates.start();
});

// check for updates on the wire.
function activityUpdateChecker(interval) {
	this.intervalID = null;
	this.interval = interval;
	this.url = '<?php echo elgg_get_site_url(); ?>mod/riverdashboard/endpoint/ping.php';
	this.seconds_passed = 0;

	this.start = function() {
		// needed to complete closure scope.
		var self = this;

		this.intervalID = setInterval(function() { self.checkUpdates(); }, this.interval);
	}

	this.checkUpdates = function() {
		this.seconds_passed += this.interval / 1000;
		// more closure fun
		var self = this;
		$.ajax({
			'type': 'GET',
			'url': this.url,
			'data': {'seconds_passed': this.seconds_passed},
			'success': function(data) {
				if (data) {
					$('#riverdashboard-updates').html(data).slideDown();
					// could crank down the interval here.
					// if we change the message to simply "New Posts!"
					// we could stop the polling altogether.
				}
			}
		})
	}

	this.stop = function() {
		clearInterval(this.interval);
	}

	this.changeInterval = function(interval) {
		this.stop();
		this.interval = interval;
		this.start();
	}
}
</script>
