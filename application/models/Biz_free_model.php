<?php
class Biz_free_model extends CB_Model {
	public $price_ft = 16.5;
	public $price_ft_img = 22.0;
	public $price_at = 9.9;
	public $price_sms = 16.5;
	public $price_lms = 16.5;
	public $price_mms = 300.0;
	public $price_phn = 16.5;

	public $charge = array(30000, 50000, 100000, 300000, 500000, 1000000, 1500000, 2000000, 2500000, 3000000);
	public $deposit = array("30000"=>0,"50000"=>1,"100000"=>2,"300000"=>3,"500000"=>4,"1000000"=>5,"1500000"=>6,"2000000"=>7,"2500000"=>8,"3000000"=>9);
	public $weight = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
	public $reject_phn = "080-870-6789";

	public $buttonName = array("DS"=>"배송조회","WL"=>"웹링크","AL"=>"앱링크","BK"=>"봇키워드","MD"=>"메시지전달");
	public $buttonType = array("배송조회"=>"DS","웹링크"=>"WL","앱링크"=>"AL","봇키워드"=>"BK","메시지전달"=>"MD");

	function __construct()
	{
		// 모델 생성자 호출
		parent::__construct();
	
		// 기본 단가 수집
		$price = $this->db->query("select * from cb_wt_setting limit 1")->row();
		$this->price_ft = $price->wst_price_ft;
		$this->price_ft_img = $price->wst_price_ft_img;
		$this->price_at = $price->wst_price_at;
		$this->price_sms = $price->wst_price_sms;
		$this->price_lms = $price->wst_price_lms;
		$this->price_mms = $price->wst_price_mms;
		$this->price_grs = $price->wst_price_grs;
		$this->price_nas = $price->wst_price_nas;
		$this->price_015 = $price->wst_price_015;
		$this->price_phn = $price->wst_price_phn;
		$this->price_dooit = $price->wst_price_dooit;
		$this->weight[0] = $price->wst_weight1;
		$this->weight[1] = $price->wst_weight2;
		$this->weight[2] = $price->wst_weight3;
		$this->weight[3] = $price->wst_weight4;
		$this->weight[4] = $price->wst_weight5;
		$this->weight[5] = $price->wst_weight6;
		$this->weight[6] = $price->wst_weight7;
		$this->weight[7] = $price->wst_weight8;
		$this->weight[8] = $price->wst_weight9;
		$this->weight[9] = $price->wst_weight10;
	}

	/*
	*	로그인 없이 처리해야 하는 부분에 대하여 모델을 생성함.
	*
	*  테이블 생성 쿼리 및 트리거 생성 쿼리는 Biz_model.php 와 동일하게 유지하여야 함!
	*
	*/

	function check_model()
	{
		echo '<pre> :: ';print_r("ok");
	}

