<link rel="stylesheet" href="/css/animate.css">
<script src="/js/wow.min.js"></script>
<script>
new WOW().init();
</script>
<div class="voucher_wrap">

	<div class="voucher_mbox3">
		<div class="voucher_mbox5 pay_card" id="voucher">
			<div class="content">
				<p class="b_tit1">지니 카드결제 기능 신청하기</p>
                <?
                $parent_id = $this->funn->getParent($this->member->item('mem_id'));
                $plustxt = "";
                if($this->member->item('mem_id')==895||$parent_id==895){
                    $plustxt = " 빅포스";
                }
                 ?>
                <button class="btn_myModal2" style="margin-right:138px;"  onClick="window.open('/uploads/전자결제 신청서<?=$plustxt?>.pdf')">지니 카드결제 신청서 다운로드</button>
				<form id="voucher-form" action="/card/save" method="post"  enctype="multipart/form-data">
					<div class="voucher_form">
						<ul>
                            <input type=hidden name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>"/>
							<li>
								<span>이름(*)</span> <input type="text" id="user_name" name="user_name" placeholder="이름" class="">
							</li>
							<li>
								<span>전화번호(*)</span> <input type="text" id="tel" name="tel" placeholder="전화번호" class="" onKeyup="this.value=this.value.replace(/[^-0-9]/g,'');">
							</li>
							<li>
								<span>이메일</span> <input type="text" id="email" name="email" placeholder="이메일" class="">
							</li>
							<li>
								<span>업체명(*)</span> <input type="text" id="company_name" name="company_name" placeholder="업체명" class="">
							</li>
                            <li>
                      			<span class="tit">사업자등록증</span>
                      			<div class="text">
                                    <label for="img_file1" class="templet_img_box">
                                        <div id="div_preview1">
                                            <img id="img_preview1" style="display:block;width:100%">
                                        </div>
                                    </label>
                                </div>
                      			<input type="hidden">
                      			<input type="hidden">
                      			<input type="file" title="이미지 파일" name="img_file1" id="img_file1" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;" accept="image/*">
                  		    </li>
                            <li>
                      			<span class="tit">통장사본</span>
                      			<div class="text">
                                    <label for="img_file2" class="templet_img_box">
                                        <div id="div_preview2">
                                            <img id="img_preview2" style="display:block;width:100%">
                                        </div>
                                    </label>
                                </div>
                      			<input type="hidden">
                      			<input type="hidden">
                      			<input type="file" title="이미지 파일" name="img_file2" id="img_file2" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;" accept="image/*">
                  		    </li>
                            <li>
                      			<span class="tit">신청서</span>
                      			<div class="text">
                                    <label for="img_file3" class="templet_img_box">
                                        <div id="div_preview3">
                                            <img id="img_preview3" style="display:block;width:100%">
                                        </div>
                                    </label>
                                </div>
                      			<input type="hidden">
                      			<input type="hidden">
                      			<input type="file" title="이미지 파일" name="img_file3" id="img_file3" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;" accept="image/*">
                  		    </li>
							<li>
								<span>문의내용</span>
								<textarea id="content" name="content" cols="100" rows="6" placeholder="문의내용"></textarea>
							</li>
						</ul>
						<input type="hidden" name="state" value="신청">
					</div>
					<p>
						<button id="voucher_submit_btn">신청하기</button>
					</p>
				</div>
			</div>
		</form>
		<div class="voucher_bot">
			<div class="cer_logo">
				<ul>
					<li><img src="/images/logo_innobiz.png" alt="" /></li>
					<li><img src="/images/logo_cert1.png" alt="" /></li>
					<li><img src="/images/logo_cert2.png" alt="" /></li>
					<li><img src="/images/logo_cert3.png" alt="" /></li>
					<li><img src="/images/logo_cert4.png" alt="" /></li>
					<li><img src="/images/logo_cert.png" alt="" /></li>
				</ul>
			</div>
			<div class="content">
				사업자등록번호 : 364-88-00974 | 통신판매업 : 신고번호 2018-창원의창-0272호 |
				특수한 유형의 부가통신사업자 등록번호: 제 3-02-19-0001호 | 대표이사 : 송종근 <br />
				Copyright © DHN Corp. All rights reserved.
			</div>
		</div>
	</div>

<script>
	$(function() {
		$('#voucher_submit_btn').click(function(){
			if($('#user_name').val() == ''){
				alert('이름 입력해주세요.');
				$('#user_name').focus();
				return false;
			}
			if($('#tel').val() == ''){
				alert('전화번호를 입력해주세요.');
				$('#tel').focus();
				return false;
			}
			if($('#company_name').val() == ''){
				alert('업체명을 입력해주세요.');
				$('#company_name').focus();
				return false;
			}
            if($('#img_preview1').css('background') == ''){
                alert('사업자등록증 사진을 첨부해주세요.');
				return false;
            }
            if($('#img_preview2').css('background') == ''){
                alert('통장사본 사진을 첨부해주세요.');
				return false;
            }
            if($('#img_preview3').css('background') == ''){
                alert('신청서 사진을 첨부해주세요.');
				return false;
            }
			return true;
		});
	});

    //이미지 선택 클릭시
	$("#img_file1").change(function() {
		//alert("#img_file_change");
		if(this.value.length > 0) {
			//alert("this.value : "+ this.value);
			if (this.files && this.files[0]) {
				remove_img(1);
				readURL(this, "div_preview1");
			}
		}
	});

    //이미지 선택 클릭시
	$("#img_file2").change(function() {
		//alert("#img_file_change");
		if(this.value.length > 0) {
			//alert("this.value : "+ this.value);
			if (this.files && this.files[0]) {
				remove_img(2);
				readURL(this, "div_preview2");
			}
		}
	});

    //이미지 선택 클릭시
	$("#img_file3").change(function() {
		//alert("#img_file_change");
		if(this.value.length > 0) {
			//alert("this.value : "+ this.value);
			if (this.files && this.files[0]) {
				remove_img(3);
				readURL(this, "div_preview3");
			}
		}
	});

    //배경 이미지 초기화
	function remove_img(seq){
		$("#img_preview" + seq).attr("src", "");
		$("#img_preview" + seq).css("display", "none");
		$("#div_preview" + seq).css({"background":"url('')"});
		$("#div_preview" + seq).addClass("templet_img_box2");
	}

    //이미지 경로 세팅
	function readURL(input, divid) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid);
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+divid).css({"background":"url(" + e.target.result + ")"});
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

</script>
