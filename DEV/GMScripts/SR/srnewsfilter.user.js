// ==UserScript==
// @name          SR News Filter
// @namespace     tag:SRNewsFilter
// @description   Filter Stellar Realms news messages
// @include       http://sr.primeaxiom.com/news.asp
// ==/UserScript==

// Code starts here

// To escape string literals in regexps
RegExp.escape = function(text) {
  if (!arguments.callee.sRE) {
    var specials = [
      '/', '.', '*', '+', '?', '|',
      '(', ')', '[', ']', '{', '}', '\\'
    ];
    arguments.callee.sRE = new RegExp(
      '(\\' + specials.join('|\\') + ')', 'g'
    );
  }
  return text.replace(arguments.callee.sRE, '\\$1');
}


function initTable() {
	var filterSpan = document.createElement("span");
	filterSpan.innerHTML =	'<table width="100%" cellspacing="0" cellpadding="0">' +
							'<tr><td width="100%" colspan="3" class="v11-TABLE-title">&nbsp;News Filter</td></tr>' +
							'<tr><td class="v11-cell-normal" rowspan="2">Filter by type:&nbsp;<br>' +
							'<select size="4" id="filterType" multiple>' +
							'<option>Commerce</option>' +
							'<option>Intelligence</option>' +
							'<option>Scout reports</option>' +
							'<option>Battle reports</option>' +
							'<option>Diplomacy</option>' +
							'<option>Construction</option>' +
							'<option>Other Messages</option>' +
							'</select></td>' +
							'<td nowrap class="v11-cell-normal" valign="top"><p>From: <input type="text" id="filterFrom" size="40"><br>' + 
							'Subject: <input type="text" id="filterSubj" size="40"></p></td>' +
							'<td width="100%" class="v11-cell-normal" valign="top">Message: ' +
							'<textarea id="filterMsg" rows="2" cols="50" style="width: 100%;"></textarea></td></tr>' +
							'<tr><td width="50%" class="v11-cell-normal" valign="top" colspan="2">' +
							'<button id="doFilter">Apply Filter</button> &nbsp; <button id="clearFilter">Clear Filters</button></p></td>' +
							'</tr></table><br>';
	document.body.insertBefore(filterSpan, document.body.childNodes[7]);
	document.getElementById('doFilter').addEventListener('click', filterNews, false);
	document.getElementById('clearFilter').addEventListener('click', clearFilter, false);
}

function filterNews() {
	
	var filterSubj = document.getElementById('filterSubj').value;
	var regexSubj = "^\\s*Subject: ";
	var regexFrom = "^\\s*From: .*"+document.getElementById('filterFrom').value+".*";
	var filterMsg = document.getElementById('filterMsg').value;
	
	var tempSubj = "";
	var findMsg = new Array();
	if (filterMsg.length > 0) findMsg[0] = filterMsg;
	var avoidMsg = new Array();
	
	// Message types
	var filterType = document.getElementById('filterType');
	if (filterType.options[6].selected) {
		
		// All messages accepted, except for those deselected
		if (!filterType.options[0].selected) { tempSubj += "|Sale Completed|Sale Posted"; }
		if (!filterType.options[1].selected) { tempSubj += "|Intelligence Report|Agent Training Complete"; }
		if (!filterType.options[2].selected) { tempSubj += "|Combat Report"; }
		if (!filterType.options[3].selected) { tempSubj += "|Combat Report"; }
		if (!filterType.options[4].selected) { tempSubj += "|Diplomatic Communique"; }
		if (!filterType.options[5].selected) { tempSubj += "|Structure Complete|Unit Construction Complete"; }
		if (tempSubj.length > 0) {
			regexSubj += "(?!(?:"+tempSubj.slice(1)+")$)";
		}
		if (filterSubj.length > 0) {
			regexSubj += ".*"+RegExp.escape(filterSubj);
		}
		
	} else {
		
		// Message must be one of the following
		if (filterType.options[0].selected) { tempSubj += "|Sale Completed|Sale Posted"; }
		if (filterType.options[1].selected) { tempSubj += "|Intelligence Report|Agent Training Complete"; }
		if (filterType.options[2].selected) { tempSubj += "|Combat Report"; findMsg[findMsg.length] = "reconnoiter"; }
		if (filterType.options[3].selected) { tempSubj += "|Combat Report"; avoidMsg[avoidMsg.length] = "reconnoiter"; }
		if (filterType.options[4].selected) { tempSubj += "|Diplomatic Communique"; }
		if (filterType.options[5].selected) { tempSubj += "|Structure Complete|Unit Construction Complete"; }
		if (tempSubj.length > 0) {
			regexSubj += "(?:"+tempSubj.slice(1)+")$";
		} else {
			if (filterSubj.length > 0) regexSubj += ".*"+RegExp.escape(filterSubj);
		}
		
	}
	
	var rSubj = new RegExp(regexSubj, "i");
	var rFrom = new RegExp(regexFrom, "i");
	
	applyFilter(function(newsNodes) {
		// Check subject
		if (!newsNodes[6].nodeValue.match(rSubj))
			return false;
		// Check sender
		if (!newsNodes[2].nodeValue.match(rFrom))
			return false;
		// Check message body
		for (var i=0; i<findMsg.length; i++)
			if (newsNodes[0].parentNode.innerHTML.toLowerCase().indexOf(findMsg[i].toLowerCase(),newsNodes[0].parentNode.innerHTML.indexOf("<hr>")+4) == -1)
				return false;	// Not found after <hr>
		for (var i=0; i<avoidMsg.length; i++)
			if (newsNodes[0].parentNode.innerHTML.toLowerCase().indexOf(avoidMsg[i].toLowerCase(),newsNodes[0].parentNode.innerHTML.indexOf("<hr>")+4) != -1)
				return false;	// Found after <hr>
		return true;
		});
	
}

function clearFilter() {
	document.getElementById('filterSubj').value = "";
	document.getElementById('filterFrom').value = "";
	document.getElementById('filterMsg').value = "";
	var filterType = document.getElementById('filterType').options;
	for (var i=0; i<filterType.length; i++)
		filterType[i].selected = false;
	
	applyFilter(function (newsNodes) { return true; });
}

function applyFilter(filterFunction) {
	var newsRows = document.getElementsByTagName("tbody")[2].childNodes;
	for (var i = 6; i <= (newsRows.length-2); i += 2) {
		newsRows[i].style.display = 'none';
		if (filterFunction(newsRows[i].childNodes[3].childNodes)) {
			newsRows[i].style.display = 'table-row';
		} else {
			newsRows[i].style.display = 'none';
		}
	}
}

// Program flow
initTable();