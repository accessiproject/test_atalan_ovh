class TableClass {
    constructor(array, idparent) {
        this.array = array;
        this.idparent = idparent;
        this.idparent = document.getElementById(idparent);
        this.idparent.innerHTML = "";
        for (let i = 0; i < this.array.length; i++) {
            this.tr = document.createElement("tr");
            for (this.value in this.array[i]) {
                this.td = document.createElement("td");
                if (Array.isArray(this.array[i][this.value]) == true) {
                    this.ul = document.createElement("ul");
                    for (let j = 0; j < this.array[i][this.value].length; j++) {
                        this.ul.innerHTML += "<li>" + this.array[i][this.value][j] + "</li>";
                    }
                    this.td.appendChild(this.ul);
                } else
                    this.td.innerHTML = this.array[i][this.value];
                this.tr.appendChild(this.td);
            }
            this.idparent.appendChild(this.tr);
        }
    }
}