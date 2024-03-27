    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/main/">홈</a></li>
            <li><a href="/myinfo/charge#">내 정보</a></li>
            <li class="current"><a href="/myinfo/charge#" title="">충전</a></li>
        </ul>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br><br>
                            <button type="button" id="btn_confirm" name="btn_confirm" class="btn btn-primary" data-dismiss="modal">확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal select fade" id="agree_contract" role="dialog" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-contract modal-dialog modal-lg select-dialog" id="modal">
            <div class="modal-content">
                <br>
                <h4 class="modal-title" align="center">비즈엠 서비스 동의</h4>
                <div class="modal-body select-body">
                    <form action="/myinfo/charge#" method="post" id="agree_contract_form" class="form" role="form">
                        <input type="hidden" name="csrfmiddlewaretoken" value="1G1LSwPoiJMSibYZ0K0phAnotyEjKsS9">
                        <div class="form-group clearfix">
                            <textarea id="textarea_agree" name="textarea_agree" class="form-control" readonly="" rows="10" cols="100" style="cursor: default;">비즈엠 서비스계약서

이용자(이하 “갑”이라 한다)와 주식회사 스윗트래커(이하 “을”이라 한다)는 “을”이 공급하는 카카오 비즈메시지 및/또는 문자메시지(이하 “비즈엠”이라 한다)를 “갑”의 “수신자”에게 정보를 발송함에 있어 다음과 같이 비즈엠 공급계약서 (이하 “본 계약”이라 한다)을 체결하고 상호신뢰의 원칙 하에 이를 성실히 수행한다.

제1조 [목 적]
본 계약은 “갑”이 “수신자”에게 “을”이 제공하는 “비즈엠”을 이용하여 정보를 발송하기 위해 필요한 제반 사항들을 정하기 위해 체결되었다.

제2조 [정 의]
본 계약에서 사용되는 각 용어의 정의는 다음 각 호와 같다.
①	“비즈엠”이란 “갑”의 “수신자”(제2호에 정함)에게 “정보”(제3호에 정함) 및 “광고”(제7호에 정함)를 “카카오톡”(제4호에 정함) 또는 “문자메시지”를 통해 발송하는 서비스로서 “을”이 ㈜카카오로부터 “공식딜러”(제5호에 정함)의 자격을 부여 받아 “갑”에게 공급하는 서비스를 말한다.
②	“수신자”라 함은 “갑”과 거래 또는 계약관계에 있거나, “갑”으로부터 정보 및/또는 광고를 제공받기로 동의하여 “갑”의 정보 및/또는 광고를 수신하는 자를 의미한다.
③	“정보”란 “갑”이 “수신자”와 거래 또는 계약관계에 있어 “수신자”가 인지하여야 할 필요성이 있는 재화, 서비스 및 이와 관련된 정보로서, 상업적 목적의 홍보성/광고성 정보를 포함하지 않아야 한다. 이는 “비즈엠” 중 알림톡서비스 및/또는 문자메시지로 발송이 가능하다.
④	“카카오톡”이란 주식회사 카카오에서 제공하고 있는 모바일 채팅 메신저이다.
⑤	“공식딜러”란 “비즈엠”의 영업과 판매를 ㈜카카오로부터 위탁 받아 제반 업무를 수행할 수 있도록 ㈜카카오와 계약을 체결한 자를 말한다.
⑥	“템플릿”이란 “비즈엠” 발송에 반복적으로 사용하는 문구를 일정한 작성 규칙에 따라 고정적인 표현 영역과 가변적인 표현 영역을 구분하여 표시한 것이다.
⑦	“광고”란 “카카오톡”의 플러스친구 또는 옐로아이디로 친구를 맺은 “갑”에게 홍보/광고 정보를 발송하는 것을 말하며, 이는 “비즈엠” 중 친구톡서비스 및/또는 문자메시지로 발송이 가능하다.
⑧	“선불충전”이란 “갑”이 “을”의 “비즈엠”을 이용하기 위하여 ”을”이 정한 “비즈엠” 요금제에 정해진 사용금액을 선결제한 후 결제금액에 해당하는 “비즈엠”을 이용하는 것을 말한다.

