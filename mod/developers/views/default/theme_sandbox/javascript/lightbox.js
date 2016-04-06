define(function(require) {
	var lightbox = require('elgg/lightbox');
	var opts = {
		photo: true,
		width: 600
	};
	lightbox.bind('[rel="lightbox-gallery"]', opts, false);
});