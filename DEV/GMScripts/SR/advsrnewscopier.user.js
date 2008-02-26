// ==UserScript==
// @name          SR News Copier
// @namespace     tag:SRNewsCopier
// @description   Add functions to Stellar-Realms News page for easy data sharing.
// @include       http://sr.primeaxiom.com/news.asp
// ==/UserScript==

(function()
{
   	var scouturl = ['http://www.idsfadt.com/Cabal/Scout/scout_internals.php?action=input&report='];
	var failedReports = 0;

    //Extract the usefull data from a scout report.
    function extractScoutMsg(msgToExtract,delimiter)
	{
	        var msgExtd, msgDefenses;

			//get date
			//GM_log(msgToExtract.childNodes[0].nodeValue.match(/Date:\s+([^M]*M)/)[1] + delimiter);
	        msgExtd = msgToExtract.childNodes[0].nodeValue.match(/Date:\s+([^M]*M)/)[1] + delimiter;

	        //get from
	        //GM_log(msgToExtract.childNodes[2].nodeValue.match(/From:\s+(.*)/)[1] + delimiter);
	        msgExtd += msgToExtract.childNodes[2].nodeValue.match(/From:\s+(.*)/)[1] + delimiter;

			//get type strcutures or fleet recon
			//GM_log(msgToExtract.childNodes[8].nodeValue.match(/to (.*) at/)[1] + delimiter);
			msgExtd += msgToExtract.childNodes[6].nodeValue.match(/to (.*) at/)[1] + delimiter;

			//get planet
			//GM_log(msgToExtract.childNodes[6].nodeValue.match(/at (.*)./)[1]  + delimiter);
	     	msgExtd += msgToExtract.childNodes[6].nodeValue.match(/at (.*)./)[1]  + delimiter;

			//Get defenses
	        msgDefenses = msgToExtract.innerHTML.replace(/\n/g, " ").match(/defending force consisted of ([^.]*)\./);

	        if (msgDefenses)
	        {
	            msgExtd += msgDefenses[1];
	        }
	        else
	        {
				//check for a failed scouting
				msgDefenses = msgToExtract.innerHTML.replace(/\n/g, " ").match(/we have not heard from the fleet ([^.]*)\./);

	            if (msgDefenses)
	        	{
					//Failed scouting
					msgExtd += 'However, we have not heard from the fleet for their regular status updates';
					failedReports++;
				}
				else
				{
					//No defending forces were found
					msgExtd += 'No defending forces were found.';
				}

	        }
	        return msgExtd;
    }

    //Event handler that sends the usefull data from a scout report to the Scout Report Tool:
    function scoutToDatabase(ev)
    {
        for (intI=0;intI<scouturl.length;intI++)
		{
			var request = extractScoutMsg(ev.currentTarget.parentNode,'<br>');

   		 	request = scouturl[intI] + encodeURIComponent(request);
        	GM_xmlhttpRequest({
	            method: 'GET',
	            url: request,
	            headers: {'User-agent': 'Mozilla/4.0 (compatible) Greasemonkey'},
	            onload: function(rD) {alert('status: ' + rD.statusText);},
	            onerror: function(rD) {alert('Error: ' + rD.status);},
	            onreadystatechange: function(rD) {var blub = rD.status;}
        	});
        }
    }

	/* Creates a submit button with a given:
    *   Name (inputName)
    *   Caption (inputValue)
    *   OnClick event handler (clickHandler)
    */
    function createClickButton(inputName, inputValue, clickHandler)
    {
        btnInput = document.createElement('input');
        btnInput.setAttribute('type','submit');
        btnInput.setAttribute('name',inputName);
        btnInput.setAttribute('value',inputValue);
        btnInput.setAttribute('style','margin-right: 2pt;');
        btnInput.addEventListener('click',clickHandler,false);
        return(btnInput);
    }

    // -------- Main -------- //
    var tbody = document.getElementsByTagName("center")[1].childNodes[5].childNodes[1];
    var cellMsg;

    for (i = 3; i < tbody.childNodes.length / 2; i++)
    {
        cellMsg = tbody.childNodes[i*2].childNodes[3];

       	if (cellMsg.childNodes[6].nodeValue.match(/reconnoiter structures/) || cellMsg.childNodes[6].nodeValue.match(/conduct fleet reconnaissance/))
        {
            cellMsg.setAttribute('msgType', 'StructScout');
            cellMsg.appendChild(createClickButton('StD' + i,'Scout to Database',scoutToDatabase));
        }
    }

    /* Send scouting reports to the database -------- //
    *
    *   Still need some cleaning up and need to rewrite the doselected function
    *   otherwise the selectall is a bit useless :)
    */

    function initTable()
    {
        // Setup initial table contents with 4 extra buttons
        var filterSpan = document.createElement("span");
        filterSpan.innerHTML =  '<table width="100%" cellspacing="0" cellpadding="0"><tbody id="multiScoutBody">' +
                                '<tr id="multiScoutHeader"><td width="100%" colspan="3" class="v11-TABLE-title">Multi Scout Submit</td></tr>' +
                                '<tr><td width="100%" colspan="3" class="v11-cell-normal">' +
                                ' <button id="multiSubmitScout-ScoutReportsSelAll">Select all scouts</button>' +
                                ' <button id="multiSubmitScout-ScoutReportsSelNone">Deselect all scouts</button>' +
                                ' <button id="multiSubmitScout-SubmitToDB">Submit to DB</button>' +
								' <button id="multiSubmitScout-SetPauseLength">Set Pause Length</button>' +
                                '</td></tr>' +
                                '</tbody></table><br>';
        document.body.appendChild(filterSpan);

        // add 3 extra buttons to the event listener
        document.getElementById("multiSubmitScout-ScoutReportsSelAll").addEventListener('click', function () { selectAllScouts(true); }, false);
        document.getElementById("multiSubmitScout-ScoutReportsSelNone").addEventListener('click', function () { selectAllScouts(false); }, false);
        document.getElementById("multiSubmitScout-SubmitToDB").addEventListener('click', sendMultipleScoutToDB, false);
		document.getElementById("multiSubmitScout-SetPauseLength").addEventListener('click', setPauseLengthOnDbSubmit, false);
    }

    function selectAllScouts(state)
    {
        var checkboxes = document.getElementsByName("deleteBox");
        if (checkboxes) {
            for (var i=0; i<checkboxes.length; i++)
            {
                // Select,Deselect all scouting reports
                if (checkboxes[i].parentNode.parentNode.childNodes[3].childNodes[6].nodeValue.match(/reconnoiter structures/) || checkboxes[i].parentNode.parentNode.childNodes[3].childNodes[6].nodeValue.match(/conduct fleet reconnaissance/))
                {
                    checkboxes[i].checked = state;
                }
            }
        }
    }

	var scoutToDbArrayControl = 0;
	var reportsSent = 0;
	var blnSend = false;
	var pauseLengthBetweenReports = 200;

    function setPauseLengthOnDbSubmit()
    {
		pauseLengthBetweenReports = prompt("For testing you may change the pause between reports", "enter time in milliseconds");
    }

    function sendMultipleScoutToDB()
    {
        var checkboxes = document.getElementsByName("deleteBox");
        if (checkboxes)
        {
            if (checkboxes[scoutToDbArrayControl].checked)
                {
                    //just an extra test
                    if (checkboxes[scoutToDbArrayControl].parentNode.parentNode.childNodes[3].childNodes[6].nodeValue.match(/reconnoiter structures/) || checkboxes[scoutToDbArrayControl].parentNode.parentNode.childNodes[3].childNodes[6].nodeValue.match(/conduct fleet reconnaissance/))
		    		{
                        for (intI=0;intI<scouturl.length;intI++)
						{
							var request = extractScoutMsg(checkboxes[scoutToDbArrayControl].parentNode.parentNode.childNodes[3].childNodes[6].parentNode,'<br>');

                        	request = scouturl[intI] + encodeURIComponent(request);
                        	GM_log(request);
							GM_xmlhttpRequest({
								method: 'GET',
								url: request,
								headers: {'User-agent': 'Mozilla/4.0 (compatible) Greasemonkey'},
								onerror: function(rD) {alert('Error: ' + rD.status);},
								onreadystatechange: function(rD) {var blub = rD.status;}
							});

			      		 	blnSend = true;
						}
                    }
                    reportsSent++;
                }

                scoutToDbArrayControl++;
         }

         if (scoutToDbArrayControl == checkboxes.length && blnSend == true)
         {
                if (failedReports > 0)
                {
					alert(reportsSent+ " reports sent to the DB including " + failedReports + " failed scouting reports");
				}
				else
				{
					alert(reportsSent+ " reports sent to the DB");
				}

                //reset values
                failedReports = 0;
                scoutToDbArrayControl = 0;
                reportsSent = 0;
         }
         else if (scoutToDbArrayControl == checkboxes.length && blnSend == false)
         {
            alert("No new reports sent. Last processed " + scoutToDbArrayControl + " news messages out of a total of "+ checkboxes.length + " messages." + "Last sent " + reportsSent + " reports to the DB");
         }
         else
         {
            window.setTimeout(sendMultipleScoutToDB, pauseLengthBetweenReports);
         }
    }

    initTable();

    // -------- End Main -------- //
})();