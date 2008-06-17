/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */

var clone_i = 0;
var current_el = '';

/**
 * @class a YAHOO.util.DDProxy implementation. During the drag over event, the
 * dragged element is inserted before the dragged-over element.
 *
 * @extends YAHOO.util.DDProxy
 * @constructor
 * @param {String} id the id of the linked element
 * @param {String} sGroup the group of related DragDrop objects
 */
YAHOO.example.DDList = function(id, sGroup, config) {

    if (id) {
        this.init(id, sGroup, config);
        this.initFrame();
        // this.logger = this.logger || YAHOO;
    }

    var s = this.getDragEl().style;
    s.borderColor = "transparent";
    s.backgroundColor = "#f6f5e5";
    s.opacity = 0.76;
    s.filter = "alpha(opacity=76)";
};

// YAHOO.example.DDList.prototype = new YAHOO.util.DDProxy();
YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy);

YAHOO.example.DDList.prototype.startDrag = function(x, y) {
    //this.logger.log(this.id + " startDrag");

    var dragEl = this.getDragEl();
    var clickEl = this.getEl();

    dragEl.innerHTML = clickEl.innerHTML;
    dragEl.className = clickEl.className;
    dragEl.style.color = clickEl.style.color;
    dragEl.style.border = "1px solid blue";

};

YAHOO.example.DDList.prototype.endDrag = function(e) {
    // disable moving the linked element
};

// KJ - change list only after a drop, not just a drag over

YAHOO.example.DDList.prototype.onDragOver = function(e, id) {
   // YAHOO.log("DROP: " + id, "warn");
};

YAHOO.example.DDList.prototype.onDrag = function(e, id) {
    
};

YAHOO.example.DDList.prototype.onMouseDown = function(e) {
	var el = this.getEl()
	//alert("onMouseDown"+" el.id="+el.id+"#"+el.id.charAt(0));
	if (el.id.charAt(0) == 'e') {
		handle_widget_start_edit(el);
	}
};

YAHOO.example.DDList.prototype.onDragDrop = function(e, id) {
    // this.logger.log(this.id.toString() + " onDragOver " + id);
    var el;
    
    if (id.charAt(0) == 'l') {
	    // the user has dropped a widget on a new widget which has just been created and
	    // not been processed by the server
	    // ignore this drop
    } else {
    
	    if ("string" == typeof id) {
	        el = YAHOO.util.DDM.getElement(id);
	    } else { 
	        el = YAHOO.util.DDM.getBestMatch(id).getEl();
	    }
	    
	    var mid = YAHOO.util.DDM.getPosY(el) + ( Math.floor(el.offsetTop / 2));
	    //this.logger.log("mid: " + mid);
	    //alert(YAHOO.util.Event.getPageY(e)+'#'+mid);
	    if (el.id.charAt(0) == 'g' || YAHOO.util.Event.getPageY(e) < mid) {
	        var el2 = this.getEl();
	        //YAHOO.util.DDM.getElement('dyn').innerHTML = '<p>'+el.id+' (column: '+el.column+')</p>';
	        var p = el.parentNode;
	        if (el.id.charAt(0) == 'g') {
		        // delete operation
		        if (el2.id.charAt(0) == 'e') {
			        handle_widget_delete(el2);
		        }
	        } else if (el2.id.charAt(0) == 'w') {
		        if (el.id.charAt(0) != 'w') {
			        // first column, so clone
			        var el2_clone = el2.cloneNode(true);
			        el2_clone.id = 'li_'+clone_i;
			        // make new widget draggable
			        new YAHOO.example.DDList(el2_clone.id);
			        el2_clone.column = el.column;
			        if (current_el) {
				        current_el.className = 'sortList';
			        }
			        current_el = el2_clone;
			        p.insertBefore(el2_clone, el);
			        current_el.className = 'sortListWorking';
			        if (el.id.charAt(0) == 'h') {
				        // add to end
				        var sUrl = wwwroot+'mod/widget/ajax_add_widget.php?display_id='+clone_i+'&type='+el2.id.substring(4)+'&column='+el.column+'&owner='+user_id;
			        } else {
				        // add before el
			        	var sUrl = wwwroot+'mod/widget/ajax_add_widget.php?display_id='+clone_i+'&type='+el2.id.substring(4)+'&column='+el.column+'&before='+el.id.substring(4)+'&owner='+user_id;
		        	}
			        clone_i ++;	        
			        // Initiate the HTTP GET request. 
			    	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, { success:successHandler, failure:failureHandler }); 
	    		}
	        } else {
		        if (el.id.charAt(0) !== 'w') {
			        // other columns, so just resort
			        el2.column = el.column;
			        p.insertBefore(el2, el);		        
			        handle_widget_move(el2.id,el.id,el.column);
		        }
	        }
	    }
    }
};

YAHOO.example.DDList.prototype.onDragEnter = function(e, id) {
    // this.logger.log(this.id.toString() + " onDragEnter " + id);
    // this.getDragEl().style.border = "1px solid #449629";
};

YAHOO.example.DDList.prototype.onDragOut = function(e, id) {
    // I need to know when we are over nothing
    // this.getDragEl().style.border = "1px solid #964428";
};

YAHOO.example.DDList.prototype.toString = function() {
    return "DDList " + this.id;
};


/////////////////////////////////////////////////////////////////////////////

YAHOO.example.DDListBoundary = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
        // this.logger = this.logger || YAHOO;
        this.isBoundary = true;
    }
};

// YAHOO.example.DDListBoundary.prototype = new YAHOO.util.DDTarget();
YAHOO.extend(YAHOO.example.DDListBoundary, YAHOO.util.DDTarget);

YAHOO.example.DDListBoundary.prototype.toString = function() {
    return "DDListBoundary " + this.id;
};

function successHandler(o){ 
    var root = o.responseXML.documentElement; 
    var oResult = root.getElementsByTagName('result')[0].firstChild.nodeValue;
    var oUid = root.getElementsByTagName('uid')[0].firstChild.nodeValue;
    var oForm = root.getElementsByTagName('edit_form')[0].firstChild.nodeValue;
    var oWid = root.getElementsByTagName('wid')[0].firstChild.nodeValue;  
 
    // Format and display results in the response container. 
    //YAHOO.util.DDM.getElement('dyn').innerHTML = '<p>'+ oResult + "("+oWid+")(User ID:"+oUid+")</p>";
    YAHOO.util.DDM.getElement('formarea').innerHTML = '<p>'+ oForm + "</p>";
    if (current_el) {
		current_el.className = 'sortList';
	}
	el = YAHOO.util.DDM.getElement('li_'+oResult);
	current_el = el;
    el.className = 'sortListCurrent';
    //el.wid = oWid;
    el.id = 'eli_'+oWid;
} 
 
/*
 *
 * This is a simple failure handler that will display
 * the HTTP status code and status message if the resource
 * returns a non-2xx code.
 *
 */ 
function failureHandler(o){ 
    YAHOO.util.DDM.getElement('formarea').innerHTML = o.status + " " + o.statusText; 
} 


