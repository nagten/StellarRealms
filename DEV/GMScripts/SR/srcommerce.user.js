// ==UserScript==
// @name          SR Commerce
// @namespace     SRCommerce
// @description   Calculate average commerce price
// @include       http://sr.primeaxiom.com/commerce.asp
// ==/UserScript==

var quantity;
var price;
var table = document.body.childNodes[7].childNodes[3].childNodes[5].childNodes[1].childNodes[1];
var tablelength = table.childNodes.length;

//Skip the 3 first lines (table header) and advance with 2 (tableborder)
for (var intI=4; intI<tablelength; intI = intI + 2)
{
	//get the quantity out the table
 	quantity = table.childNodes[intI].childNodes[3].firstChild.nodeValue;
 	
 	//get the price out the table
 	price = table.childNodes[intI].childNodes[7].firstChild.nodeValue;
 
 	//Calculate the average price and append the Price table element with the average price 
 	table.childNodes[intI].childNodes[7].firstChild.nodeValue = price + ' (' + Math.round((price/quantity)*100)/100 +')';	
}