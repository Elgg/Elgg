/**
 * Functions for text editing (toolbar stuff)
 *
 * @todo I'm no JS guru please help if you know how to improve
 * @author Andreas Gohr <andi@splitbrain.org>
 */

/**
 * Creates a toolbar button through the DOM
 *
 * Style the buttons through the toolbutton class
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function createToolButton(icon,label,key,id){
    var btn = document.createElement('button');
    var ico = document.createElement('img');

    // preapare the basic button stuff
    btn.className = 'toolbutton';
    btn.title = label;
    if(key){
        btn.title += ' [ALT+'+key.toUpperCase()+']';
        btn.accessKey = key;
    }

    // set IDs if given
    if(id){
        btn.id = id;
        ico.id = id+'_ico';
    }

    // create the icon and add it to the button
    ico.src = DOKU_BASE+'lib/images/toolbar/'+icon;
    btn.appendChild(ico);

    return btn;
}

/**
 * Creates a picker window for inserting text
 *
 * The given list can be an associative array with text,icon pairs
 * or a simple list of text. Style the picker window through the picker
 * class or the picker buttons with the pickerbutton class. Picker
 * windows are appended to the body and created invisible.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function createPicker(id,list,icobase,edid){
    var cnt = list.length;

    var picker = document.createElement('div');
    picker.className = 'picker';
    picker.id = id;
    picker.style.position = 'absolute';
    picker.style.display  = 'none';

    for(var key in list){
        if (!list.hasOwnProperty(key)) continue;
        var btn = document.createElement('button');

        btn.className = 'pickerbutton';

        // associative array?
        if(isNaN(key)){
            var ico = document.createElement('img');
            ico.src       = DOKU_BASE+'lib/images/'+icobase+'/'+list[key];
            btn.title     = key;
            btn.appendChild(ico);
            eval("btn.onclick = function(){pickerInsert('"+id+"','"+
                                  jsEscape(key)+"','"+
                                  jsEscape(edid)+"');return false;}");
        }else{
            var txt = document.createTextNode(list[key]);
            btn.title     = list[key];
            btn.appendChild(txt);
            eval("btn.onclick = function(){pickerInsert('"+id+"','"+
                                  jsEscape(list[key])+"','"+
                                  jsEscape(edid)+"');return false;}");
        }

        picker.appendChild(btn);
    }
    var body = document.getElementsByTagName('body')[0];
    body.appendChild(picker);
}

/**
 * Called by picker buttons to insert Text and close the picker again
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function pickerInsert(pickerid,text,edid){
    // insert
    insertAtCarret(edid,text);
    // close picker
    pobj = document.getElementById(pickerid);
    pobj.style.display = 'none';
}

/**
 * Show a previosly created picker window
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function showPicker(pickerid,btn){
    var picker = document.getElementById(pickerid);
    var x = findPosX(btn);
    var y = findPosY(btn);
    if(picker.style.display == 'none'){
        picker.style.display = 'block';
        picker.style.left = (x+3)+'px';
        picker.style.top = (y+btn.offsetHeight+3)+'px';
    }else{
        picker.style.display = 'none';
    }
}

/**
 * Create a toolbar
 *
 * @param  string tbid ID of the element where to insert the toolbar
 * @param  string edid ID of the editor textarea
 * @param  array  tb   Associative array defining the buttons
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function initToolbar(tbid,edid,tb){
    var toolbar = $(tbid);
    if(!toolbar) return;

    //empty the toolbar area:
  
    var cnt = tb.length;
    for(var i=0; i<cnt; i++){
        // create new button
		btn = $(tb[i]['id']);
        // add button action dependend on type
        switch(tb[i]['type']){
            case 'format':
                var sample = tb[i]['title'];
                if(tb[i]['sample']){ sample = tb[i]['sample']; }

                eval("btn.onclick = function(){insertTags('"+
                                        jsEscape(edid)+"','"+
                                        jsEscape(tb[i]['open'])+"','"+
                                        jsEscape(tb[i]['close'])+"','"+
                                        jsEscape(sample)+
                                    "');return false;}");
                break;
            case 'insert':
                eval("btn.onclick = function(){insertAtCarret('"+
                                        jsEscape(edid)+"','"+
                                        jsEscape(tb[i]['insert'])+
                                    "');return false;}");
                break;
            case 'signature':
                if(typeof(SIG) != 'undefined' && SIG != ''){
                    eval("btn.onclick = function(){insertAtCarret('"+
                                            jsEscape(edid)+"','"+
                                            jsEscape(SIG)+
                                        "');return false;}");
                }
                break;
            case 'picker':
                createPicker('picker'+i,
                             tb[i]['list'],
                             tb[i]['icobase'],
                             edid);
                eval("btn.onclick = function(){showPicker('picker"+i+
                                    "',this);return false;}");
                break;
            case 'mediapopup':
                eval("btn.onclick = function(){window.open('"+
                                        jsEscape(tb[i]['url'])+((tb[i]['url'].indexOf('?')>0)?"&":"?")+"input_field="+jsEscape(edid)+"','"+
                                        jsEscape(tb[i]['name'])+"','"+
                                        jsEscape(tb[i]['options'])+
                                    "');return false;}");
                break;
        } // end switch
    } // end for
}

/**
 * Format selection
 *
 * Apply tagOpen/tagClose to selection in textarea, use sampleText instead
 * of selection if there is none. Copied and adapted from phpBB
 *
 * @author phpBB development team
 * @author MediaWiki development team
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Jim Raynor <jim_raynor@web.de>
 */
