<!-- 페이지인식 -->
<script type="text/javascript">

$(document).ready(function(){
    $('#accordion').children().eq(5).addClass('open');
// #accordion에 자식 요소(li)가 몇번째인지를 확인한 후 open이라는 클래스 추가
});
</script>
<div class="wrap_mall">
	<div class="s_tit">
		상품관리
	</div>
  <div class="mall_content">
      <!-- 요약 패널 -->
      <div class="panel">
        <div class="panel_body">
          <div class="info_summery">
            <ul>
              <li>
                <span class="info_icon"><i class="material-icons">grid_on</i></span>
                <div class="info_detail"><p>전체상품수량</p><strong><?=$all_count?></strong><em>건</em></div>
              </li>
              <li>
                <span class="info_icon"><i class="material-icons">local_shipping</i></span>
                <div class="info_detail"><p>진열중</p><strong><?=$dp_status1?></strong><em>건</em></div>
              </li>
              <li>
                <span class="info_icon"><i class="material-icons">done_outline</i></span>
                <div class="info_detail"><p>진열안함</p><strong><?=$dp_status2?></strong><em>건</em></div>
              </li>
              <li>
                <span class="info_icon"><i class="material-icons">add_shopping_cart</i></span>
                <div class="info_detail"><p>품절</p><strong><?=$dp_status3?></strong><em>건</em></div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- 요약 패널 END -->
      <!-- 검색 패널 -->
      <div class="panel">
        <div class="panel_body rows">
          <div class="row_box">
            <div class="row_wrap">
              <div class="row_label">상세검색</div>
              <div class="row_contents">
                <div class="form_row"><input type="text" style="width: 100%" id="goodscode" value="<?=$search['goodscode']?>" placeholder="상품코드 검색"></div>
                <div class="form_row"><input type="text" style="width: 100%" id="goodsname" value="<?=$search['goodsname']?>" placeholder="상품명 검색"></div>
                <button class="btn md blue search" onclick="open_page(1)">상품검색</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 검색 패널 END -->
      <!-- 목록 패널 -->
      <div class="panel">
        <div class="panel_header title">전체 상품목록 (<?=$total_rows?>건)
          <div class="fr">
            <select style="margin: 0 5px;" id="g_category1" class="select_op">
              <option value="">카테고리별 분류</option>
                                         <?
                                        // log_message("ERROR", "count : ". count($category1). "/ ".$search['bc']);
                                         foreach($category1 as $c) {
                                             //log_message("ERROR", "C : ".$c->id);
                                             $sel = "";
                                             if($search['bc'] == $c->id)
                                                 $sel = "selected";

                                             echo "<option value='$c->id' $sel >$c->name</option>";
                                         }
                                         ?>
            </select>
            <!-- 1차카테고리 선택후 하위카테고리 있을 경우 하위카테고리 표시  -->
            <select style="display: ;" id="g_subcategory" class="select_op">
              <option value="">하위 카테고리</option>
                                 <?php
                                 $sel = "";
                                 if(!empty($category2)) {
                                 foreach($category2 as $sc) {
                                     if($search['sc'] == $sc->id) {
                                         $sel = "selected";
                                     } else {
                                         $sel = "";
                                     }
                                     echo "<option value='$sc->id' $sel >$sc->name</option>";
                                 }
                                 }
                                 ?>
            </select>


