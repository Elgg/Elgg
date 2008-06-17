var http_Request = false;
var success = false;
var objectid = "";
var thesuffix = "";

var sURL = unescape(window.location.pathname);

function cw_showhide(oid)
{
	var e = document.getElementById(oid);
	if(e.style.display == 'none') {
		e.style.display = 'block';
	} else {
		e.style.display = 'none';
	}
}

function cw_hide(oid)
{
	var e = document.getElementById(oid);
	
	e.style.display = 'none';
	
}

function cw_getAjaxObj()
{
	var xmlHttp;
	
	try
	{
    		// Firefox, Opera 8.0+, Safari
    		xmlHttp=new XMLHttpRequest();
    }
  	catch (e)
    	{
    		// Internet Explorer
    		try
      		{
      			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      		}
    		catch (e)
      		{
      			try
        		{
        			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        		}
      			catch (e)
        		{
				return false;
        		}
      		}
    	}

	return xmlHttp;
}

function cw_alertContents()
{
	if (http_Request.readyState == 4) {
		if (http_Request.status == 200) {
			
			result = http_Request.responseXML;
			message = result.getElementsByTagName('message')[0];

			error = result.getElementsByTagName('error')[0];
			if (error.textContent == '0') {
				success = true;
			}

			if (success == true)
			{
				document.location.reload();
				//document.getElementById('commentwall_post_ajaxmessages_' + objectid + thesuffix).innerHTML = '<a href="">' + message.textContent + '</a>'; 
				document.getElementById('commentwall_post_link_' + objectid + thesuffix).innerHTML = '&nbsp;';
				
				
			}
			else
			{
				document.getElementById('commentwall_post_ajaxmessages_' + objectid + thesuffix).innerHTML = message.textContent; 
			}
			
		} else {
			alert('There was a problem with the request.');
		}
	}
}

function cw_sendcomment(url, formid, oid, suffix)
{
	var parameters;
	objectid = oid;
	if (suffix!='') thesuffix = suffix;

	http_Request = cw_getAjaxObj();

	if (http_Request==false)
	{
		return false;
	}

	// Construct parameters
	frm = document.getElementById(formid + thesuffix);

	parameters = "";
	for(var i = 0;i < frm.elements.length;i++) 
	{ 
		element = frm.elements[i]; 

		parameters = parameters + element.name +"=" + encodeURI( element.value ) + "&";
	}
	parameters = parameters + "returnformat=xml";

	// Post result
    http_Request.onreadystatechange = cw_alertContents;
	http_Request.open('POST', url, true);
	http_Request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_Request.setRequestHeader("Content-length", parameters.length);
	http_Request.setRequestHeader("Connection", "close");
	http_Request.send(parameters);

	if (success==true) { 
		frm.text.value=""; 
	}
}