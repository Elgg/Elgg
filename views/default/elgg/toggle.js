define(['jquery'], function ($) {
	/**
	 * Toggles an element based on clicking a separate element
	 *
	 * Use class="elgg-toggle" on the toggler element
	 * Set the href to target the item you want to toggle (<a class="elgg-toggle" href="#id-of-target">)
	 * or use data-toggle-selector="your_jquery_selector" to have an advanced selection method
	 *
	 * By default elements perform a slideToggle.
	 * If you want a normal toggle (hide/show) you can add data-toggle-slide="0" on the elements to prevent a slide.
	 *
	 * @param {Object} event
	 * @return void
	 */
	function toggle(event) {
		event.preventDefault();
		
		var $this = $(this);
	
		var selector = $this.data().toggleSelector;
		if (!selector) {
			selector = $this.attr('href');
		}
	
		var $elements = $(selector);
	
		$this.toggleClass('elgg-state-active');
	
		$elements.each(function(index, elem) {
			var $elem = $(elem);
			if ($elem.data().toggleSlide != false) {
				$elem.slideToggle('medium');
			} else {
				$elem.toggle();
			}
		});
	
		$this.trigger('elgg_ui_toggle', [{
			$toggled_elements: $elements
		}]);
	};
	
	$(document).on('click', '.elgg-toggle', toggle);
});
