/* popup normal para share
-------------------------------------------------------------- */
function PopupCenter(pageURL, title,w,h,scrol) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars='+scrol+', resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

/* alert
-------------------------------------------------------------- */
jQuery(document).ready(function($) {
$("#cl").click(function(){
alert("fabrix@fabrix.net");
});
});

/* select
-------------------------------------------------------------- */
function selectcopy(fieldid){
	var field=document.getElementById(fieldid) || eval('document.'+fieldid)
	field.select()
	if (field.createTextRange){ //if browser supports built in copy and paste (IE only at the moment)
		field.createTextRange().execCommand("Copy")
		alert("Value copied to clipboard!")
	}
}
