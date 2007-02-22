// ==UserScript==
// @name          SR News - time details
// @namespace     tag:SRNewsTime
// @description   Add age in turns of Stellar Realms news messages
// @include       http://sr.primeaxiom.com/news.asp
// ==/UserScript==

// Enter the server time's offset from GMT, in _milliseconds_.


// Code starts here

var curDate, newsTBody;
var timezoneOffset = GM_getValue('timezoneServer',0)*60000;	// in ms

// Configuration button
var configServerTimezone = document.createElement('button');
configServerTimezone.appendChild(document.createTextNode('Set server timezone'));
configServerTimezone.addEventListener('click',function () { GM_setValue('timezoneServer',parseInt(prompt("Enter the offset from UTC in minutes. e.g. -300 for EST, 60 for CET.",GM_getValue('timezoneServer',0)))); }, false);
document.getElementsByTagName("tbody")[2].childNodes[0].childNodes[1].appendChild(configServerTimezone);

curDate = new Date();
curDate.setTime(curDate.getTime() + curDate.getTimezoneOffset()*60000);

newsRows = document.getElementsByTagName("tbody")[1].childNodes;
for (var i = 6; i <= (newsRows.length-2); i += 2) {
	// Extract date
	var resultArray = newsRows[i].childNodes[3].childNodes[0].nodeValue.match(/\w*Date:  (\d+)\/(\d+)\/(\d+) (\d+):(\d+):(\d+) (AM|PM)/i);
	var newsDate = new Date(parseInt(resultArray[3]),parseInt(resultArray[1])-1,parseInt(resultArray[2]),(parseInt(resultArray[4]) % 12) + ((resultArray[7] == "PM") ? 12 : 0),parseInt(resultArray[5]),parseInt(resultArray[6]));
	// Get newsdate in UTC timezone
	newsDate.setTime(newsDate.getTime()-timezoneOffset);
	var localDate = new Date();
	localDate.setTime(newsDate.getTime()-localDate.getTimezoneOffset()*60000);
	// Update 'date' text
	newsRows[i].childNodes[3].childNodes[0].nodeValue = newsRows[i].childNodes[3].childNodes[0].nodeValue + "; local:  " + localDate.toLocaleString() + " (" + Math.floor((curDate.getTime()-newsDate.getTime())/1200000) + " turns ago)";
}