<!-- 								<select style="margin-left: 5px;" id="g_category2">
              <option value="">이벤트카테고리별 분류</option>
                                         <?
                                        // log_message("ERROR", "count : ". count($category2). "/ ".$search['bc']);
                                        // foreach($category2 as $c) {
                                             //log_message("ERROR", "C : ".$c->id);
                                         //    $sel = "";
                                         //    if($search['sc'] == $c->id)
                                         //        $sel = "selected";

                                        //     echo "<option value='$c->id' $sel >$c->name</option>";
                                       //  }
                                         ?>
            </select> -->
            <select style="margin-left: 5px;"  id="g_dpstatus" class="select_op">
              <option value= "">진열상태별 분류</option>
              <option value="1" <?=($search['dp'] == 1)?'selected':''?>>진열중</option>
              <option value="2" <?=($search['dp'] == 2)?'selected':''?>>진열안함</option>
              <option value="3" <?=($search['dp'] == 3)?'selected':''?>>품절</option>
            </select>

            <select style="margin-left: 5px;" id="g_dp_period" class="select_op">
              <option value= "">진열기간설정 분류</option>
              <option value="Y" <?=($search['dp_period'] == 'Y')?'selected':''?>>설정됨</option>
              <option value="N" <?=($search['dp_period'] == 'N')?'selected':''?>>설정안함</option>
            </select>
            <select style="margin-left: 5px;" id="g_img_flag" class="select_op">
              <option value= "">이미지 상태</option>
              <option value="Y" <?=($search['g_img_flag'] == 'Y')?'selected':''?>>등록됨</option>
              <option value="N" <?=($search['g_img_flag'] == 'N')?'selected':''?>>등록안됨</option>
            </select>
            <select style="margin-left: 5px;"  id="g_reserver_flag" class="select_op">
              <option value= "">예약건 분류</option>
              <option value="R" <?=($search['res_flag'] == 'R')?'selected':''?>>수정 예약건</option>
              <option value="C" <?=($search['res_flag'] == 'C')?'selected':''?>>예약없음</option>
            </select>
            <select style="margin-left: 5px;"  id="g_per_page" class="select_op">
              <option value="10" <?=($perpage == 10)?'selected':''?>>10개씩</option>
              <option value="25" <?=($perpage == 25)?'selected':''?>>25개씩</option>
              <option value="50" <?=($perpage == 50)?'selected':''?>>50개씩</option>
              <option value="100" <?=($perpage == 100)?'selected':''?>>100개씩</option>
              <option value="200" <?=($perpage == 200)?'selected':''?>>200개씩</option>
              <option value="300" <?=($perpage == 300)?'selected':''?>>300개씩</option>
            </select>
            <!-- 셀렉트 옵션 선택시 자동 분류로 수정 -->
            <!--<button class="btn md dark" style="margin-left: 15px;" onclick="open_page(1)">분류</button>-->
          </div>
        </div>
        <div class="panel_body">
          <div class="table_top">
            <div class=" fr">
              <button type="button" class="btn md excel" onclick="download();">엑셀파일 다운로드</button>
            </div>
            <div class="table_info_group">
              <button class="btn md red" onclick="ondelete();">선택삭제</button>
            </div>
            <!--div class="table_info_group">
              <button class="btn md dark" onclick="rollback();">설정가격 롤백</button>
            </div-->
            <div class="table_info_group">
              <select id="ch_category">
                <option value="">카테고리 변경</option>
                                          <?php
                                          foreach($category1 as $c) {
                                              echo "<option value='$c->id'>$c->name</option>";
                                          }
                                          ?>
              </select>
              <!-- <select id="ch_category2">
              <option value="">이벤트 카테고리 변경</option>
                                          <?php
                                         // foreach($category2 as $sc) {
                                          //    echo "<option value='$sc->id'>$sc->name</option>";
                                         // }
                                          ?>
              </select> -->
              <select id="ch_dpstatus">
                <option value="">진열상태 변경</option>
                <option value="1">진열중</option>
                <option value="2">진열안함</option>
                <option value="3">품절</option>
              </select>
            </div>
            <button class="btn md dark" style="margin-left: 5px;" onclick="onchange_status()">일괄수정</button>
          </div>
          <table class="order edit">
            <colgroup>
              <col width="40px">
              <col width="120px">
              <col width="80px">
              <col width="180px">
              <col width="100px">
              <col width="100px">
              <col width="80px">
              <col width="80px">
              <col width="60px">
              <col width="100px">
              <col width="100px">
              <col width="80px">
                <col width="160px">
                <col width="120px">
              <col width="120px">
            </colgroup>
            <thead>
              <tr>
                <th>
                  <div class="check-box">
                      <input id="check_all" type="checkbox"><label for="check_all"></label>
                    </div>
                  </th>
                <th>상품코드</th>
                <th colspan="2">상품명</th>
                <th>용량</th>
                <th>원산지</th>
                <th>정상가</th>
                <th>판매가</th>
                <th>재고</th>
                <th>카테고리</th>
                <th>하위카테고리</th>
                <th>진열상태</th>
                <th>진열기간설정</th>
                <th>예약수정</th>
                <th>관리</th>
              </tr>
            </thead>
            <tbody class="tbody">
            <? foreach($goodslist as $r) { ?>
              <!--tr <? if($r->source=='R') echo "class='booking_wrap'"; ?>-->
              <tr>
                <td>
                  <div class="check-box">
                      <input id="i_<?=$r->source.$r->id?>" type="checkbox" class="chk"><label for="i_<?=$r->source.$r->id?>"></label>
                    </div>
                  </td>
                <td><a href="/goodsmng/addgoods/<?=$r->source.$r->id?>"><?=$r->code?></a></td>
                <td class="product_list_img"><img src="<?=str_replace('/var/www/mall','',$r->thumb_path)?>"></td>
                <td><input type="text" id="description_<?=$r->source.$r->id?>" value="<?=$r->description?>"></td>
                <td><input type="text" id="option_<?=$r->source.$r->id?>" value="<?=$r->option?>"></td>
                <td><input type="text" id="coo_<?=$r->source.$r->id?>" value="<?=$r->coo?>"></td>
                <td><input type="text" class="tr" id="unit_price_<?=$r->source.$r->id?>" value="<?=(empty($r->unit_price))?'':number_format($r->unit_price)?>" onkeyup="numberWithCommas(this, this.value)"></td>
                <td><input type="text" class="tr" id="dc_unit_price_<?=$r->source.$r->id?>" value="<?=(empty($r->dc_unit_price))?'':number_format($r->dc_unit_price)?>" onkeyup="numberWithCommas(this, this.value)"></td>
                <td><? if(!empty($r->stock_flag) && $r->stock_flag == 'Y') { ?><input type="text" class="tc" id="g_stock_<?=$r->source.$r->id?>" value="<?=($r->stock>0)?$r->stock:'품절'?>"> <?php } ?> </td>
                <td>
                  <!--select style="width: 100%;" id="g_category">
                                          <?php
                                          $sel = "";
                                          foreach($category1 as $c) {
                                              if($goods->category1 == $c->id) {
                                                  $sel = "selected";
                                              } else {
                                                  $sel = "";
                                              }
                                              echo "<option value='$c->id' $sel >$c->name</option>";
                                          }
                                          ?>
                  </select>
                  <select style="width: 100%;" id="g_category">
                    <option><?=$r->bc_name?></option>
                  </select-->
                  <?=$r->bc_name?>
                </td>
                <td><?=$r->sc_name?></td>
                <td>
                  <?=$this->common->get_dp_status($r->dp_status)?>
                </td>

                <td>
                  <? if ($r->dp_exp_setup=='Y') { ?>
                  <p class="<?=($r->dp_exp_setup=='Y')?'icon_dp':'icon_no-dp'?>"><?=($r->dp_exp_setup=='Y')?''.$this->common->get_dp_status($r->dp_option).'':''?></p>
                  <p class="dp_time"><?=($r->dp_exp_setup=='Y')?substr($r->dp_from,5):''?> ~ <?=($r->dp_exp_setup=='Y')?substr($r->dp_to,5):''?></p>
                  <? } ?>
                </td>
                <td><? if($r->reserv_flag=='Y') echo "<p class='icon_booking'>".substr($r->reserv_date,5)." ".$r->reserv_time.":00</p>"; ?></td>
                <td><button class="btn sm dark" onclick="onmodify_id('<?=$r->source.$r->id?>')">변경</button><button class="btn sm red" onclick="ondelete_id('<?=$r->source.$r->id?>')" style="margin-left: 10px">X</button></td>
              </tr>
            <? } ?>
            </tbody>
          </table>
          <div class="table_bottom tc">
            <?=$page_html?>
          </div>
        </div>
      </div>
      <!-- 목록 패널 END -->
    </div>

