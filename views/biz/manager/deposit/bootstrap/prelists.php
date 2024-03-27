<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>선충전 목록 (<?php echo element('total_rows', element('data', $view), 0); ?>건)</h3>
		</div>
		<div class="white_box">
			<?php
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
			echo form_open(current_full_url(), $attributes);
			?>
			<div style="display:inline-block; width:100%;">
				<div class="fr">
                    <input type="button" class="" id="excel_down" value="TG선충전한도변경" onclick="showModal()"/>
                    <!-- 
    				<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$param['startDate'];?>" readonly="readonly"> ~
    				<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$param['endDate'];?>" readonly="readonly">
    				-->
					<input type="button" class="btn_excel_down" id="excel_down" value="엑셀 내려받기" onclick="prelists_download()"/>
				<!-- 전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건 -->
            </div>
				<div class="btn-group btn-group-sm" role="group">
					<input type="hidden" id="depToType" value="<?=$this->input->get('dep_to_type')?>" />
					<input type="hidden" id="depPayType" value="<?=$this->input->get('dep_pay_type')?>" />
				</div>

			<?php
			ob_start();
			?>
			<?php
			$buttons = ob_get_contents();
			ob_end_flush();
			?>
			</div>
			<table class="table_list mg_t10">
				<colgroup>
				<col width="5%" />
                <col width="10%" />
				<col width="13%" />
                <col width="*" />
				<col width="8%" />
				<col width="8%" />
				<col width="8%" />
				<col width="10%" />
				<col width="8%" />
				</colgroup>
				<thead>
				<tr>
					<th><a href="<?php echo element('dep_id', element('sort', $view)); ?>">번호</a></th>
                    <th>소속</th>
					<th>업체명</th>
                    <th>내용</th>
					<th>선충전금액</th>
					<th>선충전차감금액</th>
                    <th>최종 금액</th>
                    <th>일시</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
			<?php
            //print_r($view['data']);
            $rowCount = 0;
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {
				$rowCount += 1;    
			?>
				<tr>
					<td class="align-center"><?php echo number_format(element('num', $result)); ?></td>
                    <td class="align-center"><?=element('myadmin', $result)?></td>
					<td class="align-center"><a href="#" onClick="pre_list(<?=$result['mem_id']?>);"><?php echo html_escape(element('mem_username', $result)); ?></a></td>
					<td><a href="#" onClick="pre_list(<?=$result['mem_id']?>);">
                        <?php echo nl2br(html_escape(element('dep_content', $result))); if(!empty(element('dep_admin_memo', $result))){ echo ' - '.nl2br(html_escape(element('dep_admin_memo', $result))); } ?>
                    </a></td>
					<td class="text-right"><?php echo number_format(element('sum_dep', $result)) . '원'; ?></td>
					<td class="text-right"><?php echo number_format(element('ser_dep', $result)) . '원'; ?></td>
					<td class="text-right"><?php echo number_format(element('pre_cash', $result)) . '원'; ?></td>
                    <td class="align-center"><?php echo element('pre_upd_date', $result); ?></td>
                    <td>
                    	<input type="button" class="btn_excel_down" id="excel_down_<?=$rowCount?>" value="상세 내려받기" onclick="prelists_detail_download(<?=$result['mem_id']?>)"/>
                    </td>
                    <!-- 
					<td>
                        <a href="<?php echo $this->pagedir; ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a>
                        <a href="/biz/main?<?=element('mem_id', $result)?>" class="btn btn-outline btn-default btn-xs">전환</a>
                    </td>
                    -->
				</tr>
			<?php
				}
			}
			if ( ! element('list', element('data', $view))) {?>
				<tr>
					<td colspan="9" class="nopost">자료가 없습니다</td>
				</tr>
			<?php
				}?>
				</tbody>
			</table>
			<?//php echo element('paging', $view); ?>
			<div class="page_cen"><?=$page_html?></div>
			<div style="width:100%; margin-bottom:30px; display:inline-block;">
			<div class="pull-left ml20 fr"><?php echo admin_listnum_selectbox();?></div>
			<?php echo $buttons; ?>
			<?php echo form_close(); ?>
			<!--<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">-->
				<div class="fl" style="">
					<div class="box-search">
							<select class="" name="sfield" id="sfield">
							<?php echo element('search_option', $view); ?>
							</select>
								<input type="text" class="" name="skeyword" id="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." onKeypress="if(event.keyCode==13){ open_page(1); }">
								<button class="btn btn-default btn-md" name="search_submit" type="button" onClick="open_page(1);">검색!</button>
					</div>
				</div>
			<!--</form>-->
		</div>
		</div>
	</div>
