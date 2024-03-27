    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/test/index/">TEST</a></li>
            <li class="current"><a href="#" title="">TEST</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <div class="row">
    		<div class="col-xs-12">
    			<div class="widget">
                    <div class="widget-header">
                        <h3>TEST</h3>
                    </div>
                    <div id="monitoring_div" class="widget-content">
                    
                    </div>
                </div>
    		</div>
     	</div>
    </div>


<script langauge="javascript">
function view_monitoring_div()
{
	$('#monitoring_div').html('').load(
		(document.location.href.indexOf("biz/test") > -1) ? "test/view" : "",
		{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>" }
	);

}
$(document).ready(function() {
	view_monitoring_div();
	//setInterval('view_monitoring_div()', 60000); // 60초 후에 새로 고침
});
</script>
