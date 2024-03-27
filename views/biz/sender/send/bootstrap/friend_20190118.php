		  <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
    <script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
    <script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
//-->
</script>
<input type="hidden" id="navigateURL" value="" />


    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/sender/send/friend">발신</a></li>
            <li><a href="#">발신</a></li>
            <li class="current"><a href="#" title="">친구톡</a></li>
        </ul>
    </div>
    <div class="snb_nav">
        <ul class="clear">
            <li><a href="/biz/sender/send/talk">알림톡</a></li>
            <li class="active"><a href="/biz/sender/send/friend">친구톡</a></li>
			<li><a href="/biz/sender/send/lms">문자</a></li> 
        </ul>
    </div><!--//.snb_nav-->
    <form action="/biz/sender/friend/all" method="post" id="sendForm" name="sendForm">
    <div class="content_wrap">
        <div class="col-xs-8">
            <div class="widget box mt20">
                <div class="widget-header">
                    <h4>발신프로필 선택</h4>
                </div>
                <div class="widget-content" id="send_friend_content" >


                </div>
            </div>
            <div class="widget box mt20">
                <div class="widget-header">
                    <h4>전화번호 파일 업로드 및 양식 다운로드</h4>
                </div>
                <div class="widget-content">
                    <div class="row">
                        <div class="col-xs-9 file">
