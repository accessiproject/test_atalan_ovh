class SelectTableAjaxClass {
	constructor(iddiv,parameter) {
		this.iddiv=iddiv;
		this.parameter=parameter;
		this.div=document.getElementById(iddiv);
		this.creationParameter();
	}
	
	/*
	creationParameter(action) {
		this.selects=this.div.querySelectorAll("select");
		if (action=="select") {
			for (let i=0;i<this.selects.length;i++) {
				
				if (this.selects[i].value=="")
					this.value=this.selects[i].id;
				else
					this.value=this.selects[i].value;
				
					if (this.parameter!=null) {
					this.param=this.parameter + "&" + this.selects[i].id + "=" + this.value;
					console.log(this.param);
				} else {
					this.param=this.selects[i].id + "=" + this.value;
					console.log(this.param);
				}
				
				script_ajax(this.param);
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
				this.argument=this.parameter + "&" + this.argument;
		}
	}
	*/

	creationParameter() {
		this.selects=this.div.querySelectorAll("select");
		if (action=="select") {
			for (let i=0;i<this.selects.length;i++) {
				
				if (this.selects[i].value=="")
					this.value=this.selects[i].id;
				else
					this.value=this.selects[i].value;
				
					if (this.parameter!=null) {
					this.param=this.parameter + "&" + this.selects[i].id + "=" + this.value;
					console.log(this.param);
				} else {
					this.param=this.selects[i].id + "=" + this.value;
					console.log(this.param);
				}
				
				script_ajax(this.param);
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
				this.argument=this.parameter + "&" + this.argument;
		}
	}
}