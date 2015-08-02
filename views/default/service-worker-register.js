define(function(require) {
	var elgg = require('elgg');
	
	navigator.serviceWorker.register(elgg.get_site_url() + "service-worker.js");
});