<!--                             <button type="button" onclick="upload();" class="btn pull-right" style="width:112px; margin:0px -6px 0 350px; position:absolute; z-index:10;"><i class="icon-folder-open"></i> 파일업로드</button></td> -->
                            <input type="file" name="filecount" id="filecount" multiple="multiple" onchange="readURL(this);" style="cursor: default; padding: 20px 20px 20px 20px !important;">
                        </div>
                        <div class="col-xs-2">
                            <button class="btn pull-right" type="button" onclick="download();" style="width:158px;margin-right:-60px;"><i class="icon-cloud-download"></i>업로드 양식 다운로드</button>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $('#filecount').filestyle({
                            input: true,
                            buttonName: '',
                            iconName: 'icon-folder-open',
                            buttonText: '파일업로드'
                        });
                    </script>
                    <style>
                        .file input{
                            cursor: default !important;
                        }
                        .file button{
                            position: absolute;
                            z-index: -1;
                        }
                    </style>
                </div>
            </div>
            <div class="widget box mt10">
					<style type="text/css">
					#tel_tbody_tr td { line-height:2.4; }
					</style>
                <div class="widget-header">
                    <h4>수신 번호</h4>
                    <button class="btn pull-right" type="button" id="btn_load_customer" onclick="load_customer_select();"><i class="icon-user"></i> 고객정보 불러오기</button>
                    <button class="btn pull-right" type="button" onclick="load_customer_all();" style="margin-right:3px;"><i class="icon-user"></i> 전체 고객정보 불러오기</button>
                </div>
                <div class="widget-content tel_content">
                    <table class="table table-fixedheader table-checkable mt10 table-center" id="tel">
                        <thead>
                        <tr id="tel_tr">
                            <th class="checkbox-column" style="width:10% !important"><input type="checkbox" id="all_select" class="uniform"></th>
                            <th width="15%">고객구분</th>
                            <th width="15%">고객명</th>
                            <th width="40%">전화번호</th>
                            <th width="20%">삭제</th>
                        </tr>
                        </thead>
                        <tbody id="tel_tbody">


                        <tr class="1" id="tel_tbody_tr">
                            <td class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_del" value="1" class="uniform"></td>
									 <td width="15%"><span id="ab_kind">&nbsp;</span></td>
									 <td width="15%"><span id="ab_name">&nbsp;</span></td>
                            <td width="40%">
                                <input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number"
                                                   placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);">

                            </td>
                            <td width="20%"><a href="javascript:tel_remove(1);" id="tel_remove" class="btn  btn-sm" title="삭제"><i
                                    class="icon-trash"></i> 삭제</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

					<div class="widget-header reserve">
						<h4 class="tel_h4"><input type="checkbox" name="reserve_check" id="reserve_check" onclick="ReserveCheck();" class="uniform"> 예약 발송</h4>
					</div>
					<div class="widget-content reserve_content" hidden>
						<div class='input-group date' id='datetimepicker'>
							<input type='text' class="form-control input-width-medium" id="reserve" style="background-color: white; cursor: default;" readnly/>
							<span class="input-group-addon" style="margin-left: 500px; width:96px !important;">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
					<script type="text/javascript">
						var default_date = moment().add(10,'minutes');
						var max_date = moment().add(1,'months').add(15,'minutes');
						$('#datetimepicker').datetimepicker({
							locale : 'ko',
							format : 'YYYY-MM-DD HH시 mm분',
							defaultDate : default_date,
							minDate: default_date,
							maxDate: max_date,
							disabledHours:[21,22,23,24,0,1,2,3,4,5,6],
							ignoreReadonly: true
						});
					</script>

					 <div class="widget-content">
                    <div class="mt20 align-center">
                        <button class="btn" type="button" id="number_add" onclick="add_number();"><i class="icon-plus"></i> 번호 추가</button>
                        <button class="btn" type="button" id="number_del" onclick="selectDelRow();"><i class="icon-trash"></i> 선택 삭제</button>
                        <button class="btn" type="button" id="send_select" onclick="select_send();"><i class="icon-ok"></i> 선택 발송</button>

                        <!--<button class="btn" id="save_temporal_msgs" type="button" onclick="saveTemporalMsgs();"><i class="icon-save"></i> 임시 저장</button>-->

                        <button class="btn btn-custom" type="button" id="send_all" onclick="all_send();"><i class="icon-download-alt"></i> 전체 발송</button>
                        <br/><br/><br/><br/><br/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="preview_wrap">
                <p><img src="/bizM/images/img_phone.jpg" alt="" style="width:100%;"></p>
                <div class="abs_box">
                    <p class="pre_txt">미리보기</p>
                    <div class="scroller" data-height="470px" data-always-visible="1" data-rail-visible="0">
                        <div class="txt" id="phone_standard">
                            <span><img src="/bizM/images/bullet_edge.png" alt=""></span>
                            <p class="message">
                                <textarea id="text" data-always-visible="1" data-rail-visible="0" cols="25" readonly="readonly" style="margin-left:-5px;overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"> 메세지를 입력해주세요.</textarea>
                            </p>
                        </div>
                    </div>
                </div>
                    <div class="mt20 align-center">
                        <button class="btn" type="button" id="msg_save_temp" onclick="msg_save('TEMP');">임시저장</button>
                        <button class="btn" type="button" id="msg_save_" onclick="msg_save('SAVE');">저장</button>
                        <button class="btn" type="button" id="open_msg_list_" onclick="open_msg_lms_list();">내문자함</button>
 
                    </div>                
                
            </div>
        </div>
	</div><!--//.content_wrap-->
	</form>


	<div class="modal select fade" id="sendbox_select" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
		<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
			<div class="clear modal-content">
				<h4 class="modal-title" align="center">내 문자함 (친구톡)</h4>
				<div class="modal-body select-body">
					<div class="clear sendbox-tab_wrap">
						 <ul class="sendbox-tab">
							<li class="on"><a href="javascript:void(bind_sendbox('history'))">보낸문자</a></li>
							<li><a href="javascript:void(bind_sendbox('save'))">저장문자</a></li>
						 </ul>
						 <div style="float:right;">
							 <select class="select2 input-width-medium" id="searchType_sendbox" onchange="getSelectValue(this.form);">
								<option value="subject">문자제목</option>
								<option value="content">문자내용</option>
							 </select>&nbsp;
							 <input type="text" class="form-control input-width-medium inline"
									  id="searchFor_sendbox" name="search" placeholder="검색어 입력" value=""/>&nbsp;
							 <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_sendbox();'"/>
						 </div>
					</div>


					<div class="widget-content" id="sendbox_list">


					</div>
					<div class="btn_wrap" align="center">
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_sendbox();">확인</button>
						<br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>




	 <!--프로필 선택-->
    <div class="modal fade" id="profile_danger" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal" style="width:400px;">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                        <div class="row align-center" id="danger" style="height: 300px; padding-top: 70px;">
                            <p class="alert alert-danger">
                                    프로필이 없습니다. 프로필을 등록해 주세요.
                            </p>
                            <br/><button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 85px;">닫기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal select fade" id="profile_select" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
        <div class="modal-dialog modal-lg select-dialog" id="modal">
            <div class="modal-content">

                <br/>
                <h4 class="modal-title" align="center">발신프로필 선택하기</h4>
                <div class="modal-body select-body">
                    <div class="select-content">
                        <div class="mb20 mt10 clear">
                            <select class="select2 input-width-medium" id="searchType2" onchange="getSelectValue(this.form);">
                                <option value="all">검색항목</option>
                                <option value="pf_ynm">업체명</option>
                                <option value="pf_key">프로필 키</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchStr2" name="search" placeholder="검색어 입력" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_profile();'"/>
                        </div>

                        <div class="widget-content" id="profile_list">


								</div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary" id="code" name="code" onclick="select_profile();">확인</button>
                        <br/><br/>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content identify">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
                                확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary submit">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalSelect" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content select_send">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary select-send" onclick="select_move();">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary del">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary selDel">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary temp">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary up">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary down">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalFilterAll" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">선택추가</button>
                            <button type="button" class="btn btn-primary filter_all">전체추가</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal select fade" id="myModalUserMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg select-dialog">
            <div class="modal-content">
                <br/>
                <h4 class="modal-title" align="center">내 문자함</h4>
                <div class="modal-body select-body" style="height: 600px;">
                    <div >
                        <div style="padding-bottom:20px;">
                            <select class="select2 input-width-medium" id="searchKind">
                                <option value="all">전체</option>
                                <option value="FT">친구</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="search_msg(1)"/>
                        </div>

                        <div class="content" id="modal_user_msg_list" style="overflow-y:scroll; height: 460px;" style="border:1px solid #aaa;">
									
                        </div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="delete_msg()">선택 삭제</button>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_msg()">선택 추가</button>
                        <br/><br/>
                    </div>

                </div>
            </div>
        </div>
    </div>    
    
    <div class="modal select fade" id="myModalLoadCustomers" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden style="overflow-y:hidden;">
        <div class="modal-dialog modal-lg select-dialog">
            <div class="modal-content">
                <br/>
                <h4 class="modal-title" align="center">고객정보 가져오기</h4>
                <div class="modal-body select-body" style="height: 600px;">

                    <div class="mb20 mt10">
                        <div style="padding-bottom:20px;">
                            <select class="select2 input-width-medium" id="searchType">
                                <option value="all">전체</option>
                                <option value="normal">정상</option>
                                <option value="reject">수신거부</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchStr" name="searchStr" placeholder="전화번호 입력" value=""/>&nbsp;
                                                        고객구분
                            <select class="select2 input-width-medium" id="searchGroup" >    
                            <option value="">전체</option>
                            <?foreach($kind as $r) {?>
                            	<option value="<?=$r->ab_kind?>"><?=$r->ab_kind?></option>
                            <?}?>   
                            	<option value="FT">친구등록</option>
                            	<option value="NFT">친구등록안됨</option>
                            </select>&nbsp;
                             
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchName" name="searchName" placeholder="고객명 입력" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="search_question(1)"/>
                        </div>

                        <div class="widget-content id='modal_customer_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
									<div align="left" style="overflow-y:scroll; height: 380px;">
										<table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
											 <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
											 <colgroup>
												  <col width="10%">
												  <col width="20%">
												  <col width="15%">
												  <col width="15%">
												  <col width="30%">
												  <col width="10%">
											 </colgroup>
											 <thead>
											 <tr p style="cursor:default">
												  <th class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_all" id="sel_all" class="uniform"></th>
												  <th class="text-center">등록일</th>
												  <th class="text-center">전화번호</th>
												  <th class="text-center">상태</th>
												  <!-- 추가수정 수신거부, 메모 기능(메모,수정 타이틀 추가) -->
												  <th class="text-center">메모</th>
											 </tr>
											 </thead>
											 <tbody>
												<!-- 고객 목록 표시 -->
											 </tbody>
										</table>
									</div>
									<style>
									.text-center { vertical-align: middle !important; }
									</style>
									<script type="text/javascript">
									$( document ).ready(function() {
										$('tr input').uniform();
									});

									$('tbody tr').click(function() {
										var check = $(this).find('.checker').children('span').is('.checked');

										if(check == true) {
											$(this).find('td input[type="checkbox"]').prop('checked', true);
											$(this).addClass('checked');
										} else {
											$(this).find('td input[type="checkbox"]').prop('checked', false);
											$(this).removeClass('checked');
										}
										$.uniform.update();
									});

									//수신번호 전체 선택 안되는 현상 수정
									$("#sel_all").click(function(){
										if($("#sel_all").prop("checked")) {
											$("input:checkbox[name='selCustomer']").prop("checked",true);
											$.uniform.update();
										} else {
											$("input:checkbox[name='selCustomer']").prop("checked",false);
											$.uniform.update();
										}
									});
									</script>
                        </div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_customer()">선택 추가</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_customer('GS')">구분 전체추가</button>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .text {
            vertical-align: middle !important;
            line-height: 20px !important;
        }
       .scrolltbody {
        border-collapse: collapse;
        padding: 0!important;
        }
       .scrolltbody th { text-align: center }
       .select{
            vertical-align: middle;
            top: 50%;
            transform: translateY(-50%);
            overflow-x: hidden;
            overflow-y: hidden;
            height: 720px;
        }
        .select-dialog {
            width: 820px;
            height: 650px;
        }
        .select-body {
            width: 100%;
            height: 650px;
        }
        .modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
    </style>
	<script type="text/javascript">
	$("#nav li.nav10").addClass("current open");
    function insert_char(n){
         var adds = n ;
     
         var txtArea = $('#templi_cont');
         var txtValue = txtArea.val();

         var selectPos = txtArea.prop('selectionStart');// 커서 위치 지정
         var beforeTxt = txtValue.substring(0, selectPos);  // 기존텍스트 ~ 커서시작점 까지의 문자
         var afterTxt = txtValue.substring(txtArea.prop('selectionEnd'), txtValue.length);   // 커서끝지점 ~ 기존텍스트 까지의 문자
     
         txtArea.val( beforeTxt + adds + afterTxt );
     
         selectPos = selectPos + adds.length;
         txtArea.prop({
        	    'selectionStart': selectPos,
        	    'selectionEnd': selectPos
        	});

         txtArea.focus(); 
		chkword_templi();
		onPreviewText();
     }
  	
	function scroll_prevent() {
return;
			window.onscroll = function(){
			var arr = document.getElementsByTagName('textarea');
			for( var i = 0 ; i < arr.length ; i ++  ){
				try { arr[i].blur(); }catch(e){}
			}
		}
	}

	$("#myModal").unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			$(".enter").click();
		}
	});

	$(document).ready(function() {
<?if(date('H') < '08' || date('H') > '20') {?>
		$(".content").html("저녁8시~아침8시 까지는 친구톡이 차단<?=($mem->mem_2nd_send) ? '되어  일반문자 로만 전송됩니다.' : ' 됩니다.'?>");
		$('#myModal').modal({backdrop: 'static'});
<?}?>
		$('#send_friend_content').html('').load(
			(document.location.href.indexOf("/sender/send/friend") > -1) ? "/biz/sender/send/select_profile" : "",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>" },
			function() {
				if(document.location.href.indexOf("/sender/send/friend") > -1) {
					var current_btn_num = $("#friendtalk_table tr:last").attr("name");
					$("#no").attr("hidden", false);
					$("#btn_type_td").attr("hidden", false);
					$("#wl_1_"+current_btn_num).attr("hidden",false);
					$("#wl_2_"+current_btn_num).attr("hidden",false);
					$("#wl_3_"+current_btn_num).attr("hidden",false);
					$("#wl_4_"+current_btn_num).attr("hidden",false);
					$("#wl_5_"+current_btn_num).attr("hidden",false);
					$("#btn_del").attr("hidden", false);
					$("#btn_add_msg").attr("hidden", true);
					$("#btn_type1").val("WL");
					$("#btn_type1").select2();
					//$("#btn_type1 option:eq(1)").prop("selected", true);
					//$("#btn_type1").select2().select2("val", "WL");
					var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
					var pf_ynm = $("#pf_ynm").val();

				    // 20190107 이수환 수정 
					//$("#btn_name2_1").val("세일전단지");//pf_ynm);
					$("#btn_name2_1").val($("#find_linkbtn_name").val());
					$("input[name=btn_url21]").val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
					link_name(document.getElementById("btn_type1"),1);

					var content = "(광고) "+$("#pf_ynm").val()+"\r\r\r<?=((strpos($mem->mad_free_hp, 'http')===false) ? '': $mem->mad_free_hp)?>\r수신거부 : 홈>친구차단";
					$("#templi_cont").val(content);
					chkword_templi();
					onPreviewText();
				} else {
					$("#btn_add").attr("hidden", true);
					$("#modalBtn").attr("disabled",true).css("cursor","default").css("background-color","#2E6492");
					$("#templi_cont").val('').attr("disabled",true).css("cursor","default");
					$("#btn_name").val('').attr("disabled",true).css("cursor","default");
					$("#btn_url").val('').attr("disabled",true).css("cursor","default");
					$("#lms_select").attr("checked",true).attr("disabled",true).css("cursor","default");
					var msg = ''.replace(/&lt;br \/&gt;/gi, "\r").replace(/&amp;gt;/gim, ">").replace(/&amp;lt;/gim, "<").replace(/&amp;amp;/gim, "&");
					$("#lms_kakaolink_select").attr("disabled",false);
					$("#lms_kakaolink_select").uniform();
					$("#lms").attr("disabled",false).val(msg).focus();
					chkword_lms();
					resize_cont(document.getElementById("lms"));

					$("#text").val(msg);
					onPreviewText();
				}
			}
		);
	});

	// 템플릿 클릭 선택
	var selected="";
	$(".scrolltbody tbody tr").click(function() {
		$(".scrolltbody tbody tr").css("background-color", "white");
		$(".scrolltbody tbody tr").css("color", "dimgrey");
		$(this).css("background-color", "#4d7496");
		$(this).css("color", "white");
		selected = $(this).find(".pf_key").text();
	});

	//미리보기 클릭시 그림자 효과 없애기
	$("#text").click(function() {
		$(this).css("box-shadow","none");
	});
	$("#linkView").click(function() {
		$(this).css("box-shadow","none");
	});
	// 템플릿내용 미리보기
	// var contents = document.getElementById('templi_cont').value;
	var height = $(".message").prop("scrollHeight");
	if( height < 408 ) {
		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
	} else {
		$("#text").css("height","1px").css("height","460px");
	}
	$(function() {
		$("#templi_cont.autosize").keyup(function () {
			var height = $(".message").prop("scrollHeight");
			if( height < 408 ) {
				$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			} else {
				var message = $("#text").val();
				var height = $(".message").prop("scrollHeight");
				if(message==""){
					$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
				} else {
					$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				}
			}
		});
	});

	// 미리보기 2차발신 우선
	function onPreviewText() {
		var file = document.getElementById('filecount').value;
		if (file == ''){
			var cont=$('#lms').val();
			if (!cont || cont == "" ) {
				cont=$('#templi_cont').val();
			}
			$('#text').val(cont);

			var height = $(".message").prop("scrollHeight");
			if( height < 408 ) {
				$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			} else {
				var message = $("#text").val();
				if(message==""){
					$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
				} else {
					$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				}
			}
			if (cont && cont == "") {
				$('#text').val(' 메세지를 입력해주세요.');
			}
		}
		scroll_prevent();
	}

	//프로필 선택 모달 확인
	function select_profile() {
		var activeTr = $("#profile_list tbody tr.active");

		if(activeTr.length == 0 ) {
			$("#profile_select").css("tabindex","-1");
			$("#myModal").css("tabindex","1");
			$(".content").html("프로필을 먼저 선택해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#profile_select").focus();
			});
		} else {
			var profileKey = activeTr.find('.pf_key').text();

			$('#send_friend_content').html('').load(
				"/biz/sender/send/select_profile",
				{
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"profileKey": profileKey
				},
				function() {
					$("#profile_select").modal("hide");
					$('.uniform').uniform();

					var current_btn_num = $("#friendtalk_table tr:last").attr("name");
					$("#no").attr("hidden", false);
					$("#btn_type_td").attr("hidden", false);
					$("#wl_1_"+current_btn_num).attr("hidden",false);
					$("#wl_2_"+current_btn_num).attr("hidden",false);
					$("#wl_3_"+current_btn_num).attr("hidden",false);
					$("#wl_4_"+current_btn_num).attr("hidden",false);
					$("#wl_5_"+current_btn_num).attr("hidden",false);
					$("#btn_del").attr("hidden", false);
					$("#btn_add_msg").attr("hidden", true);
					$("#btn_type1").val("WL");
					$("#btn_type1").select2();
					//$("#btn_type1 option:eq(1)").prop("selected", true);
					//$("#btn_type1").select2().select2("val", "WL");
					var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
					var pf_ynm = $("#pf_ynm").val();
				    // 20190107 이수환 수정 
					//$("#btn_name2_1").val("세일전단지");//pf_ynm);
					$("#btn_name2_1").val($("#find_linkbtn_name").val());

					$("input[name=btn_url21]").val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
					refreshPreview();
					chkword_templi();
					link_name(document.getElementById("btn_type1"),1);
				}
			);
		}
	}

	function refreshPreview() {
		var html = '<p class="message">' +
		'        <textarea id="text" data-always-visible="1"' +
		'data-rail-visible="0" cols="25" readonly="readonly"' +
		'style="margin-left:-5px;margin-top:-4px;overflow:hidden;border:0;background-color:white;resize:none;cursor:default;" placeholder=" 메세지를 입력해주세요."> 메세지를 입력해주세요.</textarea>' +
		'</p>';
		$('#phone_standard').html('').html(html);
		onPreviewText();
	}

	function search_enter() {
		$(document).unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$("#check").click();
			}
		});
	}

	//친구톡, 2차발신 입력란 가변 처리
	function resize_cont(obj) {
		$(this).on('keyup', function(evt){
			if(evt.keyCode == 8){
				if (obj.scrollHeight < 103){
					obj.style.height = "1px";
					obj.style.height = "103px";
				}
			}
		});
		if (obj.scrollHeight <= 409 && obj.scrollHeight > 113 ) {
			obj.style.height = "1px";
			obj.style.height = (20 + obj.scrollHeight) + "px";
		} else if (obj.scrollHeight > 412){
			obj.style.height = "1px";
			obj.style.height = "412px";
		} else if (obj.scrollHeight <= 430 && obj.scrollHeight > 133) {
			obj.style.height = "1px";
			obj.style.height = (obj.scrollHeight-20) + "px";
		} else if (obj.scrollHeight < 103){
			obj.style.height = "1px";
			obj.style.height = "103px";
		}
		obj.focus();
	}

	<?/*---------------------------*
		| 카카오 친구추가 링크 넣기 |
		*---------------------------*/?>
	function insert_kakao_link(check){
		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;
		$.ajax({
			url: "/biz/short_url",
			type: "POST",
			data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", "long_url":long_url},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var short_url = "카톡친구추가 바랍니다!  " + json['short_url'];
				var content;
				var index;
				var cont;
				cont = $("#lms").val();
				if (check.checked) {
					if (new RegExp(/무료수신거부/gi).test($("#lms").val())) {
						content = $("#lms").val().replace("무료수신거부", short_url + "\r무료수신거부");
						$("#lms").val(content).focus();
					} else {
						content = $("#lms").val().replace(cont, cont + "\r" + short_url);
						$("#lms").val(content).focus();
					}
					//2차발신 입력란 가변 처리

					resize_cont(document.getElementById("lms"));
					chkword_lms();
					//미리보기 insert_kakao_link
					onPreviewText();
				} else {
					if ((new RegExp(/무료수신거부/gi).test(cont))) {
						index = cont.indexOf("무료수신거부");
						var first = cont.slice(0, index - 1).replace(short_url, "");
						var last = cont.slice(index);
						$("#lms").val(first + last).focus();
						chkword_lms();
					} else {
						index = cont.indexOf(short_url);
						$("#lms").val(cont.slice(0, index - 1)).focus();
						chkword_lms();
					}
					onPreviewText(check);
					if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
						$("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
					} else {
						resize_cont(document.getElementById("lms"));
					}
					chkword_lms();
				}
			}
		});
	}

	<?/*-----------------------*
		| 2차 발신여부 체크박스 |
		*-----------------------*/?>
	function select_lms(check){
		var sms_sender = $("#sms_sender").val();
		//alert(check.checked);
		//if(check.checked == 'undefined
		if (sms_sender == "None" || sms_sender.replace(/ /gi, "") == "") {
			$(".content").html("발신번호가 등록되지 않았습니다.<br>" + "발신프로필 목록에서 발신번호를 등록해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$("input:checkbox[id='lms_select']").removeAttr('checked');
			$(".uniform").uniform();
		} else {
			if (check.checked  || (check.checked == undefined  && $("#lms_select").is(":checked"))) {
				var pf_ynm = $("#pf_ynm").val();

<?if($mem->mem_2nd_send=='NONE' || $mem->mem_2nd_send=='') { /* 업체정보에 2차발신이 선택 되지 않으면 LMS 발신 불가 하도록 함. */ ?>
                alert('2차 발신이 설정 되지 않았습니다.\n관리자에게 문의 하세요.');
                $("input:checkbox[id='lms_select']").removeAttr('checked');
<?} else {  if($mem->mem_2nd_send=='phn' || $mem->mem_2nd_send=='015' || $mem->mem_2nd_send=='NASELF' || $mem->mem_2nd_send=='GREEN_SHOT' || $mem->mem_2nd_send=='PHONE') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입해요 */ ?>
				var phn = '<?=$this->Biz_model->reject_phn?>';
				<?if( $mem->mem_2nd_send=='015') { ?>
				phn = '080-1111-1111';
				<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
				phn = '080-855-5947';
				<?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
				phn = '080-156-0186';
				<?} else if($mem->mem_2nd_send=='PHONE') { ?>
				phn = '080-889-8383';
				<?}?>
				if (phn != "None" && phn.replace(/ /gi, "") != "") {
					var cont = $("#templi_cont").val().replace("수신거부 : 홈>친구차단", "무료수신거부 : " + phn);
				} else {
					var cont = $("#templi_cont").val().replace("수신거부 : 홈>친구차단", "무료수신거부 : ");
				}
<?} else {?>
				var cont = $("#templi_cont").val().replace("수신거부 : 홈>친구차단", "");
<?}?>
 
                var kakaochar = new Array('(미소)','(윙크)','(방긋)','(반함)','(눈물)','(절규)','(크크)','(메롱)','(잘자)','(잘난척)','(헤롱)',
                                		'(놀람)','(아픔)','(당황)','(풍선껌)','(버럭)','(부끄)','(궁금)','(흡족)','(깜찍)','(으으)','(민망)',
                                		'(곤란)','(잠)','(행복)','(안도)','(우웩)','(외계인)','(외계인녀)','(공포)','(근심)','(악마)','(썩소)',
                                		'(쳇)','(야호)','(좌절)','(삐침)','(하트)','(실연)','(별)','(브이)','(오케이)','(최고)','(최악)',
                                		'(그만)','(땀)','(알약)','(밥)','(커피)','(맥주)','(소주)','(와인)','(치킨)','(축하)','(음표)',
                                		'(선물)','(케이크)','(촛불)','(컵케이크a)','(컵케이크b)','(해)','(구름)','(비)','(눈)','(똥)',
                                		'(근조)','(딸기)','(호박)','(입술)','(야옹)','(돈)','(담배)','(축구)','(야구)','(농구)','(당구)',
                                		'(골프)','(카톡)','(꽃)','(총)','(크리스마스)','(콜)' );
                 
                for ( var i = 0; i < kakaochar.length ; i++ ) {
                    cont = cont.replace(kakaochar[i],"");
                } 
 
                
				if ($("#lms").val().replace(/ /gi, "") == "") {
						$("#lms").val(cont);
						$("#text").val(cont);
				}
				$("#lms_kakaolink_select").removeAttr("disabled");
				$("#lms_kakaolink_select").uniform();
				$("#lms").removeAttr("disabled").css("background-color", "white").focus();
				resize_cont(document.getElementById("lms"));

				//미리보기 링크 삭제
				$("#friendtalk_table tr").each(function () {
					if ($(this).find("#no").text()) {
						var name = $(this).find("#no").parent().attr("name");
						$("#btn_preview_div" + name).remove();
					}
				});

				$("#text").css("margin-bottom", "0px");

				var height = $("#text").prop("scrollHeight");
				if (height < 408) {
					$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
				} else {
					var message = $("#text").val();
					var height = $("#text").prop("scrollHeight");
					if (message == "") {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
					} else {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					}
				}
				chkword_lms();
				<? } ?>
			} else {
				//카카오 친구추가 링크 체크박스 선택 되어있을 경우 해제
				if ($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
					$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
				}
				$("#lms_kakaolink_select").attr("checked", false).attr('disabled', true);
				$("#lms_kakaolink_select").uniform();
				$("#lms").val("").css("background-color", "#EEEEEE").attr('disabled', 'disabled').css("height", "103px");

				$('.uniform').uniform();

				//미리보기 친구톡으로 변경되는 부분
				var templi_cont = $("#templi_cont").val();
				$("#text").val(templi_cont);
				//미리보기 링크 보이기
				$("#friendtalk_table tr").each(function () {
					if ($(this).find("#no").text()) {
						var current_btn_num = $(this).find("#no").parent().attr("name");
						link_name(document.getElementById("btn_type" + current_btn_num), current_btn_num);
					}
				});
				$("#text").css("margin-bottom", "10px");
				var height = $("#text").prop("scrollHeight");
				if (height < 408) {
					$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
				} else {
					var message = $("#text").val();
					var height = $("#text").prop("scrollHeight");
					if (message == "") {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
					} else {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					}
				}
         }
		}
	}

	//미리보기 - 버튼링크
	function link_name(obj,no) {
		var file = document.getElementById('filecount').value;
		if (file == '' && $("input:checkbox[id='lms_select']").is(":checked") != true) {
			var name = $("#btn_preview"+no).val();
			if (name == undefined) {
				if(!(obj.value=="N" || obj.value=="DS" || obj.value=="WL" || obj.value=="AL" || obj.value=="BK" || obj.value=="MD")) {
					var count = no-1;
					$("#text").css("margin-bottom","10px");
					var html = '<div id="btn_preview_div' + no + '" class="' + no + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
					'<p data-always-visible="1" id="btn_preview' + no + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
					'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
					'>' + obj.value + '</p></div>';
					if (no == 1) {
						$("#text").after(html);
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					} else {
						for(count; count >= 0 ; count --) {
							if($("#btn_preview" + count).val() != undefined && $("#btn_preview" + no).val() == undefined) {
								$("#btn_preview_div" + count).after(html);
								$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
							} else if(count == 0) {
								if($("#btn_preview" + no).val() == undefined) {
									$("#text").after(html);
									$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
								}
							}
						}
					}
				} else {
					var count = no-1;
					var btn = 0;
					if(obj.value=="DS"){
						btn = 1;
					} else if (obj.value=="WL") {
						btn = 2;
					} else if (obj.value=="AL") {
						btn = 3;
					} else if (obj.value=="BK") {
						btn = 4;
					} else if (obj.value=="MD") {
						btn = 5;
					}
					var content;
					if($("#btn_name"+btn+"_"+no).val().trim() != "") {
						content = $("#btn_name"+btn+"_"+no).val();
					} else {
						content = "버튼명을 입력해주세요.";
					}
					$("#text").css("margin-bottom","10px");
					var html = '<div id="btn_preview_div' + no + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
					'<p data-always-visible="1" id="btn_preview' + no + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
					'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
					'>' + content + '</p></div>';
					if (no == 1) {
						$("#text").after(html);
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					} else {
						for(count; count >= 0 ; count --) {
							if($("#btn_preview" + count).val() != undefined && $("#btn_preview" + no).val() == undefined) {
								$("#btn_preview_div" + count).after(html);
								$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
							} else if(count == 0) {
								if($("#btn_preview" + no).val() == undefined) {
									$("#text").after(html);
									$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
								}
							}
						}
					}
				}
			} else {
				if(obj.value=="DS" || obj.value=="WL" || obj.value=="AL" || obj.value=="BK" || obj.value=="MD") {
					var btn;
					if(obj.value=="DS"){
						btn = 1;
					} else if (obj.value=="WL") {
						btn = 2;
					} else if (obj.value=="AL") {
						btn = 3;
					} else if (obj.value=="BK") {
						btn = 4;
					} else if (obj.value=="MD") {
						btn = 5;
					}
					var content;
					if ($("#btn_name"+btn+"_"+no).val().trim() != "") {
						content = $("#btn_name"+btn+"_"+no).val();
					} else {
						content = "버튼명을 입력해주세요.";
					}
					$("#btn_preview" + no).text(content);
					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				} else if (obj.value=="N") {
					if(no==1){
						$("#text").css("margin-bottom","0px");
					}
					$("#btn_preview_div" + no).remove();
				} else {
					if(obj.value.replace(/ /gi,"").trim()) {
						$("#btn_preview" + no).text(obj.value);
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					} else {
						$("#btn_preview" + no).text("버튼명을 입력해주세요.");
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					}
				}
			}
		}
	}

	//버튼링크 추가
	function btn_add(){
		var lastItemNo = $("#friendtalk_table tr:last").attr("name");
		var tr_length = $("#friendtalk_table tr").length-5;
		var current_btn_num = lastItemNo;
		var obj = Number(current_btn_num)+1;
		if(tr_length == 5 && $("#no").is(":hidden") == true) {
			$("#no").attr("hidden", false);
			$("#btn_type_td").attr("hidden", false);
			$("#n_1_"+current_btn_num).attr("hidden", false);
			$("#n_2_"+current_btn_num).attr("hidden", false);
			$("#btn_del").attr("hidden", false);
			$("#btn_add_msg").attr("hidden", true);
			$('select.select2').select2();
		} else if (tr_length < 12) {
			var btn_tr = tr_length-1;
			var no = btn_tr/2;
			var col_html = "";
			//공통
			col_html += "               <tr id='btn_content_" + obj + "_1' name='" + obj + "'>";
			col_html += "                   <td id='no' align='center' rowspan='2'>"+no+"</td>";
			col_html += "                   <td id='btn_type_td' name='btn_type_td' align='center' rowspan='2'>";
			col_html += "                       <select class='select2 input-width-small' id='btn_type" + obj +"' name='btn_type' onchange=\"modify_btn_type(" + obj + "), link_name(this," + obj + ");\">";
			col_html += "                           <option value='N'>선택</option>";
			col_html += "                           <option value='WL'>웹링크</option>";
			col_html += "                           <option value='AL'>앱링크</option>";
			col_html += "                           <option value='BK'>봇키워드</option>";
			col_html += "                           <option value='MD'>메시지전달</option>";
			col_html += "                       </select>";
			col_html += "                   </td>";
			col_html += "                   <td id='btn_add_msg' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
			//N
			col_html += "                   <td id='n_1_" + obj + "' align='center' rowspan='2'></td>";
			col_html += "                   <td id='n_2_" + obj + "' align='center' width='67px'></td>";
			//BK
			col_html += "                   <td id='bk_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name4' id='btn_name4_" + obj + "' maxlength='10' onkeyup='link_name(this," + obj + ");scroll_prevent();btn_name_chk(this," + obj + ");' type='text' class='form-control input-width-small inline'></td>";
			col_html += "                   <td id='bk_2_" + obj + "' align='center' width='67px' hidden></td>";
			//MD
			col_html += "                   <td id='md_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name5' id='btn_name5_" + obj + "' maxlength='10' onkeyup='link_name(this," + obj + ");scroll_prevent();btn_name_chk(this," + obj + ");' type='text' class='form-control input-width-small inline'></td>";
			col_html += "                   <td id='md_2_" + obj + "' align='center' width='67px' hidden></td>";
			//WL
			col_html += "                   <td id='wl_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name2' id='btn_name2_" + obj + "' maxlength='10' onkeyup='link_name(this," + obj + ");scroll_prevent();btn_name_chk(this," + obj + ");' type='text' class='form-control input-width-small inline'></td>";
			col_html += "                   <td id='wl_2_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Mobile</label><input style='margin-left: 10px;' type='text' value='http://' name='btn_url21' id='wl_2_btn_url_" + obj + "' maxlength='254' class='form-control input-width-medium inline'></td>";
			//AL
			col_html += "                   <td id='al_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name3' id='btn_name3_" + obj + "' maxlength='10' onkeyup='link_name(this," + obj + ");scroll_prevent();btn_name_chk(this," + obj + ");' type='text' class='form-control input-width-small inline'></td>";
			col_html += "                   <td id='al_2_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Android</label><input style='margin-left: 10px;' type='text' name='btn_url31' id='al_2_btn_url_" + obj + "' maxlength='254' class='form-control input-width-medium inline'></td>";
			//공통
			col_html += "                   <td id='btn_del' align='center' rowspan='2'><a onclick='btn_del(" + obj + ");' style='cursor:pointer'>제거</a></td>";
			col_html += "               </tr>";
			col_html += "               <tr id='btn_content_" + obj + "' name='" + obj + "'>";
			col_html += "                   <td id='wl_3_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>PC(선택)</label><input style='margin-left: 10px;' type='text' name='btn_url22' maxlength='254' class='form-control input-width-medium inline'></td>";
			col_html += "                   <td id='al_3_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>iOS</label><input style='margin-left: 10px;' type='text' name='btn_url32' maxlength='254' class='form-control input-width-medium inline'></td>";
			col_html += "               </tr>";

			$("#btn_content_" + lastItemNo).after(col_html);
			$('select.select2').select2();
		}
	}

	//버튼링크 타입 변경
	function modify_btn_type(counter){
		var btn_type=$("#btn_type"+counter).val(); //바꾸고 싶은 타입 값
		if(btn_type==null) return;

		$("#n_1_"+counter).attr("hidden",true);
		$("#n_2_"+counter).attr("hidden",true);
		$("#wl_1_"+counter).attr("hidden",true);
		$("#wl_2_"+counter).attr("hidden",true);
		$("#wl_3_"+counter).attr("hidden",true);
		$("#wl_4_"+counter).attr("hidden",true);
		$("#wl_5_"+counter).attr("hidden",true);
		$("#al_1_"+counter).attr("hidden",true);
		$("#al_2_"+counter).attr("hidden",true);
		$("#al_3_"+counter).attr("hidden",true);
		$("#al_4_"+counter).attr("hidden",true);
		$("#al_5_"+counter).attr("hidden",true);
		$("#bk_1_"+counter).attr("hidden",true);
		$("#bk_2_"+counter).attr("hidden",true);
		$("#bk_3_"+counter).attr("hidden",true);
		$("#md_1_"+counter).attr("hidden",true);
		$("#md_2_"+counter).attr("hidden",true);
		$("#md_3_"+counter).attr("hidden",true);

		if (btn_type == "N") {
			$("#n_1_"+counter).attr("hidden",false);
			$("#n_2_"+counter).attr("hidden",false);
		} else if (btn_type == "WL") {
			$("#wl_1_"+counter).attr("hidden",false);
			$("#wl_2_"+counter).attr("hidden",false);
			$("#wl_3_"+counter).attr("hidden",false);
			$("#wl_4_"+counter).attr("hidden",false);
			$("#wl_5_"+counter).attr("hidden",false);
		} else if (btn_type == "AL") {
			$("#al_1_"+counter).attr("hidden",false);
			$("#al_2_"+counter).attr("hidden",false);
			$("#al_3_"+counter).attr("hidden",false);
			$("#al_4_"+counter).attr("hidden",false);
			$("#al_5_"+counter).attr("hidden",false);
		} else if (btn_type == "BK") {
			$("#bk_1_"+counter).attr("hidden",false);
			$("#bk_2_"+counter).attr("hidden",false);
			$("#bk_3_"+counter).attr("hidden",false);
		} else if (btn_type == "MD") {
			$("#md_1_"+counter).attr("hidden",false);
			$("#md_2_"+counter).attr("hidden",false);
			$("#md_3_"+counter).attr("hidden",false);
		}
	}

    //버튼링크 제거
	function btn_del(obj) {
		var tr_length = $("#friendtalk_table tr").length-2;
		// 2019.01.04(이) 링크 버튼이 한개 있을때 제거 후 추가 불가능한 상태를 추가 할수 있도록 변경
		// if(tr_length < 6) { ====> if(tr_length < 9) {	변경
		if(tr_length < 9) {		 	
			$("#n_1_" + obj).attr("hidden", true);
			$("#n_2_" + obj).attr("hidden", true);
			$("#ds_1_"+ obj).attr("hidden", true);
			$("#ds_2_"+ obj).attr("hidden", true);
			$("#wl_1_"+ obj).attr("hidden", true);
			$("#wl_2_"+ obj).attr("hidden", true);
			$("#wl_3_"+ obj).attr("hidden", true);
			$("#al_1_"+ obj).attr("hidden", true);
			$("#al_2_"+ obj).attr("hidden", true);
			$("#al_3_"+ obj).attr("hidden", true);
			$("#bk_1_"+ obj).attr("hidden", true);
			$("#bk_2_"+ obj).attr("hidden", true);
			$("#md_1_"+ obj).attr("hidden", true);
			$("#md_2_"+ obj).attr("hidden", true);
			$("#btn_type" + obj).val("N");
			$("#btn_type" + obj).select2();
			//$("#btn_type" + obj + " option:eq(0)").prop("selected", true);
			//$("#btn_type" + obj).select2("val", "N");

			$("#friendtalk_table").find(document.getElementsByName("btn_name1")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_name2")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_name3")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_name4")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_name5")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_url21")).val("http://");
			$("#friendtalk_table").find(document.getElementsByName("btn_url22")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_url31")).val("");
			$("#friendtalk_table").find(document.getElementsByName("btn_url32")).val("");
			$("#no").attr("hidden", true);
			$("#btn_type_td").attr("hidden", true);
			$("#btn_del").attr("hidden", true);
			$("#btn_add_msg").attr("hidden", false);

			$("#btn_preview_div" + obj).remove();
			$("#text").css("margin-bottom","0px");
		} else {
			$("#btn_content_" + obj).remove();
			$("#btn_content_" + obj + "_1").remove();
			$("#btn_preview_div" + obj).remove();
			var no = 1;
			$("#friendtalk_table tr").find("#no").each(function () {
				$(this).text(no);
				no++;
			});
		}
	}

	//예약발송 체크박스 체크
	function ReserveCheck() {
		if($("#reserve_check").is(":checked") == true) {
			$(".reserve_content").attr("hidden", false);
		} else {
			$(".reserve_content").attr("hidden", true);
		}
	}

	//발신번호 추가
	function add_number(phn, kind, name) {
		if(typeof(kind)=="undefined") { kind = ""; }
		if(typeof(name)=="undefined") { name = ""; }
		var lastItemNo = $("#tel tbody tr:last").attr("class");
		var no = parseInt(lastItemNo) + 1;
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var phnNumVal = '';
		if (typeof phn != 'undefined') {
			phnNumVal = ' value=' +  phn + ' ';
		}

		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ($("input:checkbox[value='1']").is(":checked") == true) {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index >= 5) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
						'<input class="uniform" name="sel_del" id="'+ no +'" value="' + no + '" type="checkbox"></td>' +
						'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
						'<td width="40%"><input type="text" ' + phnNumVal +
						'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
						'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
						'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
						'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
						'<td width="40%"><input type="text" ' +  phnNumVal +
						'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
						'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {
				$(".content").html("최대 60,000개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		} else {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index > 5) {
					var tr = '<tr class="' + no + '" name="'+no+'" id="tel_tbody_tr" name="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
						'<input type="checkbox" name="sel_del" id="'+ no +'"  value="'+no+'" class="uniform"></td>'+
						'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
						'<td width="40%"><input type="text" ' + phnNumVal +
						'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
						'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
						'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
						'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
						'<td width="40%"><input type="text" ' +  phnNumVal +
						'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제">' +
						'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {
				$(".content").html("최대 60,000개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		}
	}

	// 발신번호 삭제
	function tel_remove(obj) {
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if(row_index==2) {
			var tel_number = $("#tel_number").val();
			if(tel_number==""){
				$(".content").html("삭제할 수신 번호가 없습니다.");
				$("#myModal").modal({backdrop: 'static'});
				$("#myModal").unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
			} else {
				$(".content").html("삭제하시겠습니까?");
				$("#myModalCheck").modal({backdrop: 'static'});
				$("#myModalCheck").unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						tel_del(obj);
					}
				});
				$(".submit").click(function() {
					tel_del(obj);
					$(".submit").unbind("click");
				});
			}
		} else {
			$(".content").html("삭제하시겠습니까?");
			$("#myModalCheck").modal({backdrop: 'static'});
			$("#myModalCheck").unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					tel_del(obj);
				}
			});
			$(".submit").click(function() {
				tel_del(obj);
				$(".submit").unbind("click");
			});
		}
	}

	function tel_del(obj) {
		$("#myModalCheck").modal("hide");
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var tr = $("." + obj);
		if (row_index != 2) {
			if (row_index > 6) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "235px");
				$(this).css("overflow", "scroll");
			} else {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", (height - 47) + "px");
			}
		} else {
			tr.remove();
			var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
				'<input class="uniform" name="sel_del" id="' + 1 + '"  value="' + 1 + '" type="checkbox"></td>'+
				'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
				'<td width="40%"><input type="text" ' +
				'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
				'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
				'<i class="icon-trash"></i> 삭제</a></td></tr>';
			$("#tel_tbody").html(tr);
			var height = $("#tel_tbody").prop("scrollHeight");
			$("#tel_tbody").css("height", "47px");
			$("#1").uniform();
		}
	}

	//발신번호 선택삭제
	function selectDelRow() {
		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
			$(".content").html("삭제할 수신 번호를 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			$("input[name=sel_del]:checked").each(function () {
				var obj = $(this).val();
				var table = document.getElementById("tel");
				var row_index = table.rows.length;
				if (row_index == 2) {
					var tel_number = $("#tel_number").val();
					if (tel_number == "") {
						$(".content").html("삭제할 수신 번호가 없습니다.");
						$("#myModal").modal({backdrop: 'static'});
						$("#myModal").unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("삭제하시겠습니까?");
						$("#myModalCheck").modal({backdrop: 'static'});
						$("#myModalCheck").unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".submit").click();
							}
						});
						$(".submit").click(function() {
							tel_select_del(obj);
							$(".submit").unbind("click");
						});
					}
				} else {
					$(".content").html("삭제하시겠습니까?");
					$("#myModalCheck").modal({backdrop: 'static'});
					$("#myModalCheck").unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".submit").click();
						}
					});
					$(".submit").click(function() {
						tel_select_del(obj);
						$(".submit").unbind("click");
					});
				}
			});
		}
	}

	//팝업창 선택 삭제
	function tel_select_del(obj) {
		$("#myModalCheck").modal("hide");
		var tr = $("." + obj);
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var cnt = $("input[name=sel_del]:checked").length;
		var row = row_index-cnt;
		if(cnt!=0) {
			if (row_index >= 7) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "235px");
				$(this).css("overflow", "scroll");
			} else if (row < 6 && row >= 2) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", (row*47-47) + "px");
			} else if (1 >= row) {
				tr.remove();
				if(row==1) {
					var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
						'<input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>'+
						'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
						'<td width="40%"><input type="text" ' +
						'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
						'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("#tel_tbody").html(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "47px");
					$("#1").uniform();
				}
			}
		}
	}

	//발신번호 숫자만 입력 가능
	function SetNum(obj) {
		if($("#pf_key").val()==null || $("#pf_key").val()==''){
			$(".content").html("프로필을 먼저 선택해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				obj.value = "";
			});
		} else {
			var val = obj.value;
			var re = /[^0-9]/gi;
			obj.value = val.replace(re, "");
		}
	}

	//모달 검색
	function search() {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile");
		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "search");
		selectedField.setAttribute("value", search);
		form.appendChild(selectedField);
		var selectedField2 = document.createElement("input");
		selectedField2.setAttribute("type", "hidden");
		selectedField2.setAttribute("name", "selectBox");
		selectedField2.setAttribute("value", selectBox);
		form.appendChild(selectedField2);
		form.submit();
	}

	//프로파일 모달 검색
	function search_profile() {
		var type = $('#searchType2').val() || 'all';
		var searchFor = $('#searchStr2').val() || '';

		$('#profile_list').html('').load(
			"/biz/sender/send/profile",
			{
				"search_type": type,
				"search_for": searchFor
				// page 불필요.. 향후에도 필요할 일이 없다고 보임
			}
		);
	}

	function search_sendbox() {
		try {
			open_page_sendbox(1);
		}catch(e){}
	}

	//모달 페이징
	function page(page) {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile");
		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);
		var searchField = document.createElement("input");
		searchField.setAttribute("type", "hidden");
		searchField.setAttribute("name", "search");
		searchField.setAttribute("value", search);
		form.appendChild(searchField);
		var selectField = document.createElement("input");
		selectField.setAttribute("type", "hidden");
		selectField.setAttribute("name", "selectBox");
		selectField.setAttribute("value", selectBox);
		form.appendChild(selectField);
		form.submit();
	}

	function showLimitOver($str)
	{
		$(".content").html("금일 발송 가능 건수를 모두 사용하였습니다."+$str);
		$('#myModal').modal({backdrop: 'static'});
		$('#filecount').filestyle('clear');
	}


