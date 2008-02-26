// ==UserScript==
// @name          SR Multi Scout
// @namespace     tag:SRMultiScout
// @description   Send scouts to several planets
// @include       http://sr.primeaxiom.com/fleets_deploy.asp
// ==/UserScript==

//default age treshold 72 turns -> 24 hours
var ageThreshold = GM_getValue('ageThreshold', 72);

//Check Scout type within bounds
{
	//greasemonkey.scriptvals.tag:SRMultiScout,2006-11-27:SRMultiScout/SR Multi Scout.scoutType
	var scoutType = GM_getValue('scoutType', 0);
	if (scoutType < 0) GM_setValue('scoutType', 0); //Normal scout
	if (scoutType > 1) GM_setValue('scoutType', 1); //Deep recon scout

	var scoutType = GM_getValue('reconnaiterType', 0);
	if (scoutType < 0 ) GM_setValue('reconnaiterType', 0); //Fleet recon //|| > 2
	if (scoutType == 1) GM_setValue('reconnaiterType', 1); //Struct recon
	if (scoutType == 2) GM_setValue('reconnaiterType', 2); //Both, fleet and structure recon

	var scoutType = GM_getValue('inputNumberOfShips', 1);
	if (scoutType < 0 ) GM_setValue('inputNumberOfShips', 1); //Number of ships to use
}

function initTable()
{
	//Setup initial table contents
	var filterSpan = document.createElement("span");
	filterSpan.innerHTML =	'<table width="100%" cellspacing="0" cellpadding="0"><tbody id="multiScoutBody">' +
							'<tr id="multiScoutHeader"><td width="100%" colspan="3" class="v11-TABLE-title">Multi Scout</td></tr>' +
							'<tr><td width="100%" colspan="3" class="v11-cell-normal">Scout type: ' +
							'<label for="multiScout-OptReconnaiterType0"><input type="radio" name="multiScout-OptReconnaiterType" value="0" id="multiScout-OptReconnaiterType0"> Fleet</label>' +
							'<label for="multiScout-OptReconnaiterType1"><input type="radio" name="multiScout-OptReconnaiterType" value="1" id="multiScout-OptReconnaiterType1"> Structures</label>' +
							'<label for="multiScout-OptReconnaiterType2"><input type="radio" name="multiScout-OptReconnaiterType" value="2" id="multiScout-OptReconnaiterType2"> Both</label>' +
							'<label for="multiScout-OptTypeScout0"><input type="radio" name="multiScout-OptTypeScout" value="0" id="multiScout-OptTypeScout0"> Scout</label>' +
							'<label for="multiScout-OptTypeScout1"><input type="radio" name="multiScout-OptTypeScout" value="1" id="multiScout-OptTypeScout1"> Deep Recon</label>' +
							'<label for="multiScout-InNumberOfShips0"><input type="text" name="multiScout-InNumberOfShips" value="1" size = "4" id="multiScout-InNumberOfShips0"> Number of ships</label>' +
							'  <button id="multiScout-OptAgeThreshold">Highlight threshold</button>' +
							'</td></tr>' +
							'<tr id="multiScout-PlanetAddRow"><td width="100%" colspan="3" class="v11-cell-normal">' +
							'Track a new planet: <span id="multiScout-PlanetListHolder"></span> <button id="multiScout-PlanetAdd">Track planet</button> <button id="multiScout-PlanetAddAll">Track all planets</button>' +
							'  Selected planets: <button id="multiScout-PlanetSelAll">Select all</button> <button id="multiScout-PlanetSelNone">Deselect all</button>' +
							' <button id="multiScout-PlanetSelScout">Scout</button> <button id="multiScout-PlanetSelDelete">Delete</button>' +
							'</td></tr>' +
							'</tbody></table><br>';
	document.body.appendChild(filterSpan);

	//Fill in 'add planet'
	var planetList = document.getElementsByName("TargetID")[0].cloneNode(true);
	planetList.id = "multiScout-PlanetList";
	document.getElementById("multiScout-PlanetListHolder").appendChild(planetList);
	document.getElementById("multiScout-PlanetAdd").addEventListener('click', addPlanet, false);
	document.getElementById("multiScout-PlanetAddAll").addEventListener('click', addAllPlanets, false);

	//Select scout type default is Normal Scout
	document.getElementById("multiScout-OptTypeScout"+GM_getValue('scoutType', 0)).checked = true;

	//Select reconnaiter type default is fleet
	document.getElementById("multiScout-OptReconnaiterType"+GM_getValue('reconnaiterType', 0)).checked = true;

	//Set option handlers
	document.getElementById("multiScout-OptTypeScout0").addEventListener('click', function () { GM_setValue('scoutType', 0); }, false);
	document.getElementById("multiScout-OptTypeScout1").addEventListener('click', function () { GM_setValue('scoutType', 1); }, false);

	document.getElementById("multiScout-OptReconnaiterType0").addEventListener('click', function () { GM_setValue('reconnaiterType', 0); }, false);
	document.getElementById("multiScout-OptReconnaiterType1").addEventListener('click', function () { GM_setValue('reconnaiterType', 1); }, false);
	document.getElementById("multiScout-OptReconnaiterType2").addEventListener('click', function () { GM_setValue('reconnaiterType', 2); }, false);

	//document.getElementById("multiScout-InNumberOfShips0").addEventListener('click', function () { GM_setValue('inputNumberOfShips', 1); }, false);

	document.getElementById("multiScout-OptAgeThreshold").addEventListener('click', function ()
	{GM_setValue('scoutType', prompt("Highlight planets that haven't been scouted for how many turns? (Changes take effect after refresh.)", GM_getValue('ageThreshold', ageThreshold))); }, false);

	//Selected planet buttons
	document.getElementById("multiScout-PlanetSelAll").addEventListener('click', function () { selectAll(true); }, false);
	document.getElementById("multiScout-PlanetSelNone").addEventListener('click', function () { selectAll(false); }, false);
	document.getElementById("multiScout-PlanetSelScout").addEventListener('click', sendScoutToMany, false);
	document.getElementById("multiScout-PlanetSelDelete").addEventListener('click', deleteSelected, false);
}

