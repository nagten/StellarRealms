// ==UserScript==
// @name          SR Planet Overview ADV Tools
// @namespace     tag:SRPlanetTools
// @description   Add functions to Stellar-Realms Planet Overview for easy data sharing
// @include       http://sr.primeaxiom.com/planet_overview.asp
// ==/UserScript==

/*
 * A JavaScript implementation of the Secure Hash Algorithm, SHA-1, as defined
 * in FIPS PUB 180-1
 * Version 2.1a Copyright Paul Johnston 2000 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for details.
 */

/*
 * Configurable variables. You may need to tweak these to be compatible with
 * the server-side, but the defaults work in most cases.
 */
var hexcase = 0;  /* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad  = "="; /* base-64 pad character. "=" for strict RFC compliance   */
var chrsz   = 8;  /* bits per input character. 8 - ASCII; 16 - Unicode      */

/*
 * These are the functions you'll usually want to call
 * They take string arguments and return either hex or base-64 encoded strings
 */
function hex_sha1(s){return binb2hex(core_sha1(str2binb(s),s.length * chrsz));}
function b64_sha1(s){return binb2b64(core_sha1(str2binb(s),s.length * chrsz));}
function str_sha1(s){return binb2str(core_sha1(str2binb(s),s.length * chrsz));}
function hex_hmac_sha1(key, data){ return binb2hex(core_hmac_sha1(key, data));}
function b64_hmac_sha1(key, data){ return binb2b64(core_hmac_sha1(key, data));}
function str_hmac_sha1(key, data){ return binb2str(core_hmac_sha1(key, data));}

/*
 * Perform a simple self-test to see if the VM is working
 */
function sha1_vm_test()
{
  return hex_sha1("abc") == "a9993e364706816aba3e25717850c26c9cd0d89d";
}

/*
 * Calculate the SHA-1 of an array of big-endian words, and a bit length
 */
function core_sha1(x, len)
{
  /* append padding */
  x[len >> 5] |= 0x80 << (24 - len % 32);
  x[((len + 64 >> 9) << 4) + 15] = len;

  var w = Array(80);
  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;
  var e = -1009589776;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    var olde = e;

    for(var j = 0; j < 80; j++)
    {
      if(j < 16) w[j] = x[i + j];
      else w[j] = rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
      var t = safe_add(safe_add(rol(a, 5), sha1_ft(j, b, c, d)),
                       safe_add(safe_add(e, w[j]), sha1_kt(j)));
      e = d;
      d = c;
      c = rol(b, 30);
      b = a;
      a = t;
    }

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
    e = safe_add(e, olde);
  }
  return Array(a, b, c, d, e);

}

/*
 * Perform the appropriate triplet combination function for the current
 * iteration
 */
function sha1_ft(t, b, c, d)
{
  if(t < 20) return (b & c) | ((~b) & d);
  if(t < 40) return b ^ c ^ d;
  if(t < 60) return (b & c) | (b & d) | (c & d);
  return b ^ c ^ d;
}

/*
 * Determine the appropriate additive constant for the current iteration
 */
function sha1_kt(t)
{
  return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
         (t < 60) ? -1894007588 : -899497514;
}

/*
 * Calculate the HMAC-SHA1 of a key and some data
 */
function core_hmac_sha1(key, data)
{
  var bkey = str2binb(key);
  if(bkey.length > 16) bkey = core_sha1(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++)
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_sha1(ipad.concat(str2binb(data)), 512 + data.length * chrsz);
  return core_sha1(opad.concat(hash), 512 + 160);
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left.
 */
function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * Convert an 8-bit or 16-bit string to an array of big-endian words
 * In 8-bit function, characters >255 have their hi-byte silently ignored.
 */
function str2binb(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (32 - chrsz - i%32);
  return bin;
}

/*
 * Convert an array of big-endian words to a string
 */
function binb2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (32 - chrsz - i%32)) & mask);
  return str;
}

/*
 * Convert an array of big-endian words to a hex string.
 */
function binb2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) +
           hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8  )) & 0xF);
  }
  return str;
}

/*
 * Convert an array of big-endian words to a base-64 string
 */
