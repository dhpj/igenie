<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/login_check.php');
?>
<?
    $page_name = $flag == 'c' ? '캠페인' : ($flag == 'a' ? '광고그룹' : '');
?>
<!-- 타이틀 영역 -->
<div class="send_menu">
	<ul>
        <li><a href="/kakao/campaign_lists/c" class="<?=$flag == 'c' ? 'sm_on' : ''?>"><i class="xi-view-stream"></i>캠페인 목록</a></li>
        <li><a href="/kakao/campaign_lists/a" class="<?=$flag == 'a' ? 'sm_on' : ''?>"><i class="xi-view-stream"></i>광고그룹 목록</a></li>
        <li><a href="/kakao/campaign_lists/cr" class="<?=$flag == 'cr' ? 'sm_on' : ''?>"><i class="xi-view-stream"></i>소재 목록</a></li>
        <!-- <li><a href="#"><i class="xi-view-list"></i> 탭3 </a></li> -->
	</ul>
</div>

<!-- 컨텐츠 전체 영역 -->
<div id="mArticle" class="kakao kakao_basic campaign_list">
    <div class="form_section">
        <div class="inner_tit">
            <h3><?=$page_name?> 목록</h3>
        </div>

        <div class="white_box">

            <div class="search_box">
				<select class="" id="search_sel">
					 <option value="1" <?=$search_sel == '1' ? 'selected' : ''?>><?=$page_name?> 이름</option>
                 <?if($flag == 'a' || $flag == 'cr'){?>
					 <option value="2" <?=$search_sel == '2' ? 'selected' : ''?>>소속</option>
                 <?}?>
				</select>
				<input type="text" class="searchBox" id="search_txt" placeholder="검색어 입력" value="<?=$search_txt?>" onkeyup="if(window.event.keyCode==13){open_page(1)}">
				<input type="button" class="btn md" value="조회" onclick="open_page(1)">
			</div>

            <div class="table_list">
				<table class="campaign_list">
					<colgroup>
							<col width="180px">
							<col width="*">
							<col width="140px">
							<col width="140px">
							<col width="140px">
							<col width="140px">
					</colgroup>
					<thead>
                        <tr>
                        	<th>생성일</th>
                        	<th><?=$page_name?> 이름</th>
                        	<th>상태</th>
                        	<th>
                            <?if($flag == 'c' || $flag == 'a'){?>
                                이름수정
                            <?}else if ($flag == 'cr'){?>
                                수정
                            <?}?>
                            </th>
                        	<th>삭제</th>
                        <?if($flag == 'c'){?>
                            <th>소속 채널</th>
                        <?}else if($flag == 'a'){?>
                        	<th>소속 캠페인</th>
                        <?}else if($flag == 'cr'){?>
                        	<th>소속 그룹</th>
                        <?}?>
                        </tr>
                    </thead>
                    <tbody>
                    <?
                        if(!empty($list)){
                            foreach($list as $a){
                    ?>
                        <tr>
                            <td><?=$a->ct?></td>
                            <td>
                            <?if($flag == 'c' || $flag == 'a'){?>
                                <input type="text" class="name" value="<?=$a->name?>">
                            <?}else if ($flag == 'cr'){?>
                                <?=$a->name?>
                            <?}?>
                            </td>
                            <td>
                                <label class="form-switch">
                                    <input type="checkbox" class="trigger" onchange='change_status(this, "<?=$a->ad_id?>", "<?=$a->kakao_id?>", "<?=$flag?>")' <?=$a->status == 'Y' ? 'checked' : ''?>>
                                    <i></i>
                                </label>
                            </td>
                            <?
                                if ($flag == 'cr'){
                                    $type = $a->pcr_type;
                                    $msg_addr = '';
                                    if ($type != 'bt'){
                                        $msg_addr = '_' . $type;
                                    }
                                }
                            ?>
                            <td><button class="btn sm" <?if ($flag == 'c' || $flag == 'a'){?> onclick='change_name(this, "<?=$a->ad_id?>", "<?=$a->kakao_id?>", "<?=$flag?>", "<?=$a->c_id?>")' <?}else if($flag == 'cr'){?>onclick='go_modify("<?=$a->kakao_id?>", "<?=$msg_addr?>", "<?=$a->ad_id?>")'<?}?>>수정</button></td>
                            <td><button class="btn sm" onclick='delete_this(<?=$a->id?>, "<?=$a->ad_id?>", "<?=$a->kakao_id?>", "<?=$flag?>")'>삭제</button></td>
                            <td><?=$a->name2?></td>
                        </tr>
                    <?
                            }
                        } else {
                    ?>
                        <tr>
                            <td colspan='7'><?=$page_name?>이 존재하지 않습니다.</td>
                        </tr>
                    <?
                        }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>

    function open_page(page){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/kakao/campaign_lists/<?=$flag?>");

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "page");
        field.setAttribute("value", page);
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "search_sel");
        field.setAttribute("value", $('#search_sel').val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "search_txt");
        field.setAttribute("value", $('#search_txt').val().trim());
        form.appendChild(field);

        form.submit();
    }

    function go_modify(id, msg_addr, adid){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/kakao/creative_write" + msg_addr);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "id");
        field.setAttribute("value", id);
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "token");
        field.setAttribute("value", Kakao.Auth.getAccessToken());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "adid");
        field.setAttribute("value", adid);
        form.appendChild(field);

        form.submit();
    }

    function change_status(t, adid, kid, flag){
        var config = t.checked ? 'ON' : 'OFF';
        function rollback_status(t){
            $(t).prop('checked', (t.checked ? false : true));
        }
        if (flag == 'c'){
            $.ajax({
                url: "/kakao/put_campaign_status",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                    , token : Kakao.Auth.getAccessToken()
                    , adid : adid
                    , cid : kid
                    , config : config
                },
                success: function (json) {
                    if (json.data.code != undefined){
                        alert('상태변경에 실패했습니다. 관리자에게 문의해주세요.');
                        rollback_status(t);
                    }
                }
            });
        } else if (flag == 'a'){
            $.ajax({
    			url: "/kakao/put_ad_group_status",
    			type: "POST",
    			data: {
                    "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                  , token : Kakao.Auth.getAccessToken()
                  , adid : adid
                  , aid : kid
                  , config : config
                },
    			success: function (json) {
                    if (json.data.code != undefined){
                        alert('상태변경에 실패했습니다. 관리자에게 문의해주세요.');
                        rollback_status(t);
                    }
    			}
    		});
        } else if (flag == 'cr'){
            $.ajax({
    			url: "/kakao/put_creative_status",
    			type: "POST",
    			data: {
                    "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                  , token : Kakao.Auth.getAccessToken()
                  , adid : adid
                  , crid : kid
                  , config : config
                },
    			success: function (json) {
                    if (json.data.code != undefined){
                        alert('상태변경에 실패했습니다. 관리자에게 문의해주세요.');
                        rollback_status(t);
                    }
    			}
    		});
        }
    }

    function change_name(t, adid, kid, flag, cid){
        var name = $(t).parents('tr').find('.name').val();
        if (flag == 'c'){
            $.ajax({
    			url: "/kakao/put_campaign",
    			type: "POST",
    			data: {
                    "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                  , token : Kakao.Auth.getAccessToken()
                  , adid : adid
                  , cid : kid
                  , name : name
                },
    			success: function (json) {
                    if (json.data.id != Number(id)){
                        alert('이름수정에 실패했습니다. 관리자에게 문의해주세요.');
                    } else {
                        alert('수정되었습니다.');
                    }
    			}
    		});
        } else if (flag == 'a'){
            $.ajax({
    			url: "/kakao/put_ad_group",
    			type: "POST",
    			data: {
                    "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                  , token : Kakao.Auth.getAccessToken()
                  , adid : adid
                  , cid : cid
                  , aid : kid
                  , name : name
                },
    			success: function (json) {
                    if (json.data.id != Number(id)){
                        alert('이름수정에 실패했습니다. 관리자에게 문의해주세요.');
                    } else {
                        alert('수정되었습니다.');
                    }
    			}
    		});
        }
    }

    function delete_this(id, adid, kid, flag){
        if (confirm('삭제하시면 복귀가 되지 않습니다.\n정말 삭제하시겠습니까?')){
            if (flag == 'c'){
                $.ajax({
        			url: "/kakao/delete_campaign",
        			type: "POST",
        			data: {
                        "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                      , token : Kakao.Auth.getAccessToken()
                      , adid : adid
                      , cid : kid
                      , pcid : id
                    },
        			success: function (json) {;
                        if (json.data != null){
                            alert('삭제에 실패했습니다. 관리자에게 문의해주세요.');
                        } else {
                            location.reload();
                        }
        			}
        		});
            } else if (flag == 'a'){
                $.ajax({
                    url: "/kakao/delete_ad_group",
        			type: "POST",
        			data: {
                        "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                      , token : Kakao.Auth.getAccessToken()
                      , adid : adid
                      , aid : kid
                      , paid : id
                    },
        			success: function (json) {
                        if (json.data != null){
                            alert('삭제에 실패했습니다. 관리자에게 문의해주세요.');
                        } else {
                            location.reload();
                        }
        			}
        		});
            } else if (flag == 'cr'){
                $.ajax({
        			url: "/kakao/delete_creative",
        			type: "POST",
        			data: {
                        "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                      , token : Kakao.Auth.getAccessToken()
                      , adid : adid
                      , crid : kid
                      , pcrid : id
                    },
        			success: function (json) {
                        if (json.data != null){
                            alert('삭제에 실패했습니다. 관리자에게 문의해주세요.');
                        } else {
                            location.reload();
                        }
        			}
        		});
            }
        }
    }

</script>