function addPlanet()
{
	//Add planet
	var selectedPlanet = document.getElementById("multiScout-PlanetList").options[document.getElementById("multiScout-PlanetList").selectedIndex];

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
	//greasemonkey.scriptvals.tag:SRMultiScout,2006-11-27:SRMultiScout/SR Multi Scout.planetDate-12
	var curTime = new Date();
	GM_setValue('planetDate-'+selectedPlanet.value, Math.floor(curTime.getTime()/1200000) - ageThreshold);

	addRow(selectedPlanet.value, true);
}

function addAllPlanets()
{
	//Add planet
	//greasemonkey.scriptvals.tag:SRMultiScout,2006-11-27:SRMultiScout/SR Multi Scout.planets = ",1,20,30"
	var planets = document.getElementById("multiScout-PlanetList").options;
	var planetListLength = document.getElementById("multiScout-PlanetList").length;
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
	newRow.id = "multiScout-PlanetRow"+planetID;

	//Planet cell
	var planetCell = document.createElement("td");
	planetCell.className = "v11-column-header";
	planetCell.noWrap = true;

	//'Select' checkbox
	var planetCheck = document.createElement("input");
	planetCheck.type = "checkbox";
	planetCheck.name = "multiScout-PlanetSelect";
	planetCheck.value = planetID;
	planetCell.appendChild(planetCheck);

	//Find and select planet from list
	var planets = document.getElementById("multiScout-PlanetList").options;
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
	statusSpan.id = "multiScout-PlanetStatus"+planetID;

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
		statusSpan.appendChild(document.createTextNode("Scout should arrive in "+(0-age)+" turns. "));
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
	//Send scout
	var buttonSend = document.createElement('button');
	buttonSend.appendChild(document.createTextNode("Send scout"));
	buttonSend.addEventListener('click', function () { changeStatus(planetID, "Attempting to send scout. "); sendScout(planetID); }, false);
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
		document.getElementById("multiScoutBody").insertBefore(newRow, document.getElementById("multiScout-PlanetAddRow"));
	}
	else
	{
		document.getElementById("multiScoutBody").insertBefore(newRow, document.getElementById("multiScoutHeader").nextSibling);
	}
}

