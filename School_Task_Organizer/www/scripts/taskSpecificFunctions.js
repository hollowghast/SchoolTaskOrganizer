function showTaskInput(){
	var form = document.getElementById('taskInputForm');
	if(form.style.display == "none"){
		form.style.display = "block";
	}
	else{
		form.style.display = "none";
	}
	
}

function toggleTaskDone(id){
	var btn = document.getElementById(id);
	
	if(btn.innerHTML == "\u26AA"){
		btn.innerHTML = "\u2713";
	}else{
		btn.innerHTML = "\u26AA";
	}
}