	/*
	*  기본 단가 수집
	*/
	function getBasePrice()
	{
		$sql = "
			select wst_mem_id mad_mem_id, '' mad_free_hp,
				wst_price_ft mad_price_ft, wst_price_ft_img mad_price_ft_img, wst_price_at mad_price_at, wst_price_sms mad_price_sms,
				wst_price_lms mad_price_lms, wst_price_mms mad_price_mms, wst_price_phn mad_price_phn,
                wst_price_grs mad_price_grs, wst_price_nas mad_price_nas, wst_price_015 mad_price_015,
				0 payback_at, 0 payback_ft, 0 payback_ft_img, 0 payback_phn, 0 payback_sms, 0 payback_lms, 0 payback_mms
			from cb_wt_setting limit 1
		";
		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) return $result[0];
		return null;
	}
	/*
	*  자신의 단가와 상위 관리자와의 단가 차액을 반환 : array
	*	lv100 미만인 최고상위자의 단가만 반환 : 하위의 단가에 대한 마진이나 단가처리는 lv50이 알아서 처리함
	*/
	function getParentPrice($mem_id)
	{
		$sql = "
			select b.mem_userid, b.mem_level, i.*
				,(i.mad_price_at - ifnull(a.mad_price_at, i.mad_price_at)) payback_at
				,(i.mad_price_ft - ifnull(a.mad_price_ft, i.mad_price_ft)) payback_ft
				,(i.mad_price_ft_img - ifnull(a.mad_price_ft_img, i.mad_price_ft_img)) payback_ft_img
				,(i.mad_price_grs - ifnull(a.mad_price_grs, i.mad_price_grs)) payback_grs
				,(i.mad_price_nas - ifnull(a.mad_price_nas, i.mad_price_nas)) payback_nas
				,(i.mad_price_015 - ifnull(a.mad_price_015, i.mad_price_015)) payback_015
				,(i.mad_price_phn - ifnull(a.mad_price_phn, i.mad_price_phn)) payback_phn
				,(i.mad_price_dooit - ifnull(a.mad_price_dooit, i.mad_price_dooit)) payback_dooit
				,(i.mad_price_sms - ifnull(a.mad_price_sms, i.mad_price_sms)) payback_sms
				,(i.mad_price_lms - ifnull(a.mad_price_lms, i.mad_price_lms)) payback_lms
				,(i.mad_price_mms - ifnull(a.mad_price_mms, i.mad_price_mms)) payback_mms
			from
				cb_wt_member_addon i left join
				cb_wt_member_addon a on 1=1 inner join
				cb_member b on a.mad_mem_id=b.mem_id inner join
				(
					SELECT distinct @r AS _id, (SELECT  @r := mrg_recommend_mem_id FROM cb_member_register WHERE mem_id = _id ) AS mrg_recommend_mem_id
					FROM
						(SELECT  @r := ".$mem_id.", @cl := 0) vars,	cb_member_register h
					WHERE    @r <> 0
				) c on a.mad_mem_id=c.mrg_recommend_mem_id
			where i.mad_mem_id=".$mem_id." and b.mem_level < 100
			order by b.mem_level desc
		";

		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) return $result[0];
		return null;
	}
	/*
	*  지정된 mem_id의 단가를 반환 : array
	*  주로 대형(lv100)의 단가를 구하기 위해 사용함
	*  제3의 에이전트가 있을수 있음 : 온라인발송 agent 제공시 추가소지가 있음
	*/
	function getMemberPrice($mem_id)
	{
		$sql = "
			select b.mem_userid, b.mem_level, a.*,
				a.mad_price_phn phn1, a.mad_price_sms sms1, a.mad_price_lms lms1, a.mad_price_mms mms1,
				a.mad_price_phn phn2, a.mad_price_sms sms2, a.mad_price_lms lms2, a.mad_price_mms mms2,
				a.mad_price_phn phn3, a.mad_price_sms sms3, a.mad_price_lms lms3, a.mad_price_mms mms3
			from
				cb_wt_member_addon a inner join
				cb_member b on a.mad_mem_id=b.mem_id
			where a.mad_mem_id=".$mem_id;

		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) {
			if($result[0]["mem_level"] >= 100) {
				/* 각 에이전트별 대형의 단가를 추가 */
				$agent = array("PRCOM"=>"1", "GREEN_SHOT"=>"2", "015"=>"3", "NASELF"=>"4");
				$sql = "select * from cb_wt_adm_price";
				$query = $this->db->query($sql);
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
	
	function getMemberPriceJSON($mem_id)
	{
	    $sql = "
			select b.mem_userid, b.mem_level, a.*,
				a.mad_price_phn phn1, a.mad_price_sms sms1, a.mad_price_lms lms1, a.mad_price_mms mms1,
				a.mad_price_phn phn2, a.mad_price_sms sms2, a.mad_price_lms lms2, a.mad_price_mms mms2,
				a.mad_price_phn phn3, a.mad_price_sms sms3, a.mad_price_lms lms3, a.mad_price_mms mms3
			from
				cb_wt_member_addon a inner join
				cb_member b on a.mad_mem_id=b.mem_id
			where a.mad_mem_id=".$mem_id;
	    
	    $result =  $this->db->query($sql)->result_array();
	    if(count($result) > 0) {
	        if($result[0]["mem_level"] >= 100) {
	            /* 각 에이전트별 대형의 단가를 추가 */
	            $agent = array("PRCOM"=>"1", "GREEN_SHOT"=>"2", "015"=>"3", "NASELF"=>"4");
	            $sql = "select * from cb_wt_adm_price";
	            $query = $this->db->query($sql);
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
	        return json_encode($result[0]);
	    }
	    return null;
	}
	/*
	*  관리자의 메시지 발송 처리 : 로그인 되지 않은 상태에서도 발송되어야 함
	*  지정된 사용자 아이디와 프로필로 메시지를 발송 : 전화상담예약 저장시 사용 -> /model/Board.write.model.php
	*/
	function adm_send_message($content)
	{
		$userid = "webthink7";
		$pf_key = "761283e30af0bba98fb0c802a60f6b06619210ff";	//- @webthink
		$senderBox = "01065748654";									//- 발신자
		$phoneNo = "01093111339";										//- 수신자
		$mem = $this->get_member($userid);

		//- 먼저 발송캐시에 등록함
		$ok = 0; $err = 0;
		/* 메시지 목록을 sc테이블에 담았다가 발송 */
		$param = array(
			$phoneNo,										// 알림을 받을 사람 전화번호
			$content,										// 템플릿내용
			null,												// 버튼 1~5
			null,
			null,
			null,
			null,
			"N",												// SMS재발신여부
			"(광고)[대형네트웍스] ".$content." \r\r무료수신거부 : 080-870-6789",				// LMS/SMS내용
			$senderBox,										// 발신자
			null,												// 이미지링크들
			null,
			''													// 템플릿코드
		);

		$this->db->query("delete from cb_sc_".$userid);
		$sql = "insert into cb_sc_".$userid." 
				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template) 
			values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
		$this->db->query($sql, $param);
		if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); $err++; } else { $ok++; }
		if($ok < 1) { return 0; }

		$this->load->library('sweettracker');

		//$msg = $this->Biz_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
		//if($msg!="") {
		//	header('Content-Type: application/json');
		//	echo '{"message": "'.$msg.'", "code": "fail"}';
		//	return;
		//}

		//- 발송내역에 기록
		$data = array();
		$data['mst_mem_id'] = $mem->mem_id;
		$data['mst_template'] = '';
		$data['mst_profile'] = $pf_key;
		$data['mst_datetime'] =  cdate('Y-m-d H:i:s');
		$data['mst_kind'] =  "ft";
		$data['mst_content'] =  $content;
		$data['mst_sms_send'] =  'N';
		$data['mst_lms_send'] =  'N';
		$data['mst_mms_send'] =  'N';
		$data['mst_sms_content'] =  '';
		$data['mst_lms_content'] =  "(광고)[대형네트웍스] ".$content." \r\r무료수신거부 : 080-870-6789";
		$data['mst_mms_content'] =  '';
		$data['mst_img_content'] =  '';
		$data['mst_button'] = '';
		$data['mst_reserved_dt'] =  '00000000000000';
		$data['mst_sms_callback'] =  $senderBox;
		$data['mst_status'] = '1';
		$data['mst_qty'] =  '1';
		$data['mst_amount'] = 0;;
		$this->db->insert("cb_wt_msg_sent", $data);
		$gid = $this->db->insert_id();

		if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $mem->mem_id, $mem->mem_userid, $pf_key, '00000000000000'); }

		return $ok;
	}

	/*
	*  아이디로 회원정보를 반환
	*  addon을 지정하면 소속, 단가 등의 부가정보를 포함
	*/
	function get_member($userid, $withAddon=false)
	{
		if($withAddon) {
			$sql = "
				select a.*, b.mrg_recommend_mem_id,
					a.mem_deposit total_deposit,
					c.*
				from cb_member a
					inner join cb_member_register b on a.mem_id=b.mem_id
					left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
				where a.mem_userid=?";
		} else {
			$sql = "
				select a.*,
					a.mem_deposit total_deposit
				from cb_member a
				where a.mem_userid=?";
		}
		return $this->db->query($sql, array($userid))->row();
	}

	/* /controller/Payment.php 모듈에서 가상계좌 입금이 확인저장되면 amt로그에 기록
	*
	*  inicis 가상계좌 입금결과 등이 들어오면 로그인되지 않은 상태에서 처리가 됨
	*/
	function deposit_appr($mem_id, $dep_id)
	{
		$deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
		/* 포인트 가중치를 입금자의 것으로 적용해야함! */
		$adPoint = 0;
		$weight = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=?", array($deposit->mem_id))->row_array();
		if($weight && count($weight) > 0) {
			$adPoint = intval($deposit->dep_deposit * ($weight["mad_weight".($this->deposit[$deposit->dep_deposit] + 1)] / 100));
		}
		if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {
			$dData['amt_datetime'] = cdate('Y-m-d H:i:s');
			$dData['amt_kind'] = ($deposit->dep_deposit > 0) ? '1' : '2';
			$dData['amt_amount'] = abs($deposit->dep_deposit);
			$dData['amt_point'] = $adPoint;
			$dData['amt_memo'] = $deposit->dep_content;
			$dData['amt_reason'] = $deposit->dep_id;
			$this->db->insert("cb_amt_".$deposit->mem_userid, $dData);
			if ($this->db->error()['code'] > 0) { return 0; }
			//- 충전인 경우만 보너스 포인트 처리
			if($deposit->dep_deposit > 0) {
				/* 포인트 보너스 */
				$sql = "update cb_member set mem_point = ifnull(mem_point, 0) + ".$adPoint." where mem_id=?";
				$this->db->query($sql, array($deposit->mem_id));
				$pData = array();
				$pData['mem_id'] = $deposit->mem_id;
				$pData['poi_datetime'] = cdate('Y-m-d H:i:s');
				$pData['poi_content'] = "예치금 보너스 적립";
				$pData['poi_point'] = $adPoint;
				$pData['poi_type'] = "deposit";
				$pData['poi_related_id'] = $deposit->mem_id;
				$pData['poi_action'] = "예치금충전(".number_format($deposit->dep_deposit).")";
				$this->db->insert("cb_point", $pData);
				if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
			}
		}
		return 0;
	}
	
	/*
	*  로그인창에서 회원가입시 로그인되지 않은 상태에서 부가 테이블들에 값을 넣어줌
	*  파트너 관리의 회원등록은 Biz_model.php에서 동일하게 처리
	*/
	function make_new_member_data($id) {
		/* 사이트 회원가입으로 신규 가입시 기본 정보 처리해야함 */
		$rData = array();
		$cnt = $this->db->query("select count(*) cnt from cb_member_register where mem_id=?", array($id))->row();
		if($cnt->cnt < 1) {
			$rData['mem_id'] = $id;
			$rData['mrg_ip'] = $this->input->ip_address();
         $rData['mrg_datetime'] = cdate('Y-m-d H:i:s');
         $rData['mrg_useragent'] = $this->agent->agent_string();
         $rData['mrg_referer'] = $this->session->userdata('site_referer');
		}
		$rData['mrg_recommend_mem_id'] = 3;		// 최초 상위딜러
		if($cnt->cnt < 1) {
			$this->db->insert("cb_member_register", $rData);
		} else {
			$this->db->update("cb_member_register", $rData, array("mem_id"=>$id));
		}

		$aData = array();
		$cnt = $this->db->query("select count(*) cnt from cb_wt_member_addon where mad_mem_id=?", array($id))->row();
		if($cnt->cnt < 1) {
			$aData['mad_mem_id'] = $id;
			$aData['mad_free_hp'] = "";
		}
		$aData['mad_price_ft'] = $this->price_ft;
		$aData['mad_price_ft_img'] = $this->price_ft_img;
		$aData['mad_price_at'] = $this->price_at;
		$aData['mad_price_sms'] = $this->price_sms;
		$aData['mad_price_lms'] = $this->price_lms;
		$aData['mad_price_mms'] = $this->price_mms;
		$aData['mad_price_grs'] = $this->price_grs;
		$aData['mad_price_nas'] = $this->price_nas;
		$aData['mad_price_015'] = $this->price_015;
		$aData['mad_price_phn'] = $this->price_phn;
		$aData['mad_weight1'] = $this->weight[0];
		$aData['mad_weight2'] = $this->weight[1];
		$aData['mad_weight3'] = $this->weight[2];
		$aData['mad_weight4'] = $this->weight[3];
		$aData['mad_weight5'] = $this->weight[4];
		$aData['mad_weight6'] = $this->weight[5];
		$aData['mad_weight7'] = $this->weight[6];
		$aData['mad_weight8'] = $this->weight[7];
		$aData['mad_weight9'] = $this->weight[8];
		$aData['mad_weight10'] = $this->weight[9];
		if($cnt->cnt < 1) {
			$this->db->insert("cb_wt_member_addon", $aData);
		} else {
			$this->db->update("cb_wt_member_addon", $aData, array("mad_mem_id"=>$id));
		}
	}

	function make_msg_log_table($userid)
	{
		/*
			스윗트렉커의 에이전트 결과 테이블과 동일한 형태 유지 + mem_userid 필트를 추가함 17/12/6
			에이전트가 결과를 처리하여 TBL_REQUST_RESULT에 저장하면 이 테이블로 옮겨온다.
		*/
		$sql = "
			CREATE TABLE IF NOT EXISTS `cb_msg_".$userid."`
			(
			  `MSGID` varchar(20) NOT NULL,
			  `AD_FLAG` varchar(1) DEFAULT NULL,
			  `BUTTON1` longtext,
			  `BUTTON2` longtext,
			  `BUTTON3` longtext,
			  `BUTTON4` longtext,
			  `BUTTON5` longtext,
			  `CODE` varchar(4) DEFAULT NULL,
			  `IMAGE_LINK` longtext,
			  `IMAGE_URL` longtext,
			  `KIND` varchar(1) DEFAULT NULL,
			  `MESSAGE` longtext,
			  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
			  `MSG` longtext NOT NULL,
			  `MSG_SMS` longtext,
			  `ONLY_SMS` varchar(1) DEFAULT NULL,
			  `P_COM` varchar(2) DEFAULT NULL,
			  `P_INVOICE` varchar(100) DEFAULT NULL,
			  `PHN` varchar(15) NOT NULL,
			  `PROFILE` varchar(50) DEFAULT NULL,
			  `REG_DT` datetime NOT NULL,
			  `REMARK1` varchar(50) DEFAULT NULL,
			  `REMARK2` varchar(50) DEFAULT NULL,
			  `REMARK3` varchar(50) DEFAULT NULL,
			  `REMARK4` varchar(50) DEFAULT NULL,
			  `REMARK5` varchar(50) DEFAULT NULL,
			  `RES_DT` datetime DEFAULT NULL,
			  `RESERVE_DT` varchar(14) NOT NULL,
			  `RESULT` varchar(1) DEFAULT NULL,
			  `S_CODE` varchar(2) DEFAULT NULL,
			  `SMS_KIND` varchar(1) DEFAULT NULL,
			  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
			  `SMS_SENDER` varchar(15) DEFAULT NULL,
			  `SYNC` varchar(1) NOT NULL,
			  `TMPL_ID` varchar(30) DEFAULT NULL,
			  `mem_userid` varchar(50) default '',
			  PRIMARY KEY (`MSGID`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8
		";
		$this->db->query($sql);
		if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
	}

	function make_customer_book($userid)
	{
		$sql = "CREATE TABLE IF NOT EXISTS `cb_ab_".$userid."` (
		  `ab_id` 			bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
		  `ab_name` 		varchar(100)  			DEFAULT '' 		comment '고객명',
		  `ab_tel` 			varchar(15)   			DEFAULT '' 		comment '전화번호',
		  `ab_kind` 		varchar(50)   			DEFAULT '' 		comment '구분',
		  `ab_status` 		char(1)   				DEFAULT '1' 	comment '상태',
		  `ab_memo` 		varchar(1000) 			DEFAULT '' 		comment '메모',
		  `ab_group` 		varchar(1000) 			DEFAULT '' 		comment '고객분류',
		  `ab_send_count`	bigint 		  			DEFAULT '0'		COMMENT '발송횟수',
		  `ab_datetime` 	datetime 				DEFAULT NULL 	comment '등록일시',
		  `ab_last_datetime` datetime 				DEFAULT NULL 	comment '최종발송일시',
		  PRIMARY KEY (`ab_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$this->db->query($sql);
		if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
	}

	function make_user_image_table($userid)
	{
		$sql = "
			CREATE TABLE IF NOT EXISTS `cb_img_".$userid."` (
			  `img_id` 			bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
			  `img_filename` 	varchar(200)  			DEFAULT '' 		comment '파일명',
			  `img_url` 		varchar(200) 			default '' 		comment '이미지URL',
			  `img_originname` varchar(200)   		DEFAULT '' 		comment '실제이름',
			  `img_filesize` 	bigint unsigned		DEFAULT '0' 	comment '파일크기',
			  `img_width` 		int    unsigned  		DEFAULT '0' 	comment '넓이',
			  `img_height` 	int    unsigned		DEFAULT '0'		comment '높이',
			  `img_type` 		varchar(20) 			DEFAULT '' 		comment 'MIME',
			  `img_is_image`	char(1) 	  				DEFAULT '1'		COMMENT '이미지여부',
			  PRIMARY KEY (`img_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
		$this->db->query($sql);
		if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
	}

	function make_user_deposit_table($userid) {
		$sql = "
			CREATE TABLE /*IF NOT EXISTS*/ `cb_amt_".$userid."` (
			  `amt_datetime`	datetime   	  			DEFAULT null 	comment '발생일시',
			  `amt_kind` 		char(1) 		  			DEFAULT '' 		COMMENT '구분(충전,사용,현금환불,사용취소)',
			  `amt_amount` 	decimal(14,2) 			DEFAULT '0.0' 	comment '금액',
			  `amt_deposit`	decimal(14,2) 			DEFAULT '0.0' 	comment '예치금사용금액',
			  `amt_point` 		decimal(14,2) 			DEFAULT '0.0' 	comment '포인트사용금액',
			  `amt_memo` 		varchar(100) 			DEFAULT '' 		comment '내용',
			  `amt_reason` 	varchar(50) 			DEFAULT '' 		comment '근거자료',
			  `amt_payback`	decimal(14,2)			default '0.0'	comment '수익금',
			  `amt_admin`		decimal(14,2)			default '0.0'	comment '관리자단가'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8
		";
		$this->db->query($sql);
		if ($this->db->error()['code'] > 0) { return 0; }
		$sql = "
			CREATE TRIGGER `ins_cb_amt_".$userid."` BEFORE INSERT ON `cb_amt_".$userid."`
			  FOR EACH ROW
			BEGIN
				if NEW.amt_kind='1' then
					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='".$userid."';
					set NEW.amt_deposit := NEW.amt_amount;
				else
					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='".$userid."';
					IF NEW.amt_kind>='A' and NEW.amt_kind<='Z' THEN
						if @nPoint > 0 then
							set @nPoint := @nPoint - NEW.amt_amount;
							if @nPoint < 0 then
								set NEW.amt_point := NEW.amt_amount - @nPoint;
								set NEW.amt_deposit := abs(@nPoint);
								set @nDeposit := @nDeposit + @nPoint;
								set @nPoint := 0;
							else
								set NEW.amt_point := NEW.amt_amount;
							end if;
						else
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						end if;
					else
						if NEW.amt_kind='2' then
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						else
							set @nPoint := @nPoint + NEW.amt_amount;
							set NEW.amt_point := NEW.amt_amount;
						end if;
					end if;
					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='".$userid."';
				END IF;
			END;
		";			
		$this->db->query($sql);
		if ($this->db->error()['code'] < 1) { return 1; } else { echo '<pre> :: ';print_r($this->db->error()); exit; return 0; }
	}

	function make_send_cache_table($userid)
	{
		if($init) {
			$this->db->delete("sc_".$userid);
		}
		$sql = "
			CREATE TABLE IF NOT EXISTS  `cb_sc_".$userid."` (
			  `sc_id` 				bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
			  `sc_name` 			varchar(100) 			DEFAULT '' 		COMMENT '고객명',
			  `sc_tel` 				varchar(15) 			DEFAULT '' 		COMMENT '전화번호',
			  `sc_content` 		varchar(5000) 			DEFAULT '' 		COMMENT '내용(친구톡/알림톡)',
			  `sc_button1` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼1',
			  `sc_button2` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼2',
			  `sc_button3`			varchar(600) 			DEFAULT '' 		COMMENT '버튼3',
			  `sc_button4` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼4',
			  `sc_button5` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼5',
			  `sc_sms_yn` 			char(1) 					DEFAULT 'N' 	COMMENT 'SMS재발신여부',
			  `sc_lms_content` 	varchar(5000) 			DEFAULT '' 		COMMENT '내용LMS',
			  `sc_sms_callback`	varchar(15) 			DEFAULT '' 		COMMENT 'SMS발신번호',
			  `sc_img_url` 		varchar(1000) 			DEFAULT '' 		COMMENT '이미지URL',
			  `sc_img_link` 		varchar(1000) 			DEFAULT '' 		COMMENT '이미지Link',
			  `sc_template` 		varchar(30) 			DEFAULT ''		COMMENT '템플릿코드',
			  `sc_p_com`			varchar(5)				default ''		comment '택배사코드',
			  `sc_p_invoice`		varchar(100)			default ''		comment '택배송장번호',
			  `sc_s_code`			varchar(5)				default ''		comment '쇼핑몰코드',
			  `sc_reserve_dt`		varchar(14)				default ''		comment '예약전송일시',
			  PRIMARY KEY (`sc_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8
		";
		$this->db->query($sql);
		if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
	}
}
?>