<script>

 $(function(){
     $("#check_all").click(function(){
         var chk = $(this).is(":checked");//.attr('checked');
         if(chk) $(".tbody .chk").prop('checked', true);
         else  $(".tbody .chk").prop('checked', false);
     });

     $("#goodsname").keydown(function (key) {

         if(key.keyCode == 13){//키가 13이면 실행 (엔터는 13)
          open_page(1);
         }

     });
    $(".select_op").change(function() {
      open_page(1);
    });
 });

function onchange_status() {
var all_status = $("#change_status").val();
var category = $("#ch_category").val();
var category2 = $("#ch_category2").val();
var dpstatus = $("#ch_dpstatus").val();

$(".chk").each(function() {
  var chk = $(this).is(":checked");
  if(chk) {
      var goodid = $(this).attr("id").substring(2);

        changestatus(goodid, category,category2, dpstatus );
  }

});

setTimeout(function() {
  location.reload();
  }, 200);

}

function changestatus(id, category,category2, dpstatus) {
$.ajax({
  url: "/goodsmng/changestatus",
  type: "POST",
  data: {"goodsid":id, "category":category, "category2":category2, "dpstatus":dpstatus},
  success: function (json) {

    }
});
}

function ondelete() {
//console.log("삭제 시작");
if(confirm("상품을 삭제 하시겠습니까?")) {
  var chkid = new Array();
    $(".chk").each(function() {
      var chk = $(this).is(":checked");
      if(chk) {
        var line = { "id" : $(this).attr("id").substring(2)};
        chkid.push(line);
      }
    });
    var chkids = JSON.stringify(chkid);
  $.ajax({
    url: "/goodsmng/deletechkgoods",
    type: "POST",
    data: {"chkids":chkids },
    success: function (json) {

    }
  });

    setTimeout(function() {
      location.reload();
      }, 500);
}
}

