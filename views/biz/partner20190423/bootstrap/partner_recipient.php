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
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/partner/list">파트너</a></li>
            <li class="current"><a href="#" title="">파트너 상세보기</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <form action="#">
                    <div class="widget">
                        <div class="widget-content">
                            
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle !important;">계정</th>
                                    <td><?=$rs->mem_userid?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">업체명</th>
                                    <td><?=$rs->mem_username?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">잔액</th>
                                    <td>₩<?=number_format($rs->mem_point + $rs->total_deposit, 1)?> &nbsp; [예치금 : <?=number_format($rs->total_deposit, 1)?>, 포인트 : <?=number_format($rs->mem_point, 1)?>] &nbsp; &nbsp; (사용가능: <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($this->Biz_model->getAbleCoin($rs->mem_id, $rs->mem_userid), 2); ?></strong></font>)</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <div class="snb_nav">
        <ul class="clear">
            <li><a href="/biz/partner/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li><a href="/biz/partner/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li class="active"><a href="/biz/partner/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
        </ul>
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

    <div class="row">
        <div class="col-xs-12">
            <form action="#">
                <div class="widget">
                    <div class="widget-content" id="customer_list">

                    </div>
                    <div class="mt30 align-center">
                        <a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="submit btn btn-custom">수정</a>
                    </div>
                </div>
            </form>
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
               "/biz/partner/inc_recipient_lists?<?=$param['key']?>",
					{
						"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
						"search_type": type,
						"search_for": searchFor,
						'page': page
					}
            );
        }
    </script>
