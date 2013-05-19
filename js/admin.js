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

/*******************************************************************************
ESPECIFIC OF PLUGIN
*******************************************************************************/
// select  all
function selectcopy(fieldid){
	var field=document.getElementById(fieldid) || eval('document.'+fieldid)
	field.select()
	if (field.createTextRange){ //if browser supports built in copy and paste (IE only at the moment)
		field.createTextRange().execCommand("Copy")
		alert("Value copied to clipboard!")
	}
}

// crop text
jQuery(document).ready(function($) {
	var showChar = 50;
	var ellipsestext = "...";
	var moretext = "[+]";
	var lesstext = "[-]";
	$('.more').each(function() {
		var content = $(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar-1, content.length - showChar);

			var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

			$(this).html(html);
		}

	});

	$(".morelink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});
});

