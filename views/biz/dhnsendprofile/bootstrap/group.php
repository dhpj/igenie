    <style>
        .dual-list .list-group {
            margin-top: 20px;
        }

        .list-left li, .list-right li, .list-profile-group li {
            cursor: pointer;
        }

        .list-arrows {
            padding-top: 150px;
        }

        .list-arrows button {
            margin-bottom: 20px;
        }
    </style>
    <input type='hidden' name='csrfmiddlewaretoken' value='46jMlCPiJqlrgyBaN6djd306uGmSiG8o' />
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="submit" class="btn btn-primary delete-btn" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	발신 프로필 그룹 관리
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="inner_notice">
		<h3>발신프로필 그룹 가이드</h3>
		<ul>
			<li>하나의 발신프로필은 여러 그룹에 중복으로 속할 수 있습니다.</li>
			<li>동일 그룹에 속한 발신프로필은 발신 프로필 그룹으로 등록된 템플릿을 공유하여 사용 할 수 있습니다.</li>
			<li>발신 프로필 그룹 신규 등록/삭제는 관리자에게 요청해주시기 바랍니다.</li>
		</ul>
	</div>
	<div class="form_section">
		<div class="inner_tit">
			<h3>발신 프로필 그룹</h3>
		</div>
		<div class="inner_content">
			<div class="group_container">
				<div class="group_wrap" style="width: 540px">
					<table class="group_table">
						<colgroup>
							<col width="200px">
							<col width="*">
						</colgroup>
						<thead>
							<tr>
								<th>그룹</th>
								<th>그룹키</th>
							</tr>
						</thead>
						<tbody>
							<? $groupIndex = 0;
							foreach($grouplist as $r) {
							    if ($groupIndex == 0) {
							?>
							<input type="hidden" id="selectedgroupmemid" value="<?=$r->spg_mem_id?>" />
							<input type="hidden" id="selectedgroup" value="<?=$r->spg_pf_key?>" />
							<?  } ?>
							<tr name="grouplist" id="group<?=$groupIndex ?>" class="<?=($groupIndex == 0)? 'active' : '' ?>" onclick="groupChange(<?=$groupIndex ?>);">
								<td><?=$r->spg_name?></td>
								<td id="groupprofile<?=$groupIndex ?>" class="tc"><?=$r->spg_pf_key?><input type="hidden" id="groupmemid<?=$groupIndex ?>" value="<?=$r->spg_mem_id?>" /></td>
							</tr>								
							<? $groupIndex = $groupIndex + 1;
							} ?>
						</tbody>
					</table>
				</div>
				
				<div class="group_wrap" style="width: 320px;">
					<table class="group_table">
						<colgroup>
							<col width="*">
							<col width="70px">
						</colgroup>
						<thead>
							<tr>
								<th colspan="2">발신프로필</th>
							</tr>
						</thead>
						<tbody id="groupinprofile">
						</tbody>
					</table>
				</div>
				<div class="group_wrap" style="width: 320px;">
					<table class="group_table">
						<colgroup>
							<col width="*">
							<col width="70px">
						</colgroup>
						<thead>
							<tr>
								<th colspan="2">
									<div class="group_search">
										<input id="search_out_profile" type="text" style="width: calc(100% - 90px)"> <button class="btn md fr dark" onclick="groupOutProfile()">검색</button>
									</div>
								</th>
							</tr>
						</thead>
						<tbody id="groupoutprofile">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- div id="overlay" style="display: none;">
        <img src="/collected_statics/assets/img/loader.gif" alt="Loading"/><br/>
        Loading...
    </div-->

<script type="text/javascript">
function groupChange(groupIndex) {
    $("tr[name=grouplist]").removeClass("active");
    $("#group"+groupIndex).addClass("active");
    $("#selectedgroup").val($("#groupprofile"+groupIndex).text());
    $("#selectedgroupmemid").val($("#groupmemid"+groupIndex).val());
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();
    groupInProfile();
    groupOutProfile();
    //groupInProfile(groupMemId, groupProfile);
    //groupOutProfile(groupMemId, groupProfile);
}

function reloadGroupProfiles() {
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();
    groupInProfile();
    groupOutProfile();
    //groupInProfile(groupMemId, groupProfile);
    //groupOutProfile(groupMemId, groupProfile);	
}

