define(['jquery', 'tagify/tagify.min'], function($, Tagify) {
	var tags = {
		/**
		 * Initialize Tagify on elements defined by the selector
		 *
		 * You can pass additional options for Tagify in data-tagify-opts on the elements
		 *
		 * @param {string} selector Element selector
		 * @return void
		 */
		init: function (selector) {
			if (!$(selector).length) {
				return;
			}
			
			var defaults = {
				originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
			};

			$(selector).each(function (index, elem) {
				var opts = $(elem).data('tagifyOpts') || {};
				opts = $.extend({}, defaults, opts);
				
				new Tagify(elem, opts);
			});
		}
	};

	return tags;
});
