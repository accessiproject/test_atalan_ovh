class SelectTableAjaxClass {
	constructor(iddiv,parameter,idtest) {
		this.iddiv=iddiv;
		this.parameter=parameter;
		this.idtest=idtest;
		this.idtest=document.getElementById(idtest);
		this.div=document.getElementById(iddiv);
		this.getXmlhttp();
		this.creationParameter("select");
		this.creationParameter("fill");
	}
	
	creationParameter(action) {
		this.selects=this.div.querySelectorAll("select");
		if (action=="select") {
			for (let i=0;i<this.selects.length;i++) {
				
				if (this.selects[i].value=="")
					this.value=this.selects[i].id;
				else
					this.value=this.selects[i].value;
				
					if (this.parameter!=null) {
					this.param=this.parameter.join("&") + "&" + this.selects[i].id + "=" + this.value;
					console.log(this.param);
				} else {
					this.param=this.selects[i].id + "=" + this.value;
					console.log(this.param);
				}
				
				this.fillTable(this.param);
			}
		} else {
			this.tab=[];
			for (let i=0;i<this.selects.length;i++) {
				this.selects[i].addEventListener("change",()=>this.creationParameter("fill"));
				
				if (this.selects[i].value=="")
					this.value=this.selects[i].id;
				else
					this.value=this.selects[i].value;
				
					this.tab.push(this.selects[i].id + "=" + this.value);
			}
			this.argument= this.tab.join("&");
			if (this.parameter!=null)
				this.argument=this.parameter.join("&") + "&" + this.argument;
			
			this.idtest.innerHTML=this.argument;
		}
	}
	
	//objet ajax
	getXmlhttp() {
		if (window.XMLHttpRequest)
			return new XMLHttpRequest();
		else if (window.ActiveXObject)
			return new ActiveXObject("Msxml2.XMLHTTP");
		else
			throw new Error("Could not create HTTP request object.");
	}
	
	fillTable(parameter) {
		console.log(parameter);
		/*
		xmlhttp=getXmlhttp();
		xmlhttp.open("GET","http://127.0.0.1:8000/ajax?"+parameter,true);    
		xmlhttp.onreadystatechange=mafonction;
		xmlhttp.send();
		function mafonction() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				let tab = JSON.parse(xmlhttp.responseText);     
				console.log(xmlhttp.responseText);          
				var select = document.getElementById("device");
				for (let i=0;i<tab.length;i++) {    
					opt=document.createElement("option");
					opt.innerHTML=tab[i].email;
					opt.value=i;
					select.appendChild(opt);   
				}
			}
		}
		*/
	}
}