<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<!-- <link rel="stylesheet" href="/css/tui-grid.css" />
<script src="/js/tui-grid.js"></script> -->
<!-- 본문 영역 -->
<div class="tit_wrap">영업자 관리</div>

<!-- 직책목록 (주)지니 계정에서는 flag 2만 보이도록 -->
<?php
if($this->member->item("mem_id") == '962'){
    $position = $ps2;
}else{
    $position = $ps1;
}
?>
<div class="salesman_box">
  <div class="w50">
    <div class="wb_header"> 영업자 등록/관리 </div>
    <div class="wb_body wb_list">
      <dl>
        <dt> 영업자등록 </dt>
        <dd>
          <select name="point" id="new_branch">
            <option value="" selected="">지점선택</option>
                <?
                if($this->member->item("mem_id") == '962'){
                    echo '<option value="6">(주)지니</option>';
                }else{
					foreach ($br as $r) {
					    echo '<option value="'.$r->dbo_id.'" '.(($r->dbo_id == $rr->ds_dbo_id)?'selected':'').'>'.$r->dbo_name.'</option>';
						}
                }
				?>
          </select>
          <select name="position" id="new_position">
            <option value="" selected="">직책선택</option>
                <?
                if(!empty($position)){
					foreach ($position as $p) {
					    echo '<option value="'.$p->pos_id.'" '.(($p->pos_id == $rr->ds_position_id)?'selected':'').'>'.$p->pos_name.'</option>';
						}
                    }
				?>
          </select>
          <select name="" id="new_state">
            <option value="" selected="">상태선택</option>
                <option value="W" <?=($rr->ds_task_state=='W'?'selected':'')?>>근무</option>
                <option value="L" <?=($rr->ds_task_state=='L'?'selected':'')?>>퇴사</option>
                <option value="A" <?=($rr->ds_task_state=='A'?'selected':'')?>>휴직</option>
          </select>
          <input type="text" class="ip_w100" id="new_name" placeholder="이름 입력" >
          <input type="button" class="btn md" id="check" value="저장" onclick="person_insert()">
        </dd>
      </dl>
      <? if($this->member->item("mem_id") == '3'){ //dhn 최고관리자 계정에서만 노출 ?>
      <dl>
        <dt> 지점관리 </dt>
        <dd>
          <ul class="manager_list">
        <?
			foreach ($br as $r) {
		?>
            <li>
              <input type="text" id="bname_<?=$r->dbo_id?>" value="<?=$r->dbo_name?>">
              <button class="manager_insert" onclick="change_manager(<?=$r->dbo_id?>)"><i class="material-icons">done</i></button>
              <button class="manager_del" onclick="remove_manager(<?=$r->dbo_id?>)"><i class="material-icons">clear</i></button>
            </li>
             <?
                }
             ?>
          </ul>
          <p class="btn_add_mg">
            <input type="text" id="new_branch_name" class="ip_w105" placeholder="지점명 입력">
            <input type="button" class="btn md" id="check" value="지점등록" onclick="change_manager(0)">
          </p>
        </dd>
      </dl>
    <? } ?>