</div>
<!-- 템플릿등록 Modal -->
<div id="modal_templet" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content"> <span id="close_templet" class="dh_close pre_close">&times;</span>
	<p class="modal-tit">상세보기</p>
    <input type="hidden" id ="pre_id" value="">
    <table id="table_list" class="table_list mg_t10">
        <colgroup>
        <col width="20%" />
        <col width="*" />
        <col width="15%" />
        </colgroup>
        <thead>
        <tr>
            <th>일시</th>
            <th>내용</th>
            <th>금액</th>
        </tr>
        </thead>
        <tbody class="t_body">
        </tbody>
    </table>
    <div id="member_page" class="page_cen" style="margin-top:10px;"></div><?//라이브러리 페이징 영역?>
  </div>
</div>

<div id="dh_myModal2" class="dh_modal">
	<div class="modal-content2_1">
		<span id="dh_close2" class="dh_close">&times;</span>
		<div class="img_choice">
			<input type="text" id="tg_pre_amt" value="" onkeypress="onlyNumber();">
            <button onclick="set_tg_amt();" class="tg_modify" style="margin-left:10px;cursor:pointer;">수정</button>
		</div>
	</div>
</div>

<script type="text/javascript">

$(".pre_close").click(function(){
    $(".modal-content").hide();
    $("#modal_templet").hide();
});

function pre_list(mem_id){
    var mem_id = mem_id;
    //alert("mem_id : "+ mem_id); return;
    var perpage = "10";

    $("#modal_templet").show();
    $(".modal-content").show();
    $("tbody.t_body").empty();
    $("p.modal-tit").empty();

    $.ajax({
        type : "POST",
        url : "/biz/manager/deposit/pre_list",
        data : {
          <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
          mem_id: mem_id,
          perpage : perpage
        },
        dataType : "JSON",
        success: function (json) {
        console.log(json);
        $("#pre_id").val(json.mem_id);
        $("p.modal-tit").append('상세보기 - '+json.mem_username);
            if(json.total > 0){
                $(".t_body").append(json.html);
                $("#member_page").html(json.page_html);
            }else{
                $(".t_body").append('<tr><td colspan="3" class="nopost">자료가 없습니다.</td></tr>');
            }
        },
        error: function (json, status, er) {
            //alert('취소');
            console.log(data);
        }
    });
}


