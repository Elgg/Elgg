var http_Request = false;
var success = false;
var objectid = "";

function showhide(oid)
{
	var e = document.getElementById(oid);
	if(e.style.display == 'none') {
		e.style.display = 'block';
	} else {
		e.style.display = 'none';
	}
}

function getAjaxObj()
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

function alertContents()
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
				document.getElementById('ajaxmessages_' + objectid).innerHTML = '<a href="">' + message.textContent + '</a>'; 
				document.getElementById('ajaxmessages_post_' + objectid).innerHTML = '&nbsp;';
			}
			else
			{
				document.getElementById('ajaxmessages_' + objectid).innerHTML = message.textContent; 
			}
		} else {
			alert('There was a problem with the request.');
		}
	}
}

function sendcomment(url, formid, oid)
{
	var parameters;
	objectid = oid;

	http_Request = getAjaxObj();

	if (http_Request==false)
	{
		return false;
	}

	// Construct parameters
	frm = document.getElementById(formid);

	parameters = "";
	for(var i = 0;i < frm.elements.length;i++) 
	{ 
		element = frm.elements[i]; 

		parameters = parameters + element.name +"=" + encodeURI( element.value ) + "&";
	}
	parameters = parameters + "returnformat=xml";

	frm.new_comment.disabled=true;

	// Post result
        //http_Request.overrideMimeType('text/html');
        http_Request.onreadystatechange = alertContents;
	http_Request.open('POST', url, true);
	http_Request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_Request.setRequestHeader("Content-length", parameters.length);
	http_Request.setRequestHeader("Connection", "close");
	http_Request.send(parameters);

	frm.new_comment.disabled=false;

	if (success==true) { 
		frm.new_comment.value=""; 
	}
}