<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
	var ft_price = '<?=$this->Biz_model->price_ft?>';
	var ft_img_price = '<?=$this->Biz_model->price_ft_img?>';
	var sms_price = '<?=$this->Biz_model->price_sms?>';
	var lms_price = '<?=$this->Biz_model->price_lms?>';
	var mms_price = '<?=$this->Biz_model->price_mms?>';
	var phn_price = '<?=$this->Biz_model->price_phn?>';
<?if($mem->mem_2nd_send=='phn') { echo "var resend_price = '".$this->Biz_model->price_phn."';"; }
	else if($mem->mem_2nd_send=='sms') { echo "var resend_price = '".$this->Biz_model->price_sms."';"; }
	else if($mem->mem_2nd_send=='lms') { echo "var resend_price = '".$this->Biz_model->price_lms."';"; }
	else if($mem->mem_2nd_send=='mms') { echo "var resend_price = '".$this->Biz_model->price_mms."';"; }
	else if($mem->mem_2nd_send=='GREEN_SHOT') { echo "var resend_price = '".$this->Biz_model->price_grs."';"; }
	else if($mem->mem_2nd_send=='NASELF') { echo "var resend_price = '".$this->Biz_model->price_nas."';"; }
	else { echo "var resend_price = '0';"; }
?>

	var resend_type = ms;			<?/* 2차발신 종류 */?>
	var resend_type_name = "<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>";	<?/* 2차발신 이름 */?>
	var charge_type = 0;				<?/* 발신단가 최대값 */?>
	<?/* 발신단가의 최대값과 재발신 방법을 설정 */?>
	function check_resend_method()
	{
		<?/* mms로 지정했어도 이미지가 없으면 LMS 또는 80자 미만이면 sms로 보내야 합니다. */?>
		<?/* 재발신 단가 산정 */?>
      
		if ($("input:checkbox[id='lms_select']").is(":checked") == true) { 
			charge_type = resend_price; 
			resend_type_name = "폰문자"; 
			resend_type = "P"; 
		} else { 
    		if (ms=="M" && $("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "" && $('#lms').val().replace(/ /gi, "") != "" && resend_price > charge_type) { 
        		charge_type = resend_price; 
        		resend_type_name = "MMS"; 
        		resend_type = "M"; 
        	}
    		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) > 80 && lms_price > charge_type) { 
        		charge_type = lms_price; 
        		resend_type_name = "LMS"; 
        		resend_type = "L"; 
        	}
    		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) <= 80 && sms_price > charge_type) { 
        		charge_type = sms_price; 
        		resend_type_name = "SMS"; 
        		resend_type = "S"; 
        	}
 		} 
		<?/* 친구톡, 친구톡 이미지 가격 산정 */?>
		if ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "" && $('#lms').val().replace(/ /gi, "") == "" && ft_img_price > charge_type) <?/* 친구톡 이미지 */?>
			{ charge_type = ft_img_price; }
		if (ft_price > charge_type)	<?/* 친구톡 */?>
			{ charge_type = ft_price; }
	}
