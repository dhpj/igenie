<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 관리자>예치금>무통장입금알림 controller 입니다.
 */
class Rbank_proc extends CB_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('depositlib'));
    }

    //레드뱅킹 결과값 리턴
    public function process(){
		//log_message("ERROR","CALL");
		$bank_num = $this->input->post('_bank_num'); //입금계좌번호(20Byte 이하)
		$sender = $this->input->post('_sender'); //입금자명(10자 이하)
		$money = $this->input->post('_money'); //입금금액(10자 이하)
		$oid = $this->input->post('_oid'); //가맹점에서 해당 거래를 관리할 수 있는 가맹점거래번호(32Byte 이하) (계좌상태가 [일방문자통보] 일경우 _oid가 없습니다.)
		$usmsid = $this->input->post('_usmsid'); //이아이디로 중복통보 체크 가능합니다. (32Byte 이하)
		$remain = $this->input->post('_remain'); //계좌잔액(10자 이하)
		$plus = $this->input->post('_plus'); //입금통보시: 1 출금통보시: 0(1Byte)
		$red_rtn = "";
		$dep_rtn = "";
		//log_message('ERROR', 'Redbanking 수신 : '.$oid.' / '.$bank_num);
		//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > bank_num : '. $bank_num .', sender : '. $sender .', money : '. $money .', oid : '. $oid .', usmsid : '. $usmsid .', remain : '. $remain .', plus : '. $plus);

		if($usmsid != "" and $sender != "" and $money != ""){
			//레드뱅킹 등록수 확인
			$sql = "select count(*) as cnt from cb_redbank_data where usmsid = '". $usmsid ."' /* 레드뱅킹ID */ ";
			$red_cnt = $this->db->query($sql)->row()->cnt;
			//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > red_cnt : '. $red_cnt);

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
				if($mem_id != "" and $plus = "1"){ //추가
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
					if($dep_rtn){
						$red_state = "T"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
					}
					//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 충전내역 추가 > unique_id : '. $unique_id .", dep_rtn : ". $dep_rtn);
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
				if($req->dep_cash_request == $money) {
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
					//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > 충전내역 업데이트 End');
					//echo "OK";
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

}