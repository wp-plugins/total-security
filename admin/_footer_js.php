<script language="JavaScript" type="text/javascript">
 /*<![CDATA[*/
function PopupCenter(pageURL, title,w,h,scrol) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars='+scrol+', resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}
// selec all
function selectcopy(fieldid){
	var field=document.getElementById(fieldid) || eval('document.'+fieldid)
	field.select()
	if (field.createTextRange){ //if browser supports built in copy and paste (IE only at the moment)
		field.createTextRange().execCommand("Copy")
		alert("Value copied to clipboard!")
	}
}
/*]]>*/
</script>
<script type="text/javascript">SyntaxHighlighter.all();</script>
<script type="text/javascript" src="<?php echo admin_url(); ?>load-scripts.php?c=0&amp;load=jquery-ui-core,jquery-ui-widget,jquery-ui-mouse,jquery-ui-sortable,postbox,post"></script>
