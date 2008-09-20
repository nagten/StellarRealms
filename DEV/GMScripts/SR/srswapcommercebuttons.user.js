// ==UserScript==
// @name          SwapCommerceButton
// @namespace     SwapCommerceButton
// @description   Swap Commerce Button confirm cancel
// @include       http://sr.primeaxiom.com/commerce_sell.asp
// ==/UserScript==

var table = document.body.childNodes[4].childNodes[1].childNodes[41];

//Locate the cancel node
var cancelnode = table.childNodes[1].childNodes[2].childNodes[1].childNodes[3].childNodes[9];
var cloneNode = cancelnode.cloneNode(true);

//Clone the node
table.childNodes[1].childNodes[2].childNodes[1].childNodes[3].appendChild(cloneNode);

//Remove the old cancel node
table.childNodes[1].childNodes[2].childNodes[1].childNodes[3].removeChild(cancelnode)