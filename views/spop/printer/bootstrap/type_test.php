<script type="text/javascript">
//<![CDATA[
function printPage(){
 var initBody;
 window.onbeforeprint = function(){
  initBody = document.body.innerHTML;
  document.body.innerHTML =  document.getElementById('print').innerHTML;
 };
 window.onafterprint = function(){
  document.body.innerHTML = initBody;
 };
 window.print();
 return false;
}
//]]>
</script>


<div id="print">인쇄할 영역</div>
