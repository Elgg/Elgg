/*
 * jQuery UI Droppable
 *
 * Copyright (c) 2008 Paul Bakaus
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 * 
 * http://docs.jquery.com/UI/Droppables
 *
 * Depends:
 *   ui.base.js
 *   ui.draggable.js
 *
 * Revision: $Id: ui.droppable.js 5198 2008-04-04 13:06:40Z paul.bakaus $
 */
;(function($) {
	
	$.fn.extend({
		droppable: function(options) {
			var args = Array.prototype.slice.call(arguments, 1);
			
			return this.each(function() {
				if (typeof options == "string") {
					var drop = $.data(this, "droppable");
					if(drop) drop[options].apply(drop, args);
					
				} else if(!$.data(this, "droppable"))
					new $.ui.droppable(this, options);
			});
		}
	});
	
	$.ui.droppable = function(element, options) {
		
		//Initialize needed constants
		var instance = this;
		this.element = $(element);
		$.data(element, "droppable", this);
		this.element.addClass("ui-droppable");
		
		//Prepare the passed options
		var o = this.options = options = $.extend({}, $.ui.droppable.defaults, options);
		var accept = o.accept;
		o = $.extend(o, {
			accept: o.accept && o.accept.constructor == Function ? o.accept : function(d) {
				return $(d).is(accept);
			}
		});
		
		$(element).bind("setData.droppable", function(event, key, value){
			o[key] = value;
		}).bind("getData.droppable", function(event, key){
			return o[key];
		}).bind('remove', function() {
			instance.destroy();
		});
		
		//Store the droppable's proportions
		this.proportions = { width: this.element.outerWidth(), height: this.element.outerHeight() };
		
		// Add the reference and positions to the manager
		$.ui.ddmanager.droppables.push({ item: this, over: 0, out: 1 });
		
	};
	
	$.extend($.ui.droppable, {
		defaults: {
			disabled: false,
			tolerance: 'intersect'
		}
	});
	
	$.extend($.ui.droppable.prototype, {
		plugins: {},
		ui: function(c) {
			return {
				instance: this,
				draggable: (c.currentItem || c.element),
				helper: c.helper,
				position: c.position,
				absolutePosition: c.positionAbs,
				options: this.options,
				element: this.element
			};
		},
		destroy: function() {
			var drop = $.ui.ddmanager.droppables;
			for ( var i = 0; i < drop.length; i++ )
				if ( drop[i].item == this )
					drop.splice(i, 1);
			
			this.element
				.removeClass("ui-droppable ui-droppable-disabled")
				.removeData("droppable")
				.unbind(".droppable");
		},
		enable: function() {
			this.element.removeClass("ui-droppable-disabled");
			this.options.disabled = false;
		},
		disable: function() {
			this.element.addClass("ui-droppable-disabled");
			this.options.disabled = true;
		},
		over: function(e) {
			
			var draggable = $.ui.ddmanager.current;
			if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return; // Bail if draggable and droppable are same element
			
			if (this.options.accept.call(this.element,(draggable.currentItem || draggable.element))) {
				$.ui.plugin.call(this, 'over', [e, this.ui(draggable)]);
				this.element.triggerHandler("dropover", [e, this.ui(draggable)], this.options.over);
			}
			
		},
		out: function(e) {
			
			var draggable = $.ui.ddmanager.current;
			if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return; // Bail if draggable and droppable are same element
			
			if (this.options.accept.call(this.element,(draggable.currentItem || draggable.element))) {
				$.ui.plugin.call(this, 'out', [e, this.ui(draggable)]);
				this.element.triggerHandler("dropout", [e, this.ui(draggable)], this.options.out);
			}
			
		},
		drop: function(e,custom) {
			
			var draggable = custom || $.ui.ddmanager.current;
			if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return; // Bail if draggable and droppable are same element
			
			var childrenIntersection = false;
			this.element.find(".ui-droppable").each(function() {
				var inst = $.data(this, 'droppable');
				if(inst.options.greedy && $.ui.intersect(draggable, { item: inst, offset: inst.element.offset() }, inst.options.tolerance)) {
					childrenIntersection = true; return false;
				}
			});
			if(childrenIntersection) return;
			
			if(this.options.accept.call(this.element,(draggable.currentItem || draggable.element))) {
				$.ui.plugin.call(this, 'drop', [e, this.ui(draggable)]);
				this.element.triggerHandler("drop", [e, this.ui(draggable)], this.options.drop);
			}
			
		},
		activate: function(e) {
			
			var draggable = $.ui.ddmanager.current;
			$.ui.plugin.call(this, 'activate', [e, this.ui(draggable)]);
			if(draggable) this.element.triggerHandler("dropactivate", [e, this.ui(draggable)], this.options.activate);
			
		},
		deactivate: function(e) {
			
			var draggable = $.ui.ddmanager.current;
			$.ui.plugin.call(this, 'deactivate', [e, this.ui(draggable)]);
			if(draggable) this.element.triggerHandler("dropdeactivate", [e, this.ui(draggable)], this.options.deactivate);
			
		}
	});
	
	$.ui.intersect = function(draggable, droppable, toleranceMode) {
		
		if (!droppable.offset) return false;
		
		var x1 = (draggable.positionAbs || draggable.position.absolute).left, x2 = x1 + draggable.helperProportions.width,
		    y1 = (draggable.positionAbs || draggable.position.absolute).top, y2 = y1 + draggable.helperProportions.height;
		var l = droppable.offset.left, r = l + droppable.item.proportions.width,
		    t = droppable.offset.top,  b = t + droppable.item.proportions.height;
		
		switch (toleranceMode) {
			case 'fit':
				
				if(!((y2-(draggable.helperProportions.height/2) > t && y1 < t) || (y1 < b && y2 > b) || (x2 > l && x1 < l) || (x1 < r && x2 > r))) return false;
				
				if(y2-(draggable.helperProportions.height/2) > t && y1 < t) return 1; //Crosses top edge
				if(y1 < b && y2 > b) return 2; //Crosses bottom edge
				if(x2 > l && x1 < l) return 1; //Crosses left edge
				if(x1 < r && x2 > r) return 2; //Crosses right edge
				
				//return (   l < x1 && x2 < r
				//	&& t < y1 && y2 < b);
				break;
			case 'intersect':
				return (   l < x1 + (draggable.helperProportions.width  / 2)    // Right Half
					&&     x2 - (draggable.helperProportions.width  / 2) < r    // Left Half
					&& t < y1 + (draggable.helperProportions.height / 2)        // Bottom Half
					&&     y2 - (draggable.helperProportions.height / 2) < b ); // Top Half
				break;
			case 'pointer':
				return (   l < ((draggable.positionAbs || draggable.position.absolute).left + draggable.clickOffset.left) && ((draggable.positionAbs || draggable.position.absolute).left + draggable.clickOffset.left) < r
					&& t < ((draggable.positionAbs || draggable.position.absolute).top + draggable.clickOffset.top) && ((draggable.positionAbs || draggable.position.absolute).top + draggable.clickOffset.top) < b);
				break;
			case 'touch':
				return ( (y1 >= t && y1 <= b) ||	// Top edge touching
						 (y2 >= t && y2 <= b) ||	// Bottom edge touching
						 (y1 < t && y2 > b)		// Surrounded vertically
						 ) && (
						 (x1 >= l && x1 <= r) ||	// Left edge touching
						 (x2 >= l && x2 <= r) ||	// Right edge touching
						 (x1 < l && x2 > r)		// Surrounded horizontally
						);
				break;
			default:
				return false;
				break;
			}
		
	};
	
	/*
		This manager tracks offsets of draggables and droppables
	*/
	$.ui.ddmanager = {
		current: null,
		droppables: [],
		prepareOffsets: function(t, e) {
			
			var m = $.ui.ddmanager.droppables;
			var type = e ? e.type : null; // workaround for #2317
			for (var i = 0; i < m.length; i++) {
				
				if(m[i].item.options.disabled || (t && !m[i].item.options.accept.call(m[i].item.element,(t.currentItem || t.element)))) continue;
				m[i].offset = $(m[i].item.element).offset();
				m[i].item.proportions = { width: m[i].item.element.outerWidth(), height: m[i].item.element.outerHeight() };
				
				if(type == "dragstart") m[i].item.activate.call(m[i].item, e); //Activate the droppable if used directly from draggables
			}
			
		},
		drop: function(draggable, e) {
			
			$.each($.ui.ddmanager.droppables, function() {
				
				if (!this.item.options.disabled && $.ui.intersect(draggable, this, this.item.options.tolerance))
					this.item.drop.call(this.item, e);
				
				if (!this.item.options.disabled && this.item.options.accept.call(this.item.element,(draggable.currentItem || draggable.element))) {
					this.out = 1; this.over = 0;
					this.item.deactivate.call(this.item, e);
				}
				
			});
			
		},
		drag: function(draggable, e) {
			
			//If you have a highly dynamic page, you might try this option. It renders positions every time you move the mouse.
			if(draggable.options.refreshPositions) $.ui.ddmanager.prepareOffsets(draggable, e);
			
			//Run through all droppables and check their positions based on specific tolerance options
			$.each($.ui.ddmanager.droppables, function() {
				
				if(this.item.disabled || this.greedyChild) return;
				var intersects = $.ui.intersect(draggable, this, this.item.options.tolerance);
				
				var c = !intersects && this.over == 1 ? 'out' : (intersects && this.over == 0 ? 'over' : null);
				if(!c) return;
				
				var instance = $.data(this.item.element[0], 'droppable');
				if (instance.options.greedy) {
					this.item.element.parents('.ui-droppable:eq(0)').each(function() {
						var parent = this;
						$.each($.ui.ddmanager.droppables, function() {
							if (this.item.element[0] != parent) return;
							this[c] = 0;
							this[c == 'out' ? 'over' : 'out'] = 1;
							this.greedyChild = (c == 'over' ? 1 : 0);
							this.item[c == 'out' ? 'over' : 'out'].call(this.item, e);
							return false;
						});
					});
				}
				
				this[c] = 1; this[c == 'out' ? 'over' : 'out'] = 0;
				this.item[c].call(this.item, e);
				
			});
			
		}
	};
	
/*
 * Droppable Extensions
 */
	
	$.ui.plugin.add("droppable", "activeClass", {
		activate: function(e, ui) {
			$(this).addClass(ui.options.activeClass);
		},
		deactivate: function(e, ui) {
			$(this).removeClass(ui.options.activeClass);
		},
		drop: function(e, ui) {
			$(this).removeClass(ui.options.activeClass);
		}
	});
	
	$.ui.plugin.add("droppable", "hoverClass", {
		over: function(e, ui) {
			$(this).addClass(ui.options.hoverClass);
		},
		out: function(e, ui) {
			$(this).removeClass(ui.options.hoverClass);
		},
		drop: function(e, ui) {
			$(this).removeClass(ui.options.hoverClass);
		}
	});
	
})(jQuery);