//function groupInProfile(groupMemId, groupProfile)
function groupInProfile()
{
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();
	
	$.ajax({
		url: "/dhnbiz/sendprofile/group_in_profile",
		type: "POST",
		//data: {"name":username, "phn":userphn, "orderno":userorderno },
		data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>", "group_mem_id":groupMemId, "group_profile":groupProfile },
		success: function (json) {
			var html = "";
			$("#groupinprofile").html("");
			console.log(json);
			$.each(json, function(key, value){
				var mem_id = value.spf_mem_id;
				var spf_friend = value.spf_friend;
				var spf_company = value.spf_company;
				var spf_key = value.spf_key;

				html += '<tr>\n';
				html += '<td>'+ spf_company + '(' + spf_friend +')</td>\n';
				html += '<td><button class="btn sm del" onclick="groupInProfileRemove(\''+trim(mem_id)+'\', \'' + spf_friend + '\', \'' + spf_key + '\')">삭제</button></td>\n'
				html += '</tr>\n';
			}); 
			$("#groupinprofile").html(html);
		}
	});			
}

//function groupOutProfile(groupMemId, groupProfile)
function groupOutProfile()
{
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();
	var searchProfile = $("#search_out_profile").val();
	//alert(searchProfile);
	$.ajax({
		url: "/dhnbiz/sendprofile/group_out_profile",
		type: "POST",
		//data: {"name":username, "phn":userphn, "orderno":userorderno },
		data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>", "group_mem_id":groupMemId, "group_profile":groupProfile, "search_profile":searchProfile },
		success: function (json) {
			var html = "";
			$("#groupoutprofile").html("");
			console.log(json);
			$.each(json, function(key, value){
				var mem_id = value.spf_mem_id;
				var spf_friend = value.spf_friend;
				var spf_company = value.spf_company;
				var spf_key = value.spf_key;

				html += '<tr>\n';
				html += '<td>'+ spf_company + '(' + spf_friend +')</td>\n';
				html += '<td><button class="btn sm add" onclick="groupInProfileAdd(\''+trim(mem_id)+'\', \'' + spf_friend + '\', \'' + spf_key + '\');">추가</button></td>\n'
				html += '</tr>\n';
			}); 
			$("#groupoutprofile").html(html);
		}
	});			 
}

function groupInProfileRemove(profileMemId, profileName, profileKey) {
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();

	try {
        $.ajax({
            url: "/dhnbiz/sendprofile/group_profile_remove",
            type: "POST",
            data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
                 ,'group_mem_id': groupMemId
                 ,'group_profile': groupProfile
                 ,'profile_mem_id': profileMemId
                 ,'profile_name': profileName
                 ,'profile_key': profileKey
                 },
            beforeSend:function(){
                $('#overlay').fadeIn();
            },
            complete:function(){
                $('#overlay').fadeOut();
            },
            success: function (json) {
                var message = json['message'];
                var code = json['code'];
                if (code == '200') {
					$(".content").html("발신프로필을 그룹에서 삭제되었습니다.");
                    $('#myModal').modal({backdrop: 'static'});
                    reloadGroupProfiles();
                    return true;
                } else {
                    $(".content").html(message);
                    $('#myModal').modal({backdrop: 'static'});
                }
            },
            error: function () {
                $(".content").html("처리되지 않았습니다.");
                $('#myModal').modal({backdrop: 'static'});
            }
        });
		    

	}catch(e){ alert(e.message); }	
}

function groupInProfileAdd(profileMemId, profileName, profileKey) {
    var groupMemId = $("#selectedgroupmemid").val();
    var groupProfile = $("#selectedgroup").val();

	try {
        $.ajax({
            url: "/dhnbiz/sendprofile/group_profile_add",
            type: "POST",
            data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
                 ,'group_mem_id': groupMemId
                 ,'group_profile': groupProfile
                 ,'profile_mem_id': profileMemId
                 ,'profile_name': profileName
                 ,'profile_key': profileKey
                 },
            beforeSend:function(){
                $('#overlay').fadeIn();
            },
            complete:function(){
                $('#overlay').fadeOut();
            },
            success: function (json) {
                var message = json['message'];
                var code = json['code'];
                if (code == '200') {
					$(".content").html("발신프로필을 그룹에 등록되었습니다.");
                    $('#myModal').modal({backdrop: 'static'});
                    reloadGroupProfiles();
                    return true;
                } else {
                    $(".content").html(message);
                    $('#myModal').modal({backdrop: 'static'});
                }
            },
            error: function () {
                $(".content").html("처리되지 않았습니다.");
                $('#myModal').modal({backdrop: 'static'});
            }
        });
		    

	}catch(e){ alert(e.message); }
}

$(document).ready(function() { 
	groupChange(0);
});
</script>
 