제3조 [서비스 내용 및 서비스 범위]
“을”은 “갑”에게 “비즈엠”를 제공하고 “비즈엠”의 정상 운영을 위하여 지속적으로 유지 보수하며, “갑”은 “을”의 서비스 제공에 대한 대가로 서비스 수수료를 지급한다. 본 계약에서 명시하는 “비즈엠”의 서비스 범위는 다음 각 호와 같다.
①	“비즈엠” 중 알림톡서비스는 “카카오톡”을 기반으로 “정보”를 푸시메시지로 전달하며 “갑”의 요청에 따라 문자메시지로 대체 발송할 수 있다.
②	“비즈엠” 중 알림톡서비스는 한글/영어/숫자 구분 없이 띄어쓰기를 포함하여 일천(1,000) 자 이내를 지원한다.
③	“비즈엠” 중 알림톡서비스로 발송할 수 있는 푸시메시지의 내용은 상품/서비스의 주문/예약/결제/배송, 입출금, 멤버쉽포인트, 서비스기간/서비스 시간 안내 등 정보성 메시지에 국한하며, 상업적 목적의 홍보성/광고성 정보는 포함할 수 없다.
④	“비즈엠”는 발송 내용을 사전에 템플릿으로 등록해야 하며 템플릿을 기반으로 발송이 가능하다. 이 내용에 상업적 목적의 홍보성/광고성 정보가 포함될 경우 ㈜카카오에서 검수 과정을 통해 임의로 편집, 수정, 삭제할 수 있다.
⑨	“비즈엠” 중 친구톡서비스는 “카카오톡” 상에서 친구를 맺은 “수신자”에 한하여 “정보” 및 “광고”의 발송이 가능하며, ㈜카카오의 템플릿 검수를 받지 아니한다. 단, 친구톡서비스를 제공함에 있어 “수신자”가 “카카오톡” 상의 친구 관계임에도 불구하고 고객센터 등을 통해 포괄적인 마케팅 정보 수신 거부의사를 밝힌 경우에는 해당 “사용자”는 친구톡서비스 발송 대상에서 제외되며, 모든 “사용자”에 대하여 당일 20시부터 익일 08시 사이에는 친구톡서비스 발송이 제한된다.

제4조 [계약 기간]
1.	본 계약의 계약기간은 “갑”이 “선불충전”을 완료한 시점부터 “선불충전”한 금액을 전부 사용할 때까지로 한다.
2.	“갑”은 “선불충전”을 함으로써 “선불충전”을 완료한 시점에 별도의 기명날인 절차 없이 “을”과 본 계약을 체결함을 “갑”이 인정하고 수락한 것으로 간주된다. 또한, “갑”이 “비즈엠”을 이용함으로써 “선불충전”한 금액의 잔액이 0(zero)이 되는 시점에 별도의 절차 없이 “을”과 합의 하에 본 계약을 종료함을 “갑”이 인정하고 수락한 것으로 간주된다. 만약 “선불충전”한 금액의 잔액이 0(zero)가 된 이후에 “갑”이 새롭게 “선불충전”을 한 경우 해당 “선불충전”을 완료한 시점에 본 조 첫 문장에 따라 계약이 새롭게 체결된 것으로 간주된다.
3.	본 조 제1항 내지 제2항에도 불구하고, “갑”이 서면으로 본 계약의 종료를 “갑”에게 통지할 경우 “갑”과 “을:은 상호 합의 하에 본 계약을 종료할 수 있다. 이 경우 “을”은 “갑”에게 “선불충전”한 금액의 잔액을 통지하고, 계약 종료일로부터 십(10) 영업일 이내에 “갑”이 지정한 은행 계좌로 잔액을 송금한다.
4.	“갑”이 “선불충전”한 금액의 잔액이 존재함에도 불구하고 “비즈엠” 서비스를 마지막으로 이용한 날로부터 5년이 되는 날(이하 “장기 미사용기간”이라 한다)까지 “비즈엠” 서비스를 이용하지 않을 경우 “을”은 “장기 미사용기간”이 경과한 익일에 “갑”에 대한 별도의 통지 또는 절차 없이 본 계약을 종료할 수 있다. 이 경우 ”선불충전”한 금액의 잔액은 “을”에게 귀속되며, “갑”에 대한 반환의 의무는 없다.

