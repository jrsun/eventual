
tablearr = new Array();

window.onload=function() {
	makeTable();
}

function makeTable() {

	row=new Array();
	cell=new Array();
	hcont= new Array();
	hcell= new Array();


	tab=document.createElement('table');
	tab.setAttribute('id','newtable');

	tbo=document.createElement('tbody');
	
	thd = document.createElement('thead');
	hr = document.createElement('tr');

	hcont[0] = document.createTextNode('Event');
	hcont[1] = document.createTextNode('Location');
	hcont[2] = document.createTextNode('Time');
	hcont[3] = document.createTextNode('Category');
	for(i=0;i<4;i++){
		hcell[i] = document.createElement('th');
		hcell[i].appendChild(hcont[i]);
		hr.appendChild(hcell[i]);
	}
	
	thd.appendChild(hr);
	tab.appendChild(thd);

	for(c=0;c<tablearr.length;c++){
		row[c]=document.createElement('tr');
		
		for(k=0;k<4;k++) {
			cell[k]=document.createElement('td');
			if(k==0){
				cont=document.createElement('a');
				cont.setAttribute('href', 'main.php?eventid='+tablearr[c][0]);
				cont1=document.createTextNode(tablearr[c][1]);
				cont.appendChild(cont1);
			}else{
			cont=document.createTextNode(tablearr[c][k+1]);
			}
			cell[k].appendChild(cont);
			row[c].appendChild(cell[k]);
		}
		tbo.appendChild(row[c]);
	}
	tab.appendChild(tbo);
	document.getElementById('eventstable').appendChild(tab);
}
