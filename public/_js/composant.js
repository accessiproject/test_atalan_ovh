class Composant {
	constructor(iddiv,parent,cpt) {
		this.parent=parent;
		//creation div
		this.div=document.createElement("div");
		this.div.id="iddiv"+cpt;
		parent.appendChild(this.div);
		//creation label (titre du composant technique)
		this.labelcomtitre=document.createElement("label");
		this.labelcomtitre.htmlFor="com_titre"+cpt;
		this.labelcomtitre.innerHTML="<li>" + "Titre du composant technique n°" + cpt + " :" + "</li>";
		this.div.appendChild(this.labelcomtitre);
		//creation input (titre du composant technique)
		this.inputcomtitre=document.createElement("input");
		this.inputcomtitre.id="com_titre"+cpt;
		this.inputcomtitre.name="champ"+cpt+"[]";
		this.inputcomtitre.required=true;
		this.inputcomtitre.autofocus=true;
		this.div.appendChild(this.inputcomtitre);
		//creation <p> (pour inviter le gestionnaire à choisir la façon dont il veut implémenter le composant technique)
		this.p=document.createElement("p");
		this.p.innerHTML="L'implémentation :";
		this.div.appendChild(this.p);
		//creation input radio textarea
		this.inputradiotextarea=document.createElement("input");
		this.inputradiotextarea.id="textarearadio"+cpt;
		this.inputradiotextarea.addEventListener("click",()=>this.affichertextarea(),false);
		this.inputradiotextarea.name="champ"+cpt+"[]";
		this.inputradiotextarea.value="1";
		this.inputradiotextarea.type="radio";
		this.div.appendChild(this.inputradiotextarea);
		//creation label (pour choisir le code source)
		this.labelradiotextarea=document.createElement("label");
		this.labelradiotextarea.htmlFor="textarearadio"+cpt;
		this.labelradiotextarea.innerHTML="Code source";
		this.div.appendChild(this.labelradiotextarea);
		//creation input radio url 
		this.inputradiourl=document.createElement("input");
		this.inputradiourl.id="urlradio"+cpt;
		this.inputradiourl.name="champ"+cpt+"[]";
		this.inputradiourl.type="radio";
		this.inputradiourl.value="0";
		this.inputradiourl.addEventListener("click",()=>this.afficherurl(),false);
		this.div.appendChild(this.inputradiourl);
		//creation label (pour choisir l'url)
		this.labelradiourl=document.createElement("label");
		this.labelradiourl.htmlFor="urlradio"+cpt;
		this.labelradiourl.innerHTML="URL";
		this.div.appendChild(this.labelradiourl);
		//creation de la div 
		this.divtextarea=document.createElement("div");
		this.divtextarea.id="divtextarea"+cpt;
		this.divtextarea.style="display:none";
		this.div.appendChild(this.divtextarea);
		//creation du label  
		this.labeltextarea=document.createElement("label");
		this.labeltextarea.htmlFor="com_code"+cpt;
		this.labeltextarea.innerHTML="Code source du composant technique n°" + cpt + " :";
		this.divtextarea.appendChild(this.labeltextarea);
		///creation du textarea
		this.textareacode=document.createElement("textarea");
		this.textareacode.id="com_code"+cpt;
		this.textareacode.name="champ"+cpt+"[]";
		this.textareacode.rows="3";
		this.textareacode.cols="20";
		this.divtextarea.appendChild(this.textareacode);
		//creation de la div 
		this.divurl=document.createElement("div");
		this.divurl.id="divurl"+cpt;
		this.divurl.style="display:none";
		this.div.appendChild(this.divurl);
		//creation du label  
		this.labelurl=document.createElement("label");
		this.labelurl.htmlFor="com_url"+cpt;
		this.labelurl.innerHTML="Adresse URL du composant technique n°" + cpt + " :";
		this.divurl.appendChild(this.labelurl);
		///creation de l'input de l'url
		this.urlcode=document.createElement("input");
		this.urlcode.id="com_url"+cpt;
		this.urlcode.name="champ"+cpt+"[]";
		this.urlcode.type="url";
		this.divurl.appendChild(this.urlcode);
		//creation btSup
		this.btSup=document.createElement("button");
		this.btSup.type="button";
		this.btSup.addEventListener("click",()=>this.supprimer());
		this.btSup.innerHTML= "Supprimer le composant technique n°" + cpt;
		this.div.appendChild(this.btSup);
	}
	
	affichertextarea() {
		this.divtextarea.style.display="inline";
		this.divurl.style.display="none"; 
	}
	
	afficherurl() {
		this.divtextarea.style.display="none";
		this.divurl.style.display="inline"; 
	}
	
	supprimer() {
		this.parent.removeChild(this.div);
	}
}