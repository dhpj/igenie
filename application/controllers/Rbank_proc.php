<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 관리자>예치금>무통장입금알림 controller 입니다.
 */
class Rbank_proc extends CB_Controller
{
    /**
     * 모델을 로딩합니다
     */
    // protected $models = array('Deposit', 'Unique_id', 'Biz');

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('depositlib', 'alimtalk'));
    }

    //레드뱅킹 결과값 리턴
    public function process(){

		//log_message("ERROR","CALL");
        log_message("ERROR",'/rbank_proc/process > ' . $this->input->post('_bank_num')."_".$this->input->post('_sender'));
		$bank_num = $this->input->post('_bank_num'); //입금계좌번호(20Byte 이하)
		$sender = $this->input->post('_sender'); //입금자명(10자 이하)
		$money = $this->input->post('_money'); //입금금액(10자 이하)
		$oid = $this->input->post('_oid'); //가맹점에서 해당 거래를 관리할 수 있는 가맹점거래번호(32Byte 이하) (계좌상태가 [일방문자통보] 일경우 _oid가 없습니다.)
		$usmsid = $this->input->post('_usmsid'); //이아이디로 중복통보 체크 가능합니다. (32Byte 이하)
		$remain = $this->input->post('_remain'); //계좌잔액(10자 이하)
		$plus = $this->input->post('_plus'); //입금통보시: 1 출금통보시: 0(1Byte)
		//log_message('ERROR', 'Redbanking 수신 : '.$oid.' / '.$bank_num);
		//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > bank_num : '. $bank_num .', sender : '. $sender .', money : '. $money .', oid : '. $oid .', usmsid : '. $usmsid .', remain : '. $remain .', plus : '. $plus);

		if($usmsid != "" and $sender != "" and $money != ""){
			//레드뱅킹 등록수 확인
			$sql = "select count(*) as cnt from cb_redbank_data where usmsid = '". $usmsid ."' /* 레드뱅킹ID */ ";
			$red_cnt = $this->db->query($sql)->row()->cnt;
			// log_message('ERROR', $_SERVER['REQUEST_URI'] .' > red_cnt : '. $red_cnt);

			//레드뱅킹 등록
			if($red_cnt == 0){ //추가
				//회원번호 조회 (입금자명 = 사업자등록번호)
				$sql = "select * from cb_member where mem_useyn = 'Y' and mem_biz_reg_no = '". $sender ."' /* 사업자등록번호 */ ";
				$mem = $this->db->query($sql)->row();
				$mem_id = $mem->mem_id; //회원번호
				//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > mem_id : '. $mem_id);

				//충전내역 추가
				$unique_id = "";
				$red_state = "F"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
				if($mem_id != "" and $plus == "1"){ //추가
					//가맹점거래번호 생성
					$this->load->model('Unique_id_model');
					$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
					$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);
					$red_state = "E"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)

					//충전내역 추가
					$insertdata = array();
					$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
					$insertdata['dep_deposit_datetime'] = null;
					$insertdata['dep_deposit_request'] = 0;
					$insertdata['dep_deposit'] = 0;
					$insertdata['mem_realname'] = $sender;
					$insertdata['dep_cash_request'] = $money;
					$insertdata['dep_cash'] = 0;
					$insertdata['dep_status'] = 0;
					$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (무통장입금)';
					$insertdata['dep_id'] = $unique_id;
					$insertdata['mem_id'] = $mem_id;
					$insertdata['mem_nickname'] = $mem->mem_nickname;
					$insertdata['mem_email'] = $mem->mem_email;
					$insertdata['mem_phone'] = $mem->mem_phone;
					$insertdata['dep_from_type'] = 'cash';
					$insertdata['dep_to_type'] = 'deposit';
					$insertdata['dep_pay_type'] = 'bank';
					//$insertdata['dep_ip'] = $this->input->ip_address();
					//$insertdata['dep_useragent'] = $this->agent->agent_string();
					$insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
					$dep_rtn = $this->db->insert("cb_deposit", $insertdata); //충전내역 추가

                    $sql = "
                        SELECT
                            IFNULL(SUM(a.pdt_amount), 0) AS sum
                        FROM
                            cb_pre_deposit_tg a
                        WHERE
                            a.pdt_mem_id = " . $mem_id . "
                    ";

                    $sum = $this->db->query($sql)->row()->sum;

                    if ($sum > 0){
                        $insertdata2 = array();
                        $insertdata2["pdt_mem_id"] = $mem_id;
                        $insertdata2["pdt_process_object"] = "rb";
                        $insertdata2["pdt_dep_id"] = $unique_id;
                        if ($sum > $money){
                            $insertdata2["pdt_amount"] = $money * (-1);
                        } else if ($sum <= $money) {
                            $insertdata2["pdt_amount"] = $sum * (-1);
                        }
                        $this->db->insert("cb_pre_deposit_tg", $insertdata2);
                    }

					if($dep_rtn){
						$red_state = "T"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
					}
					//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 충전내역 추가 > unique_id : '. $unique_id .", dep_rtn : ". $dep_rtn);
				} else if ($mem_id == "" and $plus == "1") {

                   $this->db
                       ->select(array(
                           "a.sender"
                         , "a.mem_id"))
                       ->from("cb_redbank_data a")
                       ->join('cb_member_dormant_dhn b', 'a.mem_id = b.mem_id', 'left')
                       ->join('cb_member c', 'a.mem_id = c.mem_id', 'left')
                       ->where(array(
                           "a.sender" => $sender
                         , "a.state" => "T"
                         , 'b.mdd_dormant_flag' => '0'
                         , 'c.mem_useyn' => 'Y'
                       ))
                       ->group_by(array(
                           "a.sender"
                         , "a.mem_id"));
                   $query = $this->db->get();
                   $row_cnt = $query->num_rows();
                   if ($row_cnt == "1"){
                       $row = $query->row();
                       $mem_id = $row->mem_id;

                       $this->db
                           ->select("*")
                           ->from("cb_member")
                           ->where("mem_id", $mem_id);
                       $mem = $this->db->get()->row();

                       //가맹점거래번호 생성
                       $this->load->model('Unique_id_model');
                       $SERVER_ADDR = $_SERVER['SERVER_ADDR'];
                       $unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);
                       $red_state = "E"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)

                       //충전내역 추가
                       $insertdata = array();
                       $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
                       $insertdata['dep_deposit_datetime'] = null;
                       $insertdata['dep_deposit_request'] = 0;
                       $insertdata['dep_deposit'] = 0;
                       $insertdata['mem_realname'] = $sender;
                       $insertdata['dep_cash_request'] = $money;
                       $insertdata['dep_cash'] = 0;
                       $insertdata['dep_status'] = 0;
                       $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (무통장입금)';
                       $insertdata['dep_id'] = $unique_id;
                       $insertdata['mem_id'] = $mem_id;
                       $insertdata['mem_nickname'] = $mem->mem_nickname;
                       $insertdata['mem_email'] = $mem->mem_email;
                       $insertdata['mem_phone'] = $mem->mem_phone;
                       $insertdata['dep_from_type'] = 'cash';
                       $insertdata['dep_to_type'] = 'deposit';
                       $insertdata['dep_pay_type'] = 'bank';
                       //$insertdata['dep_ip'] = $this->input->ip_address();
                       //$insertdata['dep_useragent'] = $this->agent->agent_string();
                       $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
                       $dep_rtn = $this->db->insert("cb_deposit", $insertdata); //충전내역 추가

                       $sql = "
                           SELECT
                               IFNULL(SUM(a.pdt_amount), 0) AS sum
                           FROM
                               cb_pre_deposit_tg a
                           WHERE
                               a.pdt_mem_id = " . $mem_id . "
                       ";

                       $sum = $this->db->query($sql)->row()->sum;

                       if ($sum > 0){
                           $insertdata2 = array();
                           $insertdata2["pdt_mem_id"] = $mem_id;
                           $insertdata2["pdt_process_object"] = "rb";
                           $insertdata2["pdt_dep_id"] = $unique_id;
                           if ($sum > $money){
                               $insertdata2["pdt_amount"] = $money * (-1);
                           } else if ($sum <= $money) {
                               $insertdata2["pdt_amount"] = $sum * (-1);
                           }
                           $this->db->insert("cb_pre_deposit_tg", $insertdata2);
                       }

                       $insertdata3 = array();
                       $insertdata3["oid"] = $unique_id;
                       $insertdata3["mem_id"] = $mem_id;
                       $insertdata3["money"] = $money;
                       $this->db->insert("cb_redbank_2nd_data", $insertdata3);

                       if($dep_rtn){
                           $red_state = "T"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
                       }
                   }

               }

				//레드뱅킹 등록
				$data = array();
				$data["usmsid"] = $usmsid; //레드뱅킹ID
				$data["sender"] = $sender; //입금자명 (사업자등록번호)
				$data["money"] = $money; //입금금액
				$data["remain"] = ($remain == "") ? "0" : $remain; //계좌잔액
				$data["oid"] = $unique_id; //가맹점거래번호
				$data["bank_num"] = $bank_num; //입금계좌번호
				$data["plus"] = $plus; //구분(1.입금, 0.출금)
				$data["mem_id"] = ($mem_id == "") ? "0" : $mem_id; //입금금액
				$data["state"] = $red_state; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
				$red_rtn = $this->db->insert("cb_redbank_data", $data); //추가
				//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 레드뱅킹 등록 > unique_id : '. $unique_id .", red_rtn : ". $red_rtn);
				$oid = $unique_id;

				if($red_state != "T") {
				    $this->send_alimtalk($sender, $money);
				}
			}

			//충전내역 업데이트
			if($oid != ""){
				//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 충전내역 업데이트 Start');
				$req = $this->db->query("select * from cb_deposit where dep_id = '". $oid ."' and status <> 'OK'" )->row();
				$this->db->select_sum('dep_deposit');
				$this->db->where(array('mem_id' => $req->mem_id, 'dep_status' => 1));
				$result = $this->db->get('deposit');
				$dpsum = $result->row_array();
				$sum = isset($dpsum['dep_deposit']) ? $dpsum['dep_deposit'] : 0;
				if( $req->dep_cash_request == $money) {
					$deposit_sum = $sum + $money;
					$updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
					$updatedata['dep_cash'] = $money;
					$updatedata['dep_deposit'] = $money;
					$updatedata['dep_deposit_sum'] = $deposit_sum;
					$updatedata['dep_status'] = 1;
					$updatedata['dep_admin_memo'] = '자동입금처리';
					$updatedata['status'] = 'OK';
					$this->db->where('dep_id', $req->dep_id);
					$this->db->update('deposit',$updatedata );
					$this->depositlib->update_member_deposit($req->mem_id);
					$this->deposit_appr($req->mem_id, $req->dep_id);
					$this->depositlib->alarm('approve_bank_to_deposit', $req->dep_id);
                    // log_message('ERROR', 'Rbank_proc > 1');
					//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 충전내역 업데이트 End');
					//echo "OK";
                    $this->deposit_eventplus($req->mem_id, $req->dep_id);
                    $this->pre_deposit($req->mem_id, $req->dep_id);//선충전차감
					$dep_rtn = "OK";

				}
			}

			//결과처리
			//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > red_rtn : '. $red_rtn .', dep_rtn : '. $dep_rtn);
			if(($red_rtn) or $dep_rtn == "OK"){
			    //log_message('ERROR', $_SERVER['REQUEST_URI'] .' > OK');
			    echo "OK";
			}

		}
    }

    public function send_alimtalk($sender, $money) {
        $phns = $this->db->query("select mem_stmall_alim_phn from cb_member cm where cm.mem_id = '3'")->row()->mem_stmall_alim_phn;
        log_message("ERROR","STMALL : ".$phns);

        $alimparam = array();
        // $alimparam['Auth'] = "b2d84a85e4e918c7753537a0434ab66235b88727";
        $alimparam['Auth'] = "e928cfe1258ede6069fde1b680aa24a25e38e148";
        $alimparam['temp'] = "2021040901";
        $alimparam['sender'] = "0557137985";
        $alimparam['name'] = "";
        $alimparam['phn'] =  $phns;
        $alimparam['add1'] = $sender;
        $alimparam['add2'] = date('Y-m-d H:i:s');
        $alimparam['add3'] = $money;
        $alimparam['add4'] = "";
        $alimparam['add5'] = "";
        $alimparam['add6'] = "";
        $alimparam['add7'] = "";
        $alimparam['add8'] = "";
        $alimparam['add9'] = "";
        $alimparam['add10'] = "";
        $alimparam['2nd'] = "N";
        $alimparam['m1'] = "";
        $alimparam['pc1'] = "";
        $alimparam['m2'] = "";
        $alimparam['pc2'] = "";
        $alimparam['m3'] = "";
        $alimparam['pc3'] = "";
        $alimparam['m4'] = "";
        $alimparam['pc4'] = "";
        $alimparam['m5'] = "";
        $alimparam['pc5'] = "";

        $this->alimtalk->send($alimparam, "alimtalk2nds");
    }

    //레드뱅킹 결과값 리턴
    public function process_org(){
        //log_message("ERROR","CALL");
        $bank_num = $this->input->post('_bank_num');
        $sender = $this->input->post('_sender');
        $money = $this->input->post('_money');
        $oid = $this->input->post('_oid');
        $usmsid = $this->input->post('_usmsid');
        $remain = $this->input->post('_remain');
        //log_message('ERROR', 'Redbanking 수신 : '.$oid.' / '.$bank_num);

        $req = $this->db->query("select * from cb_deposit where dep_id = '".$oid."' and status <> 'OK'" )->row();

        $this->db->select_sum('dep_deposit');
        $this->db->where(array('mem_id' => $req->mem_id, 'dep_status' => 1));
        $result = $this->db->get('deposit');
        $dpsum = $result->row_array();

        $sum = isset($dpsum['dep_deposit']) ? $dpsum['dep_deposit'] : 0;

        if( $req->dep_cash_request == $money) {
            $deposit_sum = $sum + $money;
            $updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
            $updatedata['dep_cash'] = $money;
            $updatedata['dep_deposit'] = $money;
            $updatedata['dep_deposit_sum'] = $deposit_sum;
            $updatedata['dep_status'] = 1;
            $updatedata['dep_admin_memo'] = '자동입금처리';
            $updatedata['status'] = 'OK';

            $this->db->where('dep_id', $req->dep_id);
            $this->db->update('deposit',$updatedata );
            $this->depositlib->update_member_deposit($req->mem_id);

            /* 2017.11.27 회원 예치금로그 */

            $this->deposit_appr($req->mem_id, $req->dep_id);
            /*----------------------------*/
            $this->depositlib->alarm('approve_bank_to_deposit', $req->dep_id);
            // log_message('ERROR', 'Rbank_proc > 2');

            echo "OK";


        } else {

        }
    }

    //회원별 충전내역 처리
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
            // log_message('ERROR', 'Rbank_proc > 3');

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

    //선충전 차감 2021-11-01 윤재박
    function pre_deposit($mem_id, $dep_id){
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_deposit');
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {
            $sql = " SELECT count(*) AS cnt FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
            // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > sql : '.$sql);
            $pre_cnt = $this->db->query($sql)->row()->cnt;
            // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_cnt : '.$pre_cnt);
            if($pre_cnt>0){
                $sql = " SELECT pre_cash FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                $pre_cash = $this->db->query($sql)->row()->pre_cash;
                // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > sql : '.$sql);
                if($pre_cash>0){
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_cash : '.$pre_cash);
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > deposit->dep_deposit : '.$deposit->dep_deposit);
                    $sql = "select * from cb_member where mem_useyn = 'Y' and mem_id = '". $mem_id ."' ";
                    $mem = $this->db->query($sql)->row();
                    $minusdeposit = 0;
                    $remaindeposit = 0;
                    if($pre_cash >= $deposit->dep_deposit){
                        $remaindeposit = $pre_cash - $deposit->dep_deposit;
                        $minusdeposit = $deposit->dep_deposit;
                        $minusdeposit = $minusdeposit*-1;
                    }else{
                        $remaindeposit=0;
                        $minusdeposit = $pre_cash;
                        $minusdeposit = $minusdeposit*-1;
                    }

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > minusdeposit : '.$minusdeposit);
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > remaindeposit : '.$remaindeposit);

                    //아이디 생성
    				$this->load->model('Unique_id_model');
    				$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
    				$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > unique_id : '.$unique_id);

                    //합계구함
                    $this->load->model('Deposit_model');
                    $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                    $deposit_sum = $sum + $minusdeposit;

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > deposit_sum : '.$deposit_sum);

                    //충전내역 추가
    				$insertdata = array();
    				$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_request'] = $minusdeposit;
    				$insertdata['dep_deposit'] = $minusdeposit;
                    $insertdata['dep_deposit_sum'] = $deposit_sum;
    				$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 선충전 차감';
                    $insertdata['dep_admin_memo'] = '선충전 차감';
    				$insertdata['dep_id'] = $unique_id;
    				$insertdata['mem_id'] = $mem_id;
    				$insertdata['mem_nickname'] = $mem->mem_nickname;
    				$insertdata['dep_from_type'] = 'service';
    				$insertdata['dep_to_type'] = 'deposit';
    				$insertdata['dep_pay_type'] = '';
                    $insertdata['dep_status'] = 1;
    				//$insertdata['dep_ip'] = $this->input->ip_address();
    				//$insertdata['dep_useragent'] = $this->agent->agent_string();
    				// $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
    				$this->db->insert("cb_deposit", $insertdata); //충전내역 추가



                    $amtData = array();
                    $amtData['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $amtData['amt_kind'] = '2';
                    $amtData['amt_amount'] = abs($minusdeposit);
                    $amtData['amt_point'] = 0;
                    $amtData['amt_memo'] = $insertdata['dep_content'];
                    $amtData['amt_reason'] = $unique_id;
                    $amtData['amt_deduct'] = -1;
                    $this->db->insert("cb_amt_".$deposit->mem_userid, $amtData);

                    $insertD = array();
                    $insertD['pre_cash'] = $remaindeposit;
                    $this->db->where('mem_id', $mem_id);
                    $this->db->update('cb_pre_deposit',$insertD );
                    // $this->db->update("cb_pre_deposit", $insertD, array("mem_id"=>$mem_id));

                }
            }

        }

    }

    //이벤트충전 2021-10-06 윤재박
	function deposit_eventplus($mem_id, $dep_id)
    {
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        /* 포인트 가중치를 입금자의 것으로 적용해야함! */
        $adPoint = 0;
        $weight = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=?", array($deposit->mem_id))->row_array();
        if($weight && count($weight) > 0) {
            $adPoint = intval($deposit->dep_deposit * ($weight["mad_weight".($this->deposit[$deposit->dep_deposit] + 1)] / 100));
        }
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {

            // log_message('ERROR', 'Rbank_proc > 4');

            if($deposit->dep_deposit > 0){
                $amt_kind  = 1;
            }
            //이벤트 가능업체 여부
            $where_mem1 = "";
            $eve_cnt = 0;
            if(!empty(config_item('eve1_member'))){
                foreach(config_item('eve1_member') as $r){
                    if($where_mem1!=""){
                        $where_mem1.=",";
                    }
                    $where_mem1.=$r;
                }
                if(!empty($where_mem1)){
                    $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                    $eve_cnt = $this->db->query($sql)->row()->cnt;
                }
            }
            $where_mem2 = "";
            $eve_cnt2 = 0;
            if(!empty(config_item('eve2_member'))){
                foreach(config_item('eve2_member') as $r){
                    if($where_mem2!=""){
                        $where_mem2.=",";
                    }
                    $where_mem2.=$r;
                }
                if(!empty($where_mem2)){
                    $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem2." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                    $eve_cnt2 = $this->db->query($sql)->row()->cnt;
                }
            }
            // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
            // $eve_cnt = $this->db->query($sql)->row()->cnt;
            //
            // //이벤트 가능업체 여부(티지)
            // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
            // $eve_cnt2 = $this->db->query($sql)->row()->cnt;

            if($amt_kind=='1' && ($eve_cnt > 0 || $eve_cnt2 > 0)){
            // if($amt_kind=='1' && $eve_cnt > 0){
                $nowTime = date("Y-m-d H:i:s"); // 현재 시간
                if($eve_cnt > 0){ //dhn
                    $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
                    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
                }else{ //티지
                    $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
                    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
                }


              if($nowTime < $expTime && $nowTime >= $startTime){


                $sql = "select * from cb_member where mem_useyn = 'Y' and mem_id = '". $mem_id ."' ";
                $mem = $this->db->query($sql)->row();


                //금액별 계산
                $bonus_amt = 0;

                if($eve_cnt > 0){ //dhn+대전연합
                    // if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                    //   $bonus_amt = 15000;
                    // }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                    //   $bonus_amt = 35000;
                    // }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                    //   $bonus_amt = 80000;
                    // }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                    //   $bonus_amt = 180000;
                    // }else if($deposit->dep_deposit>=3000000){
                    //   $bonus_amt = 300000;
                    // }

                    // if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                    //   $bonus_amt = 35000;
                    // }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                    //   $bonus_amt = 90000;
                    // }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                    //   $bonus_amt = 200000;
                    // }else if($deposit->dep_deposit>=3000000 && $deposit->dep_deposit<4000000){
                    //   $bonus_amt = 390000;
                    // }else if($deposit->dep_deposit>=4000000 && $deposit->dep_deposit<5000000){
                    //   $bonus_amt = 600000;
                    // }else if($deposit->dep_deposit>=5000000 && $deposit->dep_deposit<10000000){
                    //   $bonus_amt = 850000;
                    // }else if($deposit->dep_deposit>=10000000){
                    //   $bonus_amt = 2000000;
                    // }

                    if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                      $bonus_amt = 15000;
                    }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                      $bonus_amt = 35000;
                    }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<3000000){
                      $bonus_amt = 100000;
                    }else if($deposit->dep_deposit>=3000000 && $deposit->dep_deposit<5000000){
                      $bonus_amt = 390000;
                    }else if($deposit->dep_deposit>=5000000 && $deposit->dep_deposit<10000000){
                      $bonus_amt = 850000;
                    }else if($deposit->dep_deposit>=10000000){
                      $bonus_amt = 2000000;
                    }
                }else if($eve_cnt2 > 0){ //티지
                    if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                      $bonus_amt = 15000;
                    }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                      $bonus_amt = 35000;
                    }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                      $bonus_amt = 80000;
                    }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                      $bonus_amt = 180000;
                    }else if($deposit->dep_deposit>=3000000){
                      $bonus_amt = 300000;
                    }
                }

                if($bonus_amt>0){
                    //아이디 생성
    				$this->load->model('Unique_id_model');
    				$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
    				$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);

                    //합계구함
                    $this->load->model('Deposit_model');
                    $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                    $deposit_sum = $sum + $this->input->post('dep_deposit', null, 0);

                    //충전내역 추가
    				$insertdata = array();
    				$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_request'] = $bonus_amt;
    				$insertdata['dep_deposit'] = $bonus_amt;
                    $insertdata['dep_deposit_sum'] = $deposit_sum;
    				$insertdata['mem_realname'] = '';
    				$insertdata['dep_cash_request'] = $bonus_amt;
    				$insertdata['dep_cash'] = 0;
    				$insertdata['dep_status'] = 0;
    				$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 이벤트 (서비스입금)';
                    $insertdata['dep_admin_memo'] = '이벤트 서비스입금';
    				$insertdata['dep_id'] = $unique_id;
    				$insertdata['mem_id'] = $mem_id;
    				$insertdata['mem_nickname'] = $mem->mem_nickname;
    				$insertdata['mem_email'] = '';
    				$insertdata['mem_phone'] = '';
    				$insertdata['dep_from_type'] = 'service';
    				$insertdata['dep_to_type'] = 'deposit';
    				$insertdata['dep_pay_type'] = 'service';
                    $insertdata['dep_status'] = 1;
    				//$insertdata['dep_ip'] = $this->input->ip_address();
    				//$insertdata['dep_useragent'] = $this->agent->agent_string();
    				// $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
    				$this->db->insert("cb_deposit", $insertdata); //충전내역 추가

                    $amtData = array();
                    $amtData['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $amtData['amt_kind'] = '1';
                    $amtData['amt_amount'] = abs($bonus_amt);
                    $amtData['amt_point'] = $adPoint;
                    $amtData['amt_memo'] = $insertdata['dep_content'].",보너스";
                    $amtData['amt_reason'] = $unique_id;
                    $this->db->insert("cb_amt_".$deposit->mem_userid, $amtData);
                }
              }


            }
            if ($this->db->error()['code'] > 0) { return 0; }
            //- 충전인 경우만 보너스 포인트 처리

        }
        return 0;
    }

}