//라이브러리 페이지 조회
function pre_list_page(page){
    var pre_page = page;
    var perpage = "10";
    var pre_id = $('#pre_id').val();

    //$("#member_page").html(json.page_html);

    $.ajax({
      type : "POST",
      url : "/biz/manager/deposit/pre_list",
      data : {
        <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
        mem_id: pre_id,
        page : pre_page,
        perpage : perpage
      },
      dataType : "JSON",
      success: function (json) {
      console.log(json);
          $(".t_body").empty();
          $(".t_body").append(json.html);
          $("#member_page").html(json.page_html);
      },
      error: function (json, status, er) {
      }
    });
}

	$("#nav li.nav100").addClass("current open");

	  // 2021-06-29 엑셀 다운로드 관련 시작
	  function getFormatDate(date){
	      var year = date.getFullYear();
	      var month = (1 + date.getMonth());
	      month = month >= 10 ? month : '0' + month;
	      var day = date.getDate();
	      day = day >= 10 ? day : '0' + day;
	      return year + '-' + month + '-' + day;
	  }

	  var date = new Date();
	  var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
	  var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

	  $('#startDate').datepicker({
	      format: "yyyy-mm-dd",
	      todayHighlight: true,
	      language: "kr",
	      autoclose: true,
	      //startDate: '-6m',
	      //endDate: '-1d'
	  }).on('changeDate', function (selected) {
	      var startDate = new Date(selected.date.valueOf());
	      $('#endDate').datepicker('setStartDate', startDate);
	  });
	  $("#startDate").val(getFormatDate(firstDay));
	  var start = $("#startDate").val();

	  $('#endDate').datepicker({
	      format: "yyyy-mm-dd",
	      todayHighlight: true,
	      language: "kr",
	      autoclose: true,
	      startDate: start,
	      endDate: '+1m'
	  }).on('changeDate', function (selected) {
	      var endDate = new Date(selected.date.valueOf());
	      $('#startDate').datepicker('setEndDate', endDate);
	  });
	  $("#endDate").val(getFormatDate(lastDay));
	  var end = $("#endDate").val();

	  function prelists_download() {

	  	var dep_to_type = $('#depToType').val();
	  	var dep_pay_type = $('#depPayType').val();
	  	var start_date = $('#startDate').val();
	  	var end_date = $('#endDate').val();

	  	var form = document.createElement("form");
	  	document.body.appendChild(form);
	  	form.setAttribute("method", "post");
	  	form.setAttribute("action", "/biz/manager/deposit/prelists_download");

	  	var scrfField = document.createElement("input");
	  	scrfField.setAttribute("type", "hidden");
	  	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
	  	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
	  	form.appendChild(scrfField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "dep_to_type");
	  	resultField.setAttribute("value", dep_to_type);
	  	form.appendChild(resultField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "dep_pay_type");
	  	resultField.setAttribute("value", dep_pay_type);
	  	form.appendChild(resultField);

		/*
	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "start_date");
	  	resultField.setAttribute("value", start_date);
	  	form.appendChild(resultField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "end_date");
	  	resultField.setAttribute("value", end_date);
	  	form.appendChild(resultField);
	  	*/

	  	form.submit();
	  }

	  function prelists_detail_download(param_mem_id) {

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/manager/deposit/prelists_detail_download");
            
            var scrfField = document.createElement("input");
            scrfField.setAttribute("type", "hidden");
            scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
            scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(scrfField);
            
            var resultField = document.createElement("input");
            resultField.setAttribute("type", "hidden");
            resultField.setAttribute("name", "mem_id");
            resultField.setAttribute("value", param_mem_id);
            form.appendChild(resultField);
            
            /*
            var resultField = document.createElement("input");
            resultField.setAttribute("type", "hidden");
            resultField.setAttribute("name", "start_date");
            resultField.setAttribute("value", start_date);
            form.appendChild(resultField);
            
            var resultField = document.createElement("input");
            resultField.setAttribute("type", "hidden");
            resultField.setAttribute("name", "end_date");
            resultField.setAttribute("value", end_date);
            form.appendChild(resultField);
            */
            
            form.submit();
	  }
  
	  //2021-06-29 엑셀 다운로드 관련 끝

	//검색
	function open_page(page){
		var sfield = $("#sfield").val(); //검색타입
		var skeyword = $("#skeyword").val(); //검색내용
		var dep_to_type = "<?=$this->input->get('dep_to_type')?>"; //내역구분
		var dep_pay_type = "<?=$this->input->get('dep_pay_type')?>"; //내역구분
        var varp = "<?=$this->input->get('p')?>"; //내역구분
		var pram = "";
		if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
		if(dep_to_type != "") pram += "&dep_to_type="+ dep_to_type;
		if(dep_pay_type != "") pram += "&dep_pay_type="+ dep_pay_type;
        if(varp != "") pram += "&p="+ varp;
		//alert("page : "+ page +", tagid : "+ tagid);
		location.href = "?page="+ page + pram;
	}

    var modal = document.getElementById("dh_myModal2");
	function showModal(){
        $.ajax({
          type : "POST",
          url : "/biz/manager/deposit/get_tg_amt",
          data : {
            <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
          },
          dataType : "JSON",
          success: function (json) {
              tg_pre_amt.value = json.amt;
              var span = document.getElementById("dh_close2");
      		modal.style.display = "block";
      		span.onclick = function() {
      			modal.style.display = "none";
      		}
          		window.onclick = function(event) {
          			if (event.target == modal) {
          				modal.style.display = "none";
          			}
          		}
            }
        });
    }

    function set_tg_amt(){
        $.ajax({
            type : "POST",
            url : "/biz/manager/deposit/set_tg_amt",
            data : {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                amt : tg_pre_amt.value,
            },
            dataType : "JSON",
            success: function (json) {
                alert("수정되었습니다.");
                modal.style.display = "none";
            }
        });
    }

    function onlyNumber(){
        if((event.keyCode<48)||(event.keyCode>57)) event.returnValue=false;
    }
</script>
