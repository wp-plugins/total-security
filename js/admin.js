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

jQuery(document).ready(function($) {
        // Tooltip only Text
        $('.pluginbuddy_tip').hover(function(){
                // Hover over code
                var title = $(this).attr('title');
                $(this).data('tipText', title).removeAttr('title');
                $('<p class="tooltip"></p>')
                .text(title)
                .appendTo('body')
                .fadeIn('slow');
        }, function() {
                // Hover out code
                $(this).attr('title', $(this).data('tipText'));
                $('.tooltip').remove();
        }).mousemove(function(e) {
                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY + 10; //Get Y coordinates
                $('.tooltip')
                .css({ top: mousey, left: mousex })
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


