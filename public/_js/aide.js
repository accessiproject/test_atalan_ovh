class Aide {
	constructor(iddiv,parent,cpt) {
		this.parent=parent;
		//creation div
		this.div=document.createElement("div");
		this.div.id="iddiv";
		parent.appendChild(this.div);
		//creation label de l'aide
		this.labelaide=document.createElement("label");
		this.labelaide.htmlFor="aid_nom"+cpt;
		this.labelaide.innerHTML="<li>" + "Aide technique n°" + cpt + " :" + "</li>";
		this.div.appendChild(this.labelaide);
		//creation input (nom de l'aide technique)
		this.inputaide=document.createElement("input");
		this.inputaide.id="aid_nom"+cpt;
		this.inputaide.name="aid_nom"+cpt;
		this.inputaide.required=true;
		this.inputaide.autofocus=true;
		this.div.appendChild(this.inputaide);
		//creation btSup
		this.btSup=document.createElement("button");
		this.btSup.type="button";
		this.btSup.addEventListener("click",()=>this.supprimer());
		this.btSup.innerHTML= "Supprimer l'aide technique n°" + cpt;
		this.div.appendChild(this.btSup);
	}
	
	supprimer() {
		this.parent.removeChild(this.div);
	}
}