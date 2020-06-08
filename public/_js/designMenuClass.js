class DesignMenuClass {
    constructor(iddiv, aria_label, array) {
        this.iddiv = iddiv;
        this.aria_label = aria_label;
        this.array = array;
        this.div = document.getElementById(this.iddiv);
        this.menu();
    }

    menu() {
        console.log(this.array);
        this.nav = document.createElement("nav");
        this.nav.setAttribute("aria-label", this.aria_label);
        this.nav.setAttribute("role", "navigation");
        this.nav.className = "navbar navbar-expand-lg navbar-light bg-light";
        this.ul = document.createElement("ul");
        this.ul.className = "navbar-nav mr-auto";
        for (let i = 0; i < this.array.length; i++) {
            this.li = document.createElement("li");
            this.li.className = "nav-item";
            this.a = document.createElement("a");
            this.a.href = this.array[i][1];
            this.a.textContent = this.array[i][0];
            this.dynamic(this.array[i][2], this.a);
            this.li.appendChild(this.a);
            this.ul.appendChild(this.li);
        }
        this.nav.appendChild(this.ul);
        this.div.appendChild(this.nav);
    }

    dynamic(element, object) {
        this.url = window.location.href;
        this.str = "/" + element;
        if (this.url.includes(this.str)) {
            object.setAttribute("aria-current", "page");
            object.className = "active";
        } else {
            object.className = "nav-link";
        }
    }
}