function getXmlhttp() {
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();
	else if (window.ActiveXObject)
		return new ActiveXObject("Msxml2.XMLHTTP");
	else
		throw new Error("Could not create HTTP request object.");
}

function script_ajax(parameter) {
	xmlhttp=getXmlhttp();
	xmlhttp.open("GET","http://127.0.0.1:8000/ajax?"+parameter,true);
	//console.log(this.xmlhttp);   
	xmlhttp.onreadystatechange=mafonction;
	xmlhttp.send();
	function mafonction() {
		if (this.readyState==4 && this.status==200) {
			let result = this.responseText;
			myFunction(result);
		}
	}
}