<?php if($this->member->item("mem_level")>=100){ //최고관리자 ?>
      <dl>
        <dt> 직책관리 </dt>
        <dd>
          <ul class="manager_list">
            <?
            if(!empty($ps)){
				foreach ($ps as $p) {
			?>
            <li>
              <input type="text" id="psname_<?=$p->pos_id?>" value="<?=$p->pos_name?>">
              <button class="manager_insert" onclick="change_position(<?=$p->pos_id?>)"><i class="material-icons">done</i></button>
              <button class="manager_del" onclick="remove_position(<?=$p->pos_id?>)"><i class="material-icons">clear</i></button>
            </li>
			<?
				}
            }
			?>
          </ul>
          <!-- <p class="btn_add_mg">
              <select name="" id="position_flag">
                <option value="0" selected="">지점선택</option>
                <?php echo ($this->member->item("mem_id") == '962')?'':'<option value="1">대형네트웍스</option>'; ?>
                <option value="2">(주)지니</option>
              </select>
            <input type="text" id="new_position_name" class="ip_w105" placeholder="직책 입력">
            <input type="button" class="btn md" id="check" value="직책등록" onclick="change_position(0)">
          </p> -->
        </dd>
      </dl>
      <dl>
        <dt> 직책관리(TG) </dt>
        <dd>
          <ul class="manager_list">
            <?
            if(!empty($ps)){
				foreach ($ps2 as $p) {
			?>
            <li>
              <input type="text" id="psname_<?=$p->pos_id?>" value="<?=$p->pos_name?>">
              <button class="manager_insert" onclick="change_position(<?=$p->pos_id?>)"><i class="material-icons">done</i></button>
              <button class="manager_del" onclick="remove_position(<?=$p->pos_id?>)"><i class="material-icons">clear</i></button>
            </li>
			<?
				}
            }
			?>
          </ul>
          <p class="btn_add_mg">
              <select name="" id="position_flag">
                <option value="0" selected="">지점선택</option>
                <?php echo ($this->member->item("mem_id") == '962')?'':'<option value="1">대형네트웍스</option>'; ?>
                <option value="2">(주)지니</option>
              </select>
            <input type="text" id="new_position_name" class="ip_w105" placeholder="직책 입력">
            <input type="button" class="btn md" id="check" value="직책등록" onclick="change_position(0)">
          </p>
        </dd>
      </dl>
<?php }else if( $this->member->item("mem_id") == '962' ){ ?>
    <dl>
      <dt> 직책관리 </dt>
      <dd>
        <ul class="manager_list">
          <?
          if(!empty($ps2)){
              foreach ($ps2 as $p) {
          ?>
          <li>
            <input type="text" id="psname_<?=$p->pos_id?>" value="<?=$p->pos_name?>">
            <button class="manager_insert" onclick="change_position(<?=$p->pos_id?>)"><i class="material-icons">done</i></button>
            <button class="manager_del" onclick="remove_position(<?=$p->pos_id?>)"><i class="material-icons">clear</i></button>
          </li>
          <?
              }
          }
          ?>
        </ul>
        <p class="btn_add_mg">
            <select name="" id="position_flag">
              <option value="0" selected="">지점선택</option>
              <?php echo ($this->member->item("mem_id") == '962')?'':'<option value="1">대형네트웍스</option>'; ?>
              <option value="2">(주)지니</option>
            </select>
          <input type="text" id="new_position_name" class="ip_w105" placeholder="직책 입력">
          <input type="button" class="btn md" id="check" value="직책등록" onclick="change_position(0)">
        </p>
      </dd>
    </dl>
<?php } ?>

    </div>
  </div>
  <div class="w50_l30">
    <div class="wb_header"> 영업자 목록
     <div class="fr">
       <select name="" id="search_state" onchange="search()">
         <option value="" selected="">전체</option>
            <option value="W" <?=($param['search_state']=='W'?'selected':'')?>>근무</option>
            <option value="L" <?=($param['search_state']=='L'?'selected':'')?>>퇴사</option>
            <option value="A" <?=($param['search_state']=='A'?'selected':'')?>>휴직</option>
       </select>
     </div>
    </div>
    <div class="wb_body">
      <table>
        <colgroup>
        <col width="">
        <col width="">
        <col width="">
        <col width="">
        <col width="">
        <col width="">
        </colgroup>
        <thead>
          <tr>
            <th>지점</th>
            <th>직책</th>
            <th>이름</th>
            <th>상태</th>
            <th>등록일</th>
            <th>수정</th>
          </tr>
        </thead>
        <tbody>
        <?