제5조 [상호 협조]
1.	“갑”은 “비즈엠”을 통해 “정보” 및/또는 “광고”를 제공받는 “수신자”에게 “정보” 및/또는 “광고”를 “비즈엠”을 통해 발송한다는 내용을 “갑”의 홈페이지에 게시하거나 “수신자”에게 개별적으로 통지하는 등 관련 법률 및/또는 “갑”의 약관 등에 부합하도록 “수신자”에 대한 적절한 고지의무를 이행하여야 한다. 만약 “갑”이 고지의무를 이행하지 않아 발생하는 “수신자” 및/또는 제3자와의 분쟁, 법률책임 등에 있어 “갑”은 자신의 책임과 비용으로 “을”을 면책해야 한다.
2.	“갑”은 “비즈엠”의 "템플릿"을 영업일 기준으로 “비즈엠” 발송일로부터 24시간 전까지 “을”에게 전달해야 한다. 이는 "템플릿"의 내용 변경 시에도 동일하게 적용된다.
3.	“을”은 “비즈엠”을 통해 “갑”의 “수신자”에게 발송되는 "정보" 및/또는 “광고” 등 “비즈엠”에 포함된 내용과 관련하여 해당 사실의 존부 및 진정성, 내용의 품질, 신뢰도, 완전성, 적법성 및 타인의 권리에 대한 비침해성 등에 대하여 보증하지 아니하며, 이와 관련한 일체의 위험과 책임은 “갑”이 전적으로 부담한다.
4.	“을”은 신의 성실의 원칙에 따라 안정적인 “비즈엠” 서비스를 제공할 수 있도록, 최우선적으로 유지보수를 수행한다.

제6조 [무상유지보수]
1.	“을”은 “비즈엠”이 안정적으로 제공될 수 있도록 무상유지보수 하여야 한다.
2.	“을”은 원활한 유지보수 운영에 필수적으로 부수되는 부가적인 작업에 대하여 “갑”의 요청 시 이를 신속히 제공한다.
3.	“을”이 제공하는 무상유지보수의 범위는 아래와 같다.
①	장애 처리 서비스: “갑”의 “갑”에게 “비즈엠”을 발송함에 있어 작동 오류 내지 장애를 수정하고, 그 장애를 진단해주는 서비스
②	업그레이드 서비스: ㈜카카오의 방침에 따라 “비즈엠”의 서비스가 업그레이드 될 경우 이에 따른 성능 향상을 위한 업그레이드 서비스
③	관리지원 서비스: “갑”의 통상적인 업무 범위 내에서의 관련된 기술지원 및 자문 등을 제공하는 서비스

제7조 [서비스 비용 및 정산]
1.	“비즈엠” 이용에 대하여 “갑”은 서비스 수수료를 “선불충전” 한다.
2.	“을”은 “수신자”의 스마트폰, 휴대폰, 기타 모바일 단말기에 “비즈엠” 수신 성공이 확인될 경우 본 조 제3항의 단가를 “갑”의 “선불충전”에서 차감한다. 이 경우 수신 성공이란 “수신자”의 “비즈엠”의 확인 유무와는 상관 없이 “수신자”의 스마트폰, 휴대폰, 기타 모바일 단말기에 “비즈엠”이 도착한 것을 말한다.
3.	“을”은 “비즈엠” 수신 성공 건에 대하여 [별첨1]과 같이 서비스 수수료를 “갑”의 “선불충전” 금액에서 차감한다.
4.	“비즈엠” 서비스를 원활히 이용하기 위해서 “선불충전” 잔액을 적절히 유지할 책임은 “갑”에게 있다. “선불충전” 잔액의 부족으로 “갑”의 “비즈엠” 이용이 중단되거나 푸시메시지 및/또는 문자메시지의 일부 또는 전부가 발송되지 못하더라도 “을”은 이에 대해 책임을 지지 아니한다.