function binb2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * (3 -  i   %4)) & 0xFF) << 16)
                | (((binarray[i+1 >> 2] >> 8 * (3 - (i+1)%4)) & 0xFF) << 8 )
                |  ((binarray[i+2 >> 2] >> 8 * (3 - (i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}

// Javascript cookies

function Set_Cookie( name, value, expires, path, domain, secure )
{
// set time, it's in milliseconds
var today = new Date();
today.setTime( today.getTime() );

/*
if the expires variable is set, make the correct
expires time, the current script below will set
it for x number of days, to make it for hours,
delete * 24, for minutes, delete * 60 * 24
*/
if ( expires )
{
expires = expires * 1000 * 60 * 60 * 24;
}
var expires_date = new Date( today.getTime() + (expires) );

document.cookie = name + "=" +escape( value ) +
( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
( ( path ) ? ";path=" + path : "" ) +
( ( domain ) ? ";domain=" + domain : "" ) +
( ( secure ) ? ";secure" : "" );
}

// this function gets the cookie, if it exists
function Get_Cookie( name, defaultval ) {

var start = document.cookie.indexOf( name + "=" );
var len = start + name.length + 1;
if ( ( !start ) &&
( name != document.cookie.substring( 0, name.length ) ) )
{
return defaultval;
}
if ( start == -1 ) return defaultval;
var end = document.cookie.indexOf( ";", len );
if ( end == -1 ) end = document.cookie.length;
return unescape( document.cookie.substring( len, end ) );
}


//=============================================================================================================================
//
//			START STATS COLLECTOR
//
//=============================================================================================================================


function createInput(inputType, inputName, inputValue)
{
	btnInput = document.createElement('input');
	btnInput.setAttribute("type",inputType);
	btnInput.setAttribute("name",inputName);
	btnInput.setAttribute("value",inputValue);
	return(btnInput);
}

function getPartialPostData()
{
	//
	//		PARTIAL STATS - from the top 'summary' table
	//
	var tableStats = document.getElementsByTagName('table')[0].childNodes[1].childNodes[0];
	var resultArray, postdata;

	postdata = "";

	//
	//		PROJECTS
	//
	postdata += '&projects='+tableStats.childNodes[1].childNodes[0].nodeValue.match(/Current Projects:  (\d+)/)[1];
	postdata += '&projectsmaximum='+tableStats.childNodes[1].childNodes[2].nodeValue.match(/Maximum Projects:  (\d+)/)[1];

	//
	//		MATERIALS
	//
	postdata += '&mat[0]='+tableStats.childNodes[11].childNodes[0].nodeValue.match(/Food:  (-?\d+)/)[1];
	postdata += '&mat[1]='+tableStats.childNodes[11].childNodes[2].nodeValue.match(/Fuel:  (-?\d+)/)[1];
	postdata += '&mat[2]='+tableStats.childNodes[13].childNodes[0].nodeValue.match(/Metals:  (-?\d+)/)[1];
	postdata += '&mat[3]='+tableStats.childNodes[13].childNodes[2].nodeValue.match(/Radioactives:  (-?\d+)/)[1];

	//
	//		CREDITS
	//
	resultArray = tableStats.childNodes[7].childNodes[0].nodeValue.match(/Credits:  (-?\d+) \[(-?\d+)\]/);
	postdata += '&credits='+resultArray[1];
	postdata += '&creditsrank='+resultArray[2];

	//
	//		PRESTIGE
	//
	resultArray = tableStats.childNodes[5].childNodes[0].nodeValue.match(/Prestige:  (-?\d+) \[(\d+)\]/);
	postdata += '&prestige='+resultArray[1];
	postdata += '&prestigerank='+resultArray[2];

	//
	//		POPULATION
	//
	resultArray = tableStats.childNodes[9].childNodes[0].nodeValue.match(/Population:  (-?\d+) \[(-?\d+)\]/);
	postdata += '&pop='+resultArray[1];
	postdata += '&poprank='+resultArray[2];
	return postdata;
}


tables = document.getElementsByTagName("table");
if (tables)
{
	var valueBefore, valueDelta, valueMaximum, valueRank, postdata, varcurrent, varincommerce, vartotal;

	//
	//		MATERIALS
	//

	// Materials form
	var toolsForm = document.createElement('form');
	toolsForm.setAttribute("action","http://www.idsfadt.com/Cabal/Planetstats/planetstats.php");
	toolsForm.setAttribute("method","POST");
	toolsForm.setAttribute("target","_blank");
	toolsForm.setAttribute("style","color: #FFFFFF;");
	toolsForm.appendChild(createInput('hidden','action','uploadstats'));

	postdata = "action=uploadstats";


	//
	//		PLANETNAME
	//
	valueBefore = tables[2].childNodes[1].childNodes[2].childNodes[1].firstChild.nodeValue;
	toolsForm.appendChild(createInput('hidden','planetname',valueBefore));
	postdata += '&planetname='+valueBefore;

	// Get materials quantities
	for (i = 0; i < 4; i++)
	{	// Get each resource type

		varcurrent = tables[6].childNodes[1].childNodes[3].childNodes[i*2 + 3].childNodes[0].nodeValue * 1;
		varincommerce = tables[6].childNodes[1].childNodes[5].childNodes[i*2 + 3].childNodes[0].nodeValue * 1;
		vartotal = (varcurrent + varincommerce);
		valueDelta = tables[6].childNodes[1].childNodes[17].childNodes[i*2 + 3].childNodes[0].nodeValue - vartotal;

		toolsForm.appendChild(createInput('hidden','mat['+i+']',vartotal));
		toolsForm.appendChild(createInput('hidden','matdelta['+i+']',valueDelta));
		postdata += '&mat['+i+']='+vartotal+'&matdelta['+i+']='+valueDelta;
	}
	valueMaximum = tables[6].childNodes[1].childNodes[19].childNodes[3].childNodes[0].nodeValue.match(/\w*\d+ of (\d+) \(\d+%\)/)[1];
	toolsForm.appendChild(createInput('hidden','matmaximum',valueMaximum));
	postdata += '&matmaximum='+valueMaximum;

	//
	//		CREDITS
	//
	valueBefore = tables[7].childNodes[1].childNodes[2].childNodes[3].childNodes[0].nodeValue;
	valueDelta = tables[7].childNodes[1].childNodes[10].childNodes[3].childNodes[0].nodeValue;
	valueRank = tables[0].childNodes[1].childNodes[0].childNodes[7].childNodes[0].nodeValue.match(/\w*Credits:  -?[0-9,]+ \[(-?\d+)\]\w*/)[1];
	var valueTaxes = tables[7].childNodes[1].childNodes[4].childNodes[3].childNodes[0].nodeValue.match(/\w*Taxes:  -?\d+ \((-?\d)%\)/)[1];
	toolsForm.appendChild(createInput('hidden','credits',valueBefore));
	toolsForm.appendChild(createInput('hidden','creditsdelta',valueDelta));
	toolsForm.appendChild(createInput('hidden','creditsrank',valueRank));
	toolsForm.appendChild(createInput('hidden','taxrate',valueTaxes));
	postdata += '&credits='+valueBefore;
	postdata += '&creditsdelta='+valueDelta;
	postdata += '&creditsrank='+valueRank;
	postdata += '&taxrate='+valueTaxes;

	//
	//		PRESTIGE
	//
	valueBefore = tables[8].childNodes[1].childNodes[2].childNodes[3].childNodes[0].nodeValue;
	valueDelta = parseInt(tables[8].childNodes[1].childNodes[6].childNodes[3].childNodes[0].nodeValue) - parseInt(valueBefore);
	valueRank = tables[0].childNodes[1].childNodes[0].childNodes[5].childNodes[0].nodeValue.match(/\w*Prestige:  [0-9,]+ \[(\d+)\]\w*/)[1];
	toolsForm.appendChild(createInput('hidden','prestige',valueBefore));
	toolsForm.appendChild(createInput('hidden','prestigedelta',valueDelta));
	toolsForm.appendChild(createInput('hidden','prestigerank',valueRank));
	postdata += '&prestige='+valueBefore;
	postdata += '&prestigedelta='+valueDelta;
	postdata += '&prestigerank='+valueRank;

	//
	//		POPULATION
	//
	valueBefore = tables[5].childNodes[1].childNodes[2].childNodes[3].childNodes[0].nodeValue.match(/\w*Population:  (\d+)/)[1];
	valueDelta = tables[5].childNodes[1].childNodes[2].childNodes[3].childNodes[14].nodeValue.match(/\w*Net Growth:  (-?\d+)/)[1];
	valueRank = tables[0].childNodes[1].childNodes[0].childNodes[9].childNodes[0].nodeValue.match(/\w*Population:  -?[0-9,]+ \[(-?\d+)\]\w*/)[1];
	valueMaximum = tables[5].childNodes[1].childNodes[2].childNodes[3].childNodes[16].nodeValue.match(/\w*Maximum:  (-?\d+) \(-?\d+%\)/)[1];
	toolsForm.appendChild(createInput('hidden','pop',valueBefore));
	toolsForm.appendChild(createInput('hidden','popdelta',valueDelta));
	toolsForm.appendChild(createInput('hidden','poprank',valueRank));
	toolsForm.appendChild(createInput('hidden','popmaximum',valueMaximum));
	postdata += '&pop='+valueBefore;
	postdata += '&popdelta='+valueDelta;
	postdata += '&poprank='+valueRank;
	postdata += '&popmaximum='+valueMaximum;

	//
	//		PROJECTS
	//
	valueBefore = tables[0].childNodes[1].childNodes[0].childNodes[1].childNodes[0].nodeValue.match(/\w*Current Projects:  (\d+)\w*/)[1];
	valueMaximum = tables[0].childNodes[1].childNodes[0].childNodes[1].childNodes[2].nodeValue.match(/\w*Maximum Projects:  (\d+)\w*/)[1];
	toolsForm.appendChild(createInput('hidden','projects',valueBefore));
	toolsForm.appendChild(createInput('hidden','projectsmaximum',valueMaximum));
	postdata += '&projects='+valueBefore;
	postdata += '&projectsmaximum='+valueMaximum;

	//
	//		MISC
	//
	valueBefore = tables[2].childNodes[1].childNodes[10].childNodes[3].childNodes[8].nodeValue.match(/\w*Battles Lost:  (\d+)\w*/)[1]
	toolsForm.appendChild(createInput('hidden','battleslost',valueBefore));
	postdata += '&battleslost='+valueBefore;
	valueBefore = tables[2].childNodes[1].childNodes[10].childNodes[3].childNodes[22].nodeValue.match(/\w*Alert Level:  (\d+)\w*/)[1];
	toolsForm.appendChild(createInput('hidden','alertlevel',valueBefore));
	postdata += '&alertlevel='+valueBefore;

	//
	//		BONUSES
	//
	for (i = 0; i < 7; i++)
	{ // Seven bonuses in each table
		valueBefore = tables[10].childNodes[1].childNodes[4 + (i * 2)].childNodes[11].childNodes[0].nodeValue.slice(0,-1);
		toolsForm.appendChild(createInput('hidden','bonuses['+i+']',valueBefore));
		postdata += '&bonuses['+i+']='+valueBefore;
	}

	for (i = 0; i < 7; i++)
	{
		valueBefore = tables[11].childNodes[1].childNodes[4 + (i * 2)].childNodes[11].childNodes[0].nodeValue.slice(0,-1);
		toolsForm.appendChild(createInput('hidden','bonuses['+(i+7)+']',valueBefore));
		postdata += '&bonuses['+(i+7)+']='+valueBefore;
	}

	// Submit button
	toolsForm.appendChild(createInput('submit','submit','Submit stats'));

	// Insert tools form
	tables[0].parentNode.appendChild(toolsForm);

	var statusContainer = document.createElement('span');
	statusContainer.style.color = '#FFFFFF';
	toolsForm.appendChild(statusContainer);

	var statsHash = hex_sha1(postdata);

	// Update partial stats hash while we're at it
	//var partialpostdata = "action=partialstats";
	//partialpostdata += getPartialPostData();
	//var statsPartialHash = hex_sha1(partialpostdata);
	GM_log(postdata);
	if (Get_Cookie('pstatsAutoSubmit',0) == 1)
	{
		toolsForm.appendChild(document.createTextNode(' '));
		var toggleSubmit = document.createElement('button');
		toggleSubmit.appendChild(document.createTextNode('Auto submit off'));
		toggleSubmit.addEventListener('click',function () { Set_Cookie('pstatsAutoSubmit', 0, 365); alert("Auto submit is turned off."); }, false);
		tables[0].parentNode.appendChild(toggleSubmit);

		if (Get_Cookie('pstatsLastStatsHash','') != statsHash)
		{
			// An hour has elapsed since the last submit.
			statusContainer.appendChild(document.createTextNode(' Sending stats...'));

			GM_xmlhttpRequest(
				{
				method: 'POST',
				url: 'http://www.idsfadt.com/Cabal/Planetstats/planetstats.php',
				headers: {
					'User-agent': 'Mozilla/4.0 (compatible) Greasemonkey',
					'Content-type': 'application/x-www-form-urlencoded',
				},
				data: postdata,
				onload: function(responseDetails)
				{
					statusContainer.appendChild(document.createTextNode(' Stats updated!'));
					Set_Cookie('pstatsLastStatsHash', statsHash, 365);
					//Set_Cookie('pstatsLastPStatsHash', statsPartialHash, 365);
				}
			});
		}
		else
		{
			statusContainer.appendChild(document.createTextNode(' Stats have not changed since last submit.'));
		}
	}
	else
	{
		toolsForm.appendChild(document.createTextNode(' '));
		var toggleSubmit = document.createElement('button');
		toggleSubmit.appendChild(document.createTextNode('Auto submit on'));
		toggleSubmit.addEventListener('click',function () { Set_Cookie('pstatsAutoSubmit', 1, 365); alert("Auto submit is turned on."); window.location.reload(true); }, false);
		tables[0].parentNode.appendChild(toggleSubmit);
	}
}
