<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Funn {

    //사이트명
    public function getSiteName(){
        return "지니";
    }

    // Select 옵션이 같은 경우 Selected 처리
    // $val1 : 비교 문자열1
    // $val2 : 비교 문자열2
    public function fnSelected($val1, $val2){
        $rtn = "";
        if($val1 != "" && $val2 != ""){
            if($val1 == $val2){
                $rtn = " Selected";
            }
        }
        return $rtn;
    }

    // 10보다 작은 수일때 0을 붙인다.
    public function fnZeroAdd($num){
        $rtn = $num;
        if($num < 10){
            $rtn = "0". $num;
        }
        return $rtn;
    }

    //전화번호의 숫자만 취한 후 중간에 구분자를 넣는다.
    public function format_phone($tel, $identifier){
        $tel = preg_replace("/[^0-9]/", "", $tel);    // 숫자 이외 제거
        if (substr($tel,0,2)=='02')
            return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1". $identifier ."\\2". $identifier ."\\3", $tel);
            else if (strlen($tel)=='8' && (substr($tel,0,2)=='15' || substr($tel,0,2)=='16' || substr($tel,0,2)=='18'))
                // 지능망 번호이면
                return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1". $identifier ."\\2", $tel);
                else
                    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1". $identifier ."\\2". $identifier ."\\3", $tel);
    }

    // 문자를 자르는 함수
    // $str : 자를 대상 문자열
    // $len : 자를 길이
    // $suffix : 자른후 뒤에 붙일 문자열
    public function StringCut($str, $len, $suffix = ""){
        if($len < strlen($str)){
            $len_suffix = strlen($suffix);
            if($len > $len_suffix)  $len -= $len_suffix;
            else $suffix = "";
            $klen = $len - 1;
            while(ord($str[$klen]) & 0x80) $klen--;
            //return substr($str, 0, $len - (($len + $klen + 1) % 2)) . $suffix;
            //$str = substr($str, 0, $len - ((($len + $klen) & 1) ^ 1)) . $suffix;
            $str = mb_substr($str, 0, $len - ((($len + $klen) & 1) ^ 1), "utf-8") . $suffix;
        }
        return $str;
    }

    //원하는 길이로 랜덤 문자열 만들기
    public function RenStr($length){
        $characters = "0123456789";
        $characters .= "abcdefghijklmnopqrstuvwxyz";
        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";
        while ($length--) {
            $str .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $str;
    }

    //원하는 길이로 랜덤 숫자 만들기
    public function RenNum($length){
        $characters = "0123456789";
        $str = "";
        while ($length--) {
            $str .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $str;
    }

    //폴더가 없다면 생성
    public function create_upload_dir($dir = null){
        //값이 존재한다면 실행
        if(is_null($dir) == false || empty($dir) == false){
            //디렉토리 값이 없다면 실행 (폴더 생성)
            if (is_dir($dir) === false) {
                //log_message("error", "폴더 없음");
                @mkdir($dir, 0707);
                $file = $dir . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
        }
    }

    public function get_msg_tbl_kind($val, $img="",$wide="N") {
        if($val=="at") return '알림톡';
        if($val=="ft") {
            if($wide == "Y") {
                return '친구톡 와이드';
            } else {
                if(!empty($img)) {
                    return '친구톡 이미지';
                } else {
                    return '친구톡';
                }
            }
        }
        //if($val=="") return '친구톡(이미지)';
        if($val=="S") return 'SMS';
        if($val=="L") return 'LMS';
        if($val=="M") return 'MMS';
        if($val=="ph") return '폰문자';
        if($val=="15") return '015저가문자';
        if($val=="gs") return 'WEB(A)';
        if($val=="ns") return 'WEB(B)';
        if($val=="sm") return 'WEB(C)';
        return "";
    }

    public function get_2send_kind($val) {
        if($val=="NONE") return '보내지않음';
        if($val=="GREEN_SHOT") return 'WEB(A)';
        if($val=="NASELF") return 'WEB(B)';
        if($val=="SMART") return 'WEB(C)';
        if($val=="015") return '015문자';
        if($val=="PHONE") return 'NANO폰문자';
        if($val=="DOOIT") return '폰문자';
        return "";
    }

    public function get_phone_result_error($type, $val, $error_code="") {
        if( $type == "bk" ||  $type == "gs" ||  $type == "15"||  $type == "ns"  ) {
            switch ($val) {
                case "5":
                    return "시스템 오류";
                    break;
                case "7":
                    return "라우팅 불가";
                    break;
                case "11":
                    return "메시지 포멧오류";
                    break;
                case "12":
                    return "Agent ID 또는 PW 틀림";
                    break;
                case "13":
                    return "미 사용 상태인 Agent ID";
                    break;
                case "14":
                    return "미 허용된 IP로 접근";
                    break;
                case "15":
                    return "중복접속";
                    break;
                case "16":
                    return "회원정보 없음";
                    break;
                case "17":
                    return "선불회원 포인트 부족";
                    break;
                case "18":
                    return "재 접속 불가 시간 내 재 접속 시도";
                    break;
                case "21":
                    return "일간 전송량초과";
                    break;
                case "22":
                    return "월간 전송량초과";
                    break;
                case "25":
                    return "TPS 초과 (재전송 필요)";
                    break;
                case "30":
                    return "메시지 디코딩 실패";
                    break;
                case "31":
                    return "NPDB 가입자 없음";
                    break;
                case "32":
                    return "메시지 길이초과";
                    break;
                case "33":
                    return "MMS 제목 길이초과";
                    break;
                case "34":
                    return "수신거부 (080등)된 착신번호";
                    break;
                case "50":
                    return "MMS 파일 처리실패";
                    break;
                case "51":
                    return "이미지 파일 용량초과";
                    break;
                case "52":
                    return "사용불가 한 메세지 종류";
                    break;
                case "100":
                    return "발신번호 포멧오류 (전화번호 세칙 미 준수)";
                    break;
                case "101":
                    return "등록되지 않은 발신번호 (발신번호 사전등록제)";
                    break;
                case "102":
                    return "스팸 발신번호";
                    break;
                case "103":
                    return "스팸 착신번호";
                    break;
                case "110":
                    return "리포트 수신시간 만료 (메시지 처리시간 초과)";
                    break;
                case "111":
                    return "레포트수신시간 5일 경과 실패처리";
                    break;
                case "200":
                    return "기타오류";
                    break;
                case "201":
                    return "통신사 기타오류";
                    break;
                case "500":
                    return "Agent Send Ack 미수신, Agent 자체처리";
                    break;
                case "501":
                    return "Agent 중복 데이터, Agent 자체처리";
                    break;
                case "502":
                    return "Agent 발송시간초과 (req date초과), Agent 자체처리";
                    break;
                case "503":
                    return "Agent MMS 파일개수 설정오류, Agent 자체처리";
                    break;
                case "504":
                    return "Agent MMS 파일 포멧오류, Agent 자체처리";
                    break;
                case "505":
                    return "Agent MMS 파일사이즈 초과, Agent 자체처리";
                    break;
                case "506":
                    return "Agent 기타오류, Agent 자체처리";
                    break;
                case "510":
                    return "Agent 발송제한으로 인한 실패처리, Web 자체처리";
                    break;
                case "1013":
                    return "수신번호가 오류로 (자릿수 부족 혹은 자릿수 초과) 인해 발송실패";
                    break;
                case "1014":
                    return "메시지의 데이터가 잘못되어 발송실패";
                    break;
                case "1019":
                    return "메시지 발송 기간사업자/별정사업자 시스템 장애. 발송실패";
                    break;
                case "1022":
                    return "전송실패 (단말기 호 처리 상태)";
                    break;
                case "1023":
                    return "전송실패 (통신사 운영자에 의한 메시지 삭제)";
                    break;
                case "1024":
                    return "전송실패 (단말기 메시지 FULL)";
                    break;
                case "1025":
                    return "전송실패 (단말기 전원 꺼짐)";
                    break;
                case "1026":
                    return "전송실패 (음영지역)";
                    break;
                case "1027":
                    return "전송실패 (착신거절)";
                    break;
                case "1999":
                    return "기타실패";
                    break;
                case "2001":
                    return "포멧 에러 (결번 포함)";
                    break;
                case "2007":
                    return "번호 이동된 가입자 전송실패";
                    break;
                case "2008":
                    return "KTF로 번호 이동된 가입자 전송실패";
                    break;
                case "2009":
                    return "LGT로 번호 이동된 가입자 전송실패";
                    break;
                case "2010":
                    return "SKT로 번호 이동된 가입자 전송실패";
                    break;
                case "2011":
                    return "기간사업자/별정사업자 NPDB FAIL 전송실패";
                    break;
                case "2012":
                    return "Delivery 메시지 수신 되지 않음";
                    break;
                case "3000":
                    return "미지원 단말";
                    break;
                case "3002":
                    return "Pull 인출 시간 초과";
                    break;
                case "3011":
                    return "수신자의 수신 거부 요청에 의한 메시지 발송실패";
                    break;
                case "3012":
                    return "메시지 내용에 금칙어가 들어 있어 발송실패 ";
                    break;
                case "3018":
                    return "방통위 금지 회신번호 차단";
                    break;
                case "3019":
                    return "방통위 금지문구 차단";
                    break;
                case "3020":
                    return "운영자에 의한 수신번호 / 회신번호 동일 시 메시지 차단 ";
                    break;
                case "3030":
                    return "단말로 메시지 전송실패(기타오류)";
                    break;
                case "3040":
                    return "단말로 전송 한 메시지 전송 결과 수신실패(기타오류)";
                    break;
                case "4000":
                    return "서버 프로세스 또는 시스템 에러";
                    break;
                case "4001":
                    return "서버 인증실패";
                    break;
                case "7000":
                    return "사용자의 요청으로 기간사업자의 가입자 외에 메시지 발송 거부처리";
                    break;
                case "8001":
                    return "지원하지 않는 미디어 타입이거나 MIME 형식 오류";
                    break;
                case "8004":
                    return "올바른 컨텐츠가 아님  8004 [수정] [추가] ";
                    break;
                case "8005":
                    return "일시적인 단말문제  8005 [수정] [추가] ";
                    break;
                case "8006":
                    return "미 지원단말";
                    break;
                case "11001":
                    return "Request Body가 Json 형식이 아님";
                    break;
                case "결과 수신대기":
                    return $val;
                    break;
                case "":
                    return "기타오류";
                    break;
                default:
                    return "기타오류(".$val.")";
            }
        } else if( $type == "sm" ) {
            switch ($val) {
                case '0':
                    return '초기 입력 상태 (default)';
                    break;
                case '1':
                    return '전송 요청 완료(결과수신대기)';
                    break;
                case '3':
                    return '메시지 형식 오류';
                    break;
                case '5':
                    return '휴대폰번호 가입자 없음(미등록)';
                    break;
                case '6':
                    return '전송 성공';
                    break;
                case '7':
                    return '결번(or 서비스 정지)';
                    break;
                case '8':
                    return '단말기 전원 꺼짐';
                    break;
                case '9':
                    return '단말기 음영지역';
                    break;
                case '10':
                    return '단말기내 수신메시지함 FULL로 전송 실패 (구:단말 Busy, 기타 단말문제)';
                    break;
                case '11':
                    return '기타 전송실패';
                    break;
                case '13':
                    return '스팸차단 발신번호';
                    break;
                case '14':
                    return '스팸차단 수신번호';
                    break;
                case '15':
                    return '스팸차단 메시지내용';
                    break;
                case '16':
                    return '스팸차단 기타';
                    break;
                case '20':
                    return '*단말기 서비스 불가';
                    break;
                case '21':
                    return '단말기 서비스 일시정지';
                    break;
                case '22':
                    return '단말기 착신 거절';
                    break;
                case '23':
                    return '단말기 무응답 및 통화중 (busy)';
                    break;
                case '28':
                    return '단말기 MMS 미지원';
                    break;
                case '29':
                    return '기타 단말기 문제';
                    break;
                case '36':
                    return '유효하지 않은 수신번호(망)';
                    break;
                case '37':
                    return '유효하지 않은 발신번호(망)';
                    break;
                case '50':
                    return '이통사 컨텐츠 에러';
                    break;
                case '51':
                    return '이통사 전화번호 세칙 미준수 발신번호';
                    break;
                case '52':
                    return '이통사 발신번호 변작으로 등록된 발신번호';
                    break;
                case '53':
                    return '이통사 번호도용문자 차단서비스에 가입된 발신번호';
                    break;
                case '54':
                    return '이통사 발신번호 기타';
                    break;
                case '59':
                    return '이통사 기타';
                    break;
                case '60':
                    return '컨텐츠 크기 오류(초과 등)';
                    break;
                case '61':
                    return '잘못된 메시지 타입';
                    break;
                case '69':
                    return '컨텐츠 기타';
                    break;
                case '74':
                    return '[Agent] 중복발송 차단 (동일한 수신번호와 메시지 발송 - 기본off, 설정필요)';
                    break;
                case '75':
                    return '[Agent] 발송 Timeout';
                    break;
                case '76':
                    return '[Agent] 유효하지않은 발신번호';
                    break;
                case '77':
                    return '[Agent] 유효하지않은 수신번호';
                    break;
                case '78':
                    return '[Agent] 컨텐츠 오류 (MMS파일없음 등)';
                    break;
                case '79':
                    return '[Agent] 기타';
                    break;
                case '80':
                    return '오샷 고객필터링 차단 (발신번호, 수신번호, 메시지 등)';
                    break;
                case '81':
                    return '오샷 080 수신거부';
                    break;
                case '84':
                    return '오샷 중복발송 차단';
                    break;
                case '86':
                    return '오샷 유효하지 않은 수신번호';
                    break;
                case '87':
                    return '오샷 유효하지 않은 발신번호';
                    break;
                case '88':
                    return '오샷 발신번호 미등록 차단';
                    break;
                case '89':
                    return '오샷 시스템필터링 기타';
                    break;
                case '90':
                    return '발송제한 시간 초과';
                    break;
                case '92':
                    return '잔액부족';
                    break;
                case '93':
                    return '월 발송량 초과';
                    break;
                case '94':
                    return '일 발송량 초과';
                    break;
                case '95':
                    return '초당 발송량 초과 (재전송 필요)';
                    break;
                case '96':
                    return '발송시스템 일시적인 부하 (재전송 필요)';
                    break;
                case '97':
                    return '전송 네트워크 오류 (재전송 필요)';
                    break;
                case '98':
                    return '외부발송시스템 장애 (재전송 필요)';
                    break;
                case '99':
                    return '발송시스템 장애 (재전송 필요)';
                    break;
            }
        } else if( $type == "ft" ||  $type == "at" || $type == "ai") {
            if($error_code != "") {
                $code_first_code_str = substr($error_code,0,1);
                if($code_first_code_str == "K" || $code_first_code_str == "M" || $code_first_code_str == "E" || $code_first_code_str == "R") {
                    switch($val) {
                        case 'InvalidData':     //E101
                            return 'Request 데이터오류';
                            break;
                        case 'InvalidProfileKey':   //E102
                            return '발신 프로필 키가 없거나 유효하지 않음';
                            break;
                        case 'EmptyTemplateCode':   //E103
                            return '템플릿 코드가 없음';
                            break;
                        case 'InvalidPhoneNumber':   //E104
                            return '잘못된 전화번호- 유효하지 않은 전화번호-안심번호';
                            break;
                        case 'InvalidSenderNumber':   //E105
                            return '유효하지 않은 SMS 발신번호';
                            break;
                        case 'EmptyMessage':   //E106
                            return '메시지 내용이 없음';
                            break;
                        case 'SMSEmptyMessage':   //E107
                            return '카카오 발송 실패시 SMS전환발송을 하는 경우 SMS 메시지 내용이 없음';
                            break;
                        case 'InvalidReservationDate':   //E108
                            return '예약일자 이상(잘못된 예약일자 요청)';
                            break;
                        case 'DuplicatedMsgId':   //E109
                            return '중복된 MsgId 요청';
                            break;
                        case 'RequestMsgIdNotFound':   //E110
                            return 'MsgId를 찾을 수 없음';
                            break;
                        case 'RequestImgNotFound':   //E111
                            return '첨부 이미지 URL 정보를 찾을 수 없음';
                            break;
                        case 'OverLimitMessageLength':   //E112
                            return '메시지 길이제한 오류(메시지 제한길이 또는 1000자초과)';
                            break;
                        case 'OverLimitMsgIdLength':   //E113
                            return '메시지ID 길이제한 오류(메시지ID 20자 초과)';
                            break;
                        case 'OverLimitRequest':   //E998
                            return '최대요청수초과';
                            break;
                        case 'UnknownException':   //E999
                            return '시스템 오류';
                            break;
                        case 'NotAvailableSendMessage':     //K101
                            return '메시지를 전송할 수 없음';
                            break;
                        case 'InvalidPhoneNumber':   //K102
                            return '전화번호 오류';
                            break;
                        case 'OverLimitMessageLength':   //K103
                            return '메시지 길이제한 오류(메시지 제한길이 또는 1000자초과)';
                            break;
                        case 'TemplateNotFound':   //K104
                            return '템플릿을 찾을 수 없음';
                            break;
                        case 'NoMatchedTemplate':   //K105
                            return '메시지 내용이 템플릿과 일치하지 않음';
                            break;
                        case 'InvalidImage':   //K106
                            return '첨부 이미지 URL 또는 링크 정보가 올바르지 않음';
                            break;
                        case 'UnknownException':   //K999
                            return '시스템 오류';
                            break;
                        case 'ProcessingMessageSend':     //M001
                            return 'SMS 발송 처리 중';
                            break;
                        case 'NotAvailableSendMessage':     //M101
                            return '메시지를 전송할 수 없음';
                            break;
                        case 'InvalidPhoneNumber':   //M102
                            return '전화번호 오류';
                            break;
                        case 'DoNotDisturbDoNotDisturb':   //M103
                            return '착신거부';
                            break;
                        case 'SpamMessage':   //M104
                            return '스팸번호로 등록 됨';
                            break;
                        case 'TurnOff':   //M105
                            return '전원 꺼짐전원 꺼짐';
                            break;
                        case 'OverLimitMessageLength':   //M106
                            return '메시지 길이제한 오류(메시지 제한길이 또는 1000자초과)';
                            break;
                        case 'UnknownException':   //M999
                            return '시스템 오류';
                            break;
                        case 'DuplicatedMsgid':   //R109
                            return '중복된 MsgId 요청';
                            break;
                        default:
                            return $val;
                            break;
                    }
                } else {
                    switch ($error_code) {
                        case '1001':
                            return str_replace("NoJsonBody","Request Body가 Json형식이 아님", $val);
                            break;
                        case '1002':
                            return str_replace("InvalidHubPartnerKey","허브 파트너 키가 유효하지 않음", $val);
                            break;
                        case '1003':
                            return str_replace("InvalidSenderKey","발신 프로필 키가 유효하지 않음", $val);
                            break;
                        case '1004':
                            return str_replace("NoValueJsonElement","Request Body(Json)에서 name을 찾을 수 없음", $val);
                            break;
                        case '1006':
                            return str_replace("DeletedSender","삭제된 발신프로필. (메시지 사업 담당자에게 문의)", $val);
                            break;
                        case '1007':
                            return str_replace("StoppedSender","차단 상태의 발신프로필. (메시지 사업 담당자에게 문의)", $val);
                            break;
                        case '1011':
                            return str_replace("ContractNotFound","계약정보를 찾을 수 없음. (메시지 사업 담당자에게 문의)", $val);
                            break;
                        case '1012':
                            return str_replace("InvalidUserKeyException","잘못된 형식의 유저키 요청", $val);
                            break;
                        case '1013':
                            return str_replace("InvalidAppLink","유효하지 않은 app연결", $val);
                            break;
                        case '1014':
                            return str_replace("InvalidBizNum","유효하지 않은 사업자번호", $val);
                            break;
                        case '1015':
                            return str_replace("TalkUserIdNotFonud","유효하지 않은 app user id 요청", $val);
                            break;
                        case '1016':
                            return str_replace("BizNumNotEqual","사업자등록번호 불일치", $val);
                            break;
                        case '1020':
                            return str_replace("InvalidReceiveUser","전화번호 or app user id가 유효하지 않거나 미입력 요청", $val);
                            break;
                        case '1021':
                            return str_replace("BlockedProfile","차단 상태의 카카오톡 채널 (카카오톡 채널 운영툴에서 확인)", $val);
                            break;
                        case '1022':
                            return str_replace("DeactivatedProfile","닫힘 상태의 카카오톡 채널 (카카오톡 채널 운영툴에서 확인)", $val);
                            break;
                        case '1023':
                            return str_replace("DeletedProfile","삭제된 카카오톡 채널 (카카오톡 채널 운영툴에서 확인)", $val);
                            break;
                        case '1024':
                            return str_replace("DeletingProfile","삭제대기 상태의 카카오톡 채널 (카카오톡 채널 운영툴에서 확인)", $val);
                            break;
                        case '1025':
                            return str_replace("SpammedProfile","메시지차단 상태의 카카오톡 채널 (카카오톡 채널 운영툴에서 확인)", $val);
                            break;
                        case '1026':
                            return str_replace("UnableUseMessageType","이미지알림톡는 realtime으로 발송 불가", $val);
                            break;
                        case '1030':
                            return str_replace("InvalidParameterException","잘못된 파라메터 요청", $val);
                            break;
                        case '2003':
                            return str_replace("FailedToSendMessageByNoFriendshipException","메시지 전송 실패(테스트 서버에서 카카오톡 채널을 추가하지 않은 경우)", $val);
                            break;
                        case '2004':
                            return str_replace("FailedToMatchTemplateException","템플릿 일치 확인시 오류 발생(내부 오류 발생)", $val);
                            break;
                        case '2006':
                            return str_replace("FailedToMatchSerialNumberPrefixPattern","명시된 시리얼넘버 형식 불일치", $val);
                            break;
                        case '3000':
                            return str_replace("UnexpectedException","예기치 않은 오류 발생", $val);
                            break;
                        case '3005':
                            return str_replace("AckTimeoutException","메시지를 발송했으나 수신확인 안됨 (성공불확실)", $val);
                            break;
                        case '3006':
                            return str_replace("FailedToSendMessageException","내부 시스템 오류로 메시지 전송 실패", $val);
                            break;
                        case '3008':
                            return str_replace("InvalidPhoneNumberException","전화번호 오류", $val);
                            break;
                        case '3010':
                            return str_replace("JsonParseException","Json 파싱 오류", $val);
                            break;
                        case '3011':
                            return str_replace("MessageNotFoundException","메시지가 존재하지 않음", $val);
                            break;
                        case '3012':
                            return str_replace("SerialNumberDuplicatedException","메시지 일련번호가 중복됨", $val);
                            break;
                        case '3013':
                            return str_replace("MessageEmptyException","메시지가 비어 있음", $val);
                            break;
                        case '3014':
                            return str_replace("MessageLengthOverLimitException","메시지 길이 제한 오류(텍스트 타입 1000자 초과, 이미지 타입 400자 초과)", $val);
                            break;
                        case '3015':
                            return str_replace("TemplateNotFoundException","템플릿을 찾을 수 없음", $val);
                            break;
                        case '3016':
                            return str_replace("NoMatchedTemplateException","메시지 내용이 템플릿과 일치하지 않음", $val);
                            break;
                        case '3018':
                            return str_replace("NoSendAvailableException","메시지를 전송할 수 없음", $val);
                            break;
                        case '3022':
                            return str_replace("NoSendAvailableTimeException","메시지 발송 가능한 시간이 아님(친구톡 / 마케팅 메시지는 08시부터 20시까지 발송 가능)", $val);
                            break;
                        case '3024':
                            return str_replace("MessageInvalidImageException","메시지에 포함된 이미지를 전송할 수 없음", $val);
                            break;
                        case '3025':
                            return str_replace("ExceedMaxVariableLengthException","변수 글자수 제한 초과", $val);
                            break;
                        case '3026':
                            return $val;
                            //return str_replace("Button chat_extra(event)-InvalidExtra(EventName)Exception '([A-Za-z0-9_]{1,50})'","상담/봇 전환 버튼 extra, event 글자수 제한 초과", $val);
                            break;
                        case '3027':
                            return $val;
                            //return str_replace("NoMatchedTemplateButtonException / NoMatchedTemplateQuickReplyException","메시지 버튼/바로연결이 템플릿과 일치하지 않음", $val);
                            break;
                        case '3028':
                            return str_replace("NoMatchedTemplateTitleException","메시지 강조 표기 타이틀이 템플릿과 일치하지 않음", $val);
                            break;
                        case '3029':
                            return str_replace("ExceedMaxTitleLengthException","메시지 강조 표기 타이틀 길이 제한 초과 (50자)", $val);
                            break;
                        case '3030':
                            return str_replace("NoMatchedTemplateWithMessageTypeException","메시지 타입과 템플릿 강조유형이 일치하지 않음", $val);
                            break;
                        case '3031':
                            return str_replace("NoMatchedTemplateHeaderException","헤더가 템플릿과 일치하지 않음", $val);
                            break;
                        case '3032':
                            return str_replace("ExceedMaxHeaderLengthException","헤더 길이 제한 초과(16자)", $val);
                            break;
                        case '3033':
                            return str_replace("NoMatchedTemplateItemHighlightException","아이템 하이라이트가 템플릿과 일치하지 않음", $val);
                            break;
                        case '3034':
                            return str_replace("ExceedMaxItemHighlightTitleLengthException","아이템 하이라이트 타이틀 길이 제한 초과(이미지 없는 경우 30자, 이미지 있는 경우 21자)", $val);
                            break;
                        case '3035':
                            return str_replace("ExceedMaxItemHighlightDescriptionLengthException","아이템 하이라이트 디스크립션 길이 제한 초과(이미지 없는 경우 19자, 이미지 있는 경우 13자)", $val);
                            break;
                        case '3036':
                            return str_replace("NoMatchedTemplateItemListException","아이템 리스트가 템플릿과 일치하지 않음", $val);
                            break;
                        case '3037':
                            return str_replace("ExceedMaxItemDescriptionLengthException","아이템 리스트의 아이템의 디스크립션 길이 제한 초과(23자)", $val);
                            break;
                        case '3038':
                            return str_replace("NoMatchedTemplateItemSummaryException","아이템 요약정보가 템플릿과 일치하지 않음", $val);
                            break;
                        case '3039':
                            return str_replace("ExceedMaxItemSummaryDescriptionLengthException","아이템 요약정보의 디스크립션 길이 제한 초과(14자)", $val);
                            break;
                        case '3040':
                            return str_replace("InvalidItemSummaryDescriptionException","메시지 강조 표기 타이틀 길이 제한 초과 (50자)", $val);
                            break;
                        case '4000':
                            return str_replace("ResponseHistoryNotFoundException","메시지 전송 결과를 찾을 수 없음", $val);
                            break;
                        case '4001':
                            return str_replace("UnknownMessageStatusError","알 수 없는 메시지 상태", $val);
                            break;
                        default:
                            return $val;
                            break;
                    }
                }
            }
            //return $val;
        } else {
            switch ($val) {
                case "101":
                    return "SLOWDOWN 성공";
                    break;
                case "110":
                    return "부분적 성공";
                    break;
                case "200":
                    return "포멧 관련 알 수 없는 오류 발생";
                    break;
                case "201":
                    return "주소(포멧) 에러";
                    break;
                case "202":
                    return "길이 오류";
                    break;
                case "203":
                    return "MIME 형식 오류 ( 컨텐츠 개체,사이즈, 타입 등의 오류)";
                    break;
                case "204":
                    return "Message ID 오류 (중복, 부재)";
                    break;
                case "205":
                    return "Head 내 각 필드의 부적절";
                    break;
                case "206":
                    return "Body 내 각 필드의 부적절";
                    break;
                case "207":
                    return "지원하지 않는 미디어 존재";
                    break;
                case "208":
                    return "착신가입자 없음";
                    break;
                case "209":
                    return "암호화 처리 에러";
                    break;
                case "300":
                    return "MMS를 미 지원 단말";
                    break;
                case "301":
                    return "단말 수신용량 초과";
                    break;
                case "302":
                    return "전송 시간 초과";
                    break;
                case "303":
                    return "읽기 확인 미지원 단말";
                    break;
                case "304":
                    return "전원 꺼짐";
                    break;
                case "305":
                    return "음영 지역";
                    break;
                case "306":
                    return "기타";
                    break;
                case "307":
                    return "비가입자";
                    break;
                case "400":
                    return "서버실패 (프로세스 또는 시스템 에러)";
                    break;
                case "401":
                    return "인증실패";
                    break;
                case "402":
                    return "네트웍 에러 발생";
                    break;
                case "403":
                    return "서비스의 일시적인 에러";
                    break;
                case "404":
                    return "BIND 되어 있지 않음";
                    break;
                case "405":
                    return "메시지 전송량 초과";
                    break;
                case "500":
                    return "번호 이동 에러";
                    break;
                case "501":
                    return "과금 오류";
                    break;
                case "502":
                    return "전송 속도 초과 등 이유로 수신 불가";
                    break;
                case "503":
                    return "스팸 메시지";
                    break;
                case "504":
                    return "일간발송제한량 초과";
                    break;
                case "505":
                    return "월간발송제한량 초과";
                    break;
                case "506":
                    return "발송제한시간내 발송시도 오류";
                    break;
                case "507":
                    return "이통사 전송제한";
                    break;
                case "508":
                    return "Block Callback number";
                    break;
                case "509":
                    return "Block Destination number";
                    break;
                case "510":
                    return "Blocked by 080 system";
                    break;
                case "901":
                    return "유효시간 초과";
                    break;
                case "902":
                    return "폰 넘버 에러";
                    break;
                case "903":
                    return "스팸 번호, 사용 X";
                    break;
                case "904":
                    return "이통사에서 응답 없음";
                    break;
                case "905":
                    return "파일크기 오류";
                    break;
                case "906":
                    return "지원되지 않는 파일";
                    break;
                case "907":
                    return "파일 오류";
                    break;
                case "908":
                    return "중복 발송 실패";
                    break;
                default:
                    return $val;
            }
        }
        return "";
    }

    // 발신번호 유효성 검사
    public function is_valid_callback($callback)
    {
        $_callback = preg_replace('/[^0-9]/', '', $callback);
        if (!
            preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",
                $_callback) && ! preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/", $_callback)) {
                    $_result = false;
                }
                if (preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",
                    $_callback)) {
                        $_result = false;
                    }
                    return true;
    }

    public function get_amt_tbl_kind($val) {
        if($val=="A") return '알림톡';
        if($val=="F") return '친구톡(텍스트)';
        if($val=="I") return '친구톡(이미지)';
        if($val=="S") return 'SMS';
        if($val=="L") return 'LMS';
        if($val=="M") return 'MMS';
        if($val=="P") return '폰문자';
        return "";
    }

    public function get_inspect_status_name($val) {
        if($val=="REG") return '등록';
        if($val=="REQ") return '검수요청';
        if($val=="APR") return '승인';
        if($val=="REJ") return '반려';
        return "";
    }

    public function get_tmpl_status_name($val) {
        //if($val=="R") return '대기(발송전)';
        //if($val=="R") return '대기';
        if($val=="R") return '정상';
        if($val=="A") return '정상';
        if($val=="S") return '차단';
        return "";
    }

    public function get_comment_status_name($val) {
        if($val=="INQ") return '-';
        if($val=="REP") return '답변';
        return "";
    }

    public function get_template_button_name($val) {
        if($val=="DS") return "배송조회";
        if($val=="WL") return "웹링크";
        if($val=="AL") return "앱링크";
        if($val=="BK") return "봇키워드";
        if($val=="MD") return "메시지전달";
    }

    public function setColorLabel($val) {
        //if($val=="REG") return '<span class="label label-success">'.$this->get_inspect_status_name($val).'</span>';
        //if($val=="REQ") return '<span class="label label-info">'.$this->get_inspect_status_name($val).'</span>';
        //if($val=="APR") return '<span class="label label-red">'.$this->get_inspect_status_name($val).'</span>';
        //if($val=="REJ") return '<span class="label label-danger">'.$this->get_inspect_status_name($val).'</span>';
        //if($val=="R") return '<span class="label label-success">'.$this->get_tmpl_status_name($val).'</span>';
        //if($val=="A") return '<span class="label label-red">'.$this->get_tmpl_status_name($val).'</span>';
        //if($val=="S") return '<span class="label label-danger">'.$this->get_tmpl_status_name($val).'</span>';
        //if($val=="INQ") return '<span class="label">'.$this->get_comment_status_name($val).'</span>';
        //if($val=="REP") return '<span class="label label-red">'.$this->get_comment_status_name($val).'</span>';
        if($val=="REG") return $this->get_inspect_status_name($val);
        if($val=="REQ") return $this->get_inspect_status_name($val);
        if($val=="APR") return $this->get_inspect_status_name($val);
        if($val=="REJ") return $this->get_inspect_status_name($val);
        if($val=="R") return $this->get_tmpl_status_name($val);
        if($val=="A") return $this->get_tmpl_status_name($val);
        if($val=="S") return $this->get_tmpl_status_name($val);
        if($val=="INQ") return $this->get_comment_status_name($val);
        if($val=="REP") return $this->get_comment_status_name($val);
        return "";
    }

    /* 유니코드 문자를 UTF-8로 변환 */
    public function fnUnicodeToUTF8($string) {
        return html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($string)), null, 'UTF-8');
    }

    /* 사용자의 사용가능 예치금+포인트를 반환 */
    public function getCoin($id, $uid) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_dhn_model');
            //log_message("ERROR", "TEST111111111111111 ");
        }
        $result = array(
            "price_at"=>$CI->biz_dhn_model->price_at,
            "price_ft"=>$CI->biz_dhn_model->price_ft,
            "price_ft_img"=>$CI->biz_dhn_model->price_ft_img,
            "price_phn"=>$CI->biz_dhn_model->price_phn,
            "price_sms"=>$CI->biz_dhn_model->price_sms,
            "price_lms"=>$CI->biz_dhn_model->price_lms,
            "price_grs"=>$CI->biz_dhn_model->price_grs,
            "price_grs_sms"=>$CI->biz_dhn_model->price_grs_sms,
            "price_grs_mms"=>$CI->biz_dhn_model->price_grs_mms,
            "price_nas"=>$CI->biz_dhn_model->price_nas,
            "price_nas_sms"=>$CI->biz_dhn_model->price_nas_sms,
            "price_nas_mms"=>$CI->biz_dhn_model->price_nas_mms,
            "price_smt"=>$CI->biz_dhn_model->price_smt,
            "price_smt_sms"=>$CI->biz_dhn_model->price_smt_sms,
            "price_smt_mms"=>$CI->biz_dhn_model->price_smt_mms,
            "price_rcs"=>$CI->biz_dhn_model->price_rcs,
            "price_rcs_sms"=>$CI->biz_dhn_model->price_rcs_sms,
            "price_rcs_mms"=>$CI->biz_dhn_model->price_rcs_mms,
            "price_rcs_tem"=>$CI->biz_dhn_model->price_rcs_tem,
            "price_015"=>$CI->biz_dhn_model->price_015,
            "price_mms"=>$CI->biz_dhn_model->price_mms
        );
        $result['coin'] = $CI->biz_dhn_model->getAbleCoin($id, $uid);
        return $result;
    }

    public function getCoin_new($id, $uid) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_dhn_model');
            //log_message("ERROR", "TEST111111111111111 ");
        }
        $result = array(
            "price_at"=>$CI->biz_dhn_model->price_at,
            "price_ft"=>$CI->biz_dhn_model->price_ft,
            "price_ft_img"=>$CI->biz_dhn_model->price_ft_img,
            "price_phn"=>$CI->biz_dhn_model->price_phn,
            "price_sms"=>$CI->biz_dhn_model->price_sms,
            "price_lms"=>$CI->biz_dhn_model->price_lms,
            "price_grs"=>$CI->biz_dhn_model->price_grs,
            "price_grs_sms"=>$CI->biz_dhn_model->price_grs_sms,
            "price_grs_mms"=>$CI->biz_dhn_model->price_grs_mms,
            "price_nas"=>$CI->biz_dhn_model->price_nas,
            "price_nas_sms"=>$CI->biz_dhn_model->price_nas_sms,
            "price_nas_mms"=>$CI->biz_dhn_model->price_nas_mms,
            "price_smt"=>$CI->biz_dhn_model->price_smt,
            "price_smt_sms"=>$CI->biz_dhn_model->price_smt_sms,
            "price_smt_mms"=>$CI->biz_dhn_model->price_smt_mms,
            "price_rcs"=>$CI->biz_dhn_model->price_rcs,
            "price_rcs_sms"=>$CI->biz_dhn_model->price_rcs_sms,
            "price_rcs_mms"=>$CI->biz_dhn_model->price_rcs_mms,
            "price_rcs_tem"=>$CI->biz_dhn_model->price_rcs_tem,
            "price_015"=>$CI->biz_dhn_model->price_015,
            "price_mms"=>$CI->biz_dhn_model->price_mms,

            "price_v_at"=>$CI->biz_dhn_model->price_v_at,
            "price_v_ft"=>$CI->biz_dhn_model->price_v_ft,
            "price_v_ft_img"=>$CI->biz_dhn_model->price_v_ft_img,
            "price_v_smt"=>$CI->biz_dhn_model->price_v_smt,
            "price_v_smt_sms"=>$CI->biz_dhn_model->price_v_smt_sms,
            "price_v_smt_mms"=>$CI->biz_dhn_model->price_v_smt_mms,
            "price_v_rcs"=>$CI->biz_dhn_model->price_v_rcs,
            "price_v_rcs_sms"=>$CI->biz_dhn_model->price_v_rcs_sms,
            "price_v_rcs_mms"=>$CI->biz_dhn_model->price_v_rcs_mms,
            "price_v_rcs_tem"=>$CI->biz_dhn_model->price_v_rcs_tem
        );
        $coinget = $CI->biz_dhn_model->getAbleCoin_new($id, $uid, $voucherfix);
        $result['coin'] = $coinget["mCoin"];
        $result['vcoin'] = $coinget["vCoin"];
        $result['bcoin'] = $coinget["bCoin"];
        return $result;
    }

    public function getPreCoin($id) {
        $CI =& get_instance();
        $sql = " SELECT count(*) AS cnt FROM cb_pre_deposit WHERE mem_id = ".$id;
        $pre_cnt = $CI->db->query($sql)->row()->cnt;
        $pre_cash = 0;
        if($pre_cnt>0){
            $sql = " SELECT pre_cash FROM cb_pre_deposit WHERE mem_id = ".$id;
            $pre_cash = $CI->db->query($sql)->row()->pre_cash;
        }
        return $pre_cash;
    }

    public function getVCoin($id, $uid) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_dhn_model');
        }
        // $CI =& get_instance();
        // $sql = " SELECT count(*) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        // $kvd_cnt = $CI->db->query($sql)->row()->cnt;
        // $kvd_cash = 0;
        // if($kvd_cnt>0){
        //     $sql = " SELECT kvd_cash FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        //     $kvd_cash = $CI->db->query($sql)->row()->kvd_cash;
        // }
        $result = array(
            "price_at"=>$CI->biz_dhn_model->price_v_at,
            "price_ft"=>$CI->biz_dhn_model->price_v_ft,
            "price_ft_img"=>$CI->biz_dhn_model->price_v_ft_img,
            "price_phn"=>$CI->biz_dhn_model->price_phn,
            "price_sms"=>$CI->biz_dhn_model->price_sms,
            "price_lms"=>$CI->biz_dhn_model->price_lms,
            "price_grs"=>$CI->biz_dhn_model->price_grs,
            "price_grs_sms"=>$CI->biz_dhn_model->price_grs_sms,
            "price_grs_mms"=>$CI->biz_dhn_model->price_grs_mms,
            "price_nas"=>$CI->biz_dhn_model->price_nas,
            "price_nas_sms"=>$CI->biz_dhn_model->price_nas_sms,
            "price_nas_mms"=>$CI->biz_dhn_model->price_nas_mms,
            "price_smt"=>$CI->biz_dhn_model->price_v_smt,
            "price_smt_sms"=>$CI->biz_dhn_model->price_v_smt_sms,
            "price_smt_mms"=>$CI->biz_dhn_model->price_v_smt_mms,
            "price_rcs"=>$CI->biz_dhn_model->price_v_rcs,
            "price_rcs_sms"=>$CI->biz_dhn_model->price_v_rcs_sms,
            "price_rcs_mms"=>$CI->biz_dhn_model->price_v_rcs_mms,
            "price_rcs_tem"=>$CI->biz_dhn_model->price_v_rcs_tem,
            "price_015"=>$CI->biz_dhn_model->price_015,
            "price_mms"=>$CI->biz_dhn_model->price_mms,
            "price_imc"=>$CI->biz_dhn_model->price_imc
        );
        $result['coin'] = $CI->biz_dhn_model->getAbleVCoin($id, $uid);
        // $result = $CI->biz_dhn_model->getAbleVCoin($id, $uid);
        return $result;
    }

    public function getBCoin($id, $uid) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_dhn_model');
        }
        // $CI =& get_instance();
        // $sql = " SELECT count(*) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        // $kvd_cnt = $CI->db->query($sql)->row()->cnt;
        // $kvd_cash = 0;
        // if($kvd_cnt>0){
        //     $sql = " SELECT kvd_cash FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        //     $kvd_cash = $CI->db->query($sql)->row()->kvd_cash;
        // }
        // $result = array(
        //     "price_at"=>$CI->biz_dhn_model->price_at,
        //     "price_ft"=>$CI->biz_dhn_model->price_ft,
        //     "price_ft_img"=>$CI->biz_dhn_model->price_ft_img,
        //     "price_phn"=>$CI->biz_dhn_model->price_phn,
        //     "price_sms"=>$CI->biz_dhn_model->price_sms,
        //     "price_lms"=>$CI->biz_dhn_model->price_lms,
        //     "price_grs"=>$CI->biz_dhn_model->price_grs,
        //     "price_grs_sms"=>$CI->biz_dhn_model->price_grs_sms,
        //     "price_grs_mms"=>$CI->biz_dhn_model->price_grs_mms,
        //     "price_nas"=>$CI->biz_dhn_model->price_nas,
        //     "price_nas_sms"=>$CI->biz_dhn_model->price_nas_sms,
        //     "price_nas_mms"=>$CI->biz_dhn_model->price_nas_mms,
        //     "price_smt"=>$CI->biz_dhn_model->price_smt,
        //     "price_smt_sms"=>$CI->biz_dhn_model->price_smt_sms,
        //     "price_smt_mms"=>$CI->biz_dhn_model->price_smt_mms,
        //     "price_rcs"=>$CI->biz_dhn_model->price_rcs,
        //     "price_rcs_sms"=>$CI->biz_dhn_model->price_rcs_sms,
        //     "price_rcs_mms"=>$CI->biz_dhn_model->price_rcs_mms,
        //     "price_rcs_tem"=>$CI->biz_dhn_model->price_rcs_tem,
        //     "price_015"=>$CI->biz_dhn_model->price_015,
        //     "price_mms"=>$CI->biz_dhn_model->price_mms
        // );
        $result['coin'] = $CI->biz_dhn_model->getAbleBCoin($id, $uid);
        // $result = $CI->biz_dhn_model->getAbleVCoin($id, $uid);
        return $result;
    }

    public function kvdFlag($id) {
        $CI =& get_instance();
        $sql = " SELECT count(1) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        $kvd_cnt = $CI->db->query($sql)->row()->cnt;

        $kvd_flag = "";
        if($kvd_cnt>0){
            $sql = " SELECT kvd_proc_flag FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
            $kvd = $CI->db->query($sql)->row();
            $kvd_flag = $kvd->kvd_proc_flag;
        }

        return $kvd_flag;
    }

    public function uptVCoin($id, $v_coin) {
        $CI =& get_instance();
        $sql = " SELECT count(1) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
        $kvd_cnt = $CI->db->query($sql)->row()->cnt;
        $kvd_cash = 0;
        if($kvd_cnt>0){
            $sql = " SELECT * FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$id;
            $kvd = $CI->db->query($sql)->row();
            $kvd_cash = $kvd->kvd_remain_cash;
            $kvd_flag = $kvd->kvd_proc_flag;

            if($v_coin<0 && $v_coin != $kvd_cash&&$kvd_flag=='N'){
                $sql = "UPDATE igenie.cb_kvoucher_deposit
                        SET kvd_remain_cash='".$v_coin."', kvd_minus_date = now()
                        WHERE kvd_mem_id = '".$id."'";
                $CI->db->query($sql);
            }
        }
    }

    public function getSent($id) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_dhn_model');
        }
        $result['sent'] = $CI->biz_dhn_model->getTodaySent($id);
        return $result;
    }

    public function getParent($id)
    {
        $CI =& get_instance();
        $sql = "
			select mrg_recommend_mem_id
			from cb_member_register where mem_id = '".$id."' limit 1
		";
        $result =  $CI->db->query($sql)->row()->mrg_recommend_mem_id;

        return $result;
    }

    /* 기본 단가 수집 */
    public function getBasePrice()
    {
        $CI =& get_instance();
        $sql = "
			select wst_mem_id mad_mem_id, '' mad_free_hp,
				wst_price_ft mad_price_ft, wst_price_ft_img mad_price_ft_img, wst_price_at mad_price_at, wst_price_sms mad_price_sms,
				wst_price_lms mad_price_lms, wst_price_mms mad_price_mms, wst_price_phn mad_price_phn,
				0 payback_at, 0 payback_ft, 0 payback_ft_img, 0 payback_phn, 0 payback_sms, 0 payback_lms, 0 payback_mms
			from cb_wt_setting limit 1
		";
        $result =  $CI->db->query($sql)->result_array();
        if(count($result) > 0) return $result[0];
        return null;
    }
    /* 상위 관리자의 단가를 반환 : array */
    public function getParentPrice($mem_id)
    {
        $CI =& get_instance();
        $sql = "
			select b.mem_userid, b.mem_level, i.*
				,(i.mad_price_at - ifnull(a.mad_price_at, i.mad_price_at)) payback_at
				,(i.mad_price_ft - ifnull(a.mad_price_ft, i.mad_price_ft)) payback_ft
				,(i.mad_price_ft_img - ifnull(a.mad_price_ft_img, i.mad_price_ft_img)) payback_ft_img
				,(i.mad_price_phn - ifnull(a.mad_price_phn, i.mad_price_phn)) payback_phn
				,(i.mad_price_sms - ifnull(a.mad_price_sms, i.mad_price_sms)) payback_sms
				,(i.mad_price_lms - ifnull(a.mad_price_lms, i.mad_price_lms)) payback_lms
				,(i.mad_price_mms - ifnull(a.mad_price_mms, i.mad_price_mms)) payback_mms
			from
				cb_wt_member_addon i left join
				cb_wt_member_addon a on 1=1 inner join
				cb_member b on a.mad_mem_id=b.mem_id inner join
				(
					SELECT  @r AS _id, (SELECT  @r := mrg_recommend_mem_id FROM cb_member_register WHERE mem_id = _id ) AS mrg_recommend_mem_id
					FROM
						(SELECT  @r := ".$mem_id.", @cl := 0) vars,	cb_member_register h
					WHERE    @r <> 0
				) c on a.mad_mem_id=c.mrg_recommend_mem_id
			where i.mad_mem_id=".$mem_id." and b.mem_level < 100
			order by b.mem_level desc
		";

        $result =  $CI->db->query($sql)->result_array();
        if(count($result) > 0) return $result[0];
        return null;
    }
    /* 지정된 mem_id의 단가를 반환 : array */
    public function getMemberPrice($mem_id)
    {
        $CI =& get_instance();
        $sql = "
			select b.mem_userid, b.mem_level, a.*,
				a.mad_price_phn phn1, a.mad_price_sms sms1, a.mad_price_lms lms1, a.mad_price_mms mms1,
				a.mad_price_phn phn2, a.mad_price_sms sms2, a.mad_price_lms lms2, a.mad_price_mms mms2,
				a.mad_price_phn phn3, a.mad_price_sms sms3, a.mad_price_lms lms3, a.mad_price_mms mms3
			from
				cb_wt_member_addon a inner join
				cb_member b on a.mad_mem_id=b.mem_id
			where a.mad_mem_id=".$mem_id;

        $result =  $CI->db->query($sql)->result_array();
        if(count($result) > 0) {
            if($result[0]["mem_level"] >= 100) {
                /* 각 에이전트별 대형의 단가를 추가 */
                $agent = array("prcom"=>"1", "dooit"=>"2");
                $sql = "select * from cb_wt_adm_price";
                $query = $CI->db->query($sql);
                if($query) {
                    $price = $query->result();
                    foreach($price as $p) {
                        $result[0]["phn".$agent[$p->adm_agent]] = $p->adm_price_phn;
                        $result[0]["sms".$agent[$p->adm_agent]] = $p->adm_price_sms;
                        $result[0]["lms".$agent[$p->adm_agent]] = $p->adm_price_lms;
                        $result[0]["mms".$agent[$p->adm_agent]] = $p->adm_price_mms;
                    }
                }
            }
            return $result[0];
        }
        return null;
    }

    function getTodaySent($mem_id)
    {
        $CI =& get_instance();
        $sql = "SELECT ifnull(sum(mst_qty), 0) as sent_qty FROM dhn.cb_wt_msg_sent
			where mst_mem_id=? and (substr(mst_reserved_dt, 1, 8)='".date('Ymd')."'
				or (substr(mst_datetime, 1, 10)='".date('Y-m-d')."' and mst_reserved_dt='00000000000000'))";
        $result = $CI->db->query($sql, array($mem_id))->row();
        return $result->sent_qty;
    }


    /**
     * 남은 일수 반환
     *
     * @author      webthink Inc. - darkskill
     * @version     0.0.1
     * @param       string	날짜 Y-m-d
     * @param       string	날짜 Y-m-d (default 오늘날짜)
     * @return      int
     */
    public function get_datediff($date, $now="") {
        if(empty($now)) $now = date("Y-m-d");
        return ( strtotime($date) - strtotime($now) ) / 86400;
    }

    function strip_date($d, $len=8) {
        return substr('00000000'.str_replace(array('/', '-', '.', ' '), '', $d), $len*-1);
    }

    function format_date($d, $str='-', $len=8) {
        $d = $this->strip_date($d, $len);
        if($len==2 && strlen($d)>=2) { return substr($d, -2); }
        if($len==4 && strlen($d)>=4) { return substr(substr($d, -4), 0, 2).$str.substr($d, -2); }
        if($len==14 && strlen($d)>=14) { return substr($d, 0, 4).$str.substr($d, 4, 2).$str.substr($d, 6, 2)." ".substr($d, 8, 2).":".substr($d, 10, 2).":".substr($d, -2); }
        return substr($d, 0, 4).$str.substr($d, 4, 2).$str.substr($d, -2);
    }

    /* 내장 json_decode는 PHP 버전 5.2 이상에서 지원 */
    function json_decode($json)
    {
        $comment = false;
        $out = '$x=';

        for ($i=0; $i<strlen($json); $i++)
        {
            if (!$comment)
            {
                if ($json[$i] == '{' || $json[$i] == '[')        $out .= ' array(';
                else if ($json[$i] == '}' || $json[$i] == ']')    $out .= ')';
                else if ($json[$i] == ':')    $out .= '=>';
                else                         $out .= $json[$i];
            }
            else $out .= $json[$i];
            if ($json[$i] == '"')    $comment = !$comment;
        }
        eval($out.';');
        return $x;
    }

    /* 내장 json_encode는 PHP 버전 5.2 이상에서 지원 */
    function json_encode($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a))
            {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = $this->json_encode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = $this->json_encode($k).':'.$this->json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }

    /* 검색결과 json 반환 */
    function autocomplete_value_to_json($ars_sql, $ars_keytext, $aro_field)
    {
        $CI =& get_instance();
        $query = $CI->db->query($ars_sql);
        if ($query->num_rows() > 0) {
            $lo_result =  $query->result();
            foreach($lo_result as $tr):
            if(is_array($aro_field)) {
                $n = 0;
                $a = array();
                foreach($aro_field as $fn):
                if($n==0) {
                    $a["value"] = $tr->$fn;
                } else if($n==1) {
                    //$a["label"] = str_replace($ars_keytext, "<font color='red'>".$ars_keytext."</font>", $tr->$fn);
                    $a["label"] = $tr->$fn;
                } else {
                    $a["val".$n] = $tr->$fn;
                }
                $n++;
                endforeach;
                $lo_arr[] = $a;
            }
            else {
                //$lo_arr[] = array("value"=>$tr->I_SEQ, "label"=>str_replace($ars_keytext, "<font color='red'>".$ars_keytext."</font>", $tr->$aro_field));
                $lo_arr[] = array("value"=>$tr->I_SEQ, "label"=>$tr->$aro_field);
            }
            endforeach;
            return $this->json_encode($lo_arr);
        }
        return "";
    }

    function get_page_name() {
        $CI =& get_instance();
        $ls_url = $CI->input->server('PHP_SELF');
        return substr(strrchr($ls_url, '/'), 1);
    }

    public function fc_mailing($Mail_Name,$Mail_From,$Mail_To,$Mail_Subject,$Mail_Contents, $ars_html=false, $ars_charset='', $Mail_ToName=''){
        if($Mail_From != '' && $Mail_To != '' && $Mail_Subject != '' && $Mail_Contents != '') {
            if(empty($Mail_ToName)) $Mail_ToName = $Mail_To;
            $CI =& get_instance();
            $CI->load->library('sendmail');
            $dMail = $CI->sendmail;
            $dMail->setUseSMTPServer(true);
            $dMail->setFrom($Mail_From, $Mail_Name);
            $dMail->setSubject($Mail_Subject);
            $dMail->setMailBody($Mail_Contents, $ars_html);
            $dMail->addTo($Mail_To, $Mail_ToName);
            $dMail->send();
        }
    }

    public function fc_mailing_attach($Mail_Name,$Mail_From,$Mail_To,$Mail_Subject,$Mail_Contents, $ars_html=false, $Mail_Attach, $Attach_Name, $nl2br=''){
        if($Mail_From != '' && $Mail_From != '' && $Mail_Subject != '' && $Mail_Contents != '') {
            $CI =& get_instance();
            $CI->load->library('sendmail');
            $dMail = $CI->sendmail;
            $dMail->setUseSMTPServer(true);
            $dMail->setFrom($Mail_From, $Mail_Name);
            $dMail->setSubject($Mail_Subject);
            if($nl2br) $dMail->setMailBody($Mail_Contents, $ars_html, 1);
            else $dMail->setMailBody($Mail_Contents, $ars_html);
            if(!empty($Mail_Attach)) {
                if(strpos(strtolower($Mail_Attach), "<body")===false) {
                    $Mail_Attach = '<!DOCTYPE html><html lang="ko"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head></body>'.$Mail_Attach.'</body></html>';
                }
                $fp = fopen($CI->input->server("DOCUMENT_ROOT").'/system/temp/sheet.html','w+');
                fwrite($fp, $Mail_Attach);
                fclose($fp);
                $dMail->addAttach($Attach_Name, $CI->input->server("DOCUMENT_ROOT").'/system/temp/sheet.html');
            }
            $dMail->addTo($Mail_To, $Mail_To);
            $dMail->send();
        }
    }

    public function fc_check_mobile() {
        global $HTTP_USER_AGENT;
        $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

        $checkCount = 0;
        for($i=0; $i<sizeof($MobileArray); $i++){
            if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
        }
        return ($checkCount >= 1) ? 1 : 0;
    }

    function POST_FIELDS()
    {

        $result = array();
        foreach($_POST as $key => $value) //POST용
        {
            if(substr($key, 0, 3)=="_f_") {
                //$$key = $value; // register_globals option 편하게(?) 사용하기 위한 부분
                $fn = substr($key, 3);
                if(!is_array($value)) {
                    $val = $this->security->xss_clean($value);
                    $result[$fn] = (substr($fn, 0, 2)=="N_") ? str_replace(",", "", $val) : $val;
                }
                else
                {
                    for($a=0; $a < sizeof($value); $a++) {
                        $val = $this->security->xss_clean($value[$a]);
                        $result[$fn."_".$a] = (substr($fn, 0, 2)=="N_") ? str_replace(",", "", $val) : $val;
                    }
                }
            }
        }
        return $result;
    }

    function POST_FIELDS_DTL()
    {
        $result = Array();
        foreach($_POST as $key => $value) //POST용
        {
            if(substr($key, 0, 4)=="_df_") {
                //$$key = $value;
                $key = substr($key, 4);

                if(!is_array($value)) {
                    if(strpos($key, "-")!==false) {
                        $val = $this->security->xss_clean($value);
                        $result[substr($key, strrpos($key, "-")+1)][substr($key, 0, strrpos($key, "-"))] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                    } else {
                        $result[$key] = $this->security->xss_clean($value);
                    }
                }
                else
                {
                    for($a=0; $a < sizeof($value); $a++)
                    {
                        if(strpos($key, "-")!==false) {
                            $val = $this->security->xss_clean($value[$a]);
                            $result[substr($key, strrpos($key, "-")+1)][substr($key, 0, strrpos($key, "-"))."_".$a] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                        } else {
                            $val = $this->security->xss_clean($value);
                            $result[$key."_".$a] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                        }
                    }
                }
            }
        }
        return $result;
    }

    function GET_FIELDS()
    {

        $result = array();
        foreach($_GET as $key => $value) //GET용
        {
            if(substr($key, 0, 3)=="_f_") {
                $fn = substr($key, 3);
                if(!is_array($value)) {
                    $val = $this->security->xss_clean($value);
                    $result[$fn] = (substr($fn, 0, 2)=="N_") ? str_replace(",", "", $val) : $val;
                }
                else
                {
                    for($a=0; $a < sizeof($value); $a++) {
                        $val = $this->security->xss_clean($value[$a]);
                        $result[$fn."_".$a] = (substr($fn, 0, 2)=="N_") ? str_replace(",", "", $val) : $val;
                    }
                }
            }
        }
        return $result;
    }

    function GET_FIELDS_DTL()
    {
        $result = Array();
        foreach($_GET as $key => $value) //GET용
        {
            if(substr($key, 0, 4)=="_df_") {
                $key = substr($key, 4);

                if(!is_array($value)) {
                    if(strpos($key, "-")!==false) {
                        $val = $this->security->xss_clean($value);
                        $result[substr($key, strrpos($key, "-")+1)][substr($key, 0, strrpos($key, "-"))] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                    } else {
                        $result[$key] = $this->security->xss_clean($value);
                    }
                }
                else
                {
                    for($a=0; $a < sizeof($value); $a++)
                    {
                        if(strpos($key, "-")!==false) {
                            $val = $this->security->xss_clean($value[$a]);
                            $result[substr($key, strrpos($key, "-")+1)][substr($key, 0, strrpos($key, "-"))."_".$a] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                        } else {
                            $val = $this->security->xss_clean($value);
                            $result[$key."_".$a] = ((substr($key, 0, 2)=="N_") ? str_replace(",", "", $val) : $val);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function SET_FIELD($arr)
    {
        echo("<div style=\"display:none;\">\r\n");
        foreach($arr as $key => $value) {
            echo("<span class=\"value _sf_".$key."\" tag=\"".$key."\">".$value."</span>\r\n");
        }
        echo("</div>");
    }

    public function number2hangul($number){

        $num = array('', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구');
        $unit4 = array('', '만', '억', '조', '경');
        $unit1 = array('', '십', '백', '천');

        $res = array();

        $number = str_replace(',','',$number);
        $split4 = str_split(strrev((string)$number),4);

        for($i=0;$i<count($split4);$i++){
            $temp = array();
            $split1 = str_split((string)$split4[$i], 1);
            for($j=0;$j<count($split1);$j++){
                $u = (int)$split1[$j];
                if($u > 0) $temp[] = $num[$u].$unit1[$j];
            }
            if(count($temp) > 0) $res[] = implode('', array_reverse($temp)).$unit4[$i];
        }
        return implode('', array_reverse($res));
    }

    // 다차원 배열 정렬
    public function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                    $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    //base64 암호화
    public function encrypt($string) {
        $key = "base64_dhn1!"; //암오화 키
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    //base64 복호화
    public function decrypt($string) {
        $key = "base64_dhn1!"; //암오화 키
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    //브라우저 버전 확인
    public function fnBrowser($userAgent){
        $browser = "";
        if ( preg_match("/MSIE*/", $userAgent) ) { //Explorer 6 ~ 10 or ETC
            if ( preg_match("/MSIE 6.0[0-9]*/", $userAgent) ) {
                $browser = "Explorer 6";
            }elseif ( preg_match("/MSIE 7.0*/", $userAgent) ) {
                $browser = "Explorer 7";
            }elseif ( preg_match("/MSIE 7.0*/", $userAgent) ) {
                $browser = "Explorer 8";
            }elseif ( preg_match("/MSIE 8.0*/", $userAgent) ) {
                $browser = "Explorer 8";
            }elseif ( preg_match("/MSIE 9.0*/", $userAgent) ) {
                $browser = "Explorer 9";
            }elseif ( preg_match("/MSIE 10.0*/", $userAgent) ) {
                $browser = "Explorer 10";
            }else{
                $browser = "Explorer ETC"; //익스플로러 기타
            }
        } elseif ( preg_match("/Trident*/", $userAgent) && preg_match("/rv:11.0*/", $userAgent) && preg_match("/Gecko*/", $userAgent) ) {
            $browser = "Explorer 11";
        } elseif ( preg_match("/(Mozilla)*/", $userAgent) ) {
            $browser = "mozilla"; //모질라 (파이어폭스)
        } elseif ( preg_match("/(Nav|Gold|X11|Mozilla|Nav|Netscape)*/", $userAgent) ) {
            $browser = "Netscape/mozilla"; //네스케이프, 모질라(파이어폭스)
        } elseif ( preg_match("/Opera*/", $userAgent) ) {
            $browser = "Opera"; //오페라
        } else {
            $browser = "Other";
        }
        return $browser;
    }

    //익스플로러 여부
    public function fnIeYn($userAgent){
        $rtn = "N";
        $browser = $this -> fnBrowser($userAgent);
        if ( preg_match("/Explorer*/", $browser) ) {
            $rtn = "Y";
        }
        return $rtn;
    }

    //파일 사이즈 반환 (파일사이즈, 소수점)
    public function fnFileSize($file_size, $decimal = 1){
        $size = $file_size;
        if($file_size < 1024){
            $size = number_format($file_size * 1.024).'byte';
        }else if(($size > 1024) && ($file_size < 1024000)){
            $size = number_format($file_size * 0.001024, $decimal).'KB';
        }else if($size > 1024000){
            $size = number_format($file_size * 0.000001024, $decimal).'MB';
        }
        return $size;
    }

    //curl 통신 확인
    public function curl_alive($chkUrl, $timeout = 10){
        $handles = curl_init($chkUrl);
        curl_setopt($handles, CURLOPT_NOBODY, true);
        curl_setopt($handles, CURLOPT_TIMEOUT, $timeout);
        curl_exec($handles);
        $code = curl_getinfo($handles, CURLINFO_HTTP_CODE);
        curl_close($handles);
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > code : ". $code);
        $rtn = 0;
        if($code > 0) $rtn = 1; //통신성공
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > rtn : ". $rtn);
        return $rtn;
    }

    //숫자 이외 제거
    public function numbers_only($num){
        $num = preg_replace("/[^0-9]/", "", $num); // 숫자 이외 제거
        return $num;
    }

    //DHN 은행계좌 리턴
    public function fnBankInfo($bank_info){
        if($bank_info == "52204693604037"){
            $rtn = "기업은행 522-046936-04-037 주식회사 대형네트웍스";
        }else{
            $rtn = $bank_info;
        }
        return $rtn;
    }

    //결제방법 2021-03-05
    public function get_charge_type($val){
        $rtn = "";
        if($val=="1"){
            $rtn = "현장결제(카드)";
        }else if($val=="2"){
            $rtn = "현장결제(현금)";
        }else if($val=="3"){
            $rtn = "현장결제(지역화폐)";
        }else if($val=="4"){
            $rtn = "계좌이체";
        }else if($val=="5"){
            $rtn = "카드결제";
        }else if($val=="6"){
            $rtn = "카드결제";
        }else if($val=="7"){
            $rtn = "현장결제";
        }
        return $rtn;
    }

    public function get_division_type($val){
        $rtn = "";
        if($val=="1"){
            $rtn = "배달";
        }else if($val=="2"){
            $rtn = "포장<br>(매장 방문)";
        }else{
            $rtn = '';
        }
        return $rtn;
    }

    //주문상태 2021-03-05
    public function user_order_status($val){
        $rtn = "";
        if($val=="0"){
            $rtn = "주문완료";
        }else if($val=="1"){
            $rtn = "상품준비중";
        }else if($val=="2"){
            $rtn = "배송중";
        }else if($val=="3"){
            $rtn = "배송완료";
        }else if($val=="4"){
            $rtn = "주문취소";
        }else if($val=="9"){
            $rtn = "주문대기";
        }
        return $rtn;
    }

    //진행상태 2021-03-05
    public function get_order_status($val){
        $rtn = "";
        if($val=="0"){
            $rtn = "신규주문";
        }else if($val=="1"){
            $rtn = "상품준비중";
        }else if($val=="2"){
            $rtn = "배송중";
        }else if($val=="3"){
            $rtn = "배송완료";
        }else if($val=="4"){
            $rtn = "주문취소";
        }else if($val=="9"){
            $rtn = "주문대기";
        }
        return $rtn;
    }

    //스마트전단 주문하기 사용여부 2021-03-05
    public function get_stmall_yn($mem_stmall_yn, $psd_order_yn, $psd_order_sdt, $psd_order_edt, $psd_order_st = null, $psd_order_et = null, $psd_order_alltime=null){
        //log_message("ERROR", "/application/libraries/Funn > Start");
        //echo "/application/libraries/Funn > Start<br>";
        $today = date("Y-m-d"); //오늘날짜
        //$mem_stmall_yn = "N"; //스마트전단 주문하기 사용여부
        //$psd_order_yn = "N"; //주문하기 사용여부
        //$psd_order_sdt = "2021-03-10"; //주문하기 시작일자
        //$psd_order_edt = "2021-03-04"; //주문하기 종료일자
        //echo "/application/libraries/Funn > mem_stmall_yn : ". $mem_stmall_yn ."<br>";
        //echo "/application/libraries/Funn > psd_order_yn : ". $psd_order_yn ."<br>";
        //echo "/application/libraries/Funn > psd_order_sdt : ". $psd_order_sdt ."<br>";
        //echo "/application/libraries/Funn > psd_order_edt : ". $psd_order_edt ."<br>";
        if($mem_stmall_yn == "N") $psd_order_yn = "N";
        //echo "psd_order_yn(2) : ". $psd_order_yn ."<br>";
        if($psd_order_yn == "Y"){
            if($psd_order_sdt > $today){
                $psd_order_yn = "N";
                //echo "시작전<br>";
            }else if($psd_order_edt < $today){
                $psd_order_yn = "N";
                //echo "판매중단<br>";
            }
        }
        if($psd_order_alltime=="N"||$psd_order_alltime==null){
            if ($psd_order_st != null && $psd_order_et != null){
                $today = date("Y-m-d H:i:s");
                $s_day = date($psd_order_sdt . " " . substr($psd_order_st, 0, 2) . ":" . substr($psd_order_st, 2, 2) . ":00");
                $e_day = date($psd_order_edt . " " . substr($psd_order_et, 0, 2) . ":" . substr($psd_order_et, 2, 2) . ":00");
                $str_now = strtotime($today);
                $str_s = strtotime($s_day);
                $str_e = strtotime($e_day);
                if ($str_now > $str_s && $str_now < $str_e){
                    $psd_order_yn = "Y";
                } else {
                    $psd_order_yn = "N";
                }
            }
        }

        $stmall_yn = $psd_order_yn;
        //echo "stmall_yn : ". $stmall_yn ."<br>";
        return $stmall_yn;
    }


    public function getTotalDeposit($mem_userid) {
        $CI =& get_instance();
        if(!$CI->biz_dhn_model) {
            $CI->load->model('biz_model');
            //log_message("ERROR", "TEST111111111111111 ");
        }

        $result = $CI->biz_model->getTotalDeposit($mem_userid);
        return $result;
    }

    //콤마(,)등으로 구분된 문자열에 특정 문자가 있는지 판단 2021-09-07
    public function is_str_in_char($separator, $src_string, $tar_char){
        $return_bool = false;
        $arr_string = explode($separator, $src_string);

        foreach($arr_string as $value) {
            if($value == $tar_char) {
                $return_bool = true;
                break;
            }
        }

        return $return_bool;
    }

    public function get_img_name($name, $option='') {
        $result = array();
        $wherewhere = "";
        $name_t = preg_replace("/\(|\)|\[|\]|\/|\"|\,|\_|\-|\+|\*/", ' ', $name);


        $name_t = str_replace('암소', '', $name_t );
        $name_t = str_replace('암돼지', '', $name_t );
        $name_t = str_replace('암퇘지', '', $name_t );
        $name_t = str_replace('1등급이상', '', $name_t );
        $name_t = str_replace('냉장', '', $name_t );
        $name_t = str_replace('프리미엄', '', $name_t );
        $name_t = str_replace('미국산', '', $name_t );
        $name_t = str_replace('국내산', '', $name_t );
        $name_t = str_replace('호주산', '', $name_t );
        $name_t = str_replace('베트남산', '', $name_t );
        $name_t = str_replace('산지직송', '', $name_t );

        $name_t = str_replace('중국산', '', $name_t );

        $name_t = str_replace('국산', '', $name_t );

        $name_t = str_replace('수입산', '', $name_t );

        $name_t = str_replace('수입', '', $name_t );

        $name_t = str_replace('기획', '', $name_t );

        if(strpos($name_t,'라면')== false){
            $name_t = str_replace('멀티', '', $name_t );
        }


        if(strpos($name_t,'★')!== false){
            $nastr = explode('★', trim($name_t));
            if(!empty($nastr[1])){
                $name_t = trim($nastr[1]);
            }
        }


        if(strpos($name_t,'절임배추')!== false){
            $option = "";
            $wherewhere .= " and img_name LIKE '%절임배추%' ";
        }


        // $name_t = str_replace('★', '', $name_t );
        //
        // $name_t = str_replace('개이상구매시', '', $name_t );


        $name_t = str_replace('신라면', ' 신라면 ', $name_t );

        $name_t = str_replace('비비고', '비비고 ', $name_t );

        $name_t = str_replace('동원', '동원 ', $name_t );

        $name_t = str_replace('팔도', '팔도 ', $name_t );

        $name_t = str_replace('백설', '백설 ', $name_t );
        $name_t = str_replace('해태', '해태 ', $name_t );
        $name_t = str_replace('농심', '농심 ', $name_t );

        $name_t = str_replace('송학', '송학 ', $name_t );
        $name_t = str_replace('CJ', 'CJ ', $name_t );
        $name_t = str_replace('풀무원', '풀무원 ', $name_t );

        $name_t = str_replace('롯데', '롯데 ', $name_t );

        $name_t = str_replace('백설', '백설 ', $name_t );
        $name_t = str_replace('청우', '청우 ', $name_t );
        $name_t = str_replace('큐원', '큐원 ', $name_t );
        $name_t = str_replace('맥심', '맥심 ', $name_t );
        $name_t = str_replace('대림', '대림 ', $name_t );
        $name_t = str_replace('삼호', '삼호 ', $name_t );

        $name_t = str_replace('오리온', '오리온 ', $name_t );
        $name_t = str_replace('오뚜기', '오뚜기 ', $name_t );
        $name_t = str_replace('엘지', '엘지 ', $name_t );

        $name_t = str_replace('테크', '테크 ', $name_t );
        $name_t = str_replace('리엔', '리엔 ', $name_t );
        $name_t = str_replace('해피바스', '해피바스 ', $name_t );
        $name_t = str_replace('피죤', '피죤 ', $name_t );
        $name_t = str_replace('다우니', '다우니 ', $name_t );
        $name_t = str_replace('메디안', '메디안 ', $name_t );
        $name_t = str_replace('잘풀리는집', '잘풀리는집 ', $name_t );

        $name_t = str_replace('뽀로로', '뽀로로 ', $name_t );
        $name_t = str_replace('쁘띠첼', '쁘띠첼 ', $name_t );
        $name_t = str_replace('고메', '고메 ', $name_t );
        $name_t = str_replace('비요뜨', '비요뜨 ', $name_t );
        $name_t = str_replace('샤프란', '샤프란 ', $name_t );
        $name_t = str_replace('내몸에', '내몸에 ', $name_t );
        $name_t = str_replace('파워에이드', '파워에이드 ', $name_t );
        $name_t = str_replace('게토레이', '게토레이 ', $name_t );
        $name_t = str_replace('스프라이트', '스프라이트 ', $name_t );

        $name_t = str_replace('맛술', '맛술 ', $name_t );

        $name_t = str_replace('청정원', '청정원 ', $name_t );

        $name_t = str_replace('햇반', '햇반 ', $name_t );

        $name_t = str_replace('그래놀라', '그래놀라 ', $name_t );
        $name_t = str_replace('몽쉘', '몽쉘 ', $name_t );
        $name_t = str_replace('에너지바', '에너지바 ', $name_t );
        $name_t = str_replace('단백질바', '단백질바 ', $name_t );

        $name_t = str_replace('크라운', '크라운 ', $name_t );

        $name_t = str_replace('닥터', '닥터 ', $name_t );

        $name_t = str_replace('카스타드', '카스타드 ', $name_t );

        $name_t = str_replace('썬칩', '썬칩 ', $name_t );

        $name_t = str_replace('빙그레', '빙그레 ', $name_t );

        $name_t = str_replace('햄스빌', '햄스빌 ', $name_t );

        $name_t = str_replace('하임', '하임 ', $name_t );
        $name_t = str_replace('연세', '연세 ', $name_t );


        $name_t = str_replace('푸르밀', '푸르밀 ', $name_t );
        $name_t = str_replace('매일', '매일 ', $name_t );
        $name_t = str_replace('상하', '상하 ', $name_t );
        $name_t = str_replace('미닛', '미닛 ', $name_t );
        $name_t = str_replace('웅진', '웅진 ', $name_t );
        $name_t = str_replace('산타페', '산타페 ', $name_t );
        $name_t = str_replace('코카콜라', '코카콜라 ', $name_t );
        $name_t = str_replace('배홍동', '배홍동 ', $name_t );
        $name_t = str_replace('라면', '라면 ', $name_t );
        $name_t = str_replace('왕뚜껑', '왕뚜껑 ', $name_t );
        $name_t = str_replace('엘라스틴', '엘라스틴 ', $name_t );
        $name_t = str_replace('리스테린', '리스테린 ', $name_t );
        $name_t = str_replace('콘트라', '콘트라 ', $name_t );

        $name_t = str_replace('대란', ' 대란 계란 ', $name_t );
        $name_t = str_replace('키친타올', ' 키친타올 ', $name_t );
        $name_t = str_replace('각티슈', ' 각티슈 ', $name_t );
        $name_t = str_replace('락스', ' 락스 ', $name_t );
        $name_t = str_replace('주물럭', ' 주물럭 ', $name_t );
        $name_t = str_replace('파스타', ' 파스타 ', $name_t );
        $name_t = str_replace('스파게티', ' 스파게티 ', $name_t );
        $name_t = str_replace('오렌지', ' 오렌지 ', $name_t );
        $name_t = str_replace('피자', ' 피자 ', $name_t );
        $name_t = str_replace('떡볶이', ' 떡볶이 ', $name_t );
        $name_t = str_replace('쌀국수', ' 쌀국수 ', $name_t );
        if(strpos($name_t,'순두부')!== false){
            $name_t = str_replace('순두부', ' 순두부 ', $name_t );
        }else{
            $name_t = str_replace('두부', ' 두부 ', $name_t );
        }
        $name_t = str_replace('우유', ' 우유 ', $name_t );
        $name_t = str_replace('두유', ' 두유 ', $name_t );
        $name_t = str_replace('식혜', ' 식혜 ', $name_t );
        $name_t = str_replace('김치', ' 김치 ', $name_t );
        $name_t = str_replace('참외', ' 참외 ', $name_t );

        $name_t = str_replace('감귤', ' 감귤 ', $name_t );

        if(strpos($name_t,'닭갈비')!== false){
            $name_t = str_replace('닭갈비', ' 닭갈비 ', $name_t );
        }else if(strpos($name_t,'닭가슴살')!== false){
            $name_t = str_replace('닭가슴살', ' 닭가슴살 ', $name_t );
        }else if(strpos($name_t,'닭볶음탕')!== false){
            $name_t = str_replace('닭볶음탕', ' 닭볶음탕 ', $name_t );
        }else if(strpos($name_t,'닭도리탕')!== false){
            $name_t = str_replace('닭도리탕', ' 닭도리탕 ', $name_t );
        }else{
            $name_t = str_replace('닭', ' 닭 ', $name_t );
        }

        if(strpos($name_t,'새우젓')!== false){
            $wherewhere .= " and img_name LIKE '%새우젓%' ";
            $name_t = str_replace('특', '', $name_t );

            if(!empty($option)){
                if(strpos($option,'3kg')!== false||strpos($option,'5kg')!== false){
                    $option = "";
                }
            }
        }


        $name_t = str_replace('코다리', ' 코다리 ', $name_t );

        $name_t = str_replace('특란', ' 특란 ', $name_t );

        $name_t = str_replace('가자미', ' 가자미 ', $name_t );

        if(strpos($name_t,'갑오징어')!== false){
            $name_t = str_replace('갑오징어', ' 갑오징어 ', $name_t );
        }else if(strpos($name_t,'오징어집')!== false){
            $name_t = str_replace('오징어집', ' 오징어집 ', $name_t );
        }else{
            $name_t = str_replace('오징어', ' 오징어 ', $name_t );
        }


        if(strpos($name_t,'벌집삼겹살')!== false){
            $name_t = str_replace('삼겹살', ' 삼겹살 ', $name_t );
        }else{
            $name_t = str_replace('벌집삼겹살', ' 벌집삼겹살 ', $name_t );
        }

        if(strpos($name_t,'미역국')!== false){
            $name_t = str_replace('미역국', ' 미역국 ', $name_t );
        }else{
            $name_t = str_replace('미역', ' 미역 ', $name_t );
        }
        // $name_t = str_replace('냉면', ' 냉면 ', $name_t );
        $name_t = str_replace('한우', ' 한우 ', $name_t );
        $name_t = str_replace('한돈', ' 한돈 ', $name_t );

        $name_t = str_replace('곰탕', ' 곰탕 ', $name_t );

        $name_t = str_replace('불고기', ' 불고기 ', $name_t );
        $name_t = str_replace('양념', ' 양념 ', $name_t );
        if(strpos($name_t,'진간장')!== false){
            $name_t = str_replace('진간장', ' 진간장 ', $name_t );
        }else{
            $name_t = str_replace('간장', ' 간장 ', $name_t );
        }
        $name_t = str_replace('고추장', ' 고추장 ', $name_t );
        $name_t = str_replace('샴푸', ' 샴푸 ', $name_t );
        $name_t = str_replace('컨디셔너', ' 컨디셔너 ', $name_t );
        $name_t = str_replace('물티슈', ' 물티슈 ', $name_t );
        $name_t = str_replace('바디워시', ' 바디워시 ', $name_t );
        $name_t = str_replace('복숭아', ' 복숭아 ', $name_t );
        $name_t = str_replace('딸기', ' 딸기 ', $name_t );
        $name_t = str_replace('사과', ' 사과 ', $name_t );
        if(strpos($name_t,'청포도')==false){
            $name_t = str_replace('포도', ' 포도 ', $name_t );
        }else{
            $name_t = str_replace('청포도', ' 청포도 ', $name_t );
        }

        $name_t = str_replace('장아찌', ' 장아찌 ', $name_t );

        if(strpos($name_t,'고기만두')!== false){
            $name_t = str_replace('고기만두', ' 고기만두 ', $name_t );
        }else if(strpos($name_t,'김치만두')!== false){
            $name_t = str_replace('김치만두', ' 김치만두 ', $name_t );
        }else{
            $name_t = str_replace('만두', ' 만두 ', $name_t );
        }

        if(strpos($name_t,'햅쌀')!== false){
            $name_t = str_replace('햅쌀', ' 햅쌀 ', $name_t );
        }else if(strpos($name_t,'찹쌀')!== false){
            $name_t = str_replace('찹쌀', ' 찹쌀 ', $name_t );
        }else{
            $name_t = str_replace('쌀', ' 쌀 ', $name_t );
        }
        // $name_t = str_replace('포도', ' 포도 ', $name_t );
        $name_t = str_replace('블루베리', ' 블루베리 ', $name_t );
        $name_t = str_replace('파인애플', ' 파인애플 ', $name_t );
        $name_t = str_replace('돈가스', ' 돈가스 ', $name_t );
        $name_t = str_replace('돈까스', ' 돈까스 ', $name_t );
        $name_t = str_replace('쏘피', ' 쏘피 ', $name_t );
        $name_t = str_replace('액체세제', ' 액체세제 ', $name_t );
        $name_t = str_replace('후랑크', ' 후랑크 ', $name_t );
        $name_t = str_replace('단무지', ' 단무지 ', $name_t );
        $name_t = str_replace('어묵', ' 어묵 ', $name_t );
        $name_t = str_replace('베이컨', ' 베이컨 ', $name_t );
        $name_t = str_replace('깁밥햄', ' 김밥햄 ', $name_t );
        $name_t = str_replace('비엔나', ' 비엔나 ', $name_t );

        $name_t = str_replace('둥지', ' 둥지 ', $name_t );


        $name_t = str_replace('kg', '', $name_t );
        $name_t = str_replace('ml', '', $name_t );

        $name_t = preg_replace('/[0-9]/', ' ', $name_t);

        $name_t = preg_replace('/[ ]{2,}/', ' ', $name_t);

        if(strpos($name_t,'주스')!== false){
            $wherewhere .= " and img_name LIKE '%주스%' ";
        }

        if(strpos($name_t,'에이드')!== false){
            $wherewhere .= " and img_name LIKE '%에이드%' ";
        }

        if(strpos($name_t,'우유')!== false){
            $wherewhere .= " and img_name LIKE '%우유%' ";
        }

        if(strpos($name_t,'두유')!== false){
            $wherewhere .= " and img_name LIKE '%두유%' ";
        }

        if(strpos($name_t,'샘표')!== false){
            $wherewhere .= " and img_name LIKE '%샘표%' ";
        }

        if(strpos($name_t,'간장')!== false){
            $wherewhere .= " and img_name LIKE '%간장%' ";
        }

        if(strpos($name_t,'치즈')!== false){
            $wherewhere .= " and img_name LIKE '%치즈%' ";
        }

        if(strpos($name_t,'LG')!== false&&strpos($name_t,'테크')!== false){
            $name_t = str_replace('LG', '', $name_t );
            $wherewhere .= " and img_name LIKE '%테크%' ";
            if(!empty($option)){
                if(strpos($option,'*')!== false){
                    $opstr = explode('*', trim($option));
                    if(!empty($opstr[0])){
                        $option = trim($opstr[0]);
                    }
                }

            }

        }

        if(strpos($name_t,'클린앤리필')!== false){
            $name_t = str_replace('클린앤리필', ' 클린 ', $name_t );
        }

        if(strpos($name_t,'천일염')!== false){

            if(strpos($name_t,'두부')== false&&strpos($name_t,'부침')== false){
                $wherewhere .= " and img_name NOT LIKE '%두부%' and img_name NOT LIKE '%부침%' ";
            }

            if(strpos($name_t,'닭')== false){
                $wherewhere .= " and img_name NOT LIKE '%닭%' ";
            }

            // if(strpos($name_t,'세제')== false){
            //     $wherewhere .= " and img_name NOT LIKE '%세제%' ";
            // }

        }

        if(strpos($name_t,'콩나물')!== false){
            if(!empty($option)){
                if(strpos($option,'박스')!== false){
                    $wherewhere .= " and img_name LIKE '%박스%' ";
                    $option = "";
                }
            }
        }

        if(strpos($name_t,'환만식초')!== false){
            $name_t = str_replace('말통', '', $name_t );
            if(!empty($option)){
                if(strpos($option,'15')!== false||strpos($option,'15L')!== false){
                    $wherewhere .= " and img_name LIKE '%15%' ";
                }
            }
        }

        if(strpos($name_t,'밴댕이젓')!== false){
            if(!empty($option)){
                $option = "";
            }
        }

        if(!empty($option)){
            if(strpos($option,'kg')!== false||strpos($option,'L')!== false||strpos($option,'ml')!== false){
                $wherewhere .= " and img_name LIKE '%".$option."%' ";
            }
        }



        if(strpos($name_t,'라면')!== false){
            $wherewhere .= " and img_name LIKE '%라면%' ";
            if(!empty($option)){
                if(strpos($option,'멀티')!== false){
                    $wherewhere .= " and img_name LIKE '%멀티%' ";
                }
            }

        }

        if(strpos($name_t,'신라면')!== false){
            $wherewhere .= " and img_name LIKE '%신라면%' ";
        }

        if(strpos($name_t,'오뜨')!== false){
            if(strpos($name_t,'치즈')!== false&&strpos($name_t,'쇼콜라')!== false){
                $name_t = str_replace('쇼콜라', '', $name_t );
                $wherewhere .= " and img_name LIKE '%초코%' ";
            }
        }

        if(strpos($name_t,'안성탕면')!== false||strpos($name_t,'너구리')!== false||strpos($name_t,'짜파게티')!== false){
            if(!empty($option)){
                if(strpos($option,'6입')!== false||strpos($option,'6개입')!== false){
                    $wherewhere .= " and (img_name LIKE '%6입%' OR img_name LIKE '%6개입%') ";
                }else if(strpos($option,'5입')!== false||strpos($option,'5개입')!== false){
                    $wherewhere .= " and (img_name LIKE '%5입%' OR img_name LIKE '%5개입%') ";
                }else if(strpos($option,'멀티')!== false){
                    $wherewhere .= " and img_name LIKE '%멀티%' ";
                }

                if(strpos($name_t,'순한')!== false){
                    $wherewhere .= " and img_name LIKE '%순한%' ";
                }

                if(strpos($name_t,'얼큰')!== false){
                    $wherewhere .= " and img_name LIKE '%얼큰%' ";
                }
            }
        }

        if(strpos($name_t,'트레비')!== false||strpos($name_t,'백산수')!== false||strpos($name_t,'요플레')!== false||strpos($name_t,'조지아')!== false||strpos($name_t,'뼈로가는')!== false){
            if(strpos($name_t,'뼈로가는')!== false){
                $wherewhere .= " and img_name LIKE '%뼈로가는%' ";
                if(strpos($name_t,'치즈')!== false){
                    $wherewhere .= " and img_name LIKE '%치즈%' ";
                }
            }
            if(!empty($option)){
                if(strpos($option,'10')!== false||strpos($option,'10매')!== false){
                    $wherewhere .= " and img_name LIKE '%10%' ";
                }else{
                    $wherewhere .= " and img_name LIKE '%".$option."%' ";
                }
            }
        }

        if(strpos($name_t,'푸르밀')!== false){

            if(!empty($option)){
                if(strpos($option,'4')!== false||strpos($option,'4입')!== false){
                    $wherewhere .= " and img_name LIKE '%4입%' ";
                }
            }
        }

        if(strpos($name_t,'장액티브')!== false){
            if(strpos($name_t,'샤인머스켓')!== false){
                $name_t = str_replace('샤인머스켓', '샤인머스캣', $name_t );
            }
        }

        // if(strpos($name_t,'장액티브')!== false){
        //     if(strpos($name_t,'샤인머스켓')!== false){
        //         $name_t = str_replace('샤인머스켓', '샤인머스캣', $name_t );
        //     }
        // }

        if(strpos($name_t,'새우탕')!== false||strpos($name_t,'튀김우동')!== false||strpos($name_t,'우육탕')!== false){
            if(!empty($option)){
                if(strpos($option,'6입')!== false||strpos($option,'6개입')!== false){
                    $wherewhere .= " and (img_name LIKE '%6입%' OR img_name LIKE '%6개입%') ";
                }
            }
        }

        // if(strpos($name_t,'열라면')!== false&&strpos($name_t,'마열라면')== false){
        if(strpos($name_t,'열라면')!== false){
            $wherewhere .= " and img_name LIKE '%열라면%' ";
            if(!empty($option)){
                if(strpos($option,'5입')!== false||strpos($option,'5개입')!== false){
                    $wherewhere .= " and (img_name LIKE '%5입%' OR img_name LIKE '%5개입%') ";
                }
            }
            if(strpos($name_t,'참깨')== false){
                $wherewhere .= " and img_name NOT LIKE '%참깨%' ";
            }
        }

        if(strpos($name_t,'진라면')!== false){

            $name_t = str_replace('오뚜기', '', $name_t );
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > option : ". $option);
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > wherewhere0 : ". $wherewhere);
            if(!empty($option)){
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > option0");
                if(strpos($option,'6입')!== false||strpos($option,'6개입')!== false){
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > option1");
                    $wherewhere .= " and (img_name LIKE '%6입%' OR img_name LIKE '%6개입%') ";
                }else if(strpos($option,'5입')!== false||strpos($option,'5개입')!== false){
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > option2");
                    $wherewhere .= " and (img_name LIKE '%5입%' OR img_name LIKE '%5개입%') ";
                }
            }

            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_img_name > wherewhere1 : ". $wherewhere);


            if(strpos($name_t,'매운')!== false){
                $wherewhere .= " and img_name NOT LIKE '%순한%' ";
            }

            if(strpos($name_t,'순한')!== false){
                $wherewhere .= " and img_name NOT LIKE '%매운%' ";
            }

            if(strpos($name_t,'컵')!== false){
                $wherewhere .= " and  img_name LIKE '%컵%' ";
            }else{
                $wherewhere .= " and img_name NOT LIKE '%컵%' ";
            }

            // $wherewhere .= " and img_name LIKE '%진라면%' ";



        }

        if(strpos($name_t,'둥지')!== false){
            $wherewhere .= " and img_name LIKE '%둥지%' ";
        }

        if(strpos($name_t,'카레')!== false){
            $wherewhere .= " and img_name LIKE '%카레%' ";
        }

        if(strpos($name_t,'백세카레')!== false){
            $wherewhere .= " and img_name LIKE '%백세카레%' ";
        }

        if(strpos($name_t,'햇반')!== false){
            $wherewhere .= " and img_name LIKE '%햇반%' ";
        }

        if(strpos($name_t,'컵반')!== false){
            $wherewhere .= " and img_name LIKE '%컵반%' ";
        }

        $result["name_t"] = $name_t;
        $result["wherewhere"] = $wherewhere;

        return $result;
    }

    //Kakao Channel Public Id 가져오기
    public function get_kakao_channel_public_id($uuid) {
        $url = "http://plus-talk.kakao.com/plus/home/";

        $tmp_uuid = rawurlencode($uuid);
        $url .= $tmp_uuid;

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_kakao_channel_public_id > url : ". $url);

        $header_data = array(
            //            'Authorization: Bearer '.$accesstoken,
            'Content-Type: application/html; charset=utf-8'
        );

        $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
        curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
        curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1)
        $res = curl_exec ($ch);

        curl_close($ch);



        $channel_url = substr($res, strpos($res, "http://pf.kakao.com/"));
        //$channel_url_end = strpos($channel_url, "\n");
        $channel_url = substr($channel_url, 0, strpos($channel_url, "\n") - 1);
        $public_id = substr($channel_url, strpos($channel_url, "m/") + 2);

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > get_kakao_channel_public_id > public_id : ". $public_id);

        return $public_id;
    }

    public function get_kakao_key(){
        $days = date('j');
        return config_item('kakao_keys')[($days-1)];
    }
}
