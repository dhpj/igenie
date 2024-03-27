<style>
td .btn {font-size: 12px; cursor: pointer; background-color:#f3f3f3;}
.dh_modal {
    display: none;
    position: fixed;
    z-index: 10000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.4);
}
.modal-content {
    position: absolute;
    top:50%;
    left:50%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 200px;
    padding: 40px 50px;
}
.modal-content span{font-size:16px;}
.modal-content #check_name{
    margin:10px 0;
}
.modal-content input[type="button"]{
    background-color:#f3f3f3;
    cursor: pointer;
}
</style>

<div class="tit_wrap">
	고객의 소리 목록
</div>
<div id="mArticle">
    <div class="form_section">
        <div class="white_box">
            <div class="board">
                <div class="table_list">
                    <table>
                        <colgroup>
                            <col width="200px">
                            <col width="*">
                            <col width="100px">
                            <col width="150px">
                            <col width="150px">
                            <col width="150px">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>업체명</th>
                                <th>제목</th>
                                <th>파일수</th>
                                <th>작성자</th>
                                <th>작성날짜</th>
                                <th>확인</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?if (!empty($lists)){?>
                                <?foreach($lists as $key => $a){?>
                            <tr>
                                <td><?=$a->v_mem_username?></td>
                                <td class="tl">
                                    <a href="http://igenie.co.kr/voc/write/<?=$a->v_id?>" style="" title="<?=$a->v_title?>"><?=$a->v_title?></a>
                                </td>
                                <td>
                                    <?
                                        if (!empty($a->v_ufilename0) && !empty($a->v_ufilename1)){
                                            echo 2;
                                        } else if ((!empty($a->v_ufilename0) && empty($a->v_ufilename1)) || (empty($a->v_ufilename0) && !empty($a->v_ufilename1))){
                                            echo 1;
                                        } else {
                                            echo 0;
                                        }
                                    ?>
                                </td>
                                <td><?=$a->v_writer?></td>
                                <td><?=$a->v_insert_date?></td>
                                <td>
                                    <?if (!$a->v_check_flag){?>
                                        <input type="button" class="btn" value="확인" onclick="check_admin(<?=$a->v_id?>)">
                                    <?} else {?>
                                        <?=$a->v_check_name?>
                                    <?}?>
                                </td>
                            </tr>
                            <?}?>
                            <?} else {?>
                                <tr>
                                    <td rowspan=4>nothing</td>
                                </tr>
                            <?}?>
                        </tbody>
                    </table>
                </div>
                <!-- <div style="text-align: center">
                    <div class="searchbox">
                        <form class="navbar-form" style="padding: 0; margin: 0;" action="http://igenie.co.kr/board/notice" onsubmit="return postSearch(this);">
                            <input type="hidden" name="findex" value="">
                            <input type="hidden" name="category_id" value="">
                            <div class="form-group">
                                <select class="" name="sfield">
                                    <option value="post_both">제목+내용</option>
                                    <option value="post_title">제목</option>
                                    <option value="post_content">내용</option>
                                    <option value="post_nickname">회원명</option>
                                    <option value="post_userid">회원아이디</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Search" name="skeyword" value="">
                                <button class="btn md" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div> -->
                <div class="page_cen">
                  <?=$page_html?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="check_modal dh_modal" style="display:none">
    <div class="modal-content">
        <span>담당자</span>
        <input type="text" id="check_name" onkeypress="if(event.keyCode == 13) saved_check();">
        <input type="button" value="작성" onclick="saved_check()">
        <input type="button" value="취소" onclick="close_modal()">
    </div>
</div>

<script>
    var check_id = 0;
    function check_admin(id){
        check_id = id;
        $(".check_modal").css("display", "block");
        $("#check_name").focus();
        return;
    }
    function saved_check(){
        $.ajax({
            url: '/voc/saved_check',
            method: 'POST',
            data: {
                "<?= $this->security->get_csrf_token_name() ?>" : "<?= $this->security->get_csrf_hash() ?>",
                "id" : check_id,
                "check_name" : $("#check_name").val(),
            },
            success: function(response) {
                if (response > 0){
                    alert("체크되었습니다.")
                    location.reload();
                } else {
                    alert("체크 실패되었습니다. 관리자에게 문의해주세요.")
                }
            },
            error: function(xhr, status, error) {
                alert("체크 실패되었습니다. 관리자에게 문의해주세요.")
            }
        });
    }
    function close_modal(){
        return $(".check_modal").css("display", "none");
    }
</script>