foreach ($rs as $rr) {
?>
          <tr>
            <td>
              <select id="branch_<?=$rr->ds_id?>" name="point">
                <option value="" selected="">지점선택</option>
                <?
                if($this->member->item("mem_id") == '962'){
                    echo '<option value="6" selected="">(주)지니</option>';
                }else{
					foreach ($br as $r) {
					    echo '<option value="'.$r->dbo_id.'" '.(($r->dbo_id == $rr->ds_dbo_id)?'selected':'').'>'.$r->dbo_name.'</option>';
						}
                }
				?>
              </select>
            </td>
            <td>
              <select id="pos_<?=$rr->ds_id?>" name="position">
                <option value="" selected="">직책선택</option>
                <?
					foreach ($position as $p) {
					    echo '<option value="'.$p->pos_id.'" '.(($p->pos_id == $rr->ds_position_id)?'selected':'').'>'.$p->pos_name.'</option>';
						}
				?>
              </select>
            </td>
            <td><input type="text" class="ip_w80" id="name_<?=$rr->ds_id?>" value="<?=$rr->ds_name?>"></td>
            <td>
              <select   id="state_<?=$rr->ds_id?>" onchange="">
                <option value="W" <?=($rr->ds_task_state=='W'?'selected':'')?>>근무</option>
                <option value="L" <?=($rr->ds_task_state=='L'?'selected':'')?>>퇴사</option>
                <option value="A" <?=($rr->ds_task_state=='A'?'selected':'')?>>휴직</option>
              </select>
            </td>
            <td><?=substr($rr->ds_registration_date,0,10)?></td>
            <td>
				<button type="button" class="btn sm white" id="" onclick="person_save('<?=$rr->ds_id?>')" >수정</button>
              	<!-- <button type="button" class="btn sm white" id="" onclick="person_del('<?=$rr->ds_id?>')" >삭제</button> -->
			</td>
          </tr>
<?
}
?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>

    $('select[name=point]').change(function(){
        var point_val = $(this).val();
        var point_name = $(this).attr('id');

        console.log(point_name);

        if(point_name != 'new_branch'){
            var point_id = $(this).parent().next().children().attr('id');
        }else{
            var point_id = 'new_position';
        }

        //console.log("point_id : "+point_id+" point_val : "+point_val);

        $.ajax({
            url: "/biz/salesman/position",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                "point_val": point_val
            },
            success: function(json) {
                var data = JSON.parse(json);
                //console.log(data);

                console.log("point_id : "+point_id+" point_val : "+point_val);

                $('#'+point_id+' option').remove();
                $('#'+point_id).append("<option value selected >직책선택</option>");
                for(var i=0; i < data.length; i++){
                    console.log('data', i, data[i].pos_name, data[i].pos_id);

                    $('#'+point_id).append("<option value='"+data[i].pos_id+"'>"+data[i].pos_name+"</option>");
                }
            }
        });

    });

    //검색
    function search() {

    	var form = document.createElement("form");

    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/biz/salesman");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

    	var state = document.createElement("input");
    	state.setAttribute("type", "hidden");
    	state.setAttribute("name", "search_state");
    	state.setAttribute("value", $("#search_state").val() );
    	form.appendChild(state);

    	form.submit();

    }
	function person_insert() {
		var person = {
				ds_dbo_id : $("#new_branch").val(),
				ds_name : $("#new_name").val(),
				ds_position_id : $("#new_position").val(),
				ds_task_state : $("#new_state").val()
		};

		//console.log(person);

		$.ajax({
            url: "/biz/salesman/save",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"person": person
            },
            success: function(json) {
            	location.href = location.href;
            }
		});

	}

	function person_save(id) {
		var person = {
				ds_dbo_id : $("#branch_" + id).val(),
				ds_name : $("#name_" + id).val(),
				ds_position_id : $("#pos_" + id).val(),
				ds_task_state : $("#state_" + id).val(),
				ds_id : id
		};

		//console.log(person);

		$.ajax({
            url: "/biz/salesman/save",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"person": person
            },
            success: function(json) {
            	location.href = location.href;
            }
		});

	}

	function person_del(id) {
		var person = {
				ds_id : id
		};

		//console.log(person);

		$.ajax({
            url: "/biz/salesman/del",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"person": person
            },
            success: function(json) {
            	if(json['code'] == "ER") {
            		alert('마트톡 : ' + json['58'] + ' 건 \n지니 : ' + json['133g'] + ' 건 \no2o : ' + json['133o'] + ' 건 \n지니2 : ' + json['59g'] + ' 건' );
            	}else {
            		location.href = location.href;
            	}

            }
		});

	}

	function remove_manager(id) {
		var branch = {
				dbo_id : id
		};

		$.ajax({
            url: "/biz/salesman/dbo_remove",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"branch": branch
            },
            success: function(json) {
            	if(json['code'] == "ER") {
            		alert("지점으로 지정된 영업 담당자가 있습니다.");
            	}else {
            		location.href = location.href;
            	}

            }
		});
	}

	function change_manager(id) {
		if(id ==0) {
    		var dbo = {
    				dbo_name : $("#new_branch_name").val()
    		};
		}else {
    		var dbo = {
    				dbo_id : id,
    				dbo_name : $("#bname_" + id).val()
    		};
		}
		$.ajax({
            url: "/biz/salesman/dbo_save",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"branch": dbo
            },
            success: function(json) {
            		location.href = location.href;
            }
		});
	}

	function remove_position(id) {
		var pos = {
				pos_id : id
		};

		$.ajax({
            url: "/biz/salesman/pos_remove",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"pos": pos
            },
            success: function(json) {
            	if(json['code'] == "ER") {
            		alert("직책으로 지정된 영업 담당자가 있습니다.");
            	}else {
            		location.href = location.href;
            	}

            }
		});
	}

	function change_position(id) {
		if(id ==0) {
    		var pos = {
    				pos_name : $("#new_position_name").val(),
    				pos_flag : $("#position_flag").val()
    		};
		}else {
    		var pos = {
    				pos_id : id,
    				pos_name : $("#psname_" + id).val()
    		};
		}
        console.log(pos);
		$.ajax({
            url: "/biz/salesman/pos_save",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"pos": pos
            },
            success: function(json) {
            		location.href = location.href;
            }
		});
	}

</script>
