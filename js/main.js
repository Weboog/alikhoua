window.onload = function(){
	
	//Getting master nodes for work
	var select = document.querySelectorAll('select.done');
	var aside = document.querySelector('aside > div');
	var registerForm = aside.querySelector('form');
	var elements = registerForm.querySelector('div.elements');
	var placeholder = elements.querySelector('p#placeholder');
	var buttons = registerForm.querySelector('div');
	var cancel = document.querySelector('#cancel');
	
	//---------------------------------------------------------
	var main = document.querySelector('main');
	var selectCycles = document.querySelector('#cycles');
	var form = document.querySelector('form');
	var jsonData = document.querySelector('#json_data');
	var tables = jsonData.querySelectorAll('table');
	
	//Stocking tables in Array
	var sortedTables = [];
	tables.forEach(function(table){
		sortedTables.push(table);
	});
	main.appendChild(sortedTables[0]);
	//Assign Event to all select
	for(var i = 0;  i < select.length; i++){
		var selected = select[i];
		selected.addEventListener('change', addToList);
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
	selectCycles.addEventListener('change', showCycle);

	
	//Event Handler
	function addToList(e){
		var sel = e.target;
		if(sel.value == 1){
			cycle = String(sel.dataset.cycle);
			apt = sel.dataset.apt;
			var p = document.createElement('p');
			p.setAttribute('data-apt', cycle+apt);
			var y = cycle.slice(0, 4);
			var m = cycle.slice(4, 6);//201805
			p.innerHTML = 'Apt: '+apt+' ------- '+m+'/'+y+' ------- 100 DH';
			elements.appendChild(p);
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'apt[]';
			input.value = cycle+'|'+apt;
			registerForm.insertBefore(input, buttons);
            if(elements.querySelector('p#placeholder')){
                elements.querySelector('p#placeholder').remove();
			}
		}else{
			cycle = sel.dataset.cycle;
			apt = sel.dataset.apt;
			var px = elements.querySelector('p[data-apt="'+cycle+apt+'"]');
			elements.removeChild(px);
			var inputToDel = document.getElementById('registerForm');
			var x = inputToDel.querySelector('input[value="'+cycle+'|'+apt+'"]');
			inputToDel.removeChild(x);
            if(!elements.hasChildNodes()){
                elements.appendChild(placeholder);
            }
		}
	}//----------------------------------------------------------------------------
	
	//-------------------------------------------------

	//Cancel--------------------------------------------
	function cancelList(){
		
		var hiddenTypes = registerForm.querySelectorAll('input[type=hidden]');
        for(var i = 0; i < hiddenTypes.length; i++){
            hiddenTypes[i].remove();
        }

		while(elements.firstChild){
			elements.removeChild(elements.firstChild);
		}
		for(var i = 0; i < select.length; i++){
			if(select[i].value == 1){
				select[i].value = 0;
			}
		}
        if(!elements.hasChildNodes()){
            elements.appendChild(placeholder);
        }
	}
	cancel.addEventListener('click',cancelList);
	
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
	registerForm.onsubmit = closeMessage();
		
};