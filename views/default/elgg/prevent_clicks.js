/**
 * Inline (non-jQuery) script to prevent clicks on links that require some later loaded js to function
 *
 * @since 3.3
 */

var lightbox_links = document.getElementsByClassName('elgg-lightbox');

for (var i = 0; i < lightbox_links.length; i++) {
	lightbox_links[i].onclick = function () {
		return false;
	};
}

// @todo: in Elgg 5.0 remove the rel="toggle" related code and just leave the class="elgg-toggle"
var toggle_links = document.querySelectorAll('a[rel="toggle"]');

for (var i = 0; i < toggle_links.length; i++) {
	toggle_links[i].onclick = function () {
		return false;
	};
}

var toggle_links = document.querySelectorAll('a[class*="elgg-toggle"]');

for (var i = 0; i < toggle_links.length; i++) {
	toggle_links[i].onclick = function () {
		return false;
	};
}

