
// initialize variables... 
var ppcIE=((navigator.appName == "Microsoft Internet Explorer") || ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion)==5)));
var ppcNN6=((navigator.appName == "Netscape") && (parseInt(navigator.appVersion)==5));
var ppcNN=((navigator.appName == "Netscape")&&(document.layers));
var ppcX = 4;
var ppcY = 4;

var IsDialogVisible;
var srcFormName;
var curImg;
var currentDialog;
var hideDropDowns;
var FuncsToRun;
var img_del;
var img_close;

img_del=new Image();
img_del.src="./Images/btn_del_small.gif";

img_close=new Image();
img_close.src="./Images/btn_close_small.gif";

img_Date_UP=new Image();
img_Date_UP.src="./Images/btn_date_up2.gif";
//img_Date_UP.src="./Images/btn_dialog_up.gif";

img_Date_OVER=new Image();
img_Date_OVER.src="./Images/btn_date_over.gif";
//img_Date_OVER.src="./Images/btn_dialog_over.gif";

img_Date_DOWN=new Image();
img_Date_DOWN.src="./Images/btn_date_down.gif";
//img_Date_DOWN.src="./Images/btn_dialog_down.gif";

var indx;

IsDialogVisible = false;

//===============================================================
function calSwapImg(whatID, NewImg, override) {
	if (document.images) {
		if (!( IsDialogVisible && override )) {
			document.images[whatID].src = eval(NewImg + ".src");
		}
	}
	window.status=' ';
	return true;
}


//================================================================
function getOffsetLeft (el) {
    var ol = el.offsetLeft;

    while ((el = el.offsetParent) != null)
        ol += el.offsetLeft;
    return ol;
}


//================================================================
function getOffsetTop (el) {
    var ot = el.offsetTop;
    while((el = el.offsetParent) != null)
        ot += el.offsetTop;
    return ot;
}


//================================================================
function callDialog(frmName,dialogName,defaultValues,rtnCtrls,btnImg,xPos,yPos,hideDrops,runFuncs) {

	hideDropDowns = hideDrops;
	FuncsToRun    = runFuncs;
	srcFormName   = frmName;

	if (IsDialogVisible) {
		hideDialog();
		if (currentDialog != dialogName) {
			currentDialog = dialogName;
			showDialog(btnImg,dialogName,defaultValues,rtnCtrls,xPos,yPos);
		}
    } else {
		currentDialog = dialogName;
		showDialog(btnImg,dialogName,defaultValues,rtnCtrls,xPos,yPos);
    }
}


//====================================================================
function showDialog(btnImg,dialogName,defaultValues,rtnCtrls,xPos,yPos) {
	if (document.images['calbtn1']!=null ) document.images['calbtn1'].src = img_del.src;
	if (document.images['calbtn2']!=null ) document.images['calbtn2'].src = img_close.src;

	if (hideDropDowns) {toggleDropDowns('hidden');}

	curImg = btnImg;




	switch (dialogName) {
		case 'ScoutReportDialog' :
			displayContent('popup_layer',1,xPos,yPos,ScoutReportDialog(defaultValues,rtnCtrls));
			break;
	}

	IsDialogVisible = true;
}


//--- Scout Report Dialog -------------------------------------------------
function ScoutReportDialog(defaultValues,rtnCtrl) {

	var output = '';

    output += '<form name="SCOUT">';      
    output += '<table width="620px" border="3" class="time-Table" cellspacing="0" cellpadding="3">';

    output += '<tr>';
	output += '<td width="100%" class="time-HeadCell" align="center">Input Scout Report';
	output += '</td>';
	output += '</tr>';
     
    output += '<tr>';
	output += '<td class="time-HeadCell" align="center" width="100%">';
	output += '<textarea id="textarea" cols=110 rows=20 class="textarea"></textarea>';
	output += '</td></tr>';

	output += '<tr>';
	output += '<td class="time-HeadCell" align=center>';
	output += '<input type=button onclick=DialogSubmit(\'' + rtnCtrl + '\') value=Submit>';
	output += '&nbsp;&nbsp;&nbsp;';
	output += '<input type=button onclick=hideDialog() value=Cancel>';
	output += '</td></tr>';
    
	output += '</table>';
    output += '</form>';
    return output;
}


//====================================================================
function hideDialog() {
    displayContent('popup_layer',0,ppcX,ppcY);
    calSwapImg(curImg, 'img_Date_UP');    
    IsDialogVisible = false;
    if (hideDropDowns) {toggleDropDowns('visible');}
}



//====================================================================
function DialogSubmit(rtnCtrl) {
	gE(rtnCtrl).innerText = gE('textarea').value;
    hideDialog();

    if (FuncsToRun!=null) {
		eval(FuncsToRun); 
	}
}



//====================================================================
function displayContent(id,trigger,lax,lay,content) {
	
    //* Cross browser Layer visibility / Placement Routine
    // Make layer visible
    if (trigger == "1") {
        if (document.layers) {
			document.layers[''+id+''].visibility = "show";
        } else {
			if (document.all) {
				document.all[''+id+''].style.visibility = "visible";
			} else {
				if (document.getElementById) {
					document.getElementById(''+id+'').style.visibility = "visible";
				}
			}
        }
    // Make layer hidden
    } else {
		if (trigger=="0") {
			if (document.layers) {
				document.layers[''+id+''].visibility = "hide";
			} else {
				if (document.all) {
					document.all[''+id+''].style.visibility = "hidden";
				} else {
					if (document.getElementById) {
						document.getElementById(''+id+'').style.visibility = "hidden";
					}
				}
			}
        }
	}


    // Set horizontal position  
    if (lax){
        if (document.layers){document.layers[''+id+''].left = lax}
        else if (document.all){document.all[''+id+''].style.left=lax}
        else if (document.getElementById){document.getElementById(''+id+'').style.left=lax+"px"}
        }
    
    // Set vertical position
    if (lay){
        if (document.layers){document.layers[''+id+''].top = lay}
        else if (document.all){document.all[''+id+''].style.top=lay}
        else if (document.getElementById){document.getElementById(''+id+'').style.top=lay+"px"}
        }
        
    // Change content
    if (content){
		if (document.layers){
			sprite=document.layers[''+id+''].document;
			// add father layers if needed! document.layers[''+father+'']...
			sprite.open();
			sprite.write(content);
			sprite.close();
        }
		else if (document.all) document.all[''+id+''].innerHTML = content;  
		else if (document.getElementById){
        rng = document.createRange();
        el = document.getElementById(''+id+'');
        rng.setStartBefore(el);
        htmlFrag = rng.createContextualFragment(content)
        while(el.hasChildNodes()) el.removeChild(el.lastChild);
        el.appendChild(htmlFrag);
        }
    }
	
}



//====================================================================
function toggleDropDowns(showHow){
    var i; var j;
    for (i=0;i < document.forms.length;i++) {
        for (j=0;j < document.forms[i].elements.length;j++) {
            if (document.forms[i].elements[j].tagName == "SELECT") {
                if (document.forms[i].name != "TPPU")
                    document.forms[i].elements[j].style.visibility=showHow;
            }
        }
    }
}


//====================================================================
function calClick() {
        window.focus();
}



