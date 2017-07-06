define(function(require) {
	require('elgg/ready');
	
	var lightbox = require('elgg/lightbox');
	var opts = {
		photo: true,
		width: 600
	};
	
	lightbox.bind('[rel="lightbox-gallery"]', opts, false);
	
	$(document).on('click', '#elgg-lightbox-test-resize', function(event) {
		event.preventDefault();
		$body = $('#elgg-lightbox-test').find('.elgg-body');
		$body.append($body.html());
		
		lightbox.resize();
	});
});
