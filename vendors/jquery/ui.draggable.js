/*
 * jQuery UI Draggable
 *
 * Copyright (c) 2008 Paul Bakaus
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 * 
 * http://docs.jquery.com/UI/Draggables
 *
 * Depends:
 *   ui.base.js
 *
 * Revision: $Id: ui.draggable.js 5194 2008-04-04 12:30:10Z paul.bakaus $
 */
;(function($) {

	$.fn.extend({
		draggable: function(options) {
			var args = Array.prototype.slice.call(arguments, 1);
			
			return this.each(function() {
				if (typeof options == "string") {
					var drag = $.data(this, "draggable");
					if(drag) drag[options].apply(drag, args);

				} else if(!$.data(this, "draggable"))
					new $.ui.draggable(this, options);
			});
		}
	});
	
	$.ui.draggable = function(element, options) {
		//Initialize needed constants
		var self = this;
		
		this.element = $(element);
		
		$.data(element, "draggable", this);
		this.element.addClass("ui-draggable");
		
		//Prepare the passed options
		this.options = $.extend({}, options);
		var o = this.options;
		$.extend(o, {
			helper: o.ghosting == true ? 'clone' : (o.helper || 'original'),
			handle : o.handle ? ($(o.handle, element)[0] ? $(o.handle, element) : this.element) : this.element,
			appendTo: o.appendTo || 'parent'		
		});
		
		$(element).bind("setData.draggable", function(event, key, value){
			self.options[key] = value;
		}).bind("getData.draggable", function(event, key){
			return self.options[key];
		});
		
		//Initialize mouse events for interaction
		$(o.handle).mouseInteraction({
			executor: this,
			delay: o.delay,
			distance: o.distance || 1,
			dragPrevention: o.cancel || o.cancel === '' ? o.cancel.toLowerCase().split(',') : ['input','textarea','button','select','option'],
			start: this.start,
			stop: this.stop,
			drag: this.drag,
			condition: function(e) { return !(e.target.className.indexOf("ui-resizable-handle") != -1 || this.options.disabled); }
		});
		
		//Position the node
		if(o.helper == 'original' && (this.element.css('position') == 'static' || this.element.css('position') == ''))
			this.element.css('position', 'relative');
			
		//Prepare cursorAt
		if(o.cursorAt && o.cursorAt.constructor == Array)
			o.cursorAt = { left: o.cursorAt[0], top: o.cursorAt[1] };
		
	};
	
	$.extend($.ui.draggable.prototype, {
		plugins: {},
		ui: function(e) {
			return {
				helper: this.helper,
				position: this.position,
				absolutePosition: this.positionAbs,
				instance: this,
				options: this.options,
				element: this.element				
			};
		},
		propagate: function(n,e) {
			$.ui.plugin.call(this, n, [e, this.ui()]);
			return this.element.triggerHandler(n == "drag" ? n : "drag"+n, [e, this.ui()], this.options[n]);
		},
		destroy: function() {
			if(!$.data(this.element[0], 'draggable')) return;
			this.options.handle.removeMouseInteraction();
			this.element
				.removeClass("ui-draggable ui-draggable-disabled")
				.removeData("draggable")
				.unbind(".draggable");
		},
		enable: function() {
			this.element.removeClass("ui-draggable-disabled");
			this.options.disabled = false;
		},
		disable: function() {
			this.element.addClass("ui-draggable-disabled");
			this.options.disabled = true;
		},
		setContrains: function(minLeft,maxLeft,minTop,maxTop) {
			this.minLeft = minLeft; this.maxLeft = maxLeft;
			this.minTop = minTop; this.maxTop = maxTop;
			this.constrainsSet = true;
		},
		checkConstrains: function() {
			if(!this.constrainsSet) return;
			if(this.position.left < this.minLeft) this.position.left = this.minLeft;
			if(this.position.left > this.maxLeft - this.helperProportions.width) this.position.left = this.maxLeft - this.helperProportions.width;
			if(this.position.top < this.minTop) this.position.top = this.minTop;
			if(this.position.top > this.maxTop - this.helperProportions.height) this.position.top = this.maxTop - this.helperProportions.height;
		},
		recallOffset: function(e) {

			var elementPosition = { left: this.elementOffset.left - this.offsetParentOffset.left, top: this.elementOffset.top - this.offsetParentOffset.top };
			var r = this.helper.css('position') == 'relative';

			//Generate the original position
			this.originalPosition = {
				left: (r ? parseInt(this.helper.css('left'),10) || 0 : elementPosition.left + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollLeft)),
				top: (r ? parseInt(this.helper.css('top'),10) || 0 : elementPosition.top + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollTop))
			};
			
			//Generate a flexible offset that will later be subtracted from e.pageX/Y
			this.offset = {left: this._pageX - this.originalPosition.left, top: this._pageY - this.originalPosition.top };
			
		},
		start: function(e) {
			var o = this.options;
			if($.ui.ddmanager) $.ui.ddmanager.current = this;
			
			//Create and append the visible helper
			this.helper = typeof o.helper == 'function' ? $(o.helper.apply(this.element[0], [e])) : (o.helper == 'clone' ? this.element.clone().appendTo((o.appendTo == 'parent' ? this.element[0].parentNode : o.appendTo)) : this.element);
			if(this.helper[0] != this.element[0]) this.helper.css('position', 'absolute');
			if(!this.helper.parents('body').length) this.helper.appendTo((o.appendTo == 'parent' ? this.element[0].parentNode : o.appendTo));
			
			
			//Find out the next positioned parent
			this.offsetParent = (function(cp) {
				while(cp) {
					if(cp.style && (/(absolute|relative|fixed)/).test($.css(cp,'position'))) return $(cp);
					cp = cp.parentNode ? cp.parentNode : null;
				}; return $("body");		
			})(this.helper[0].parentNode);
			
			//Prepare variables for position generation
			this.elementOffset = this.element.offset();
			this.offsetParentOffset = this.offsetParent.offset();
			var elementPosition = { left: this.elementOffset.left - this.offsetParentOffset.left, top: this.elementOffset.top - this.offsetParentOffset.top };
			this._pageX = e.pageX; this._pageY = e.pageY;
			this.clickOffset = { left: e.pageX - this.elementOffset.left, top: e.pageY - this.elementOffset.top };
			var r = this.helper.css('position') == 'relative';

			//Generate the original position
			this.originalPosition = {
				left: (r ? parseInt(this.helper.css('left'),10) || 0 : elementPosition.left + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollLeft)),
				top: (r ? parseInt(this.helper.css('top'),10) || 0 : elementPosition.top + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollTop))
			};
			
			//If we have a fixed element, we must subtract the scroll offset again
			if(this.element.css('position') == 'fixed') {
				this.originalPosition.top -= this.offsetParent[0] == document.body ? $(document).scrollTop() : this.offsetParent[0].scrollTop;
				this.originalPosition.left -= this.offsetParent[0] == document.body ? $(document).scrollLeft() : this.offsetParent[0].scrollLeft;
			}
			
			//Generate a flexible offset that will later be subtracted from e.pageX/Y
			this.offset = {left: e.pageX - this.originalPosition.left, top: e.pageY - this.originalPosition.top };
			
			//Substract margins
			if(this.element[0] != this.helper[0]) {
				this.offset.left += parseInt(this.element.css('marginLeft'),10) || 0;
				this.offset.top += parseInt(this.element.css('marginTop'),10) || 0;
			}
			
			//Call plugins and callbacks
			this.propagate("start", e);

			this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
			if ($.ui.ddmanager && !o.dropBehaviour) $.ui.ddmanager.prepareOffsets(this, e);
			
			//If we have something in cursorAt, we'll use it
			if(o.cursorAt) {
				if(o.cursorAt.top != undefined || o.cursorAt.bottom != undefined) {
					this.offset.top -= this.clickOffset.top - (o.cursorAt.top != undefined ? o.cursorAt.top : (this.helperProportions.height - o.cursorAt.bottom));
					this.clickOffset.top = (o.cursorAt.top != undefined ? o.cursorAt.top : (this.helperProportions.height - o.cursorAt.bottom));
				}
				if(o.cursorAt.left != undefined || o.cursorAt.right != undefined) {
					this.offset.left -= this.clickOffset.left - (o.cursorAt.left != undefined ? o.cursorAt.left : (this.helperProportions.width - o.cursorAt.right));
					this.clickOffset.left = (o.cursorAt.left != undefined ? o.cursorAt.left : (this.helperProportions.width - o.cursorAt.right));
				}
			}

			return false;

		},
		clear: function() {
			if($.ui.ddmanager) $.ui.ddmanager.current = null;
			this.helper = null;
		},
		stop: function(e) {

			//If we are using droppables, inform the manager about the drop
			if ($.ui.ddmanager && !this.options.dropBehaviour)
				$.ui.ddmanager.drop(this, e);
				
			//Call plugins and trigger callbacks
			this.propagate("stop", e);
			
			if(this.cancelHelperRemoval) return false;			
			if(this.options.helper != 'original') this.helper.remove();
			this.clear();

			return false;
		},
		drag: function(e) {

			//Compute the helpers position
			this.position = { top: e.pageY - this.offset.top, left: e.pageX - this.offset.left };
			this.positionAbs = { left: e.pageX - this.clickOffset.left, top: e.pageY - this.clickOffset.top };

			//Call plugins and callbacks
			this.checkConstrains();			
			this.position = this.propagate("drag", e) || this.position;
			this.checkConstrains();
			
			$(this.helper).css({ left: this.position.left+'px', top: this.position.top+'px' }); // Stick the helper to the cursor
			if($.ui.ddmanager) $.ui.ddmanager.drag(this, e);
			return false;
			
		}
	});
	
