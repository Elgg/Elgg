define(function(require) {
	// append the title to the url
	var title = document.title;
	var e = $('a.elgg-bookmark-page');
	var link = e.attr('href') + '&title=' + encodeURIComponent(title);
	e.attr('href', link);
});

