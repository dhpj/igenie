    <form action="/biz/coupon/img_list" method="post" id="mainForm">
        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
        <div class="content_wrap" style="width:800px">
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget">
                        <div class="widget-content">
                            <table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
                                <tbody>
                                    <div class="thumb">
                                    <ul class="thumblist">
                                    <?foreach($list as $r) {?>
                                        <li style="text-align:center;">
                                        <div align="center" style="border: 1px solid black; width: 220px; height: 180px; margin-top: 20px; margin-bottom: 10px;" onclick="img_detail(<?=$r->img_id?>);">
                                            <img align="center" src="<?=$r->img_url?>" style="cursor: pointer;"/>
                                            <input type="hidden" id="img_<?=$r->img_id?>" value="<?=$base_url?>/pop/<?=$r->img_filename?>">
                                        </div>
                                        <input type="checkbox" class="uniform" name="chk_image[]"
                                               value="<?=urlencode($r->img_filename."|".$r->img_id)?>"
                                               onclick="javascript:check_image('chk_image[]')"></li>
												<?}?>
                                </ul>
                                </div>
                                </tbody>
                            </table>
                            <div class="align-center mt30"><?=$page_html?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--content_wrap-->
    </form>

    <style type="text/css">
        #myModal {
            vertical-align: middle;

            transform: translateY(-50%);
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        .thumblist {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .thumblist li {

            float: left;
            height: auto;
            width: 240px;
            max-height: fit-content;
            margin: 5px;
            overflow: hidden;

        }

        .thumblist img {
            vertical-align: middle;
            display: inline-block;
            height: 100%;
            -ms-interpolation-mode: bicubic; /* Better image scaling in IE */
        }

        p {
            clear: both
        }

    </style>

    <script type="text/javascript">
        $("#nav li.nav10").addClass("current open");
        //예-아니오에서의 확인버튼
        function click_btn_primary() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }

        //CSRF token획득
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }


        //이미지 상세보기
        function img_detail(obj) {
            var img_url = $("#img_"+obj).val();
            var html = '<div align="center"><table class="tpl_ver_form"><tr><th style="width:90px;">이미지 URL</th><td style="word-break: break-all;">' + img_url + '</td></tr></table><br><br><img align="center" src="' + img_url + '" style="width:100%;"/></div>';
            $(".content").html(html);
            $(".modal-dialog").css("width","420px").css("height","450px").css("top","10%"); //TODO 높이설정
            $("#myModal").modal('show');

        }

        //선택된 이미지 이름 확인
        function check_image(obj) {
            var sum = 0, tag = [];
            var chk = document.getElementsByName(obj);
            var tot = chk.length;
            for (var i = 0; i < tot; i++) {
                if (chk[i].checked == true) {
                    var url = chk[i].value;
                    tag[sum] = url.slice(url.lastIndexOf('/') + 1);
                    sum++;
                }
            }

            return tag
        }

        function img_open_page(page) {
            var form = document.getElementById('mainForm');
            var cfrsField = document.createElement("input");
            var pageField = document.createElement("input");
				cfrsField.setAttribute("type", "hidden");
				cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            form.submit();

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

        $('#mainForm').submit(function () {
            $('#overlay').fadeIn();
            return true;
        });

    </script>
