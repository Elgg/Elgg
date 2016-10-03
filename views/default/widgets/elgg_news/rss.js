define(['jquery', 'elgg'], function ($, elgg) {
	
	return function(selector, feed_url) {
		var $wrapper = $(selector);
		var wrapper_id = $wrapper.attr("id");
		$wrapper.empty();
		
		var config = $wrapper.data();
		
		var feed_url = config.feedUrl;
		var limit = config.limit;

		$.getJSON(
			"//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=" + limit + "&output=json_xml&q=" + encodeURIComponent(feed_url) + "&hl=en&callback=?",
			function (data) {
				if (data.responseData) {
					var xmlDoc = $.parseXML(data.responseData.xmlString);
					var feed = data.responseData.feed;
					
					var $items = $(xmlDoc).find("item");
					
					var s = "";
					
					s += "<ul class='elgg-list'>";
					$.each(feed.entries, function (index, item) {
						s += "<li class='clearfix elgg-item'>";
						var description = item.content.replace(/(<([^>]+)>)/ig,"");

						s += "<div class='pbm'>";
						s += "<div><a href='" + item.link + "' target='_blank'";
		
						s += ">" + item.title + "</a></div>";
						
						var i = new Date(item.publishedDate);
						s += "<div class='elgg-subtext'>" + i.toLocaleDateString() + "</div>";

						s += "</div>";
	
						s += "</li>";
					});
					s += "</ul>";
					
					$wrapper.replaceWith(s);
				} else {
					$wrapper.append(data.responseDetails);
				}
			}
		);
	};
});