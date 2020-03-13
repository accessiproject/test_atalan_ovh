class FormCollectionTypeClass {
	constructor(idol,idbuttonadd) {
		this.idol=idol;
		this.idbuttonadd=idbuttonadd;
		this.ol=document.getElementById(idol);
		this.ol.style.listStyleType="none";
		this.labels=this.ol.getElementsByTagName("label");
		for (let i=0;i<this.labels.length;i++) {
			this.tablabel=[];
			this.tablabel.push(this.labels[i].textContent);
		}
		this.edit_label();
		this.tab=this.ol.getElementsByTagName("li");
		console.log(this.tab[0]);
		this.create_button(this.tab[0],"delete");
		this.idbuttonadd=document.getElementById(idbuttonadd);
		this.create_button(this.idbuttonadd,"add");
	}
	
	edit_label() {
		this.tabli=this.ol.getElementsByTagName("li");
		for (let i=0;i<this.tabli.length;i++) {
			this.newtablabel=this.tabli[i].getElementsByTagName("label");
			for (let j=0;j<this.newtablabel.length;j++) {
				this.number=i+1;
				this.newtablabel[j].innerHTML=this.number + ". " + this.tablabel[j] + " n°" + this.number + " :"; 
			}
		}        
	}

	create_button(param,action) {
		this.btn=document.createElement("button");
		this.btn.innerHTML="Supprimer cet élément";
		this.btn.addEventListener("click", function(){
			if (action=="delete") {
				this.ol.removeChild(this.param);
				this.edit_label();
			} else {
				this.btn.type="button";
				alert("Bonjour");
			}
		});
		param.appendChild(this.btn);
	}
}