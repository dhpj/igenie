<?php
class Sw extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Biz');

	/**
	* 헬퍼를 로딩합니다
	*/
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'simple_html_dom'));
	}

	public function index()
	{
		$this->load->library('sweettracker');

		$pf_key = "f247f3f0e0df5a367cc433c7d280326948460da6";
		//echo '<pre> :: ';print_r($this->sweettracker->template_list($pf_key));
		//echo '<pre> :: ';print_r($this->sweettracker->sender("dhn"));


exit;
		//echo '<pre> :: ';print_r($this->sweettracker->upload_image("761283e30af0bba98fb0c802a60f6b06619210ff", "/chroot/_dhn/public_html/uploads/user_images/2017/11/15c5cde83d7a75ed5b2b904cb845a8ac.jpg"));
		//echo '<pre> :: ';print_r($this->sweettracker->sender_token("@에이치엠피", "01093111339"));
		//echo '<pre> :: ';print_r($this->sweettracker->sender("dhn"));
		$pf_key = "6d13a537f098651522d55256fab3576bcfc37f66";
		$tpl_code="mart1";
		$tmpl = $this->sweettracker->template($pf_key, $tpl_code);
		$this->db->query("update cb_wt_template_dhn set tpl_contents=?, tpl_button=? where tpl_profile_key=? and tpl_code=?", array($tmpl->data->templateContent, $tmpl->data->buttons, $pf_key, $tpl_code));
		echo '<pre> :: ';print_r($this->db->error());
		echo '<pre> :: ';print_r($tmpl);
		echo '<pre> :: ';print_r($this->sweettracker->template_list("6d13a537f098651522d55256fab3576bcfc37f66"));
exit;

		$data = array();
		$data['msgid'] = 'dhn20171124000015';						// - text(20) Y 메시지 일련번호(메시지에 대해 고유한 값이어야 함)
		$data['message_type'] = 'at';			// - text(2) N 메시지 타입(at: 알림톡, ft: 친구톡)
		$data['message_group_code'] = 'AD_20171123';	// -  text(30) N 메시지 유형을 구분하기 위한 값
		//$data['profile_key'] = '0b796447e8f8613d3ade096a5c23120b069124a9';				// -  text(40) Y 발신프로필키(메시지 발송 주체인 플러스친구에 대한 키)
		$data['profile_key'] = '89823b83f2182b1e229c2e95e21cf5e6301eed98'; // 실제
		$data['template_code'] = 'alimtalktest_004';			// -  text(10) Y 메시지 유형을 확인할 템플릿 코드(사전에 승인된 템플릿의 코드) 알림톡일때만
		//$data['template_code'] = '';			// -  text(10) Y 메시지 유형을 확인할 템플릿 코드(사전에 승인된 템플릿의 코드)
		$data['receiver_num'] = '821093111339';			// -  text(15) Y 사용자 전화번호(국가코드(대한민국:82)를 포함한 전화번호)
		//$data['message'] = '친구톡 테스트 발송';					// -  text(1000) Y 사용자에게 전달될 메시지(공백 포함 1000자)
		$data['message'] = "[카카오프렌즈샵] 주문완료 안내\n□ 주문번호:12341234\n□ 배송지 : 구/면 동/리\n□ 배송예정일 : 00월 00일\n□ 결제금액 :: 결제금액 원";
		$data['reserved_time'] = '00000000000000';			// -  text(14) Y 메시지 예약발송을 위한 시간 값 (yyyyMMddHHmmss) (즉시전송 : 00000000000000, 예약전송 :20160310210000)
		$data['sms_only'] = 'N';					// -  text(1) N 카카오 비즈메시지 발송과 관계 없이 무조건 SMS발송요청
		//$data['sms_message'] = '';				// -  text(2000) N 카카오 비즈메시지 발송이 실패했을 때 SMS전환발송을 위한 메시지
		//$data['sms_title'] = '';					// -  text(120) N LMS발송을 위한 제목
		//$data['sms_kind'] = '';					// -  text(1) N 전환발송 시 SMS/LMS 구분(SMS : S, LMS : L, 발송안함 : N) SMS 대체발송을 사용하지 않는 경우 : N
		//$data['sender_num'] = '';				// -  text(15) N SMS발신번호
		//$data['parcel_company'] = '';			// -   text(2) N 택배사 코드(부록 택배사 코드 참조)
		//$data['parcel_invoice'] = '';			// -   text(100) N 운송장번호
		//$data['s_code'] = '';						// -   text(2) N 쇼핑몰 코드(부록 쇼핑몰 코드 참조)
		////$data['btn_name'] = '';				// -   text(200) N 메시지에 첨부할 버튼 이름(템플릿 검수)
		////$data['btn_url'] = '';					// -   text(200) N 메시지에 첨부할 버튼의 url(일부 변수 가능)
		//$data['image_url'] = '';					// -   text(2083) N 친구톡 메시지에 첨부할 이미지 url
		//$data['image_link'] = '';				// -   text(2083) N 이미지 클릭시 이동할 url
		$data['ad_flag'] = 'N';					// -   text(1) N 친구톡 메시지에 광고성 메시지 필수 표기 사항을 노출 (노출 여부 Y/N, 기본값 Y)
		$data['button1'] = array("type"=>"WL","name"=>"주문내역 상세보기","url_mobile"=>"http://www.kakao.com");					// -   json N 메시지에 첨부할 버튼 1
		//{"type":"WL","name":"주문내역 상세보기","url_mobile":"http://www.kakao.com"}
		//$data['button1'] = '[]';					// -   json N 메시지에 첨부할 버튼 1
		//$data['button2'] = '[]';					// -   json N 메시지에 첨부할 버튼 2
		//$data['button3'] = '[]';					// -   json N 메시지에 첨부할 버튼 3
		//$data['button4'] = '[]';					// -   json N 메시지에 첨부할 버튼 4
		//$data['button5'] = '[]';					// -   json N 메시지에 첨부할 버튼 5
/*

dev : 키가 알림톡
		알림톡 : @dkfflaxhrxptmxm(알림톡테스트) : 89823b83f2182b1e229c2e95e21cf5e6301eed98
		친구톡 : @clsrnxhrxptmxm(친구톡테스트): 0b796447e8f8613d3ade096a5c23120b069124a9


real : 이관과정에서 키가 누락됨(그쪽문제), 메시지내용은 \n으로 줄바꿈

templi_cont:(광고)

테스트 발송

수신거부 : 홈>친구차단
msg:
kind:L
senderBox:01065748654
tit:
pf_key:2f637c7c03d0a88084ab866fd7407a7e6f46e8fd
img_url:
img_link:
btn1:[{"type":"WL","name":"에이치엠피","url_mobile":"http://plus-talk.kakao.com/plus/home/@에이치엠피","url_pc":""}]
btn2:[]
btn3:[]
btn4:[]
btn5:[]
smsOnly:
tel_number:01093111339
	*/

		$postfields = array();
		array_push($postfields, $data);
		//echo '<pre> :: ';print_r($this->sweettracker->sendMessage(0, '0b796447e8f8613d3ade096a5c23120b069124a9', $postfields));
		echo '<pre> :: ';print_r($this->sweettracker->sendMessage(0, '89823b83f2182b1e229c2e95e21cf5e6301eed98', $postfields));
	}

	public function mssql()
	{
		$conn = mssql_connect ("dba.prsms.co.kr:1767", "db_gcm01_user", "it!gcm01#user95$");
		if (!$conn) {
			die('Something went wrong while connecting to MSSQL');
		}
		mssql_close($conn);

		echo "<pre> :: ";print_r($conn);


/*
PHP7.0에서 mssql 사용하기

CentOS 7

sudo su
curl https://packages.microsoft.com/config/rhel/7/prod.repo > /etc/yum.repos.d/mssql-release.repo
exit
sudo yum update
sudo ACCEPT_EULA=Y yum install -y msodbcsql mssql-tools unixODBC-devel 
sudo yum groupinstall "Development Tools"
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
sqlsrv 설정하기

php.ini 파일에 아래 내용을 추가해 줍니다.

extension=sqlsrv.so
extension=pdo_sqlsrv.so
또는 /etc/php/7.0/mods-available 위치에 sqlsrv.ini 파일을 만들어 넣으시고 위 내용을 넣으시고, /etc/php/7.0/fpm/conf.d와 같이 conf.d 폴더에 심볼릭 링크를 연결 해 주셔도 됩니다.

사용해 보기

<?php
$serverName = "localhost";
$connectionOptions = array(
    "Database" => "SampleDB",
    "Uid" => "sa",
    "PWD" => "your_password"
);
//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn)
    echo "Connected!"

*/



	}
}?>