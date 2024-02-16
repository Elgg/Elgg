import 'jquery';
import lightbox from 'elgg/lightbox';

lightbox.bind('[rel="lightbox-gallery"]', {
	photo: true,
	width: 600
}, false);

$(document).on('click', '#elgg-lightbox-test-resize', function(event) {
	event.preventDefault();
	var $body = $('#elgg-lightbox-test').find('.elgg-body');
	$body.append($body.html());
	
	lightbox.resize();
});
