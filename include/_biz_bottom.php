
            <div id="footer">
                <address>
                    (주)스윗트래커 ㅣ 사업자등록번호 : 107-87-62216 ㅣ 대표이사 : 김영준 ㅣ 대표전화 : 1644-1201 ㅣ 팩스 : 02-6442-1656 <br>
                    주소 : 서울특별시 중구 퇴계로 18. 대우재단빌딩 9층 ㅣ 이메일 : alimtalk@sweettracker.co.kr
                </address>
                <p>Copyrightⓒ Sweettracker Corp. All Rights Reserved.</p>
            </div><!--#footer-->
        </div><!--//.container-->
    </div><!--//#content-->
</div><!--#wrap-->


     
    <!-- Smartphone Touch Events -->
    <script type="text/javascript" src="/bizM/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.event.move.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.event.swipe.js"></script>

    <!-- General -->
    <script type="text/javascript" src="/bizM/js/breakpoints.js"></script>
    <script type="text/javascript" src="/bizM/js/respond.min.js"></script>
    <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
    <script type="text/javascript" src="/bizM/js/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.slimscroll.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.slimscroll.horizontal.min.js"></script>

    <!-- Forms -->
    <script type="text/javascript" src="/bizM/js/typeahead.min.js"></script>
    <!-- AutoComplete -->
    <script type="text/javascript" src="/bizM/js/jquery.autosize.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.inputlimiter.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.uniform.min.js"></script>
    <!-- Styled radio and checkboxes -->
    <script type="text/javascript" src="/bizM/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="/bizM/js/select2.min.js"></script>
    <!-- Styled select boxes -->
    <script type="text/javascript" src="/bizM/js/fileinput.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.duallistbox.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="/bizM/js/wysihtml5.min.js"></script>
    <script type="text/javascript" src="/bizM/js/bootstrap-wysihtml5.min.js"></script>
    <script type="text/javascript" src="/bizM/js/bootstrap-multiselect.min.js"></script>
    <script type="text/javascript" src="/bizM/js/bootstrap-switch.min.js"></script>

    <!-- Page specific plugins -->
    <!-- Charts -->
    <!--[if lt IE 9]>
		<script type="text/javascript" src="plugins/flot/excanvas.min.js"></script>
	<![endif]-->
    <script type="text/javascript" src="/bizM/js/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.tooltip.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.orderBars.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.selection.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.growraf.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.easy-pie-chart.min.js"></script>


    <script type="text/javascript" src="/bizM/js/moment.min.js"></script>
    <script type="text/javascript" src="/bizM/js/daterangepicker.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.blockUI.min.js"></script>


    <!-- Form Validation -->
    <script type="text/javascript" src="/bizM/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/bizM/js/additional-methods.min.js"></script>

    <!-- Noty -->
    <script type="text/javascript" src="/bizM/js/jquery.noty.js"></script>
    <script type="text/javascript" src="/bizM/js/top.js"></script>
    <script type="text/javascript" src="/bizM/js/default.js"></script>

    <!-- App -->
    <script type="text/javascript" src="/bizM/js/app.js"></script>
    <script type="text/javascript" src="/bizM/js/plugins.js"></script>
    <script type="text/javascript" src="/bizM/js/plugins.form-components.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            "use strict";

            App.init(); // Init layout and core plugins
            Plugins.init(); // Init all plugins
            FormComponents.init(); // Init all form-specific plugins

            $('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                // startView: 1,
                todayBtn: "linked",
                language: "kr",
                orientation: "top auto",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                todayHighlight: true
            });

            $("[data-toggle='tooltip']").tooltip();


        });
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            $('.select2').select2();
        });
    </script>
    <!-- Demo JS -->
    <script type="text/javascript" src="/bizM/js/custom.js"></script>
    <script type="text/javascript" src="/bizM/js/form_validation.js"></script>




<style>
    #overlay {
        background: rgba(255, 255, 255, .8) 50% 50% no-repeat;
        color: #666666;
        position: fixed;
        height: 100%;
        width: 100%;
        z-index: 5000;
        top: 0;
        left: 0;
        float: left;
        text-align: center;
        padding-top: 25%;
        opacity: 1.0;
    }
</style>

<div id="overlay" style="display: none">
    <img src="/collected_statics/assets/img/loader.gif" alt="Loading"/><br/>
    <p id="loading_ptag">Loading...</p>
</div>
</body></html>