// ==UserScript==
// @name          SR Commerce
// @namespace     SRCommerce
// @description   Calculate average commerce price
// @include       http://sr.primeaxiom.com/commerce.asp
// ==/UserScript==

var quantity;
var price;
var table = document.body.childNodes[7].childNodes[3].childNodes[11].childNodes[1].childNodes[1];
var tablelength = table.childNodes.length;

//Skip the 3 first lines (table header) and advance with 2 (tableborder)
for (var intI=4; intI<tablelength; intI = intI + 2)
{
	//get the quantity out the table
 	quantity = table.childNodes[intI].childNodes[3].firstChild.nodeValue;

	if (table.childNodes[intI].childNodes[7].childNodes.length > 1)
	{
		//We got a special node
		if (table.childNodes[intI].childNodes[7].childNodes[1].nodeName == 'INPUT')
		{
			//INPUT node, get the price out the table
			price = table.childNodes[intI].childNodes[7].childNodes[1].value;

			table.childNodes[intI].childNodes[7].firstChild.nodeValue = ' (' + Math.round((price/quantity)*100)/100 +') ';
		}

		if (table.childNodes[intI].childNodes[7].childNodes[1].nodeName == 'FONT')
		{
			//FONT node, get the price out the table
			price = table.childNodes[intI].childNodes[7].childNodes[1].firstChild.nodeValue;

			table.childNodes[intI].childNodes[7].childNodes[1].firstChild.nodeValue = price + ' (' + Math.round((price/quantity)*100)/100 +')';
		}
 	}
	else
	{
		//Normal Node, get the price out the table
		price = table.childNodes[intI].childNodes[7].firstChild.nodeValue;

 		table.childNodes[intI].childNodes[7].firstChild.nodeValue = price + ' (' + Math.round((price/quantity)*100)/100 +')';
	}
}