<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>


	coin=0;

	<?/*----------------------------------------------------------*
	   | 수신번호 목록에서 일부 선택(체크) 하여 선택발송버튼 클릭 |
	   *----------------------------------------------------------*/?>
	function select_send() {
		window.onscroll = function(){
			var arr = document.getElementsByTagName('textarea');
			for( var i = 0 ; i < arr.length ; i ++  ){
				try { arr[i].off('blur'); }catch(e){}
			}
		};
		if($("#pf_key").val()==null || $("#pf_key").val()==''){
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ('0' == undefined && document.getElementById("templi_cont").value.replace(/ /gi,"") == "") {
			$(".content").html("템플릿 내용을 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#templi_cont").focus();
			});
		} else if ($("input:checkbox[id='lms_select']").is(":checked") == true && $('#lms').val().replace(/ /gi,"")==""){
			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});
		} else if ($("#img_url").val() != undefined && $("#img_url").val() != "" && $("#img_link").val().replace(/ /gi, "") == "") {
			// 이미지 url이 등록되면 이미지 링크도 넣어야 합니다.
			$(".content").html("이미지 링크를 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#img_link").focus();
			});
		} else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
			$(".content").html("수신 번호를 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#all_select").focus();
			});
			$(document).unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					$(".enter").click();
				}
			});
		} else {
			var check = true;
			$("#friendtalk_table tr").each(function () {
				var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
				if (btn_type != undefined) {
					var focus;
					if (btn_type == "N" && $(this).find("#no").is(":hidden") == false) {
						check = false;
						$(".content").html("버튼타입을 선택해주세요.");
						$("#myModal").modal('show');
					} else if (btn_type == "DS" && $(this).find(document.getElementsByName("btn_name1")).val().trim() == "") {
						check = false;
						focus = $(this).find(document.getElementsByName("btn_name1"));
						$(".content").html("배송조회 버튼의 버튼명을 입력해주세요.");
						$("#myModal").modal('show');
						$('#myModal').on('hidden.bs.modal', function () {
							focus.focus();
						});
					} else if (btn_type == "WL") {
						if($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name2"));
							$(".content").html("웹링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if ($(this).find(document.getElementsByName("btn_url21")).val().trim() == "" || $(this).find(document.getElementsByName("btn_url21")).val().trim() == "http://") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_url21"));
							$(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						}
					} else if (btn_type == "AL") {
						if($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name3"));
							$(".content").html("앱링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if ($(this).find(document.getElementsByName("btn_url31")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_url31"));
							$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if ($(this).next('tr').find(document.getElementsByName("btn_url32")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).parent().find(document.getElementsByName("btn_url32"));
							$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						}
					} else if (btn_type == "BK" && $(this).find(document.getElementsByName("btn_name4")).val().replace(/ /gi, "") == "") {
						check = false;
						focus = $(this).find(document.getElementsByName("btn_name4"));
						$(".content").html("봇키워드 링크 버튼의 버튼명을 입력해주세요.");
						$("#myModal").modal('show');
						$('#myModal').on('hidden.bs.modal', function () {
							focus.focus();
						});
					} else if (btn_type == "MD" && $(this).find(document.getElementsByName("btn_name5")).val().replace(/ /gi, "") == "") {
						check = false;
						focus = $(this).find(document.getElementsByName("btn_name5"));
						$(".content").html("메시지전달 링크 버튼의 버튼명을 입력해주세요.");
						$("#myModal").modal('show');
						$('#myModal').on('hidden.bs.modal', function () {
							focus.focus();
						});
					}
				}
			});
			$("input:checkbox[name=sel_del]:checked").each(function () {
				var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				if (array == "") {
					check = false;
					$(".content").html("수신 번호를 입력해주세요.");
					$('#myModal').modal({backdrop: 'static'});
					$('#myModal').on('hidden.bs.modal', function () {
						$("input:checkbox[name=sel_del]:checked").each(function () {
							array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
							if (array == "") {
								$(this).parent().parent().parent().parent().find("#tel_number").focus();
							}
						});
					});
					$('#myModal').unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
					return false;
				}
			});
			if($("#reserve_check").is(":checked") == true) {
				if($("#reserve").val().trim() == ""){
					check = false;
					$(".content").html("예약 발송 일시를 선택해주세요.");
					$('#myModal').modal({backdrop: 'static'});
				} else {
					var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
					var reserveDt = reserve+"00";
					var now = moment().add(10,'minutes');
					var min = now.format("YYYYMMDDHHmm");
					var minDt = min+"00";
					if(reserveDt < minDt){
						check = false;
						$(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
						$('#myModal').modal({backdrop: 'static'});
					} else {
						check = true;
					}
				}
			}
			if(check == false) return false;
			var _2ndflag = "N";
			if ($("input:checkbox[id='lms_select']").is(":checked") == true)
				_2ndflag = "Y";
				
			$.ajax({
				url: "/biz/sender/coin",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					coin = json['coin'];
					var limit = parseInt(json['limit']);
					var sent = parseInt(json['sent']);
					var limit_msg = "";
					if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
					if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

					var kakao = Math.floor(Number(coin) / Number(ft_price));
					var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
					var kakao_img = Math.floor(Number(coin) / Number(ft_img_price));
					var k_i = kakao_img.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');
					var cnt = $("input[name=sel_del]:checked").length;

					check_resend_method();
					<?/* 재발신 가능 건수를 산정합니다. */?>
					var resends = (charge_type > 0) ? Math.floor(Number(coin) / Number(charge_type)) : 0;
					var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

					if (Number(coin) - (Number(charge_type) * Number(cnt)) < 0) {
						$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(cnt)));
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						var content_msg = ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "") ? "친구톡 이미지 발송 가능 건수 : " + k_i + "건" : "친구톡 발송 가능 건수 : " + k + "건";
						if(ms!="" && ($('#lms').val().replace(/ /gi, "") != "" || ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != ""))) {
							content_msg += "<br/>" + resend_type_name + " 발송 가능 건수 : " + resend_cnt + "건";
						}
						$(".content").html(limit_msg + content_msg + "<br/><br/>선택 발송하시겠습니까? (발송할 건수 : " + cnt + "건)");
						$("#myModalSelect").modal({backdrop: 'static'});
						$("#myModalSelect").unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".select-send").click();
							}
						});
						$(".select-send").click(function () {
							$("#myModalSelect").modal('hide');
						});
					}
				}
			});
		}
	}

	<?/*----------------------------------------------------------------------------*
		| 수신번호 목록을 체크한 후 선택발송을 클릭했을때 실제 발송하는 부분 입니다. |
		*----------------------------------------------------------------------------*/?>
	function select_move() {
		var senderBox = $("#sms_sender").val();						<?/* callback번호 */?>
		var smsOnly='';
		var templi_cont = $("#templi_cont").val();					<?/* 친구톡 내용 */?>
		var profile = document.getElementById("pf_key").value;	<?/* 발신프로필 key */?>
		var msg=msg = $('#lms').val();
		var kind = resend_type;												<?/* 2차발신 방법 */?>
		var tit = $("#tit").val();
		var img_url = '';
		var img_link = '';
		if ($("#img_url").val() != undefined && $("#img_url") != "" && $("#img_link") != "") {
			img_url = $("#img_url").val();
			img_link = $("#img_link").val();
		}
		if ('0' != 0 && $("#tel_temp").val() == undefined){
			smsOnly = 'Y';
		}

		var btn = [];
		for (var b = 1; b <= 5; b++) {
			btn[b] = new Array();
		}
		var obj = [];
		var btn_num = 1;
		<?/* 버튼 */?>
		$("#friendtalk_table tr").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined && btn_type != "N") {
				obj[btn_num] = new Object();
				obj[btn_num].type = btn_type;
				if (btn_type == "WL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url21")).val().trim();
					obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
				} else if (btn_type == "AL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url31")).val().trim();
					obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
				} else if (btn_type == "BK") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
				} else if (btn_type == "MD") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
				}
				btn[btn_num].push(obj[btn_num]);
				btn_num++;
			}
		});
		var btn1 = JSON.stringify(btn[1]);
		var btn2 = JSON.stringify(btn[2]);
		var btn3 = JSON.stringify(btn[3]);
		var btn4 = JSON.stringify(btn[4]);
		var btn5 = JSON.stringify(btn[5]);

		var tel_number = new Array();
		$("input:checkbox[name=sel_del]:checked").each(function () {
			var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
			tel_number.push(array);

		});
		<?/* 예약발송 설정 */?>
		var reserveDt = "00000000000000";
		if($("#reserve_check").is(":checked") == true) {
			if($("#reserve").val().trim() != ""){
				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
				reserveDt = reserve+"00";
			}
		}

		$.ajaxSettings.traditional = true;
		$.ajax({
			url: "/biz/sender/friend/all",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"templi_cont": templi_cont,
				"msg": msg,
				"kind": kind,
				"senderBox": senderBox,
				"tit": tit,
				"pf_key": profile,
				"img_url": img_url, "img_link": img_link,
				"btn1": btn1, "btn2": btn2, "btn3": btn3, "btn4": btn4, "btn5": btn5,
				"smsOnly": smsOnly,
				"tel_number[]": tel_number,
				"reserveDt": reserveDt
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				$('#navigateURL').val("");
				var code = json['code'];
				var message = json['message'];
				if (code == "success") {
					//$('#navigateURL').val(document.location.href);
					$('#navigateURL').val("/biz/sender/history");
					$(".content").html("발송 요청되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("발송 실패되었습니다.<br/>" + message);
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			},
			error: function () {
				$(".content").html("처리되지 않았습니다.");
				$('#myModal').modal({backdrop: 'static'});
				$(document).unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
			}
		});
	}

	<?/*----------------------------------------------------------------------------------------------------------------*
	   | 전체고객정보 불러오기 하여 전체 발송버튼 클릭 [업로드 발송 포함] 또는 수신목록이 표시된 상태에서 전체발송 클릭 |
	   *----------------------------------------------------------------------------------------------------------------*/?>
	function all_send() {
		window.onscroll = function(){
			var arr = document.getElementsByTagName('textarea');
			for( var i = 0 ; i < arr.length ; i ++  ){
				try { arr[i].off('blur'); }catch(e){}
			}
		};
		if($("#upload_send").val() == undefined) {
			if($("#pf_key").val()==null || $("#pf_key").val()==''){
				$(".content").html("프로필을 먼저 선택해주세요.");
				$('#myModal').modal({backdrop: 'static'});
			} else if ('0' == undefined && document.getElementById("templi_cont").value.replace(/ /gi,"") == "") {
				$(".content").html("템플릿 내용을 입력해주세요.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
			} else if ($("input:checkbox[id='lms_select']").is(":checked") == true && $('#lms').val().replace(/ /gi,"")==""){
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 입력해주세요.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
			} else if ($("#img_url").val() != undefined && $("#img_url").val() != "" && $("#img_link").val().replace(/ /gi, "") == "") {
				$(".content").html("이미지 링크를 입력해주세요.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#img_link").focus();
				});
			} else if ($("#img_url").val() != undefined && $("#img_url").val() != "" && $("#templi_cont").val().length > 400) {
				$(".content").html("친구톡(이미지)의 경우 내용을 400자 이내로 입력하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
			} else if (($("#img_url").val() == undefined || $("#img_url").val() == "") && $("#templi_cont").val().length > 1000) {
				$(".content").html("친구톡은 내용을 1,000자 이내로 입력하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
			} else if ($("#templi_cont").val().indexOf("(광고)") < 0) {
				$(".content").html("친구톡 내용 첫부분에 (광고) 문구를 삽입하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
			} else if ($("#templi_cont").val().indexOf("수신거부") < 0) {
				$(".content").html("친구톡 내용에 수신거부 문구를 삽입하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
			} else if ($("input:checkbox[id='lms_select']").is(":checked") && getByteLength($("#lms")) > 1980) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>내용은 <?=($mem->mem_2nd_send=='sms') ? '80자(한글 40자)' : '2,000자(한글 1,000자)'?> 이내로<br/>입력하여야 합니다.<br/>(실제내용 1,980자 이내)");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
<?if($mem->mem_2nd_send=='phn' || $mem->mem_2nd_send=='015') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입합니다 */ ?>
			} else if ($('#lms_select').prop('checked') && $("#lms").val().indexOf("(광고)") < 0) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용 첫부분에 (광고) 문구를 삽입하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
			} else if ($('#lms_select').prop('checked') && $("#lms").val().indexOf("무료수신거부") < 0) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용에 무료수신거부 문구와 연락처를 넣어야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
<?}?>
			} else {
			<?/*----------------*
			   | 전체 발송 처리 |
			   *----------------*/?>
				var check = true;
				$("#friendtalk_table tr").each(function () {
					var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
					if (btn_type != undefined) {
						var focus;
						if (btn_type == "N" && $(this).find("#no").is(":hidden") == false) {
							check = false;
							$(".content").html("버튼타입을 선택해주세요.");
							$("#myModal").modal('show');
						} else if (btn_type == "DS" && $(this).find(document.getElementsByName("btn_name1")).val().trim() == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name1"));
							$(".content").html("배송조회 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if (btn_type == "WL") {
							if($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_name2"));
								$(".content").html("웹링크 버튼의 버튼명을 입력해주세요.");
								$("#myModalf").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).find(document.getElementsByName("btn_url21")).val().trim() == "" || $(this).find(document.getElementsByName("btn_url21")).val().trim() == "http://") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_url21"));
								$(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							}
						} else if (btn_type == "AL") {
							if($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_name3"));
								$(".content").html("앱링크 버튼의 버튼명을 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).find(document.getElementsByName("btn_url31")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_url31"));
								$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).next('tr').find(document.getElementsByName("btn_url32")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).parent().find(document.getElementsByName("btn_url32"));
								$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							}
						} else if (btn_type == "BK" && $(this).find(document.getElementsByName("btn_name4")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name4"));
							$(".content").html("봇키워드 링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if (btn_type == "MD" && $(this).find(document.getElementsByName("btn_name5")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name5"));
							$(".content").html("메시지전달 링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						}
					}
				});
				if ($("#customer_all_send").val() == undefined) {
					var table = document.getElementById("tel");
					var row_index = table.rows.length - 1;
					for (var i = 0; i < row_index; i++) {
						if (document.getElementsByClassName('tel_number')[i].value == "") {
							check = false;
							$(".content").html("수신 번호를 입력해주세요.");
							$('#myModal').modal({backdrop: 'static'});
							$('#myModal').on('hidden.bs.modal', function () {
								document.getElementsByClassName('tel_number')[i].focus();
							});
							$('#myModal').unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".enter").click();
								}
							});
							return false;
						}
					}
				}
				if($("#reserve_check").is(":checked") == true) {
					if($("#reserve").val().trim() == ""){
						check = false;
						$(".content").html("예약 발송 일시를 선택해주세요.");
						$('#myModal').modal({backdrop: 'static'});
					} else {
						var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
						var reserveDt = reserve+"00";
						var now = moment().add(10,'minutes');
						var min = now.format("YYYYMMDDHHmm");
						var minDt = min+"00";
						if(reserveDt < minDt){
							check = false;
							$(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
							$('#myModal').modal({backdrop: 'static'});
						} else {
							check = true;
						}
					}
				}
				if (check == false) return false;
				var _2ndflag = "N";
				if ($("input:checkbox[id='lms_select']").is(":checked") == true)
					_2ndflag = "Y";
					
				$.ajax({
					url: "/biz/sender/coin",
					type: "POST",
					data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
			 		       "2ndflang":_2ndflag
						},
					success: function (json) {
						coin = json['coin'];
						var limit = parseInt(json['limit']);
						var sent = parseInt(json['sent']);
						var limit_msg = "";
						if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
						if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

						var kakao = Math.floor(Number(coin) / Number(ft_price));
						var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');
						var kakao_img = Math.floor(Number(coin) / Number(ft_img_price));
						var k_i = kakao_img.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');
						var k_i = kakao_img.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');

						check_resend_method();
						// 재발신 가능 건수를 산정합니다.
						var resends = (charge_type > 0) ? Math.floor(Number(coin) / Number(charge_type)) : 0;
						var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

						var table = document.getElementById("tel");
						var row_index = 0;
						if($("#customer_all_send").val() == undefined) {
							row_index = table.rows.length - 1;
						} else {
							row_index = $("#customer_all_send").val();
						}

						if (Number(coin) - (Number(charge_type) * Number(row_index)) < 0) {
							$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(row_index)));
							$('#myModal').modal({backdrop: 'static'});
							$(document).unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".enter").click();
								}
							});
						} else {
							var content_msg = ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "") ? "친구톡 이미지 발송 가능 건수 : " + k_i + "건" : "친구톡 발송 가능 건수 : " + k + "건";
							if(ms!="" && ($('#lms').val().replace(/ /gi, "") != "" || ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != ""))) {
								content_msg += "<br/>" + resend_type_name + " 발송 가능 건수 : " + resend_cnt + "건";
							}
							$(".content").html(limit_msg + content_msg + "<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
							$("#myModalAll").modal({backdrop: 'static'});
							$("#myModalAll").unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".all").click();
								}
							});
							$(".all").click(function () {
								$("#myModalAll").modal('hide');
							});
						}
					}
				});
			}
		} else if($("#upload_send").val()!=undefined){
			<?/*-----------------------*
			   | 업로드 전체 발송 처리 |
			   *-----------------------*/?>
			var check = true;
			if($("#reserve_check").is(":checked") == true) {
				if($("#reserve").val().trim() == ""){
					check = false;
					$(".content").html("예약 발송 일시를 선택해주세요.");
					$('#myModal').modal({backdrop: 'static'});
				} else {
					var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
					var reserveDt = reserve+"00";
					var now = moment().add(10,'minutes');
					var min = now.format("YYYYMMDDHHmm");
					var minDt = min+"00";
					if(reserveDt < minDt){
						check = false;
						$(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
						$('#myModal').modal({backdrop: 'static'});
					} else {
						check = true;
					}
				}
			}
			if (check == false) return false;
			var _2ndflag = "N";
			if ($("input:checkbox[id='lms_select']").is(":checked") == true)
				_2ndflag = "Y";
				
			$.ajax({
				url: "/biz/sender/coin",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
		 		       "2ndflang":_2ndflag
					},			
				success: function (json) {
					coin = json['coin'];
					var limit = parseInt(json['limit']);
					var sent = parseInt(json['sent']);
					var limit_msg = "";
					if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
					if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

					var kakao = Math.floor(Number(coin) / Number(ft_price));
					var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
					var kakao_img = Math.floor(Number(coin) / Number(ft_img_price));
					var k_i = kakao_img.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');

					check_resend_method();
					// 재발신 가능 건수를 산정합니다.
					var resends = (charge_type > 0) ? Math.floor(Number(coin) / Number(charge_type)) : 0;
					var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
					<?/* 업로드 된 건수 */?>
					var upload_send = $("#upload_send").val();
					<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
					var content_msg = ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "") ? "친구톡 이미지 발송 가능 건수 : " + k_i + "건" : "친구톡 발송 가능 건수 : " + k + "건";
					if(ms!="" && ($('#lms').val().replace(/ /gi, "") != "" || ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != ""))) {
						content_msg += "<br/>" + resend_type_name + " 발송 가능 건수 : " + resend_cnt + "건";
					}
					$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까? (발송할 건수 : " + upload_send + "건)");

					$("#myModalAll").modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".all").click();
						}
					});
					$(".all").click(function () {
						$("#myModalAll").modal("hide");
					});
				}
			});
		}
	}

	/**
	* 한글포함 문자열 길이를 구한다
	*/
	function getTextLength(str) {
		var len = 0;
		for (var i = 0; i < str.length; i++) {
			if (escape(str.charAt(i)).length == 6) {
				len++;
			}
			len++;
		}
		return len;
	}
	/* 문자열을 지정 길이만큼 자른다 */
	function cutTextLength(msg, len) {
		var text = msg;
		var leng = text.length;
		while(getTextLength(text) > len){
			leng--;
			text = text.substring(0, leng);
		}
		return text;
	}

