/**
 * Ajax lists - append behaviour with auto click if next navigation scrolls into window
 */
define(['jquery', 'page/components/list/ajax-append'], function ($) {
	
	var debounceTimeout;
	
	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	var debounce = function (func, wait, immediate) {
		return function() {
			var context = this, args = arguments;
			var later = function() {
				debounceTimeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(debounceTimeout);
			debounceTimeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};
	
	var checkScroll = function() {
		var $elem = $('.elgg-list-container-ajax-append-auto .elgg-pagination-next > a:visible');
		if (!$elem.length) {
			return;
		}
		
		var docViewTop = $(window).scrollTop();
		var docViewBottom = docViewTop + $(window).height() + 500; // make screen larger to prevent button from coming into view

		var elemTop = $elem.offset().top;
		var elemBottom = elemTop + $elem.height();

		if ((elemBottom <= docViewBottom) && (elemTop >= docViewTop)) {
			$elem.click();
		}
	};
	
	$(document).off('scroll.ajax-append-auto');
	$(document).on('scroll.ajax-append-auto', debounce(checkScroll, 100));
});