function changeStatus(planetID, message)
{
	var statusSpan = document.getElementById("multiScout-PlanetStatus"+planetID);
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
	document.getElementById("multiScoutBody").removeChild(document.getElementById("multiScout-PlanetRow"+planetID));
}

function sendScoutWithUrl(postdata, planetID)
{
	GM_xmlhttpRequest(
	{
		method: 'POST',
		url: 'http://sr.primeaxiom.com/fleets_deploy2.asp',
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
					document.getElementById("multiScout-PlanetRow"+planetID).childNodes[1].style.backgroundColor = "#BBFFBB";
					changeStatus(planetID, "Scout should arrive in "+matches[1]+" turns. ");
					selectPlanet(planetID, false);
				}
				else
				{
					matches = responseDetails.responseText.match(/You haven't dispatched any units!/);

					if (matches)
					{
						alert("Could not send scout. You may have no more available");
					}
					else
					{
						matches = responseDetails.responseText.match(/We are unable to attack that planet due to their current diplomatic status./);

						if (matches)
						{
							alert("Could not send scout. Unable to send because of diplomatic status.");
						}
						else
						{
							alert("Could not send scout. You may have no more available, you ran out of crew, or you are allied with this planet.");
						}
					}

					// Debugging: it will add the response below the page
					/*
					var debugSpan=document.createElement("span");
					debugSpan.style.color = '#FFFFFF';
					debugSpan.appendChild(document.createTextNode("DEBUG TEXT: \n"+responseDetails.responseText));
					document.body.appendChild(debugSpan);
					*/
				}
			}
			else
			{
				//404, ...
				alert("Could not send scout error number: " + responseDetails.status + " " + statusText);
			}
		}
	});
}

function sendScout(planetID)
{
	var postdata = '';
	var numberOFShips = document.getElementById("multiScout-InNumberOfShips0").value;

	//We send out the scout
	if (GM_getValue('reconnaiterType',0) == 0)
	{
		//Reconnaiter Fleet
		postdata = "AttackTypeID=6"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('scoutType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		sendScoutWithUrl(postdata, planetID);
	}
	else if (GM_getValue('reconnaiterType',0) == 1)
	{
		//Reconnaiter Structures
		postdata = "AttackTypeID=7"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('scoutType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		sendScoutWithUrl(postdata, planetID);
	}
	else if (GM_getValue('reconnaiterType',0) == 2)
	{
		//Reconnaiter Both structure and Fleet
		postdata = "AttackTypeID=6"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('scoutType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		//Fleet
		sendScoutWithUrl(postdata, planetID);

		postdata = "AttackTypeID=7"; //scouting
		postdata = postdata + "&Unit_"+(5+GM_getValue('scoutType',0))+"="+numberOFShips;
		postdata = postdata + "&TargetID="+planetID;

		//Structure
		sendScoutWithUrl(postdata, planetID);
	}
}

function selectPlanet(planetID, state)
{
	var checkboxes = document.getElementsByName("multiScout-PlanetSelect");
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
	var checkboxes = document.getElementsByName("multiScout-PlanetSelect");
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
	var checkboxes = document.getElementsByName("multiScout-PlanetSelect");
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

function sendScoutToMany()
{
	//Send many scouts at once
	doSelected(function (planetID){	changeStatus(planetID, "Attempting to send scout. "); sendScout(planetID);});
}

function selectAll(state)
{
	//Select all checkboxes
	var checkboxes = document.getElementsByName("multiScout-PlanetSelect");
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
		var checkboxes = document.getElementsByName("multiScout-PlanetSelect");
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