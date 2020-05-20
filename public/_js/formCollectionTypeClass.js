class FormCollectionTypeClass {
    constructor(idol, idbuttonadd, libelle_delete, libelle_add, tagtitlestart, title, tagtitleend) {
        this.idol = idol;
        this.idbuttonadd = idbuttonadd;
        this.libelle_delete = libelle_delete;
        this.libelle_add = libelle_add;
        this.tagtitlestart = tagtitlestart;
        this.title = title;
        this.tagtitleend = tagtitleend;
        this.ol = document.getElementById(idol);
        this.ol.style.listStyleType = "none";
        this.labels = this.ol.getElementsByTagName("label");
        this.tablabel = [];
        for (let i = 0; i < this.labels.length; i++) {
            this.tablabel.push(this.labels[i].textContent);
        }
        this.tab1 = this.ol.getElementsByTagName("li");
        if (this.tab1.length > 1) {
            this.ol.removeChild(this.tab1[this.tab1.length - 1]);
            for (let i = 0; i < this.tab1.length; i++)
                this.create_button(this.tab1[i], "delete");
        } else
            this.ol.removeChild(this.tab1[0]);
        this.edit_label();
        this.idbuttonadd = document.getElementById(idbuttonadd);
        this.create_button(this.idbuttonadd, "add");
    }

    edit_label() {
        this.tabli = this.ol.getElementsByTagName("li");
        for (let i = 0; i < this.tabli.length; i++) {
            this.newtablabel = this.tabli[i].getElementsByTagName("label");
            for (let j = 0; j < this.newtablabel.length; j++) {
                this.number = i + 1;
                if (this.newtablabel.length > 1) {
                    if (j == 0) {
                        this.newtablabel[j].innerHTML = this.tagtitlestart + this.number + ". " + this.title + " n°" + this.number + " :" + this.tagtitleend + this.tablabel[j];
                    } else if (j == this.tablabel.length - 1) {
                        this.newtablabel[j].innerHTML = this.tablabel[j];
                    } else {
                        this.newtablabel[j].innerHTML = this.tablabel[j];
                    }
                } else
                    this.newtablabel[j].innerHTML = this.number + ". " + this.tablabel[j] + " n°" + this.number + " :";
            }
        }
    }

    create_button(param, action) {
        this.btn = document.createElement("button");
        this.btn.type = "button";
        if (action == "delete") {
            this.btn.className = "btn btn-danger";
            this.btn.innerHTML = this.libelle_delete;
            this.btn.addEventListener("click", () => this.ol.removeChild(param));
            this.btn.addEventListener("click", () => this.edit_label());
        } else {
            this.btn.className = "btn btn-info";
            this.btn.innerHTML = this.libelle_add;
            this.btn.addEventListener("click", () => this.add());
        }
        param.appendChild(this.btn);
    }

    add() {
        this.data = this.ol.getAttribute("data-prototype");
        if (this.number == null)
            this.number = 0;

        this.data = this.data.replace(/__name__/g, this.number);
        this.x = document.createElement("li");
        this.x.innerHTML = this.data;
        this.create_button(this.x, "delete");
        this.ol.appendChild(this.x);
        this.edit_label();
    }
}