/*
 * Draggable Extensions
 */
	 
	$.ui.plugin.add("draggable", "cursor", {
		start: function(e, ui) {
			var t = $('body');
			if (t.css("cursor")) ui.options._cursor = t.css("cursor");
			t.css("cursor", ui.options.cursor);
		},
		stop: function(e, ui) {
			if (ui.options._cursor) $('body').css("cursor", ui.options._cursor);
		}
	});

	$.ui.plugin.add("draggable", "zIndex", {
		start: function(e, ui) {
			var t = $(ui.helper);
			if(t.css("zIndex")) ui.options._zIndex = t.css("zIndex");
			t.css('zIndex', ui.options.zIndex);
		},
		stop: function(e, ui) {
			if(ui.options._zIndex) $(ui.helper).css('zIndex', ui.options._zIndex);
		}
	});

	$.ui.plugin.add("draggable", "opacity", {
		start: function(e, ui) {
			var t = $(ui.helper);
			if(t.css("opacity")) ui.options._opacity = t.css("opacity");
			t.css('opacity', ui.options.opacity);
		},
		stop: function(e, ui) {
			if(ui.options._opacity) $(ui.helper).css('opacity', ui.options._opacity);
		}
	});


	$.ui.plugin.add("draggable", "revert", {
		stop: function(e, ui) {
			var self = ui.instance, helper = $(self.helper);
			self.cancelHelperRemoval = true;
			
			$(ui.helper).animate({ left: self.originalPosition.left, top: self.originalPosition.top }, parseInt(ui.options.revert, 10) || 500, function() {
				if(ui.options.helper != 'original') helper.remove();
				if (!helper) self.clear();
			});
		}
	});

	$.ui.plugin.add("draggable", "iframeFix", {
		start: function(e, ui) {

			var o = ui.options;
			if(ui.instance.slowMode) return; // Make clones on top of iframes (only if we are not in slowMode)
			
			if(o.iframeFix.constructor == Array) {
				for(var i=0;i<o.iframeFix.length;i++) {
					var co = $(o.iframeFix[i]).offset({ border: false });
					$('<div class="DragDropIframeFix"" style="background: #fff;"></div>').css("width", $(o.iframeFix[i])[0].offsetWidth+"px").css("height", $(o.iframeFix[i])[0].offsetHeight+"px").css("position", "absolute").css("opacity", "0.001").css("z-index", "1000").css("top", co.top+"px").css("left", co.left+"px").appendTo("body");
				}		
			} else {
				$("iframe").each(function() {					
					var co = $(this).offset({ border: false });
					$('<div class="DragDropIframeFix" style="background: #fff;"></div>').css("width", this.offsetWidth+"px").css("height", this.offsetHeight+"px").css("position", "absolute").css("opacity", "0.001").css("z-index", "1000").css("top", co.top+"px").css("left", co.left+"px").appendTo("body");
				});							
			}

		},
		stop: function(e, ui) {
			if(ui.options.iframeFix) $("div.DragDropIframeFix").each(function() { this.parentNode.removeChild(this); }); //Remove frame helpers	
		}
	});
	
	$.ui.plugin.add("draggable", "containment", {
		start: function(e, ui) {

			var o = ui.options;
			var self = ui.instance;
			if((o.containment.left != undefined || o.containment.constructor == Array) && !o._containment) return;
			if(!o._containment) o._containment = o.containment;

			if(o._containment == 'parent') o._containment = this[0].parentNode;
			if(o._containment == 'document') {
				o.containment = [
					0,
					0,
					$(document).width(),
					($(document).height() || document.body.parentNode.scrollHeight)
				];
			} else { //I'm a node, so compute top/left/right/bottom

				var ce = $(o._containment)[0];
				var co = $(o._containment).offset();

				o.containment = [
					co.left,
					co.top,
					co.left+(ce.offsetWidth || ce.scrollWidth),
					co.top+(ce.offsetHeight || ce.scrollHeight)
				];
			}
			
			var c = o.containment;
			ui.instance.setContrains(
				c[0] - (self.offset.left - self.clickOffset.left), //min left
				c[2] - (self.offset.left - self.clickOffset.left), //max left
				c[1] - (self.offset.top - self.clickOffset.top), //min top
				c[3] - (self.offset.top - self.clickOffset.top) //max top
			);

		}
	});

	$.ui.plugin.add("draggable", "grid", {
		drag: function(e, ui) {
			var o = ui.options;
			var newLeft = ui.instance.originalPosition.left + Math.round((e.pageX - ui.instance._pageX) / o.grid[0]) * o.grid[0];
			var newTop = ui.instance.originalPosition.top + Math.round((e.pageY - ui.instance._pageY) / o.grid[1]) * o.grid[1];
			
			ui.instance.position.left = newLeft;
			ui.instance.position.top = newTop;

		}
	});

	$.ui.plugin.add("draggable", "axis", {
		drag: function(e, ui) {
			var o = ui.options;
			if(o.constraint) o.axis = o.constraint; //Legacy check
			switch (o.axis) {
				case 'x' : ui.instance.position.top = ui.instance.originalPosition.top; break;
				case 'y' : ui.instance.position.left = ui.instance.originalPosition.left; break;
			}
		}
	});

	$.ui.plugin.add("draggable", "scroll", {
		start: function(e, ui) {
			var o = ui.options;
			o.scrollSensitivity	= o.scrollSensitivity || 20;
			o.scrollSpeed		= o.scrollSpeed || 20;

			ui.instance.overflowY = function(el) {
				do { if(/auto|scroll/.test(el.css('overflow')) || (/auto|scroll/).test(el.css('overflow-y'))) return el; el = el.parent(); } while (el[0].parentNode);
				return $(document);
			}(this);
			ui.instance.overflowX = function(el) {
				do { if(/auto|scroll/.test(el.css('overflow')) || (/auto|scroll/).test(el.css('overflow-x'))) return el; el = el.parent(); } while (el[0].parentNode);
				return $(document);
			}(this);
		},
		drag: function(e, ui) {
			
			var o = ui.options;
			var i = ui.instance;

			if(i.overflowY[0] != document && i.overflowY[0].tagName != 'HTML') {
				if(i.overflowY[0].offsetHeight - (ui.position.top - i.overflowY[0].scrollTop + i.clickOffset.top) < o.scrollSensitivity)
					i.overflowY[0].scrollTop = i.overflowY[0].scrollTop + o.scrollSpeed;
				if((ui.position.top - i.overflowY[0].scrollTop + i.clickOffset.top) < o.scrollSensitivity)
					i.overflowY[0].scrollTop = i.overflowY[0].scrollTop - o.scrollSpeed;				
			} else {
				//$(document.body).append('<p>'+(e.pageY - $(document).scrollTop())+'</p>');
				if(e.pageY - $(document).scrollTop() < o.scrollSensitivity)
					$(document).scrollTop($(document).scrollTop() - o.scrollSpeed);
				if($(window).height() - (e.pageY - $(document).scrollTop()) < o.scrollSensitivity)
					$(document).scrollTop($(document).scrollTop() + o.scrollSpeed);
			}
			
			if(i.overflowX[0] != document && i.overflowX[0].tagName != 'HTML') {
				if(i.overflowX[0].offsetWidth - (ui.position.left - i.overflowX[0].scrollLeft + i.clickOffset.left) < o.scrollSensitivity)
					i.overflowX[0].scrollLeft = i.overflowX[0].scrollLeft + o.scrollSpeed;
				if((ui.position.top - i.overflowX[0].scrollLeft + i.clickOffset.left) < o.scrollSensitivity)
					i.overflowX[0].scrollLeft = i.overflowX[0].scrollLeft - o.scrollSpeed;				
			} else {
				if(e.pageX - $(document).scrollLeft() < o.scrollSensitivity)
					$(document).scrollLeft($(document).scrollLeft() - o.scrollSpeed);
				if($(window).width() - (e.pageX - $(document).scrollLeft()) < o.scrollSensitivity)
					$(document).scrollLeft($(document).scrollLeft() + o.scrollSpeed);
			}
			
			ui.instance.recallOffset(e);

		}
	});
	
	$.ui.plugin.add("draggable", "snap", {
		start: function(e, ui) {
			
			ui.instance.snapElements = [];
			$(ui.options.snap === true ? '.ui-draggable' : ui.options.snap).each(function() {
				var $t = $(this); var $o = $t.offset();
				if(this != ui.instance.element[0]) ui.instance.snapElements.push({
					item: this,
					width: $t.outerWidth(),
					height: $t.outerHeight(),
					top: $o.top,
					left: $o.left
				});
			});
			
		},
		drag: function(e, ui) {

			var d = ui.options.snapTolerance || 20;
			var x1 = ui.absolutePosition.left, x2 = x1 + ui.instance.helperProportions.width,
			    y1 = ui.absolutePosition.top, y2 = y1 + ui.instance.helperProportions.height;

			for (var i = ui.instance.snapElements.length - 1; i >= 0; i--){

				var l = ui.instance.snapElements[i].left, r = l + ui.instance.snapElements[i].width, 
				    t = ui.instance.snapElements[i].top,  b = t + ui.instance.snapElements[i].height;

				//Yes, I know, this is insane ;)
				if(!((l-d < x1 && x1 < r+d && t-d < y1 && y1 < b+d) || (l-d < x1 && x1 < r+d && t-d < y2 && y2 < b+d) || (l-d < x2 && x2 < r+d && t-d < y1 && y1 < b+d) || (l-d < x2 && x2 < r+d && t-d < y2 && y2 < b+d))) continue;

				if(ui.options.snapMode != 'inner') {
					var ts = Math.abs(t - y2) <= 20;
					var bs = Math.abs(b - y1) <= 20;
					var ls = Math.abs(l - x2) <= 20;
					var rs = Math.abs(r - x1) <= 20;
					if(ts) ui.position.top = t - ui.instance.offset.top + ui.instance.clickOffset.top - ui.instance.helperProportions.height;
					if(bs) ui.position.top = b - ui.instance.offset.top + ui.instance.clickOffset.top;
					if(ls) ui.position.left = l - ui.instance.offset.left + ui.instance.clickOffset.left - ui.instance.helperProportions.width;
					if(rs) ui.position.left = r - ui.instance.offset.left + ui.instance.clickOffset.left;
				}
				
				if(ui.options.snapMode != 'outer') {
					var ts = Math.abs(t - y1) <= 20;
					var bs = Math.abs(b - y2) <= 20;
					var ls = Math.abs(l - x1) <= 20;
					var rs = Math.abs(r - x2) <= 20;
					if(ts) ui.position.top = t - ui.instance.offset.top + ui.instance.clickOffset.top;
					if(bs) ui.position.top = b - ui.instance.offset.top + ui.instance.clickOffset.top - ui.instance.helperProportions.height;
					if(ls) ui.position.left = l - ui.instance.offset.left + ui.instance.clickOffset.left;
					if(rs) ui.position.left = r - ui.instance.offset.left + ui.instance.clickOffset.left - ui.instance.helperProportions.width;
				}

			};
		}
	});
	
	$.ui.plugin.add("draggable", "connectToSortable", {
		start: function(e,ui) {
			ui.instance.sortable = $.data($(ui.options.connectToSortable)[0], 'sortable');
			ui.instance.sortableOffset = ui.instance.sortable.element.offset();
			ui.instance.sortableOuterWidth = ui.instance.sortable.element.outerWidth();
			ui.instance.sortableOuterHeight = ui.instance.sortable.element.outerHeight();
			if(ui.instance.sortable.options.revert) ui.instance.sortable.shouldRevert = true;
		},
		stop: function(e,ui) {
			//If we are still over the sortable, we fake the stop event of the sortable, but also remove helper
			var inst = ui.instance.sortable;
			if(inst.isOver) {
				inst.isOver = 0;
				ui.instance.cancelHelperRemoval = true; //Don't remove the helper in the draggable instance
				inst.cancelHelperRemoval = false; //Remove it in the sortable instance (so sortable plugins like revert still work)
				if(inst.shouldRevert) inst.options.revert = true; //revert here
				inst.stop(e);
				inst.options.helper = "original";
			}
		},
		drag: function(e,ui) {
			//This is handy: We reuse the intersectsWith method for checking if the current draggable helper
			//intersects with the sortable container
			var inst = ui.instance.sortable;
			ui.instance.position.absolute = ui.absolutePosition; //Sorry, this is an ugly API fix
			
			if(inst.intersectsWith.call(ui.instance, {
				left: ui.instance.sortableOffset.left, top: ui.instance.sortableOffset.top,
				width: ui.instance.sortableOuterWidth, height: ui.instance.sortableOuterHeight
			})) {
				//If it intersects, we use a little isOver variable and set it once, so our move-in stuff gets fired only once
				if(!inst.isOver) {
					inst.isOver = 1;
					
					//Cache the width/height of the new helper
					var height = inst.options.placeholderElement ? $(inst.options.placeholderElement, $(inst.options.items, inst.element)).innerHeight() : $(inst.options.items, inst.element).innerHeight();
					var width = inst.options.placeholderElement ? $(inst.options.placeholderElement, $(inst.options.items, inst.element)).innerWidth() : $(inst.options.items, inst.element).innerWidth();

					//Now we fake the start of dragging for the sortable instance,
					//by cloning the list group item, appending it to the sortable and using it as inst.currentItem
					//We can then fire the start event of the sortable with our passed browser event, and our own helper (so it doesn't create a new one)
					inst.currentItem = $(this).clone().appendTo(inst.element);
					inst.options.helper = function() { return ui.helper[0]; };
					inst.start(e);
					
					//Because the browser event is way off the new appended portlet, we modify a couple of variables to reflect the changes
					inst.clickOffset.top = ui.instance.clickOffset.top;
					inst.clickOffset.left = ui.instance.clickOffset.left;
					inst.offset.left -= ui.absolutePosition.left - inst.position.absolute.left;
					inst.offset.top -= ui.absolutePosition.top - inst.position.absolute.top;
					
					//Do a nifty little helper animation: Animate it to the portlet's size (just takes the first 'li' element in the sortable now)
					inst.helperProportions = { width:  width, height: height}; //We have to reset the helper proportions, because we are doing our animation there
					ui.helper.animate({ height: height, width: width}, 500);
					ui.instance.propagate("toSortable", e);
				
				}

				//Provided we did all the previous steps, we can fire the drag event of the sortable on every draggable drag, when it intersects with the sortable
				if(inst.currentItem) inst.drag(e);
				
			} else {
				
				//If it doesn't intersect with the sortable, and it intersected before,
				//we fake the drag stop of the sortable, but make sure it doesn't remove the helper by using cancelHelperRemoval
				if(inst.isOver) {
					inst.isOver = 0;
					inst.cancelHelperRemoval = true;
					inst.options.revert = false; //No revert here
					inst.stop(e);
					inst.options.helper = "original";
					
					//Now we remove our currentItem, the list group clone again, and the placeholder, and animate the helper back to it's original size
					inst.currentItem.remove();
					inst.placeholder.remove();
					
					ui.helper.animate({ height: this.innerHeight(), width: this.innerWidth() }, 500);
					ui.instance.propagate("fromSortable", e);
				}
				
			};
		}
	})

	//TODO: wrapHelper

})(jQuery);

