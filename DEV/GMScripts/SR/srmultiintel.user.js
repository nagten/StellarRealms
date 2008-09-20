// ==UserScript==
// @name          SR MultiIntel
// @namespace     tag:SRMultiIntel
// @description   Send agents to several planets
// @include       http://sr.primeaxiom.com/intelligence_operations.asp
// ==/UserScript==

//default age treshold 72 turns -> 24 hours
var ageThreshold = GM_getValue('ageThreshold', 72);

//Check Intel type within bounds
{
	//greasemonkey.scriptvals.tag:SRmultiIntel
	var intelType = GM_getValue('intelType', 0);
	if (intelType < 0 ) GM_setValue('intelType', 1); //establish

	var intelType = GM_getValue('inputNumberOfAgents', 1);
	if (intelType < 0 ) GM_setValue('inputNumberOfAgents', 1); //Number of ships to use
}

function initTable()
{
	//Setup initial table contents
	var filterSpan = document.createElement("span");
	filterSpan.innerHTML =	'<table width="100%" cellspacing="0" cellpadding="0"><tbody id="multiIntelBody">' +
							'<tr id="multiIntelHeader"><td width="100%" colspan="3" class="v11-TABLE-title">Multi Intel</td></tr>' +
							'<tr><td width="100%" colspan="3" class="v11-cell-normal">Intel type: ' +
							'<label for="multiIntel-OptIntelType0"><input type="radio" name="multiIntel-OptIntelType" value="0" id="multiIntel-OptIntelType0"> Establish</label>' +
							'<label for="multiIntel-InNumberOfAgents0"><input type="text" name="multiIntel-InNumberOfAgents" value="1" size = "4" id="multiIntel-InNumberOfAgents0"> Number of agents</label>' +
							'  <button id="multiIntel-OptAgeThreshold">Highlight threshold</button>' +
							'</td></tr>' +
							'<tr id="multiIntel-PlanetAddRow"><td width="100%" colspan="3" class="v11-cell-normal">' +
							'Track a new planet: <span id="multiIntel-PlanetListHolder"></span> <button id="multiIntel-PlanetAdd">Track planet</button> <button id="multiIntel-PlanetAddAll">Track all planets</button>' +
							'  Selected planets: <button id="multiIntel-PlanetSelAll">Select all</button> <button id="multiIntel-PlanetSelNone">Deselect all</button>' +
							' <button id="multiIntel-PlanetSelIntel">Infiltrate</button> <button id="multiIntel-PlanetSelDelete">Delete</button>' +
							'</td></tr>' +
							'</tbody></table><br>';
	document.body.appendChild(filterSpan);

	//Fill in 'add planet'
	var planetList = document.getElementsByName("CurrentTargetID")[0].cloneNode(true);
	planetList.id = "multiIntel-PlanetList";
	document.getElementById("multiIntel-PlanetListHolder").appendChild(planetList);
	document.getElementById("multiIntel-PlanetAdd").addEventListener('click', addPlanet, false);
	document.getElementById("multiIntel-PlanetAddAll").addEventListener('click', addAllPlanets, false);

	//Select reconnaiter type default is fleet
	//document.getElementById("multiIntel-OptIntelType"+GM_getValue('intelType', 1)).checked = true;

	document.getElementById("multiIntel-OptIntelType0").addEventListener('click', function () { GM_setValue('intelType', 0); }, false);
	document.getElementById("multiIntel-OptAgeThreshold").addEventListener('click', function ()
	{GM_setValue('intelType', prompt("Highlight planets that haven't been infiltrated for how many turns? (Changes take effect after refresh.)", GM_getValue('ageThreshold', ageThreshold))); }, false);

	//Selected planet buttons
	document.getElementById("multiIntel-PlanetSelAll").addEventListener('click', function () { selectAll(true); }, false);
	document.getElementById("multiIntel-PlanetSelNone").addEventListener('click', function () { selectAll(false); }, false);
	document.getElementById("multiIntel-PlanetSelIntel").addEventListener('click', sendIntelToMany, false);
	document.getElementById("multiIntel-PlanetSelDelete").addEventListener('click', deleteSelected, false);
}

