/**
 * Lightbox initialization
 * @module elgg/ui/lightbox
 */
define('elgg/ui/lightbox', ['elgg', 'elgg/lightbox', 'elgg/init'], function (elgg, lightbox) {

	lightbox.bind(".elgg-lightbox");
	lightbox.bind(".elgg-lightbox-photo", {photo: true});
	lightbox.bind(".elgg-lightbox-inline", {inline: true});
	lightbox.bind(".elgg-lightbox-iframe", {iframe: true});

	function registerDeprecationError() {
		elgg.register_error("fancybox lightbox has been replaced by colorbox", 9999999999999);
	}

	if (typeof $.fancybox === 'undefined') {
		$.fancybox = {
			// error message for firefox users
			__noSuchMethod__: registerDeprecationError,
			close: function () {
				registerDeprecationError();
				$.colorbox.close();
			}
		};
		// support $().fancybox({type:'image'})
		$.fn.fancybox = function (arg) {
			registerDeprecationError();
			if (arg.type === 'image') {
				arg.photo = true;
			}
			this.colorbox(arg);
			return this;
		};
	}
});