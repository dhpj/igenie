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
                            <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 타이틀 영역 -->
	<div class="tit_wrap">
		파트너 관리
	</div>
<!-- 타이틀 영역 END -->
    <div class="account_tab">
        <ul>
            <li><a href="/biz/partner2/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner2/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li><a href="/biz/partner2/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li class="selected"><a href="/biz/partner2/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
        </ul>
    </div>
	<div id="mArticle">
		<div class="form_section">
			<div class="inner_tit">
				<h3>고객리스트</h3>
			</div>
			<div class="inner_content">
				<form action="#">
				<div class="widget-content" id="customer_list"></div>
				<div class="mt30 align-center">
					<a type="button" href="/biz/partner2/edit?<?=$param['key']?>" class="submit btn btn-custom">수정</a>
				</div>
				</form>
			</div>
		</div>
	</div>
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
                            <button type="button" class="btn btn-custom" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-no-results {
            display: none !important;
        }

        textarea {
            resize: none;
        }
    </style>
    <script type="text/javascript">
        $("#nav li.nav60").addClass("current open");

        $("#wrap").css('position', 'absolute');
        $(".modal-content").css('width', '320px');
        $(".modal-body").css('height', '120px');
        $("#myModal").css('margin-left', '40%');
        $("#myModal").css('margin-top', '15%');
        $("#myModal").css('overflow-x', 'hidden');
        $("#myModal").css('overflow-y', 'hidden');

		  $(document).ready(function() {
				open_page(1);
		  });

        function open_page(page, delIds) {
				var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();
            $('#customer_list').html('').load(
               "/biz/partner2/inc_recipient_lists?<?=$param['key']?>",
					{
						"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
						"search_type": type,
						"search_for": searchFor,
						'page': page
					}
            );
        }
    </script>