/*-------------------------------------------------------------------------------*/
	<?/*--------------------------------------------------------------------------------*
		| 전체발송(전체고객정보불러오기 포함) 버튼 클릭시 실제 발송 처리하는 부분입니다. |
		*--------------------------------------------------------------------------------*/?>
	function all_move() {
		var senderBox = $("#sms_sender").val();
		var smsOnly='';
		var templi_cont = $("#templi_cont").val();
		var profile = document.getElementById("pf_key").value;
		var kind = ms;
		var msg = $('#lms').val();
		var tit = $("#tit").val();

		var img_url = '';
		var img_link = '';
		if ($("#img_url").val() != undefined && $("#img_url") != "" && $("#img_link") != "") {
			img_url = $("#img_url").val();
			img_link = $("#img_link").val();
		}
		if ('0' != 0 && $("#tel_temp").val() == undefined){
			smsOnly = 'Y';
		}

		var btn = [];
		for (var b = 1; b <= 5; b++) {
			btn[b] = new Array();
		}
		var obj = [];
		var btn_num = 1;
		$("#friendtalk_table tr").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined && btn_type != "N") {
				obj[btn_num] = new Object();
				obj[btn_num].type = btn_type;
				if (btn_type == "WL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url21")).val().trim();
					obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
				} else if (btn_type == "AL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url31")).val().trim();
					obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
				} else if (btn_type == "BK") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
				} else if (btn_type == "MD") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
				}
				btn[btn_num].push(obj[btn_num]);
				btn_num++;
			}
		});
		var btn1 = JSON.stringify(btn[1]);
		var btn2 = JSON.stringify(btn[2]);
		var btn3 = JSON.stringify(btn[3]);
		var btn4 = JSON.stringify(btn[4]);
		var btn5 = JSON.stringify(btn[5]);

		var reserveDt = "00000000000000";
		if($("#reserve_check").is(":checked") == true) {
			if($("#reserve").val().trim() != ""){
				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
				reserveDt = reserve+"00";
			}
		}

		if ($("#customer_all_send").val() != undefined) { // 전체 고객 발송
			var customer_all_count = $("#customer_all_send").val();
			var customer_filter = $("#customer_filter").val();
			$.ajax({
				url: "/biz/sender/friend/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont, "msg": msg, "kind": kind, "tit": tit,
					"tit": tit, "pf_key": profile, "img_url": img_url, "img_link": img_link,
					"btn1": btn1, "btn2": btn2, "btn3": btn3, "btn4": btn4, "btn5": btn5,
					"senderBox": senderBox, "smsOnly": smsOnly,
					"customer_all_count": customer_all_count,"customer_filter": customer_filter, "reserveDt": reserveDt
				},
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					$('#navigateURL').val("");
					var code = json['code'];
					var message = json['message'];
					if (code == "success") {
						//$('#navigateURL').val(document.location.href);
						$('#navigateURL').val("/biz/sender/history");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			});
		} else if ($("#upload_send").val() != undefined) { // 업로드 발송
			var upload_count = $("#upload_send").val();
			var file_data = new FormData();
			//alert(templi_cont);
			file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			file_data.append("pf_key", profile);
			file_data.append("senderBox", senderBox);
			file_data.append("upload_count", upload_count);
			file_data.append("templi_cont", templi_cont);
			file_data.append("msg", msg);
			file_data.append("kind", kind);
			file_data.append("tit", tit);
			file_data.append("img_url", img_url);
			file_data.append("img_link", img_link);
			file_data.append("btn1", btn1);
			file_data.append("btn2", btn2);
			file_data.append("btn3", btn3);
			file_data.append("btn4", btn4);
			file_data.append("btn5", btn5);
			file_data.append("smsOnly", smsOnly);
			file_data.append("reserveDt", reserveDt);
			file_data.append("file", $("input[id=filecount]")[0].files[0]);

			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/friend/all",
				type: "POST",
				data:file_data,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					$('#navigateURL').val("");
					var code = json['code'];
					var message = json['message'];
					if (code == "success") {
						//$('#navigateURL').val(document.location.href);
						$('#navigateURL').val("/biz/sender/history");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			});
		} else {
			var tel_number = new Array();
			$("input:checkbox[name=sel_del]").each(function () {
				var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				tel_number.push(array);
			});
			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/friend/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont, "msg": msg, "kind": kind, "senderBox": senderBox, "tit": tit,
					"pf_key": profile, "img_url": img_url, "img_link": img_link,
					"btn1": btn1, "btn2": btn2, "btn3": btn3, "btn4": btn4, "btn5": btn5,
					"smsOnly": smsOnly, "tel_number[]": tel_number, "reserveDt": reserveDt
				},
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					$('#navigateURL').val("");
					var code = json['code'];
					var message = json['message'];
					if (code == "success") {
						//$('#navigateURL').val(document.location.href);
						$('#navigateURL').val("/biz/sender/history");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			});
		}
	}

	<?/*------------------------*
	  | 친구톡 내용 글자수 제한 |
	  *-------------------------*/?>
	function chkword_templi() {
		var msg_length = $("#templi_cont").val() && $("#templi_cont").val().length || 0;
		$("#type_num").html(msg_length);
		<?/* 이미지가 포함되면 400자 이내로 제한 */?>
		if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
			var limit_length = 400;
			var msg_length = $("#templi_cont").val().length;
			if (msg_length <= limit_length) {
				$("#type_num").html(msg_length);
			} else if (msg_length > limit_length) {
				$(".content").html("템플릿 내용을 400자 이내로 입력해주세요.");
				$("#myModal").modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
				var cont = $("#templi_cont").val();
				var cont_slice = cont.slice(0, 400);
				$("#templi_cont").val(cont_slice);
				$("#type_num").html(400);
				$("#text").val(cont_slice);
				return;
			} else {
				$("#type_num").html(msg_length);
			}
		} else if ($("#img_url").val() == undefined || $("#img_url").val() == "") {
			var limit_length = 1000;
			var msg_length = $("#templi_cont").val().length;
			if (msg_length <= limit_length) {
				$("#type_num").html(msg_length);
			} else if (msg_length > limit_length) {
				$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
				$("#myModal").modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
				$("#templi_cont").focus();
			});
			var cont = $("#templi_cont").val();
			var cont_slice = cont.slice(0, 1000);
			$("#templi_cont").val(cont_slice);
			$("#type_num").html(1000);
			$("#text").val(cont_slice);
				return;
			} else {
				$("#type_num").html(msg_length);
			}
		}
	}

	<?/*-------------------------*
	  | 2차발신 내용 글자수 제한 |
	  *--------------------------*/?>
	function chkword_lms() {
		chkbyte($("#lms"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>);
	}

	function chkbyte($obj, $num, maxByte) {
		var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_model->reject_phn?>';
		var phn_len = phn.length + 16;

		var strValue = $obj.val();
		var strLen = strValue.length;
		var totalByte = 0;
		var len = 0;
		var oneChar = "";
		var str2 = "";

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
			<?/* 입력한 문자 길이보다 넘치면 잘라내기 위해 저장 */?>
			if (totalByte <= maxByte) {
				 len = i + 1;
			}
		}
		$num.html(totalByte);

		<?/* 넘어가는 글자는 자른다. */?>
		if (totalByte > maxByte) {
			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 " + oriMaxByte + "자(한글 " + parseInt(oriMaxByte / 2) + "자) 이내로<br/>입력해주세요.<br/>(실제내용 " + (maxByte - phn_len - 20) + "자 이내)");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});

<?if($mem->mem_2nd_send=='phn' || $mem->mem_2nd_send=='015') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입한다 */ ?>
			if ($obj.val().lastIndexOf("무료수신거부") > -1) {
				strValue = $obj.val().substr(0, $obj.val().lastIndexOf("무료수신거부"));
			}
			str2 = strValue.substr(0, len - phn_len - 20) + "\r무료수신거부 : " + phn;
<?} else {?>
			str2 = $obj.val().substr(0, len);
<?}?>
			$obj.val(str2);
			$num.html(totalByte);
         chkbyte($obj, $num, oriMaxByte);
      }
   }

	<?/*--------------------*
	  | 글자수(Byte)를 반환 |
	  *---------------------*/?>
	function getByteLength($obj)
	{
		var strValue = $obj.val();
		var strLen = strValue.length;
		var totalByte = 0;
		var oneChar = "";

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
		}
		return totalByte;
	}
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
	//글자수 제한 - 링크 이름
	function chkword_btn() {
		var limit_length = 30;
		var link_length = $("#btn_name").val().length;
		if (link_length <= limit_length) {
			$("#link_num").html(link_length);
		} else if (link_length > limit_length) {
			$(".content").html("링크 버튼 이름을 30자 이내로 입력해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#btn_name").focus();
			});
			var cont = $("#btn_name").val();
			var cont_slice = cont.slice(0, 30);
			$("#btn_name").val(cont_slice);
			$("#link_num").html(30);
			return;
		} else {
			$("#link_num").html(link_length);
		}
	}

	//글자수 제한 - 링크 url
	function chkword_url() {
		var limit_length = 250;
		var link_length = $("#btn_url").val().length;
		if (link_length > limit_length) {
			var cont = $("#btn_url").val();
			var cont_slice = cont.slice(0, 250);
			$("#btn_url").val(cont_slice);
			$("#btn_url").focus();
		}
	}

	// input enter 키 방지
	function captureReturnKey(e) {
		if(e.keyCode==13 && e.srcElement.type != 'textarea')
			return false;
	}

	//프로필 선택시
	function profile() {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile");
		form.submit();
	}


	// 프로필선택 버튼
	function btnSelectProfile() {
		var count = '';
		console.log('count', count);
		if('' == "none") {
			$('#profile_danger').modal('show');
		} else {
			$("#profile_select").modal({backdrop: 'static'});
			$('#profile_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					console.log('code == 27');
					$("#profile_select").modal("hide");
				}
				else if ($("#search").is(":focus")) {
					if (code == 13) {
						search_enter(); // todo check
					}
				} else {
					if (code == 13) {
						select_profile();
					}
				}
			});
		}

		$('#profile_select .widget-content').html('').load(
			"/biz/sender/send/profile",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"page": 1
			},
			function() {
				//$('#profile_select').css({"overflow-y": "scroll"});
			}
		);
	}

	// copy from customer_list
	function open_page2(page) {
		var type = $('#searchType2').val() || 'all';
		var searchFor = $('#searchStr2').val() || '';

		$('#profile_select .widget-content').html('').load(
			"/biz/sender/send/profile",
			{
				"search_type": type,
				"search_for": searchFor,
				"page": page
			},
			function() {
				//$('#profile_select').css({"overflow-y": "scroll"});
			}
		);
	}

	//이미지 선택시-에이젝스
	function imageSelect() {
		cw = screen.availWidth;     //화면 넓이
		ch = screen.availHeight;    //화면 높이
		sw = 720;    //띄울 창의 넓이
		sh = 630;    //띄울 창의 높이
		ml = (cw - sw) / 2;        //가운데 띄우기위한 창의 x위치
		mt = (ch - sh) / 2;         //가운데 띄우기위한 창의 y위치

		imgSelectBox = window.open("/biz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	function setImageValue(selctImgValue, selctImgLink) {
		$("#img_url").val(selctImgValue);
		readImage(selctImgValue);

		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		$("input[name=img_link]").val(selctImgLink);
	}

	//미리보기 - 이미지
	function readImage(selctImgValue) {
		$("#image").remove();
		var image = "<img id='image' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";

		var cont_length = $("#templi_cont").val().length;
		var span = "<span id='type_num'>" + cont_length + "</span>";
		document.getElementById('templi_length').innerHTML = span + "/400자";
		$("#text").val($("#templi_cont").val());

		$('.uniform').uniform();
		$("#img_link").attr("readonly", false);
		$("#text").before(image);
		$("#friendtalk_table tr").each(function () {
			if($(this).find("#no").text()) {
				var current_btn_num = $(this).find("#no").parent().attr("name");
				link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
			}
		});
		$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
    }

	//업로드 양식 다운로드
	function download() {
		document.location.href="/uploads/number_list.xlsx";
	}

    //업로드
   function readURL(input) {
		var file = document.getElementById('filecount').value;
		file = file.slice(file.indexOf(".") + 1).toLowerCase();
		//if (file.equals("xls") || file.equals("xlsx")) {
		if (file == "xls" || file == "xlsx" || file == "txt") {
			var formData = new FormData();
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("ext", file);
			$.ajax({
				url: "/biz/sender/upload",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					var status = json['status'];
					//alert(status);
					if (status == 'error') {
						var msg = json['msg'];
						$(".content").html("올바르지 않은 파일입니다.<br>"+msg);
						$('#myModal').modal({backdrop: 'static'});
						$('#filecount').filestyle('clear');
					} else {
						var row_len = json['row_len'];

						var coin = json['coin'];
						var ft_count = json['ft_count'];
						var ft_img_count = json['ft_img_count'];
						var sms_count = 0; try { sms_count=json['sms_count']; }catch(e){}
						var lms_count = json['lms_count'];
						var mms_count = json['mms_count'];

<? /* upload 에서 반환하는 숫자의 lms는 sms(이미지가 있어도 80자 이하면 sms개수에 더해집니다.)와 mms가 포함된 갯수입니다. */
if($mem->mem_2nd_send=='mms') { /* 2차발신이 mms인 경우 이미지가 없는 발신은 lms 단가로 계산 : resend_price 적용 */ ?>
						var total_price = (Number(ft_count) * Number(ft_price)) + (Number(ft_img_count) * Number(ft_img_price)) + (Number(mms_count) * Number(resend_price)) + ((Number(lms_count) - Number(mms_count)) * Number(lms_price));
<?} else { /* 2차발신이 mms가 아닌 경우 이미지 여부와 관계없이 금액 계산 : resend_price 적용 */ ?>
						var total_price = (Number(ft_count) * Number(ft_price)) + (Number(ft_img_count) * Number(ft_img_price)) + ((Number(lms_count) - Number(sms_count)) * Number(resend_price)) + (Number(sms_count) * Number(sms_price));
<?}?>
						if (Number(coin) - Number(total_price) < 0) {
							$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+Number(total_price));
							$('#myModal').modal({backdrop: 'static'});
							$('#filecount').filestyle('clear');
						} else if (row_len > 60000) {
							$(".content").html("최대 6만건까지 가능합니다.");
							$('#myModal').modal({backdrop: 'static'});
							$('#filecount').filestyle('clear');
						} else {
							var bulk_send = json['row_len'];
							var tot_row = json['tot_row_len'];
							var ex_row = json['ex_row_len'];
							var coin = json['coin'];
							var limit_count;
							//alert(bulk_send);
							//if (ms != "" && $("#img_url").val() == "") { // 파일안에 이미지갯수.파악해서 mms가격도 고바기 해야됑..
							//	limit_count = Number(resend_price)*Number(bulk_send);
							//} else {
						//		limit_count = Number(ft_price)*Number(bulk_send);
						//	}
							//if(coin >= limit_count) {
								$("#number_add").hide();
								$("#number_del").hide();
								$("#send_select").hide();
								$("#tel").hide();
								$("#save_temporal_msgs").hide();

								//미리보기
							//	$("#text").val("파일이 업로드 되었습니다.");
							//	$("#text").css("height","1px").css("height",($("#text").prop("0px")));
							//	var height = $("#text").prop("scrollHeight");
							//	if( height < 408 ) {
							//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
							//	} else {
							//		$("#text").css("height", "1px").css("height", "460px");
							//	}
/*
								if ($("#img_url").val() != "") {
									$("#lms_ptag").html('<input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)">LMS 발신 여부()');
									var lms = "<tr id='smslms'><th>LMS</th><td colspan='5'><textarea name='msg' id='lms' name='lms' cols='30' rows='5' class='form-control autosize' " +
									"placeholder='LMS 발신 체크를 선택해주세요.' style='resize:none;cursor:default;background-color:#EEEEEE;' " +
									"onkeyup='onPreviewText();resize_cont(this);chkword_lms();' disabled='disabled'></textarea>" +
									"<p class='help-block align-left' style='width:80% !important'><input type='checkbox' id='lms_kakaolink_select' " +
									"class='uniform' onclick='insert_kakao_link(this);' style='cursor: default;' disabled> 카카오 친구추가 링크 넣기</p>" +
									"<p class='help-block align-right'><span id='lms_num'>0</span>/<?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>자</p></td><input type='hidden' name='tit' id='tit'></tr>";
									$("#templi").after(lms);
									$(".uniform").uniform();
									$("#mms").remove();
									$("#image").remove();
									$("#img_url").val("");
									$("#img_link").val("");
									$("#img_link").attr("readonly", true).css("cursor", "default");
								}
*/
							//	$("#img_select").attr('disabled', 'disabled').css("cursor","default");
							//	$("#type_num").html(0);
							//	$("#templi_cont").val("").attr("readonly",true).css("cursor","default").css("background-color","#EEEEEE");
							//	$("#lms").val("").attr("disabled",true).css("cursor","default").css("background-color","#EEEEEE");
							//	$("#lms_num").html(0);

							//	$("#lms_select").attr('disabled','disabled').css("cursor","default");
							//	$("#lms_kakaolink_select").attr('disabled','disabled').css("cursor","default");
							//	$("#lms_kakaolink_select").uniform();

							//	$("#templi_cont").val("").attr("readonly", true).css("cursor", "default").css("background-color", "#EEEEEE");

							//	if($("input:checkbox[id='lms_select']").is(":checked") == true){
							//		$("input:checkbox[id='lms_select']").removeAttr('checked');
							//	}
							//	if($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
							//		$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
							//	}

								//버튼 비활성화
								/*
								$("#friendtalk_table tr").each(function () {
									if($(this).find("#no").text()) {
										if ($(this).find("#no").text() != 1) {
											var name = $(this).find("#no").parent().attr("name");
											$("#btn_preview_div"+name).remove();
											$(this).next().remove();
											$(this).remove();
										} else {
											var current_btn_num = $(this).find("#no").parent().attr("name");
											$("#no").attr("hidden", true);
											$("#btn_type_td").attr("hidden", true);
											$("#n_1_"+current_btn_num).attr("hidden",true);
											$("#n_2_"+current_btn_num).attr("hidden",true);
											$("#wl_1_"+current_btn_num).attr("hidden",true);
											$("#wl_2_"+current_btn_num).attr("hidden",true);
											$("#wl_3_"+current_btn_num).attr("hidden",true);
											$("#al_1_"+current_btn_num).attr("hidden",true);
											$("#al_2_"+current_btn_num).attr("hidden",true);
											$("#al_3_"+current_btn_num).attr("hidden",true);
											$("#bk_1_"+current_btn_num).attr("hidden",true);
											$("#bk_2_"+current_btn_num).attr("hidden",true);
											$("#md_1_"+current_btn_num).attr("hidden",true);
											$("#md_2_"+current_btn_num).attr("hidden",true);
											$("#btn_del").attr("hidden", true);
											$("#btn_add_msg").attr("hidden", false);
											$("#btn_type"+current_btn_num).val("N");
											$("#btn_type"+current_btn_num).select2();
											//$("#btn_type"+current_btn_num+" option:eq(0)").prop("selected", true);
											//$("#btn_type"+current_btn_num).select2("val", "N");
											$("#friendtalk_table").find(document.getElementsByName("btn_name1")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name2")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name3")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name4")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name5")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url21")).val("http://");
											$("#friendtalk_table").find(document.getElementsByName("btn_url22")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url31")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url32")).val("");
										}
									}
								});
								$("#text").css("margin-bottom","0px");

								$("#btn_add").attr("hidden", true);

								document.getElementById("templi_cont").style.height = "103px";
								document.getElementById("lms").style.height = "103px";

								*/
								$(".tel_content").after('<div class="widget-content" id="upload_result"><p>업로드 결과 : ' + bulk_send + ' 명의 수신자가 지정되었습니다.(총: ' + tot_row + ' 제외: ' + ex_row + ')</p><input type="hidden" id="upload_send" value="' + bulk_send + '"></div>');
								$(".uniform").uniform();
								
							//} else {
							//	$(".content").html("충전 잔액이 부족합니다.");
							//	$('#myModal').modal({backdrop: 'static'});
							//	$('#filecount').filestyle('clear');
							//}
						}
					}
				}
			});
		} else {
			$(".content").html("xls,xlsx 파일만 가능합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$('#filecount').filestyle('clear');
				var table = document.getElementById("tel");
				var row_index = table.rows.length;

				for (var i = 1; i < row_index; i++) {
					var tr = $("." + i);
					tr.remove();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "0px");
				}

				var add = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
					'<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>'+
					'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
					'<td width="40%"><input type="text" ' +
					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
					'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
					'<i class="icon-trash"></i> 삭제</a></td></tr>';
				$("#tel_tbody").html(add);
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "47px");
				$("#1").uniform();
			});
			$(document).unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					$(".enter").click();
				}
			});
		}
	}

	<?/*----------------------------------------*
		| 업로드 버튼을 클릭하였을때 메시지 표시 |
		*----------------------------------------*/?>
	function upload() {
		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$('#filecount').attr('disabled', 'disabled');
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27 || code == 13) {
					$(".enter").click();
				}
			});
			$(".enter").click(function () {
				$("#filecount").removeAttr("disabled");
			});
		} else if ('0' != 0) {
			$('#filecount').attr('disabled', 'disabled');
		}else {
			$('#filecount').attr('disabled', 'disabled');
			$(".content").html("입력하신 수신 번호가 초기화됩니다.<br/>업로드 하시면 업로드 파일의 내용으로 발송됩니다.<br/>업로드 하시겠습니까?");
			$('#myModalUpload').modal({backdrop: 'static'});
			$('#myModalUpload').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default").click();
				} else if (code == 13) {
					check();
				}
			});
			$(".up").click(function () {
				check();
			});
		}
	}


	<?/*------------------------------------------------------------------*
		| 업로드메시지 확인 클릭시 처리 : 이미지가 등록된 경우 이미지 제거 |
		*------------------------------------------------------------------*/?>
	function check(obj) {
		if ($('#filecount').is('[disabled=disabled]') == true || obj) {
			$("#number_add").show();
			$("#number_del").show();
			$("#send_select").show();
			$("#tel").show();
			$("#save_temporal_msgs").show();
			$("#upload_result").remove();

			if (!obj) {
				$("#filecount").removeAttr("disabled");
			}
			var content = "(광고) "+$("#pf_ynm").val()+"\r\r\r\r수신거부 : 홈>친구차단";

			$("#templi_cont").val(content);
			$("#templi_cont").attr("readonly", false).css("background-color", "white");
			$("#lms_select").removeAttr("disabled");
			$("#img_select").attr('disabled', false).css("cursor","pointer");
			if ($("#img_url").val() != "") {
/*
				$("#lms_ptag").html('<input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)">LMS 발신 여부()');
				var lms = "<tr id='smslms'><th>LMS</th><td colspan='5'><textarea name='msg' id='lms' name='lms' cols='30' rows='5' class='form-control autosize' " +
					"placeholder='LMS 발신 체크를 선택해주세요.' style='resize:none;cursor:default;background-color:#EEEEEE;' " +
					"onkeyup='onPreviewText();resize_cont(this);chkword_lms();' disabled='disabled'></textarea>" +
					"<p class='help-block align-left' style='width:80% !important'><input type='checkbox' id='lms_kakaolink_select' " +
					"class='uniform' onclick='insert_kakao_link(this);' style='cursor: default;' disabled> 카카오 친구추가 링크 넣기</p>" +
					"<p class='help-block align-right'><span id='lms_num'>0</span>/<?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>자</p></td><input type='hidden' name='tit' id='tit'></tr>";
				$("#templi").after(lms);
				$(".uniform").uniform();
				$("#mms").remove();
*/
				$("#image").remove();
				$("#img_url").val("");
				$("#img_link").val("");
				$("#img_link").attr("readonly", true).css("cursor", "default");
			}
			$("#type_num").html(0);
			$("#lms").val("").attr("disabled",true).css("cursor","default").css("background-color","#EEEEEE");
			$("#lms_num").html(0);

			$("#lms_kakaolink_select").attr('disabled','disabled').css("cursor","default");
			$("#lms_kakaolink_select").uniform();

			if($("input:checkbox[id='lms_select']").is(":checked") == true){
				$("input:checkbox[id='lms_select']").removeAttr('checked');
			}
			if($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true){
				$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
			}

			$("#btn_add").attr("hidden", false);
			$("#text").css("margin-bottom","0px");

			$("#btn_load_customer").removeAttr("disabled");
			$("#number_add").removeAttr("disabled");

			$("#friendtalk_table tr").each(function () {
				if($(this).find("#no").text()) {
					if ($(this).find("#no").text() != 1) {
							var name = $(this).find("#no").parent().attr("name");
							$("#btn_preview_div"+name).remove();
							$(this).next().remove();
							$(this).remove();
					} else if ($(this).find("#no").text() == 1) {
						var current_btn_num = $(this).find("#no").parent().attr("name");
						$("#btn_preview_div"+current_btn_num).remove();
						$("#no").attr("hidden", false);
						$("#btn_type_td").attr("hidden", false);
						$("#n_1_"+current_btn_num).attr("hidden",true);
						$("#n_2_"+current_btn_num).attr("hidden",true);
						$("#wl_1_"+current_btn_num).attr("hidden",false);
						$("#wl_2_"+current_btn_num).attr("hidden",false);
						$("#wl_3_"+current_btn_num).attr("hidden",false);
						$("#al_1_"+current_btn_num).attr("hidden",true);
						$("#al_2_"+current_btn_num).attr("hidden",true);
						$("#al_3_"+current_btn_num).attr("hidden",true);
						$("#bk_1_"+current_btn_num).attr("hidden",true);
						$("#bk_2_"+current_btn_num).attr("hidden",true);
						$("#md_1_"+current_btn_num).attr("hidden",true);
						$("#md_2_"+current_btn_num).attr("hidden",true);
						$("#btn_del").attr("hidden", false);
						$("#btn_add_msg").attr("hidden", true);
						$("#btn_type"+current_btn_num).val("WL");
						$("#btn_type"+current_btn_num).select2();
						//$("#btn_type"+current_btn_num+" option:eq(1)").prop("selected", true);
						//$("#btn_type"+current_btn_num).select2().select2("val", "WL");

						var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
						var pf_ynm = $("#pf_ynm").val();
						$("#btn_name2_"+current_btn_num).val("세일전단지");//pf_ynm);
						$("input[name=btn_url21]").val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
						link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
					}
				}
			});
			$('#text').val(content);
			$("#text").css("height", "1px").css("height", ($("#text").prop("0px")));
			var height = $("#text").prop("scrollHeight");
			if (height < 408) {
				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
			} else {
				$("#text").css("height", "1px").css("height", "460px");
			}

			if ($('#filecount').val() != "") {
				$("#number_add").click(function () {
					number_add();
				});
			}
			$('#filecount').filestyle('clear');
			var table = document.getElementById("tel");
			var row_index = table.rows.length;
			for (var i = 1; i < row_index; i++) {
				var tr = $("." + i);
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "0px");
				var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
					'<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>'+
					'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
					'<td width="40%"><input type="text" ' +
					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
					'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
					'<i class="icon-trash"></i> 삭제</a></td></tr>';
				$("#tel_tbody").html(tr);
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "47px");
				$("#1").uniform();
			}
			$("#myModalUpload").modal('hide');

			if (!obj) {
				$("#filecount").removeAttr("disabled");
				$("#filecount").attr('onclick', '');
				$("#filecount").click();
				$(".up").unbind("click");
				return $("#filecount").attr('onchange', 'readURL()');
			}

		}
	}

	<?/*---------------------------------*
		| 고객정보 체크 선택하여 불러오기 |
		*---------------------------------*/?>
	function load_customer_select() {
		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
		$(".content").html("프로필을 먼저 선택해주세요.");
		$('#myModal').modal({backdrop: 'static'});
		} else {
			if ($("#upload_result").val() != undefined) {
				$("#upload_result").remove();
				check('customer_select');
			}

			$("#myModalLoadCustomers").modal({backdrop: 'static'});
			$("#myModalLoadCustomers").on('shown.bs.modal', function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			});
			$('#myModalLoadCustomers').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default.dismiss").click();
				} else if (code == 13) {
					include_customer()
				}
			});
			$("#myModalLoadCustomers .include_phns").click(function () {
				include_customer();
			});
			open_page(1);
		}
	}

	<?/*------------------------*
		| 고객정보 전체 불러오기 |
		*------------------------*/?>
	function load_customer_all(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			if (document.getElementById('filecount').value != '') {
				check('customer_all');
			}
			$.ajax({
				url: "/biz/sender/friend/load_customer",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				type: "POST",
				success: function (json) {
					var customer_count = json['customer_count'];
					if (customer_count > 60000) {
						$(".content").html("최대 60,000명까지 가능합니다.");
						$('#myModal').modal({backdrop: 'static'});
					} else if (customer_count > 0) {
						$("#number_add").hide();
						$("#number_del").hide();
						$("#send_select").hide();
						$("#tel").hide();
						$("#upload_result").remove();
						$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
					} else {
						$(".content").html("고객정보가 없습니다.");
						$('#myModal').modal({backdrop: 'static'});
					}
				},
				error: function (data, status, er) {
					$(".content").html("처리중 오류가 발생하였습니다.");
					$("#myModal").modal('show');
				}
			});
			$("#myModalFilterAll").modal('hide');
		}
	}

	<?/*----------------------------------------*
		| 그룹으로 선택한 고객정보 전체 불러오기 |
		*----------------------------------------*/?>
	function include_customer(group ) {
		if(($("#sel_all").prop('checked') && $('#searchGroup').val()!='') || group == 'GS') {
			$(".content").html("선택하신 " + $('#searchGroup').val() + " 고객 전체를 추가 하시겠습니까?");
			$('#myModalFilterAll').modal({backdrop: 'static'});
			$('#myModalFilterAll').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default").click();
				} else if (code == 13) {
					load_customer_all($('#searchGroup').val());
					return;
				}
			});
			$(".filter_all").click(function () {
				load_customer_all($('#searchGroup').val());
				return;
			});
		}
		$("input[name='selCustomer']:checked").each(function () {
			var checked = $(this).parents('tr:eq(0)');
			var kind = $(checked).find('td:eq(2)').find('#kind').val();
			var name = $(checked).find('td:eq(3)').text();
			var phn = $(checked).find('td:eq(4)').text();
			add_number(phn, kind, name);
		});
		$("#myModalLoadCustomers").modal('hide');
		$("#myModalLoadCustomers .include_phns").unbind("click");
	}


	//검색 조회
	// copy from customer_list
	function search_question(page) {
		open_page(page);
	}

	// copy from customer_list
	function open_page(page) {
		var type = $('#searchType').val() || 'all';
		var searchFor = $('#searchStr').val() || '';
		var searchGroup = $('#searchGroup').val() || '';
		var searchName = $('#searchName').val() || '';

		console.log('open_page', page);

		$('#myModalLoadCustomers .widget-content').html('').load(
			"/biz/customer/inc_lists",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_type": type,
				"search_for": searchFor,
				"search_group": searchGroup,
				"search_name": searchName,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
	}

	// profileKey 설정시
	function checkTemporalMessages() {
		var pfKey = $("#pf_key").val();
		if (pfKey && pfKey != '') {
			$(".content").html("임시저장 메세지가 있습니다.<br/>사용 하시겠습니까?");
			$("#myModalCheck").modal({backdrop: 'static'});
			$("#myModalCheck").unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					setTemporalMessages();
				}
			});
			$(".submit").unbind("click").click(function () {
				setTemporalMessages();
				$(".submit").unbind("click");
			});
		}
	}

	function setTemporalMessages() {
		$("#myModalCheck").modal("hide");

		var pfKey = $("#pf_key").val();
		console.log('setTemporalMessages', pfKey);
		$.ajax({
			url: "/biz/sender/get_temp_msg",
			type: "POST",
			data: {'key': pfKey},
			success: function(resp){
				var json = JSON.parse(resp);
				var fields = json[0].fields;
				$('#lms').val(fields.lms);
				$('#templi_cont').val(fields.templi);
				if(fields.lms && fields.lms != "") {
					$("#text").val(fields.lms);
				} else {
					$("#text").val(fields.templi);
				}
				if(fields.buttons && fields.buttons != "") {
					$("#btn_content_1").remove();
					$("#btn_content_1_1").remove();
					$("#btn_preview_div1").remove();
					var btn_content = JSON.parse(fields.buttons);

					for(var i=0;i<=btn_content.length;i++) {
						var html = "";
						var btn_type = btn_content[i]["linkType"];
						var btn_name = "";
						if (btn_content[i]["name"] != undefined) {
							 btn_name = btn_content[i]["name"];
						}
						var linkMo = btn_content[i]["linkMo"];
						var linkPc = btn_content[i]["linkPc"];
						var linkAnd = btn_content[i]["linkAnd"];
						var linkIos = btn_content[i]["linkIos"];

						var counter = i + 1;

						//공통
						html += "<tr id='btn_content_" + counter + "_1'>";
						html += "<td id='no' align='center' rowspan='2'>" + counter + "</td>";
						html += "<td id='btn_type_td' name='btn_type_td' align='center' rowspan='2'>";
						html += "<select class='select2 input-width-small' id='btn_type"+counter+"' name='btn_type' onchange='modify_btn_type("+counter+");link_name(this,"+counter+");'>";
						html += "<option value='N'>선택</option>";
						html += "<option value='WL'>웹링크</option>";
						html += "<option value='AL'>앱링크</option>";
						html += "<option value='BK'>봇키워드</option>";
						html += "<option value='MD'>메시지전달</option>";
						html += "</select>";
						html += "</td>";
						html += "<td id='btn_add_msg' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
						//N
						html += "<td id='n_1_" + counter+ "' align='center' rowspan='2' hidden></td>";
						html += "<td id='n_2_" + counter+ "' align='center' width='67px' hidden></td>";
						//봇키워드
						html += "<td id='bk_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name4' id='btn_name4_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
						html += "<td id='bk_2_" + counter+ "' align='center' hidden></td>";
						//메시지전달
						html += "<td id='md_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name5' id='btn_name5_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
						html += "<td id='md_2_" + counter+ "' align='center' hidden></td>";
						//웹링크
						html += "<td id='wl_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name2' id='btn_name2_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
						html += "<td id='wl_2_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Mobile</label><input style='margin-left: 10px;' name='btn_url21' id='wl_2_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
						//앱링크
						html += "<td id='al_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name3' id='btn_name3_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
						html += "<td id='al_2_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Android</label><input style='margin-left: 10px;' name='btn_url31' id='al_2_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
						html += "<td id='btn_del' align='center' rowspan='2'><a onclick='btn_del(" + counter + ");' style='cursor:pointer'>제거</a></td>";
						html += "</tr>";
						//웹&앱링크&공통
						html += "<tr id='btn_content_" + counter + "' name='" + counter + "'>";
						html += "<td id='wl_3_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>PC(선택)</label><input style='margin-left: 10px;' name='btn_url22' id='wl_3_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
						html += "<td id='al_3_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>iOS</label><input style='margin-left: 10px;' name='btn_url32' id='al_3_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
						html += "</tr>";

						$("#btn_content_" + i).after(html);

						$("#btn_type"+counter).val(btn_type);
						if (btn_content[i]["linkType"] == "WL") {
							$("#wl_1_"+counter).attr("hidden",false);
							$("#wl_2_"+counter).attr("hidden",false);
							$("#wl_3_"+counter).attr("hidden",false);
							$("#btn_name2_"+counter).val(btn_name);
							$("#wl_2_btn_url_"+counter).val(linkMo);
							$("#wl_3_btn_url_"+counter).val(linkPc);
						} else if (btn_content[i]["linkType"] == "AL") {
							$("#al_1_"+counter).attr("hidden",false);
							$("#al_2_"+counter).attr("hidden",false);
							$("#al_3_"+counter).attr("hidden",false);
							$("#btn_name3_"+counter).val(btn_name);
							$("#al_2_btn_url_"+counter).val(linkAnd);
							$("#al_3_btn_url_"+counter).val(linkIos);
							$("#wl_2_btn_url_"+counter).val("http://");
						} else if (btn_content[i]["linkType"] == "BK") {
							$("#bk_1_"+counter).attr("hidden",false);
							$("#bk_2_"+counter).attr("hidden",false);
							$("#btn_name4_"+counter).val(btn_name);
							$("#wl_2_btn_url_"+counter).val("http://");
						} else if (btn_content[i]["linkType"] == "MD") {
							$("#md_1_"+counter).attr("hidden",false);
							$("#md_2_"+counter).attr("hidden",false);
							$("#btn_name5_"+counter).val(btn_name);
							$("#wl_2_btn_url_"+counter).val("http://");
						}
						$('select.select2').select2();
						$("#text").css("margin-bottom","10px");
						if(btn_name.replace(/ /gi, "") == ""){
							btn_name = '버튼명을 입력해주세요.';
						}
						var html = '<div id="btn_preview_div' + counter + '" class="' + counter + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
							'<p data-always-visible="1" id="btn_preview' + counter + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
							'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
							'>' + btn_name + '</p></div>';
						if (counter == 1) {
							$("#text").after(html);
						} else {
							var no = counter-1;
							$("#btn_preview_div" + no).after(html);
						}
					}
				}
				if(fields.lms && fields.lms != "") {
					$("#lms_select").prop("checked",true);
					$('.uniform').uniform();
					$("#lms_kakaolink_select").attr("disabled",false);
					$("#lms_kakaolink_select").uniform();
					$("#lms").attr("disabled",false);
					$("#lms").val(fields.lms);
					chkword_lms();
				}
				if(fields.img_link && fields.img_link != "") {
					$('#img_link').val(fields.img_link);
				}
				select_lms(document.getElementById('lms_select'));
				$('.uniform').uniform();
			},
			error: function (data, status, er) {
				$("#myModalCheck").modal("hide");
				$(".content").html("처리중 오류가 발생하였습니다.");
				$("#myModal").modal('show');
			}
		});
	}


	function include_msg() {
		$("input[name='selMsg']:checked").each(function () {
			var checked = $(this).val();
			if(checked > 0) {
				set_lms_by_user_msg(checked, "U");
				return false;
			}
		});
		
		$("input[name='preselMsg']:checked").each(function () {
			var checked = $(this).val();
			if(checked > 0) {
				set_lms_by_user_msg2(checked, "P");
				return false;
			}
		});		
	}

	function delete_msg() {
		var msgIds = [];
		$("input[name='selMsg']:checked").each(function () {
			var checked = $(this).val();
			msgIds.push(checked);
		});
		var result = confirm('선택한 메세지를 삭제 하시겠습니까?'); 
		if(result) { //yes 
			open_page_user_lms_msg(1, msgIds);
		} else { 
			//no 
		}

		
		
	}

	function set_lms_by_user_msg(msg_id, msg_type) {

		$("#myModalUserMSGList").modal('hide');
		$("#myModalUserMSGList .include_phns").unbind("click");
		
		$.ajax({
			url: "/biz/sender/lms/msg_load",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"msg_id": msg_id,
				"msg_type" : msg_type
				},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var msg_id = json['msg_id'];
				var msg = json['msg'];
				//var lmsmsg = json['mst_lms_content'];
				var lmsmsg = '';
				if (msg_id>0) {
					$("#templi_cont").val(msg).trigger('keyup');
					chkword_templi();
					onPreviewText();
					if(lmsmsg.length > 10) {
    					$("#lms_select").prop("checked", true);
    					$.uniform.update();
    					select_lms($("#lms_select"));
    					$("#lms").val(lmsmsg).trigger('keyup');
					} else {
						$("#lms_select").prop("checked", false);
    					$.uniform.update();
    					select_lms($("#lms_select"));
					}
					$(".content").html("메시지 불러오기가 완료 되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("메시지 불러오기가 실패되었습니다.<br/>" + message );
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			},
			error: function (request,status,error){
				$(".content").html("메시지 불러오기가 실패되었습니다.(ER)"+status+","+error);
				$('#myModal').modal({backdrop: 'static'});
				$(document).unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
			}
		});

	}

	function set_lms_by_user_msg2(msg_id, msg_type) {

		$("#myModalUserMSGList").modal('hide');
		$("#myModalUserMSGList .include_phns").unbind("click");
		
		$.ajax({
			url: "/biz/sender/lms/friend_msg_load",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"msg_id": msg_id,
				"msg_type" : msg_type
				},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var msg_id = json['msg_id'];
				var msg = json['msg'];
				var mst_img_countent = json['mst_img_countent'];
				var img_filename = "http://www.bizalimtalk.kr/pop/" + json['img_filename'];
				var mst_button = JSON.parse(json['mst_button']);
				var lmsmsg = json['mst_lms_content'];
				//var lmsmsg = '';
				if (msg_id>0) {
					$("#templi_cont").val(msg).trigger('keyup');
// 					if (lms_msg) {

// 						//$("#lms_select").prop('checked', true);
// 						//select_lms($("#lms_select"));
// 						//$("#lms").val(lms_msg);

// 						//alert(lms_msg);
// 						//$("#lms").val(lms_msg).trigger('keyup');
// 					}
					if(mst_img_countent.length > 0) {
    					//$("#img_url").val(mst_img_countent);
    					//$("#img_link").val(img_filename);
						setImageValue(mst_img_countent, img_filename);
					}


					for (var item in mst_button) {
						//alert(mst_button[item]);
						var jsonA = JSON.parse(mst_button[item]);
						var jsonO = jsonA[0];

						if (jsonO && item > 0) {
							//alert(jsonO['name']);
							btn_add();
						}
					}

					var loopCount = 0;
					$("#friendtalk_table tr").each(function () { 
						if ($(this).find(document.getElementsByName("btn_type")).length > 0) {
     						var jsonA = JSON.parse(mst_button[loopCount]);
    						var jsonO = jsonA[0];
     						if (jsonO) {
     							$(this).find(document.getElementsByName("btn_type")).val(jsonO['type']).change();
     							
     							if (jsonO['type'] === "WL") {
         							$(this).find(document.getElementsByName("btn_name2")).val(jsonO['name']);
									$(this).find(document.getElementsByName("btn_url21")).val(jsonO['url_mobile']);
									$(this).find(document.getElementsByName("btn_url22")).val(jsonO['url_pc']);
     							} else if (jsonO['type'] === "AL") {
         							$(this).find(document.getElementsByName("btn_name3")).val(jsonO['name']);
									$(this).find(document.getElementsByName("btn_url31")).val(jsonO['scheme_android']);
									$(this).find(document.getElementsByName("btn_url32")).val(jsonO['scheme_ios']);
     							} else if (jsonO['type'] === "BK") {
         							$(this).find(document.getElementsByName("btn_name4")).val(jsonO['name']);
     							} else if (jsonO['type'] === "MD") {
         							$(this).find(document.getElementsByName("btn_name5")).val(jsonO['name']);
     							}
     						}
    						loopCount++;
						}
					});
					
					chkword_templi();
					onPreviewText();
					if(lmsmsg.length > 10) {
    					$("#lms_select").prop("checked", true);
    					$.uniform.update();
    					select_lms($("#lms_select"));
    					$("#lms").val(lmsmsg).trigger('keyup');
					} else {
						$("#lms_select").prop("checked", false);
    					$.uniform.update();
    					select_lms($("#lms_select"));
					}
					$(".content").html("메시지 불러오기가 완료 되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("메시지 불러오기가 실패되었습니다.<br/>" + message );
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			},
			error: function (request,status,error){
				$(".content").html("메시지 불러오기가 실패되었습니다.(ER)"+status+","+error);
				$('#myModal').modal({backdrop: 'static'});
				$(document).unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
			}
		});

	}
