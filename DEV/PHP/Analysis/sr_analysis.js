var LastWindow = 'overview';
function ShowGainLoss() {
	gE(LastWindow).style.display = 'none';
	gE('gainloss').style.display = '';
	LastWindow = 'gainloss';
}
function ShowOverview() {
	gE(LastWindow).style.display = 'none';
	gE('overview').style.display = '';
	LastWindow = 'overview';
}
function ShowRankHistory() {
	gE(LastWindow).style.display = 'none';
	gE('rankhistory').style.display = '';
	LastWindow = 'rankhistory';
}
function ShowPrestigeAverage() {
	gE(LastWindow).style.display = 'none';
	gE('prestigeaverage').style.display = '';
	LastWindow = 'prestigeaverage';
}
function ShowPrestigeHistory() {
	gE(LastWindow).style.display = 'none';
	gE('prestigehistory').style.display = '';
	LastWindow = 'prestigehistory';
}
function ShowThreats() {
	gE(LastWindow).style.display = 'none';
	gE('threats').style.display = '';
	LastWindow = 'threats';
}
function glowObject(obj) {
	obj.style.backgroundColor = '#FFFF00';
}
function dimObject(obj) {
	obj.style.backgroundColor = '#DCDCDC';
}