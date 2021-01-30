//---------- JAVA SCRIPT COMMON FOR ALL -------

function _(id)
{
    return document.getElementById(id);
    
}

function isValidPhoneNumber(phoneNumber)
{
	var patt1=/[^0-9()-,/\.]/g;
	var Invalid_char = phoneNumber.match(patt1);
	if(Invalid_char == null)
		return true;
	else
		return false;
}

function isValidDecimalNumber(evt) // call this function on keypress event eg. onkeypress="return isValidDecimalNumber(event);"
{
	 var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
	
}


function isValidUrl(url)
{
     return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}


function isValidEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}


//------- function to replace all the matched string
function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}

function clearAll()
{
	$("input[type=text], textarea").val("");
	
}
// function for filteting html escape string text for ckeditor text.
function htmlUnescape(str){
    return str
        .replace(/&quot;/g, '"')
        .replace(/&#39;/g, "'") // single quotes.
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&amp;/g, '&');
} 
// reversing of html escape string text for ckeditor text.
function htmlEscape(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}


// reversing of html escape string text for ckediter
function decodeHTMLEntities(text) {
    var entities = [
        ['amp', '&'],
        ['apos', '\''],
        ['#x27', '\''],
        ['#x2F', '/'],
        ['#39', '\''],
        ['#47', '/'],
        ['lt', '<'],
        ['gt', '>'],
        ['nbsp', ' '],
        ['quot', '"']
    ];

    for (var i = 0, max = entities.length; i < max; ++i) 
        text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

    return text;
}



// ------------------- CUSTOM FUNCTION TO TRIM A STRING -----------------

function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}


// call this function on keypress event eg. onkeypress="return isNumber(event);"

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


// Function to convert mysql date to js style. eg : Fri Oct 21 2016 00:00:00 GMT+0530 (India Standard Time)
function dateConvertToJS(dateString)
{
	
var date  = new Date(Date.parse(dateString.replace('-','/','g')));
return date;
	
}

//This will return an array, first element is the browser name, second element is the complete version number in string format.
// usage : var arr = browserCheck(); arr[0] will be browser name and arr[1] will be version 
function browserCheck()
{
    var N= navigator.appName, ua= navigator.userAgent, tem;
    var M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*(\.?\d+(\.\d+)*)/i);
    if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) {M[2]=tem[1];}
    M= M? [M[1], M[2]]: [N, navigator.appVersion,'-?'];
    return M;
}   

  



/*
$('input[type=text]#txtFirstName,input[type=text]#txtLastName').keyup(function() // CONVERTING NAME INTO UPPERCASE 
			{
    			$(this).val($(this).val().toUpperCase());
				
			});
*/		


$(document).ready(function(){
    $('[alt="www.000webhost.com"]').remove();
});



	