/*
 * 문자 내역 저장용
 */

    
    function search_msg(page) {
    	open_page_user_lms_msg('1');	
    }

    function open_page_user_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';

		$('#myModalUserMSGList .content').html('').load(
			"/biz/sender/lms/msg_save_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				"search_kind": "FT",
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();				
			}
		);

    }

    function open_page_pre_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';

		$('#myModalUserMSGList .content').html('').load(
			"/biz/sender/lms/pre_msg_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				"search_kind": "FT",
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();				
			}
		);

    }
    
	function open_msg_lms_list() {

		$("#myModalUserMSGList").modal({backdrop: 'static'});
		$("#myModalUserMSGList").on('shown.bs.modal', function () {
			$('.uniform').uniform();
			$('select.select2').select2();
		});

		$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 27) {
				$(".btn-default.dismiss").click();
			} else if (code == 13) {
				include_customer()
			}
		});

		$("#myModalUserMSGList .include_phns").click(function () {
			include_customer();
		});
		
		open_page_user_lms_msg('1');		
	}

		
	function msg_save(msg_type) {
		var msg = $('#templi_cont').val();
		var msg_kind = "FT"; 

			$.ajax({
				url: "/biz/sender/lms/msg_save",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"msg": msg, "msg_type": msg_type, "msg_kind":msg_kind
					},
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					$('#navigateURL').val("");
					var code = json['code'];
					var message = json['message'];
					if (code == "success") {
						$(".content").html("저장이 완료 되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("저장이 실패되었습니다.<br/>" + message);
						$('#myModal').modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			});
	
		
	}
	
	
	// 메시지 임시저장
	function saveTemporalMsgs() {
		var msgs = {};

		var pfKey = $("#pf_key").val();
		var lms = $('#lms').val();
		var templi = $('#templi_cont').val();

		var buttons = new Array();
		var btnInfo = "";
		var ordering = 1;
		$("#friendtalk_table tr").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined) {
				if (btn_type == "WL") {
					btnInfo = new Object();
					btnInfo.ordering = ordering;
					btnInfo.linkType = btn_type;
					btnInfo.name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
					btnInfo.linkMo = $(this).find(document.getElementsByName("btn_url21")).val().trim();
					btnInfo.linkPc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
					buttons.push(btnInfo);
					ordering++;
				} else if (btn_type == "AL") {
					btnInfo = new Object();
					btnInfo.ordering = ordering;
					btnInfo.linkType = btn_type;
					btnInfo.name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
					btnInfo.linkAnd = $(this).find(document.getElementsByName("btn_url31")).val().trim();
					btnInfo.linkIos = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
					buttons.push(btnInfo);
					ordering++;
				} else if (btn_type == "BK") {
					btnInfo = new Object();
					btnInfo.ordering = ordering;
					btnInfo.linkType = btn_type;
					btnInfo.name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
					buttons.push(btnInfo);
					ordering++;
				} else if (btn_type == "MD") {
					btnInfo = new Object();
					btnInfo.ordering = ordering;
					btnInfo.linkType = btn_type;
					btnInfo.name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
					buttons.push(btnInfo);
					ordering++;
				}
			}
		});
      buttons = JSON.stringify(buttons);

		var img_url = $('#img_url').val();
		var img_link = $('#img_link').val();

		if (pfKey && pfKey != '') {
			msgs['key'] = pfKey;
		} else {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			return;
		}

		if (lms && lms != '') {
			msgs['lms'] = lms;
		}
		if (templi && templi != '') {
			msgs['templi'] = templi;
		}
		if (buttons && buttons != '') {
			msgs['buttons'] = buttons;
		}
		if (img_url && img_url != '') {
			msgs['img_url'] = img_url;
		}
		if (img_link && img_link != '') {
			msgs['img_link'] = img_link;
		}

		$.ajax({
			url: "/biz/sender/save_temp_msg",
			type: "POST",
			data: msgs,
			success: function(){
				$(".content").html("메세지가 저장되었습니다.");
				$("#myModal").modal('show');
			},
			error: function (data, status, er) {
				$(".content").html("처리중 오류가 발생하였습니다.");
				$("#myModal").modal('show');
			}
		});
	}
	</script>
