function timed() {
	var d = new Date();
	this.minutes = d.getMinutes();
	this.hours = d.getHours();
	this.day = d.getDay();
	this.year = d.getFullYear();
	this.construct = function () {
		return "Minutes:" + this.minutes + " Hours:" + this.hours + " Days:" + this.day + " Years:" + this.year;
	};
	this.display = document.getElementById('directory').innerHTML = this.construct();
}



var sometime = new timed();
sometime.display();