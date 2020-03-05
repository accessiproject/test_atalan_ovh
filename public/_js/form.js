/*
class Form {
	constructor(iddiv,parent,cpt) {
		this.parent=parent;
		//creation div
		this.div=document.createElement("div");
		this.div.id="iddiv";
		parent.appendChild(this.div);
		//creation label de la proposition
		this.labelproposition=document.createElement("label");
		this.labelproposition.htmlFor="pro_intitule"+cpt;
		this.labelproposition.innerHTML="<li>" + "Réponse n°" + cpt + " :" + "</li>";
		this.div.appendChild(this.labelproposition);
		//creation input (intutlé de la proposition du composant technique)
		this.inputproposition=document.createElement("input");
		this.inputproposition.id="pro_intitule"+cpt;
		this.inputproposition.name="pro_intitule"+cpt;
		this.inputproposition.required=true;
		this.inputproposition.autofocus=true;
		this.div.appendChild(this.inputproposition);
		//creation btSup
		this.btSup=document.createElement("button");
		this.btSup.type="button";
		this.btSup.addEventListener("click",()=>this.supprimer());
		this.btSup.innerHTML= "Supprimer la de réponse n°" + cpt;
		this.div.appendChild(this.btSup);
	}
	
	supprimer() {
		this.parent.removeChild(this.div);
	}
}
class Proposition {
	constructor(iddiv,parent,cpt) {
		this.parent=parent;
		//creation div
		this.div=document.createElement("div");
		this.div.id="iddiv";
		parent.appendChild(this.div);
		//creation label de la proposition
		this.labelproposition=document.createElement("label");
		this.labelproposition.htmlFor="pro_intitule"+cpt;
		this.labelproposition.innerHTML="<li>" + "Réponse n°" + cpt + " :" + "</li>";
		this.div.appendChild(this.labelproposition);
		//creation input (intutlé de la proposition du composant technique)
		this.inputproposition=document.createElement("input");
		this.inputproposition.id="pro_intitule"+cpt;
		this.inputproposition.name="pro_intitule"+cpt;
		this.inputproposition.required=true;
		this.inputproposition.autofocus=true;
		this.div.appendChild(this.inputproposition);
		//creation btSup
		this.btSup=document.createElement("button");
		this.btSup.type="button";
		this.btSup.addEventListener("click",()=>this.supprimer());
		this.btSup.innerHTML= "Supprimer la de réponse n°" + cpt;
		this.div.appendChild(this.btSup);
	}
	
	supprimer() {
		this.parent.removeChild(this.div);
	}
}
*/