function rollback() {
if(confirm("판매 단가를 수정 하시겠습니까?")) {
    $(".chk").each(function() {
      var chk = $(this).is(":checked");
      if(chk) {
          var goodid = $(this).attr("id").substring(2);

          $.ajax({
            url: "/goodsmng/rollback",
            type: "POST",
            data: {"goodsid":goodid },
            success: function (json) {

            }
          });
      }

    });

    setTimeout(function() {
      location.reload();
      }, 500);
}
}

function numberWithCommas(obj, value) {
value = value.replace(/[^0-9]/g, '');		// 입력값이 숫자가 아니면 공백
value = value.replace(/,/g, '');			// ,값 공백처리
$(obj).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
}

function onmodify_id(goodid) {
var stock = null;
var description = $("#description_" + goodid).val();
var option = $("#option_" + goodid).val();
var coo = $("#coo_" + goodid).val();
var unit_price = $("#unit_price_" + goodid).val();

if ($("#g_stock_" + goodid).length > 0) {
  stock = $("#g_stock_" + goodid).val();
}

//if (unit_price == "") {	unit_price = "0"; }
var dc_unit_price = $("#dc_unit_price_" + goodid).val();

unit_price = unit_price.replace(/,/g, '');
dc_unit_price = dc_unit_price.replace(/,/g, '');

  $.ajax({
    url: "/goodsmng/modifygoods",
    type: "POST",
    data: {"goodsid":goodid, "description":description, "option":option, "coo":coo, "unit_price":unit_price, "dc_unit_price":dc_unit_price, "stock":stock },
    success: function (json) {
    alert("수정이 완료되었습니다.");
    }
  });

  setTimeout(function() {
    location.reload();
  }, 500);


}