제8조 [목적물의 소유권]
본 계약으로 인하여 “을”이 개발한 “비즈엠” 발송 프로그램 및 "템플릿"에 대한 일체의 소유권 및 지적재산권은 “을”에게 있다.

제9조 [비밀 유지]
1.	본 계약 상의 업무 수행과 관련하여 “갑”과 “을”이 상대방으로부터 전달받은 영업정보, 고객정보, Know-How, 기타 영업비밀 등(이하 “영업비밀 등”)은 본 계약 상의 목적 이외에 사용하거나 사전에 상대방의 서면동의 없이 제3자에게 누설하거나 사용하게 할 수 없다.
2.	본 조항을 위반한 당사자는 상대방에 대하여 손해배상은 물론 민∙형사 및 행정상의 제반 책임을 부담한다.
3.	본 조의 규정은 본 계약기간 내는 물론 종료 후에도 1년 동안 유효하다.

제10조 (비즈엠 서비스의 중지)
1.	“을”은 다음 각 호에 해당하는 경우 “비즈엠” 서비스 제공을 중단하거나 일시적으로 중지 또는 제한할 수 있다.
①	“을”의 “비즈엠” 서비스용 설비의 보수 등 공사로 인한 불가피한 경우
②	기간통신사업자가 인터넷 연결 등의 전기통신 서비스를 중지했을 경우
③	“을”이 수익사업으로 “비즈엠” 서비스 사업을 더 이상 지속하지 못하는 상황의 경우
④	기타 “갑”이 제공하는 “템플릿”의 내용이 “을”의 “비즈엠” 서비스 운영방식에 부합되지 않거나 또는 “갑”이 본 계약의 각 조항을 고의 또는 중대한 과실로 위반하거나 이행하지 않은 경우
2.	본 조 제1항의 3호 내지 4호에 해당하는 사유가 발생할 경우 “을”은 “을”의 판단으로 본 계약을 해지할 수 있다. 이 경우 “을”은 “갑”에게 “선불충전”한 금액의 잔액을 통지하고, 계약 해지일로부터 십(10) 영업일 이내에 “갑”이 지정한 은행 계좌로 잔액을 송금한다

제11조 [기타]
1.	본 계약에 명시되지 않은 내용은 이용약관을 따른다. 본 계약과 이용약관의 내용에 차이가 있을 경우 본 계약이 우선한다.
2.	“갑”과 “을”은 본 계약 상의 권리와 의무를 제3자에게 양도 또는 담보로 제공하거나 처분할 수 없다
3.	본 계약 상의 의무를 불이행한 일방 당사자는 계약불이행으로 인하여 상대방에게 발생한 통상의 손해를 배상하여야 한다. 단, 천재지변, 비상사태, 법규 상의 제한, 공공기관의 명령 또는 행정지도, 법원의 판결, 기간통신사업자의 서비스 중지, ㈜카카오의 “비즈엠” 서비스의 중지 또는 이에 준하는 경우에는 당사자 상호간 손해에 대한 배상의 책임을 지지 아니한다.
4.	본 계약의 일부 또는 전부의 변경은 “갑”과 “을”의 서면 합의에 의해서만 가능하다.
5.	본 계약과 관련하거나 또는 본 계약에 명시되지 아니한 사항으로 분쟁이 발생할 경우, 서울중앙지방법원을 전속적 합의관할법원으로 한다.
6.	본 계약의 어느 규정이 행정, 입법, 사법 기타 명령 또는 결정에 의하여 무효, 위법 기타 집행불가능한 것으로 인정되는 경우, 나머지 규정들은 계속 유효, 적법하고 그 조건에 따라 집행이 가능한 것으로 인정된다. 그러한 무효, 위법 기타 집행불가능한 규정들은 그 조항의 원래 내용과 취지에 가능한 한 근접하면서도 무효, 위법 기타 집행불가능하지 않은 규정으로 대체된다.
7.	본 계약서에 기술된 내용은 본 계약과 관련하여 “갑”과 “을” 사이에 이루어진 최종적인 합의의 전부이다. 본 계약과 관련하여 “갑”과 “을” 사이에 이루어진 기존의 모든 서면 또는 구두의 합의는 그 효력이 소멸되며, 본 계약서의 내용으로 대체된다.

