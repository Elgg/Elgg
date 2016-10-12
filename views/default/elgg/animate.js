define(function (require) {
	require('jquery');
	return function ($elem, animation, callback) {
		var event = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		$elem.addClass('animated ' + animation).one(event, function () {
			$elem.removeClass('animated ' + animation);
			if (typeof callback === 'function') {
				callback.call(null, $elem);
			}
		});
	};
});