function ondelete_id(goodid) {
    if(confirm("상품을 삭제 하시겠습니까?")) {
      $.ajax({
        url: "/goodsmng/deletegoods",
        type: "POST",
        data: {"goodsid":goodid },
        success: function (json) {

        }
      });

      setTimeout(function() {
        location.reload();
      }, 500);
  }

}

 function open_page(page) {

  var searchFor = $('#searchstr').val();
  var bc = $('#bigcategory').val();

  var form = document.createElement("form");
  document.body.appendChild(form);
  form.setAttribute("method", "get");
  form.setAttribute("action", "/goodsmng");

  var cfrsField = document.createElement("input");
  cfrsField.setAttribute("type", "hidden");
  cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
  cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
  form.appendChild(cfrsField);

  var pageField = document.createElement("input");
  pageField.setAttribute("type", "hidden");
  pageField.setAttribute("name", "page");
  pageField.setAttribute("value", page);
  form.appendChild(pageField);

  var ppField = document.createElement("input");
  ppField.setAttribute("type", "hidden");
  ppField.setAttribute("name", "per_page");
  ppField.setAttribute("value", $("#g_per_page").val());
  form.appendChild(ppField);

  var g_dp_period = document.createElement("input");
  g_dp_period.setAttribute("type", "hidden");
  g_dp_period.setAttribute("name", "g_dp_period");
  g_dp_period.setAttribute("value", $("#g_dp_period").val());
  form.appendChild(g_dp_period);

  var bigcategory = document.createElement("input");
  bigcategory.setAttribute("type", "hidden");
  bigcategory.setAttribute("name", "bigcategory");
  bigcategory.setAttribute("value", $("#g_category1").val());
  form.appendChild(bigcategory);

  var subcategory = document.createElement("input");
  subcategory.setAttribute("type", "hidden");
  subcategory.setAttribute("name", "subcategory");
  subcategory.setAttribute("value", $("#g_subcategory").val());
  form.appendChild(subcategory);

  var goodscode = document.createElement("input");
  goodscode.setAttribute("type", "hidden");
  goodscode.setAttribute("name", "goodscode");
  goodscode.setAttribute("value", $("#goodscode").val());
  form.appendChild(goodscode);

  var goodsname = document.createElement("input");
  goodsname.setAttribute("type", "hidden");
  goodsname.setAttribute("name", "goodsname");
  goodsname.setAttribute("value", $("#goodsname").val());
  form.appendChild(goodsname);

  var dp_status = document.createElement("input");
  dp_status.setAttribute("type", "hidden");
  dp_status.setAttribute("name", "g_dpstatus");
  dp_status.setAttribute("value", $("#g_dpstatus").val());
  form.appendChild(dp_status);

  var img_status = document.createElement("input");
  img_status.setAttribute("type", "hidden");
  img_status.setAttribute("name", "g_img_flag");
  img_status.setAttribute("value", $("#g_img_flag").val());
  form.appendChild(img_status);

  var res_flag = document.createElement("input");
  res_flag.setAttribute("type", "hidden");
  res_flag.setAttribute("name", "g_reserver_flag");
  res_flag.setAttribute("value", $("#g_reserver_flag").val());
  form.appendChild(res_flag);

 //     var searchForField = document.createElement("input");
 //     searchForField.setAttribute("type", "hidden");
 //     searchForField.setAttribute("name", "searchstr");
 //     searchForField.setAttribute("value", searchFor);
 //     form.appendChild(searchForField);

     form.submit();
 }

 function download() {

var searchFor = $('#searchstr').val();
var bc = $('#bigcategory').val();

var form = document.createElement("form");
document.body.appendChild(form);
form.setAttribute("method", "get");
form.setAttribute("action", "/goodsmng/download");

var cfrsField = document.createElement("input");
cfrsField.setAttribute("type", "hidden");
cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
form.appendChild(cfrsField);

var ppField = document.createElement("input");
ppField.setAttribute("type", "hidden");
ppField.setAttribute("name", "per_page");
ppField.setAttribute("value", $("#g_per_page").val());
form.appendChild(ppField);

var g_dp_period = document.createElement("input");
g_dp_period.setAttribute("type", "hidden");
g_dp_period.setAttribute("name", "g_dp_period");
g_dp_period.setAttribute("value", $("#g_dp_period").val());
form.appendChild(g_dp_period);

var bigcategory = document.createElement("input");
bigcategory.setAttribute("type", "hidden");
bigcategory.setAttribute("name", "bigcategory");
bigcategory.setAttribute("value", $("#g_category1").val());
form.appendChild(bigcategory);

var subcategory = document.createElement("input");
subcategory.setAttribute("type", "hidden");
subcategory.setAttribute("name", "subcategory");
subcategory.setAttribute("value", $("#g_subcategory").val());
form.appendChild(subcategory);

var goodscode = document.createElement("input");
goodscode.setAttribute("type", "hidden");
goodscode.setAttribute("name", "goodscode");
goodscode.setAttribute("value", $("#goodscode").val());
form.appendChild(goodscode);

var goodsname = document.createElement("input");
goodsname.setAttribute("type", "hidden");
goodsname.setAttribute("name", "goodsname");
goodsname.setAttribute("value", $("#goodsname").val());
form.appendChild(goodsname);

var dp_status = document.createElement("input");
dp_status.setAttribute("type", "hidden");
dp_status.setAttribute("name", "g_dpstatus");
dp_status.setAttribute("value", $("#g_dpstatus").val());
form.appendChild(dp_status);

var img_status = document.createElement("input");
img_status.setAttribute("type", "hidden");
img_status.setAttribute("name", "g_img_flag");
img_status.setAttribute("value", $("#g_img_flag").val());
form.appendChild(img_status);

var res_flag = document.createElement("input");
res_flag.setAttribute("type", "hidden");
res_flag.setAttribute("name", "g_reserver_flag");
res_flag.setAttribute("value", $("#g_reserver_flag").val());
form.appendChild(res_flag);

//     var searchForField = document.createElement("input");
//     searchForField.setAttribute("type", "hidden");
//     searchForField.setAttribute("name", "searchstr");
//     searchForField.setAttribute("value", searchFor);
//     form.appendChild(searchForField);

 form.submit();
}
</script>
<script>
 $.datepicker.setDefaults({
   closeText: "닫기",
   prevText: "이전달",
   nextText: "다음달",
   currentText: "오늘",
   monthNames: ["1월", "2월", "3월", "4월", "5월", "6월",
     "7월", "8월", "9월", "10월", "11월", "12월"
   ],
   monthNamesShort: ["1월", "2월", "3월", "4월", "5월", "6월",
     "7월", "8월", "9월", "10월", "11월", "12월"
   ],
   dayNames: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
   dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
   dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
   weekHeader: "주",
   dateFormat: "yy.m.d",
   firstDay: 0,
   isRTL: false,
   showMonthAfterYear: true,
   yearSuffix: "년"
 })

 $("#datepicker1").datepicker({
   minDate: 0,
   /* to use in wix */
   onSelect: function (selected, event) {
     window.parent.postMessage(selected, "*");
   }

 })
 $("#datepicker2").datepicker({
   minDate: 0,
   /* to use in wix */
   onSelect: function (selected, event) {
     window.parent.postMessage(selected, "*");
   }

 })
 $("#datepicker3").datepicker({
   minDate: 0,
   /* to use in wix */
   onSelect: function (selected, event) {
     window.parent.postMessage(selected, "*");
   }

 })
</script>

</div>