function insertTags(edid,tagOpen, tagClose, sampleText) {
  var txtarea = document.getElementById(edid);
  // IE
  if(document.selection  && !is_gecko) {
    var theSelection = document.selection.createRange().text;
    var replaced = true;
    if(!theSelection){
      replaced = false;
      theSelection=sampleText;
    }
    txtarea.focus();

    // This has change
    var text = theSelection;
    if(theSelection.charAt(theSelection.length - 1) == " "){// exclude ending space char, if any
      theSelection = theSelection.substring(0, theSelection.length - 1);
      r = document.selection.createRange();
      r.text = tagOpen + theSelection + tagClose + " ";
    } else {
      r = document.selection.createRange();
      r.text = tagOpen + theSelection + tagClose;
    }
    if(!replaced){
      r.moveStart('character',-text.length-tagClose.length);
      r.moveEnd('character',-tagClose.length);
    }
    r.select();
  // Mozilla
  } else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
    replaced = false;
    var startPos = txtarea.selectionStart;
    var endPos   = txtarea.selectionEnd;
    if(endPos - startPos){ replaced = true; }
    var scrollTop=txtarea.scrollTop;
    var myText = (txtarea.value).substring(startPos, endPos);
    if(!myText) { myText=sampleText;}
    if(myText.charAt(myText.length - 1) == " "){ // exclude ending space char, if any
      subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
    } else {
      subst = tagOpen + myText + tagClose;
    }
    txtarea.value = txtarea.value.substring(0, startPos) + subst +
                    txtarea.value.substring(endPos, txtarea.value.length);
    txtarea.focus();

    //set new selection
    if(replaced){
      var cPos=startPos+(tagOpen.length+myText.length+tagClose.length);
      txtarea.selectionStart=cPos;
      txtarea.selectionEnd=cPos;
    }else{
      txtarea.selectionStart=startPos+tagOpen.length;
      txtarea.selectionEnd=startPos+tagOpen.length+myText.length;
    }
    txtarea.scrollTop=scrollTop;
  // All others
  } else {
    var copy_alertText=alertText;
    var re1=new RegExp("\\$1","g");
    var re2=new RegExp("\\$2","g");
    copy_alertText=copy_alertText.replace(re1,sampleText);
    copy_alertText=copy_alertText.replace(re2,tagOpen+sampleText+tagClose);

    if (sampleText) {
      text=prompt(copy_alertText);
    } else {
      text="";
    }
    if(!text) { text=sampleText;}
    text=tagOpen+text+tagClose;
    //append to the end
    txtarea.value += "\n"+text;

    // in Safari this causes scrolling
    if(!is_safari) {
      txtarea.focus();
    }

  }
  // reposition cursor if possible
  if (txtarea.createTextRange){
    txtarea.caretPos = document.selection.createRange().duplicate();
  }
}

/*
 * Insert the given value at the current cursor position
 *
 * @see http://www.alexking.org/index.php?content=software/javascript/content.php
 */
function insertAtCarret(edid,value){

  var field = document.getElementById(edid);
  alert(field.value+value);
 
  //IE support
  if (document.selection) {
    field.focus();
    sel = document.selection.createRange();
    sel.text = value;
  //MOZILLA/NETSCAPE support
  }else if (field.selectionStart || field.selectionStart == '0') {
    var startPos  = field.selectionStart;
    var endPos    = field.selectionEnd;
    var scrollTop = field.scrollTop;
    field.value = field.value.substring(0, startPos) +
                  value +
                  field.value.substring(endPos, field.value.length);

    field.focus();
    var cPos=startPos+(value.length);
    field.selectionStart=cPos;
    field.selectionEnd=cPos;
    field.scrollTop=scrollTop;
  } else {
    field.value += "\n"+value;
  }
  // reposition cursor if possible
  if (field.createTextRange){
    field.caretPos = document.selection.createRange().duplicate();
  }
}

/**
* Extrats the url field from and EMBED HTML string
* URL regexp taked from: http://snippets.dzone.com/posts/show/452
* @param string HTML string to be processed
* @return string The url field from the EMBED or empty string
* @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
*/
function parseEmbed(text){
var embedpattern = /<embed[\w\s\"=;:.&\?\/-]*>\s*<\/embed>/;
var urlpattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?\"/; 
var embed = text.match(embedpattern);

if(embed==null) return null;

url=urlpattern.exec(embed[0]);

if(url==null) return null;

return url[0].substr(0,url[0].length-1);
}

/**
 * Called by to insert a new video string
 * @param string edid input_field id
 * @param string err_msg error message
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function insertVideo(edid,err_msg){
	var text = $('video_url');
	if(!text.value){
		alert(err_msg);
		return false;
	}
	var value = parseEmbed(text.value);
	if(value===null){
		alert(err_msg);
		return false;
	}
	var w = $('video_width');
	var h = $('video_height');

	w = w.value;
	h = h.value;
	size = (w!="" && h!="")?'@@'+w+'x'+h:'';

	text = '{{video:'+value+size+'}}';
    // insert
    opener.addResource(edid,text,opener);
    // close picker
    window.close();
}
/**
* Append te provided value in the specified field
* If tinyMCE its avaible it take care of insert on it too
* @param string id Field's id
* @param strinv value String to be appended
* @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
*/
function addResource(id,value){
  var field = $(id);
  field.value+=" "+value;

  if(window.tinyMCE != null){
    if(tinyMCE.isMSIE){
    tinyMCE.execCommand("mceSelectNode",false,id);
    }
    tinyMCE.execCommand("mceInsertContent",false,value);
  }
}