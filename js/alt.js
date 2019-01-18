window.onload = function(){
	
	var main = document.querySelector('main');
	var selectCycles = document.querySelector('#cycles');
	var form = document.querySelector('form');
	
	//Variables for index properties
	var tables;
	if(document.querySelector('#json_data')){
		var jsonData = document.querySelector('#json_data');
		tables = jsonData.querySelectorAll('table');
	}
	
	
	//Showing tables
	//Stocking tables in Array
	var sortedTables = [];
	if(tables){
		tables.forEach(function(table){
			sortedTables.push(table);
		});
	}
	//console.log(sortedTables);
	const mobile = 320;
	const desktop = 960;
	
	if(window.innerWidth <= mobile){
		
		if(sortedTables.length > 0){
			main.appendChild(sortedTables[0]);
		}
		
	}else if(window.innerWidth >= desktop){
		
		sortedTables.forEach(function(table){
			main.appendChild(table);
		})
		
	}
	
	//Select cycle to view----------------------------
	function showCycle(e){
		var cycle = e.target.value;
		var s = main.children[2];
		s.remove();
		var choosen = sortedTables.find(function(table){
			return table.caption.className == cycle;
		});
		main.appendChild(choosen);
	}
	if(selectCycles){
		selectCycles.addEventListener('change', showCycle);
	}
	
	
	//Close message------------------------------------
	function closeMessage(){
		
		if(document.querySelector('#close')){
			var close = document.querySelector('#close');
			setTimeout(function(){close.parentNode.remove()}, 3000);
			close.addEventListener('click', function(){
				this.parentNode.remove();
			});
		}	
	}
	if(form){
		form.onsubmit = closeMessage();
	}
	
}