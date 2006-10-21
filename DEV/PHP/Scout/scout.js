var http1 = createRequestObject();
var http2 = createRequestObject();
var http3 = createRequestObject();
var activePlanet = 0;

function createRequestObject(){
	var request_o;
	var browser = navigator.appName;
	if (browser == "Microsoft Internet Explorer") {
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		request_o = new XMLHttpRequest();
	}
	return request_o;
}

function getSummary(aPlanet) {
	if (aPlanet != null) {
		activePlanet = aPlanet;
	} else {
		activePlanet = '';
	}

	var d = new Date();
	var t = d.getTime();
	http1.open('get', 'scout_output.php?action=summary&ts=' + t);
	http1.onreadystatechange = displaySummary; 
	http1.send(null);
}

function displaySummary() {
	if(http1.readyState == 4){ 
		var response = http1.responseText;
		gE('summary').innerHTML =  response;
		if (activePlanet == '') {
			var planet = gE('p_1').innerHTML;
		} else {
			var planet = activePlanet;
		}
		getPlanet(planet);
	}
}


function getPlanet(id) {
	http2.open('get', 'scout_output.php?action=planet&planet=' + id);
	http2.onreadystatechange = displayPlanet; 
	http2.send(null);
}

function displayPlanet() {
	if(http2.readyState == 4){ 
		var response = http2.responseText;
		gE('planet').innerHTML =  response;
		var report = gE('d_1').innerHTML;
		getDetail(report);
	}
}


function getDetail(id) {
	http3.open('get', 'scout_output.php?action=detail&report=' + id);
	http3.onreadystatechange = displayDetail; 
	http3.send(null);
}

function displayDetail() {
	if(http3.readyState == 4){ 
		var response = http3.responseText;
		gE('detail').innerHTML =  response;
	}
}



function processInput() {
	var report = gE('hiddenHolder').innerText;
	
	report = ConvertCRLF(report);
	report = encodeURI(report);

	http1.open('get', 'scout_internals.php?action=input&report=' + report);
	http1.onreadystatechange = displayTable; 
	http1.send(null);
}

function displayTable() {
	if (http1.readyState == 4) { 
		var response = http1.responseText;
		if (isNaN(response)) {
			alert('ERROR: ' + response + ' Scout report was not recorded.');
		} else {
			
			getSummary(response);
		}
	}
}


function toggleInput() {
	var formName       = 'Editbox';
	var dialogName     = 'ScoutReportDialog';
	var defaultValues  = '';
	var returnCtrl     = 'hiddenHolder';
	var btnName        = 'btn_dialog';
	var xPos           = 80;
	var yPos           = 60;
	var functionsToRun = 'processInput()';
	calSwapImg(btnName, 'img_Date_DOWN');
	callDialog(formName,dialogName,defaultValues,returnCtrl,btnName,xPos,yPos,'',functionsToRun);
}


function ConvertCRLF(input) {
	var output = "";
	for (var i = 0; i < input.length; i++) {
		//if ((input.charCodeAt(i) == 13) && (input.charCodeAt(i + 1) == 10)) {
		if ((input.charCodeAt(i) == 13) ) {
			//i++;
			output += "|";
		} else if (input.charCodeAt(i) == 10) {
			output += "|";
		} else {
			output += input.charAt(i);
		}
	}
	return output;
}


function sortColumn(sort) {
	if (sort == null) sort = 'planet';
	var d = new Date();
	var t = d.getTime();
	http1.open('get', 'scout_output.php?action=summary&sort=' + sort + '&ts=' + t);
	http1.onreadystatechange = displaySummary; 
	http1.send(null);
}


function glowObject(obj) {
	obj.style.backgroundColor = '#FF00FF';
}
function dimObject(obj) {
	obj.style.backgroundColor = '#DCDCDC';
}