function addPlanet()
{
	//Add planet
	var selectedPlanet = document.getElementById("multiIntel-PlanetList").options[document.getElementById("multiIntel-PlanetList").selectedIndex];

	//Does planet already exist?
	var planetIDs = GM_getValue('planets', '').split(',');
	var planetIDslength = planetIDs.length;

	for (var intI=1; intI < planetIDslength; intI++)
	{
		if (planetIDs[intI] == selectedPlanet.value)
		{
			alert ("You've already chosen that planet.");
			return;
		}
	}

	//New planet, so add it
	planetIDs[planetIDs.length] = selectedPlanet.value;

	GM_setValue('planets', planetIDs.join(','));

	//Add when we last scouted that planet
	//greasemonkey.scriptvals.tag:SRmultiIntel,2006-11-27:SRmultiIntel/SR Multi Intel.planetDate-12
	var curTime = new Date();
	GM_setValue('planetDate-'+selectedPlanet.value, Math.floor(curTime.getTime()/1200000) - ageThreshold);

	addRow(selectedPlanet.value, true);
}

function addAllPlanets()
{
	//Add planet
	//greasemonkey.scriptvals.tag:SRmultiIntel,2006-11-27:SRmultiIntel/SR Multi Intel.planets = ",1,20,30"
	var planets = document.getElementById("multiIntel-PlanetList").options;
	var planetListLength = document.getElementById("multiIntel-PlanetList").length;
	var planetsList = ",";

	//Build list
	for (var intI=1; intI < planetListLength; intI++)
	{
		//Get the PlanetID it is stored in the option list
		planetsList = planetsList + planets[intI].value;

		var curTime = new Date();
		GM_setValue('planetDate-'+planets[intI].value, Math.floor(curTime.getTime()/1200000) - ageThreshold);

		if (planetListLength != intI + 1)
		{
			planetsList = planetsList + ',';
		}
	}

	GM_setValue('planets', planetsList);

	//Renew list
	addPlanets();
}

function addRow(planetID, insertAtEnd)
{
	var curTime = new Date();
	var submitTurn = GM_getValue('planetDate-'+planetID, 0);
	var age = Math.floor(curTime.getTime()/1200000) - submitTurn;

	var newRow = document.createElement("tr");
	newRow.id = "multiIntel-PlanetRow"+planetID;

	//Planet cell
	var planetCell = document.createElement("td");
	planetCell.className = "v11-column-header";
	planetCell.noWrap = true;

	//'Select' checkbox
	var planetCheck = document.createElement("input");
	planetCheck.type = "checkbox";
	planetCheck.name = "multiIntel-PlanetSelect";
	planetCheck.value = planetID;
	planetCell.appendChild(planetCheck);

	//Find and select planet from list
	var planets = document.getElementById("multiIntel-PlanetList").options;
	var planetsLength = planets.length;

	for (var intI=0; intI < planetsLength; intI++)
	{
		if (planets[intI].value == planetID)
		{
			var tempBold = document.createElement("b");
			tempBold.appendChild(document.createTextNode(planets[intI].text + " (" + planetID + "): "));
			planetCell.appendChild(tempBold);

			//to exit loop
			intI = planetsLength;
		}
	}

	//Planet cell done, append
	newRow.appendChild(planetCell);

	//Now start on status
	planetCell = document.createElement("td");
	planetCell.className = "v11-cell-normal";
	planetCell.width = "100%";

	var statusSpan = document.createElement("span");
	statusSpan.id = "multiIntel-PlanetStatus"+planetID;

	if (age >= 0)
	{
		statusSpan.appendChild(document.createTextNode("Last sent "+age+" turns ago. "));

		// Age highlight
		if (age >= ageThreshold)
		{
			planetCell.style.backgroundColor = "#FFBBBB";
		}
	}
	else
	{
		statusSpan.appendChild(document.createTextNode("Agent should arrive in "+(0-age)+" turns. "));
		planetCell.style.backgroundColor = "#C6B8D8";
	}

	planetCell.appendChild(statusSpan);

	//Status cell done; append
	newRow.appendChild(planetCell);

	//Now start on tools
	planetCell = document.createElement("td");
	planetCell.className = "v11-cell-normal";
	planetCell.noWrap = true;

	//Add buttons
	//Send Intel
	var buttonSend = document.createElement('button');
	buttonSend.appendChild(document.createTextNode("Send Agent"));
	buttonSend.addEventListener('click', function () { changeStatus(planetID, "Attempting to send Agent. "); sendIntel(planetID); }, false);
	planetCell.appendChild(buttonSend);
	planetCell.appendChild(document.createTextNode(" "));

	//delete stop tracking
	var buttonDelete = document.createElement('button');
	buttonDelete.appendChild(document.createTextNode("Stop tracking"));
	buttonDelete.addEventListener('click', function () { if (confirm("Please confirm:")) deletePlanet(planetID); }, false);
	planetCell.appendChild(buttonDelete);
	planetCell.appendChild(document.createTextNode(" "));

	//Finished, append cell/row
	newRow.appendChild(planetCell);

	if (insertAtEnd)
	{
		document.getElementById("multiIntelBody").insertBefore(newRow, document.getElementById("multiIntel-PlanetAddRow"));
	}
	else
	{
		document.getElementById("multiIntelBody").insertBefore(newRow, document.getElementById("multiIntelHeader").nextSibling);
	}
}