[별첨1]
“비즈엠” 서비스 이용료

■ 발송건수별 단가표
1. 발송건수 : 10만건 미만
2. 공급가액
 (1) 서비스명
    - 카카오 알림톡 : 12원
    - 카카오 친구톡(텍스트) : 17원
    - 카카오 친구톡(이미지) : 30원
    - 단문(SMS) : 12원
    - 장문(LMS) : 30원
    - 멀티(MMS) : 90원
 (2) 비고 : 이메일을 통한 기술 지원

1. 상기 이용 금액은 부가세 별도 금액입니다.
2. 알림톡, 친구톡 및 문자메시지는 1회 발송 금액입니다.
3. 시스템 연동과 관련하여 유선 및 이메일을 통한 기술 지원이 가능합니다.
4. 총 발송건수는 모든 카카오 비즈메시지 및 문자메시지를 합산하여 산정합니다.
5. 카카오 비즈메시지 중 알림톡을 사용하지 않고 친구톡 및 문자메시지만 사용할 경우에는 발송량에 따라 기본 요금이 적용됩니다.
6. 월 발송량이 10만건 이상일 경우에는 별도 단가협의를 진행합니다.
7. 문자메시지는 이용자의 필요에 의해 선택 할 수 있습니다.
8. 월 기본료는 요금제에 포함되어 있습니다.
                            </textarea>

                            <div class="checkbox pull-right">
                                <div class="checker" id="uniform-user_agree_contract"><span><input type="checkbox" name="user_agree_contract" id="user_agree_contract" class="required uniform"></span></div>
                                서비스 이용약관에 동의합니다.
                                <label for="user_agree_contract" class="has-error help-block" generated="true" style="display:none;"></label>

                                <text id="alert_agree_contract" style="display: none; color:#942a25; font-weight: bold;">서비스 이용약관에 동의해주세요.</text>
                            </div>
                        </div>

                        <div class="align-center clearfix">
                            <button type="button" class="btn-check btn btn-primary" onclick="proc_agree_contract();">확인</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <div class="widget mt20">
                    <div class="widget-content">
                        <table class="tpl_ver_form mt10" width="100%">
                            <colgroup>
                                <col width="200">
                                <col width="*">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>충전방법 선택</th>
                                    <td>
                                        <div class="select2-container select2 input-width-medium" id="s2id_type_pg" style="width: 150px;"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">선택하세요</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" type="text" id="s2id_autogen1"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input">   </div>   <ul class="select2-results">   </ul></div></div><select name="type_pg" id="type_pg" class="select2 input-width-medium select2-offscreen" onchange="pg_changed(this)" tabindex="-1">
                                            <option value="none">선택하세요</option>
                                            <option value="card">카드결제</option>
                                            <option value="trans">계좌이체</option>
                                            <option value="vbank">가상계좌</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>충전금액 선택</th>
                                    <td>
                                        <div class="col-xs-6">
                                        
                                            <label class="radio" name="1">
                                                <div class="radio" id="uniform-optionsMoney1"><span><input type="radio" class="uniform" id="optionsMoney1" name="optionsMoney" value="30000"></span></div>
                                                ￦ <b>30,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 0 %</span>
                                            </label>
                                        
                                            <label class="radio" name="2">
                                                <div class="radio" id="uniform-optionsMoney2"><span><input type="radio" class="uniform" id="optionsMoney2" name="optionsMoney" value="50000"></span></div>
                                                ￦ <b>50,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 0 %</span>
                                            </label>
                                        
                                            <label class="radio" name="3">
                                                <div class="radio" id="uniform-optionsMoney3"><span><input type="radio" class="uniform" id="optionsMoney3" name="optionsMoney" value="100000"></span></div>
                                                ￦ <b>100,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 3 %</span>
                                            </label>
                                        
                                            <label class="radio" name="4">
                                                <div class="radio" id="uniform-optionsMoney4"><span><input type="radio" class="uniform" id="optionsMoney4" name="optionsMoney" value="300000"></span></div>
                                                ￦ <b>300,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 5 %</span>
                                            </label>
                                        
                                            <label class="radio" name="5">
                                                <div class="radio" id="uniform-optionsMoney5"><span><input type="radio" class="uniform" id="optionsMoney5" name="optionsMoney" value="500000"></span></div>
                                                ￦ <b>500,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 7 %</span>
                                            </label>
                                        
                                            <label class="radio" name="6">
                                                <div class="radio" id="uniform-optionsMoney6"><span><input type="radio" class="uniform" id="optionsMoney6" name="optionsMoney" value="1000000"></span></div>
                                                ￦ <b>1,000,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 10 %</span>
                                            </label>
                                        
                                            <label class="radio" name="7">
                                                <div class="radio" id="uniform-optionsMoney7"><span><input type="radio" class="uniform" id="optionsMoney7" name="optionsMoney" value="3000000"></span></div>
                                                ￦ <b>3,000,000</b> (VAT 별도) <span class="pull-right text-danger" style="margin-right: 0px;">* 보너스 : 20 %</span>
                                            </label>
                                        
                                        </div>
                                    </td>
                                </tr>
                                <tr>

                                </tr>
                            </tbody>
                        </table>



                        <div class="align-center mt30">
                            <a class="btn" href="/main/">취소</a>
                            <button type="button" class="btn btn-primary" onclick="payment()">다음</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--//.content_wrap-->

    <style>
        .select2-no-results {
            display: none !important;
        }

        textarea {
            resize: none;
        }

        .modal-contract {
            width: 50%;
            height: 50%;
        }
    </style>

    <script type="text/javascript">
        "use strict";
        $(document).ready(function () {
            
        });

        document.querySelector("textarea").addEventListener("keyup",function(e){
            var charCode = e.charCode || e.keyCode || e.which;
            if (charCode == 27){
                e.cancelBubble = true;
                return false;
            }
        });

        document.querySelector(".checkbox").addEventListener("keyup",function(e){
            var charCode = e.charCode || e.keyCode || e.which;
            if (charCode == 27){
                e.cancelBubble = true;
                return false;
            }
        });

        document.querySelector(".btn-check").addEventListener("keyup",function(e){
            var charCode = e.charCode || e.keyCode || e.which;
            if (charCode == 27){
                e.cancelBubble = true;
                return false;
            }
        });

        $('#user_agree_contract').click(function(){
            if($(this).is(':checked')) {
                document.getElementById('alert_agree_contract').style.display = 'none';
            }
        });

        $(document).keydown(function(e) {
            if (e.keyCode == 27) {
                e.cancelBubble = true;
                return false;
            }
        });

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $("#btn_confirm").click();
            }
        });

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

        function payment() {
            //충전 방법
            var type_pg = document.getElementById('type_pg').value;


            //충전 금액
            var charge_money = $('input:radio[name="optionsMoney"]:checked').val();
            var charge_vat = 0;
            var discount_rate = 0;

            var moneys = {};
            
                moneys[30000] = 0;
            
                moneys[50000] = 0;
            
                moneys[100000] = 3;
            
                moneys[300000] = 5;
            
                moneys[500000] = 7;
            
                moneys[1000000] = 10;
            
                moneys[3000000] = 20;
            

            var discount_rate = moneys[charge_money];

            if (discount_rate == 'undefined') {
                console.log ('something wrong....');
            } else {

                console.log (discount_rate);
            }

            charge_vat = charge_money * 0.1;
            charge_money = +charge_money + +charge_vat;

            var IMP = window.IMP;


            //충전 방법 선택여부 체크
            if (type_pg == 'none') {
                var msg = '충전 방법을 선택해주세요.';
                $(".content").html(msg);
                $("#myModal").modal('show');
                return;
            } else if (type_pg == 'card') {
                IMP.init('imp69812451'); //'iamport' 대신 부여받은 "가맹점 식별코드"를 사용.

                IMP.request_pay({
                    pg: type_pg, // version 1.1.0부터 지원.
                    /*
                     'kakao':카카오페이,
                     'inicis':이니시스, 'html5_inicis':이니시스(웹표준결제),
                     'nice':나이스,
                     'jtnet':jtnet,
                     'uplus':LG유플러스
                     */
                    pay_method: 'card', // 'card' : 신용카드 | 'trans' : 실시간계좌이체 | 'vbank' : 가상계좌 | 'phone' : 휴대폰소액결제
                    merchant_uid: 'merchant_' + new Date().getTime(),
                    name: '(주)스윗트래커 비즈엠 웹 결제',
                    amount: charge_money,
                    vat: charge_vat,
                    buyer_email: 'None',
                    buyer_name: '티지테크',
                    buyer_tel: '029259288',
                    buyer_addr: '',
                    buyer_postcode: '',
                    notice_url: '/charge/notification'
                }, function (rsp) {
                    if (rsp.success) {
                        var msg = '결제가 완료되었습니다.';
                        msg += '<br><br>결제 금액 : ' + rsp.paid_amount + ' 원';
                        msg += '<br>카드 승인번호 : ' + rsp.apply_num;
                        msg += '<br>';
                        msg += '<br>카드 승인관련 환불문의는 대표번호(1644-1201)로 문의하시기 바랍니다.';

                        //결제 성공
                        var imp_uid = rsp.imp_uid;
                        var paid_amount = rsp.paid_amount;
                        var csrftoken = getCookie('csrftoken');

                        $.ajax({
                            url: "/myinfo/charge_process/",
                            type: "POST",
                            data: {'imp_uid': imp_uid, 'paid_amount': paid_amount, 'csrfmiddlewaretoken': csrftoken, 'vat': charge_vat, 'type_pg': type_pg},
                            beforeSend: function () {
                                $('#overlay').fadeIn();
                            },
                            complete: function () {
                                $('#overlay').fadeOut();
                            },
                            success: function (json) {
                                var card_name = json['card_name'];
                                var message = json['message'];
                                var code = json['code'];
                                var is_production = json['is_production'];
                                var amount = json['amount'];

                                $(".content").html(msg);
                                $("#myModal").modal('show');
                                $('#myModal').on('hidden.bs.modal', function () {
                                    if (code == 'success') {
                                        location.reload();
                                    }
                                });
                            },
                            error: function () {
                                var msg = '정상적으로 처리되지 않았습니다. 관리자에게 문의해주세요.';
                                $(".content").html(msg);
                                $("#myModal").modal('show');
                            }
                        });

                    } else {
                        var msg = '결제에 실패하였습니다.';
                        msg += '<br><br>에러 내용 : ' + rsp.error_msg;

                        $(".content").html(msg);
                        $("#myModal").modal('show');
                    }
                });
            } else if (type_pg == 'trans') {
                IMP.init('imp69812451'); //'iamport' 대신 부여받은 "가맹점 식별코드"를 사용.

                IMP.request_pay({
                    pg: type_pg,
                    pay_method: 'trans',
                    merchant_uid: 'merchant_' + new Date().getTime(),
                    name: '(주)스윗트래커 비즈엠 웹 결제',
                    amount: charge_money,
                    vat: charge_vat,
                    buyer_email: 'None',
                    buyer_name: '티지테크',
                    buyer_tel: '029259288',
                    buyer_addr: '',
                    buyer_postcode: '',
                    notice_url: '/charge/notification'
                }, function (rsp) {
                    if (rsp.success) {
                        var msg = '계좌이체가 완료되었습니다.';
                        msg += '<br><br>결제 금액 : ' + rsp.paid_amount + ' 원';
                        msg += '<br>';
                        msg += '<br>계좌이체 승인관련 환불문의는 대표번호(1644-1201)로 문의하시기 바랍니다.';

                        //결제 성공
                        var imp_uid = rsp.imp_uid;
                        var paid_amount = rsp.paid_amount;
                        var csrftoken = getCookie('csrftoken');

                        $.ajax({
                            url: "/myinfo/charge_process/",
                            type: "POST",
                            data: {'imp_uid': imp_uid, 'paid_amount': paid_amount, 'csrfmiddlewaretoken': csrftoken, 'vat': charge_vat, 'type_pg': type_pg},
                            beforeSend: function () {
                                $('#overlay').fadeIn();
                            },
                            complete: function () {
                                $('#overlay').fadeOut();
                            },
                            success: function (json) {
                                var code = json['code'];
                                var is_production = json['is_production'];
                                var amount = json['amount'];

                                $(".content").html(msg);
                                $("#myModal").modal('show');
                                $('#myModal').on('hidden.bs.modal', function () {
                                    if (code == 'success') {
                                        location.reload();
                                    }
                                });
                            },
                            error: function () {
                                var msg = '정상적으로 처리되지 않았습니다. 관리자에게 문의해주세요.';
                                $(".content").html(msg);
                                $("#myModal").modal('show');
                            }
                        });

                    } else {
                        var msg = '결제에 실패하였습니다.';
                        msg += '<br><br>에러 내용 : ' + rsp.error_msg;

                        $(".content").html(msg);
                        $("#myModal").modal('show');
                    }
                });

            } else if (type_pg == 'vbank') {
                var today = new Date();
                var after_seven_days = new Date(today.valueOf() + (24*60*60*1000*7)).toISOString().slice(0,10).replace(/-/g,"")+'235959';

                IMP.init('imp69812451'); //'iamport' 대신 부여받은 "가맹점 식별코드"를 사용.

                IMP.request_pay({
                    pg: type_pg,
                    pay_method: 'vbank',
                    merchant_uid: 'merchant_' + new Date().getTime(),
                    name: '(주)스윗트래커 비즈엠 웹 결제',
                    amount: charge_money,
                    vat: charge_vat,
                    buyer_email: 'None',
                    buyer_name: '티지테크',
                    buyer_tel: '029259288',
                    buyer_addr: '',
                    buyer_postcode: '',
                    custom_data: 'TGTECH',
                    vbank_due: after_seven_days, // 가상계좌 입금기한	 (선택항목) YYYYMMDDhhmm 형식
                    notice_url: '/charge/notification'
                }, function (rsp) {
                    if (rsp.success) {
                        var msg = '가상계좌 발급이 완료되었습니다.';
                        msg += '<br>아래 입금기한내 결제금액 입금시 충전 처리됩니다.';
                        msg += '<br><br>은행명 : ' + rsp.vbank_name;
                        msg += '<br>입금 계좌번호 : ' + rsp.vbank_num;
                        msg += '<br>입금 금액(VAT 포함) : ' + rsp.paid_amount + ' 원';
                        msg += '<br>예금주 : ' + rsp.vbank_holder;
                        msg += '<br>입금기한 : ' + rsp.vbank_date;

                        $(".content").html(msg);
                        $("#myModal").modal('show');

                    } else {
                        var msg = '결제에 실패하였습니다.';
                        msg += '<br><br>에러 내용 : ' + rsp.error_msg;

                        $(".content").html(msg);
                        $("#myModal").modal('show');
                    }
                });
            }
        }

        function proc_agree_contract() {
            document.getElementById('alert_agree_contract').style.display = 'none';

            var is_agree = document.getElementById("user_agree_contract");
            if (is_agree.checked) {
                //동의
                var csrftoken = getCookie('csrftoken');

                $.ajax({
                    url: "/myinfo/process_agree_contract",
                    type: "POST",
                    data: {
                        csrfmiddlewaretoken: csrftoken
                    },
                    success: function (json) {
                        //모달창 닫기 들어갈 부분
                        $('#agree_contract').modal('hide');
                    },
                    error: function (data, status, er) {
                        $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
                        $("#myModal").modal('show');
                    }
                });

            } else {
                document.getElementById('alert_agree_contract').style.display = 'block';
            }
        }

        // 지불 방법에 따른 충전 옵션 변경 (카드만 3가지로 제한)
        function pg_changed (selected) {
            if (selected.value == 'card') {
                [4,5,6,7].forEach(function(idx) {
                    var label = $("label.radio[name="+ idx + "]");
                    label.find("span").removeClass('checked');
                    label.find("input:radio[name='optionsMoney']").removeAttr('checked');
                    label.hide();
                });
            } else {
                [4,5,6,7].forEach(function(idx) {
                    $("label.radio[name="+ idx + "]").show();
                });
            }
        }





    </script>