function changeStatus(planetID, message)
{
	var statusSpan = document.getElementById("multiIntel-PlanetStatus"+planetID);
	statusSpan.replaceChild(document.createTextNode(message), statusSpan.childNodes[0]);
}

function addPlanets()
{
	var planetIDs = GM_getValue('planets', '').split(',');
	var planetIDsLength = planetIDs.length;

	for (var intI=1; intI < planetIDsLength; intI++)
	{
		addRow(planetIDs[intI], true);
	}
}

function deletePlanet(planetID)
{
	var planetIDs = GM_getValue('planets', '').split(',');
	var planetIDsLength = planetIDs.length;

	for (var intI=1; intI < planetIDsLength; intI++)
	{
		if (planetIDs[intI] == planetID)
		{
			planetIDs.splice(intI,1);
			//to exit our loop
			intI = planetIDsLength;
		}
	}

	GM_setValue('planets', planetIDs.join(','));

	//Remove row
	document.getElementById("multiIntelBody").removeChild(document.getElementById("multiIntel-PlanetRow"+planetID));
}

function sendIntelWithUrl(postdata, planetID)
{
	GM_xmlhttpRequest(
	{
		method: 'POST',
		url: 'http://sr.primeaxiom.com/intelligence_operations2.asp',
		headers: {
			'User-agent': 'Firefox/2.0.0.12',
			'Content-type': 'application/x-www-form-urlencoded',
		},
		data: postdata,
		onload: function(responseDetails)
		{
			if(responseDetails.status = 200) //completed normally
			{
				//GM_log(responseDetails.responseText);

				var matches = responseDetails.responseText.match(/Will arrive at destination in (\d+) month/);
				if (matches)
				{
					//Success
					//Update the row and add update the timing
					var curTime = new Date();
					GM_setValue('planetDate-'+planetID, Math.floor(curTime.getTime()/1200000) + parseInt(matches[1]));
					document.getElementById("multiIntel-PlanetRow"+planetID).childNodes[1].style.backgroundColor = "#BBFFBB";
					changeStatus(planetID, "Intel should arrive in "+matches[1]+" turns. ");
					selectPlanet(planetID, false);
				}
				else
				{
					matches = responseDetails.responseText.match(/You haven't dispatched any units!/);

					if (matches)
					{
						alert("Could not send Intel. You may have no more available");
					}
					else
					{
						matches = responseDetails.responseText.match(/We are unable to attack that planet due to their current diplomatic status./);

						if (matches)
						{
							alert("Could not send Intel. Unable to send because of diplomatic status.");
						}
						else
						{
							alert("Could not send Intel. You may have no more available, you ran out of crew, or you are allied with this planet.");
						}
					}

					// Debugging: it will add the response below the page

					var debugSpan=document.createElement("span");
					debugSpan.style.color = '#FFFFFF';
					debugSpan.appendChild(document.createTextNode("DEBUG TEXT: \n"+responseDetails.responseText));
					document.body.appendChild(debugSpan);

				}
			}
			else
			{
				//404, ...
				alert("Could not send Intel error number: " + responseDetails.status + " " + statusText);
			}
		}
	});
}

function sendIntel(planetID)
{
	var postdata = '';
	var numberOFShips = document.getElementById("multiIntel-InNumberOfShips0").value;

	//We send out the scout
	if (GM_getValue('reconnaiterType',0) == 0)
	{
		//Reconnaiter Fleet
		postdata = "AttackTypeID=6"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('intelType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		sendScoutWithUrl(postdata, planetID);
	}
	else if (GM_getValue('reconnaiterType',0) == 1)
	{
		//Reconnaiter Structures
		postdata = "AttackTypeID=7"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('intelType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		sendScoutWithUrl(postdata, planetID);
	}
	else if (GM_getValue('reconnaiterType',0) == 2)
	{
		//Reconnaiter Both structure and Fleet
		postdata = "AttackTypeID=6"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('intelType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		//Fleet
		sendScoutWithUrl(postdata, planetID);

		postdata = "AttackTypeID=7"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('intelType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		//Structure
		sendScoutWithUrl(postdata, planetID);
	}
}

function selectPlanet(planetID, state)
{
	var checkboxes = document.getElementsByName("multiIntel-PlanetSelect");
	if (checkboxes)
	{
		var checkboxesLength = checkboxes.length;
		for (var intI=0; intI < checkboxesLength; intI++)
		{
			if (checkboxes[intI].value == planetID)
			{
				checkboxes[intI].checked = state;
				return;
			}
		}
	}
}

function doSelected(userFunction)
{
	var checkboxes = document.getElementsByName("multiIntel-PlanetSelect");
	if (checkboxes)
	{
		var checkboxesLength = checkboxes.length;
		for (var intI=0; intI < checkboxesLength; intI++)
		{
			if (checkboxes[intI].checked)
			{
				userFunction(checkboxes[intI].value);
			}
		}
	}
}

function countSelected()
{
	//used for deleteSelected it will count the selected checkboxes
	var checkboxes = document.getElementsByName("multiIntel-PlanetSelect");
	var j=0;
	if (checkboxes)
	{
		var checkboxesLength = checkboxes.length;
		for (var intI=0; intI < checkboxesLength; intI++)
		{
			if (checkboxes[intI].checked)
			{
				j++;
			}
		}
	}
	return j;
}

function sendIntelToMany()
{
	//Send many scouts at once
	doSelected(function (planetID){	changeStatus(planetID, "Attempting to send Agent. "); sendScout(planetID);});
}

function selectAll(state)
{
	//Select all checkboxes
	var checkboxes = document.getElementsByName("multiIntel-PlanetSelect");
	if (checkboxes)
	{
		var checkboxesLength = checkboxes.length;
		for (var intI=0; intI < checkboxes.length; intI++)
		{
			selectPlanet(checkboxes[intI].value, state);
		}
	}
}

function deleteSelected()
{
	//Delete selected planets from list
	if (confirm("Confirm removal of "+countSelected()+" planets:"))
	{
		var checkboxes = document.getElementsByName("multiIntel-PlanetSelect");
		var checkboxesLength = checkboxes.length;

		if (checkboxes)
		{
			for (var intI=0; intI < checkboxesLength; intI++)
			{
				if (checkboxes[intI].checked)
				{
					deletePlanet(checkboxes[intI].value);
					intI--;
				}
			}
		}
	}
}

initTable();
addPlanets();