<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Currentvisitor class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Crontab extends CB_Controller{

	//헬퍼를 로딩합니다
	protected $helpers = array('form', 'array');

	function __construct(){
		parent::__construct();
		//라이브러리를 로딩합니다
		$this->load->library(array('querystring'));
	}

	//스케쥴 실행 (매시간 3분 마다 실행됨)
	public function schedule(){
		log_message("ERROR", "/crontab > schedule > 스케쥴 실행 (매시간 3분 마다 실행됨) Start");
		$day = date("j"); //현재 일자 0이 붙지 않음 [ 1에서 31 ]시
		$hour = date("G"); //현재 시간 0이 붙지 않는 24시간 형식 시 [ 0에서 23 ]
		if($day == 4 and $hour == 4){ //매월 4일 새벽 4시 3분 실행됨
			//log_message("ERROR", "/crontab > schedule > 정산내역(발송통계) 프로시저 실행 시작 (매월 4일 새벽 4시 3분 실행됨)");
			//$sql = "CALL cb_monthly_settlement";
			//$this->db->query($sql);
			//log_message("ERROR", "/crontab > schedule > 정산내역(발송통계) 프로시저 실행 종료 (매월 4일 새벽 4시 3분 실행됨)");
		}
		log_message("ERROR", "/crontab > schedule > 스케쥴 실행 (매시간 3분 마다 실행됨) End");
	}

    //스케쥴 실행 (매일 00:01 실행됨)
	public function check_dormancy(){
		log_message("ERROR", "/crontab > check_dormancy > 스케쥴 실행 (매일 00시 01분에 실행됨) Start");

        /*
        1 개월 = 2629800 초	10 개월 = 26298000 초	2500 개월 = 6574500000 초
        2 개월 = 5259600 초	20 개월 = 52596000 초	5000 개월 = 13149000000 초
        3 개월 = 7889400 초	30 개월 = 78894000 초	10000 개월 = 26298000000 초
        4 개월 = 10519200 초	40 개월 = 105192000 초	25000 개월 = 65745000000 초
        5 개월 = 13149000 초	50 개월 = 131490000 초	50000 개월 = 131490000000 초
        6 개월 = 15778800 초	100 개월 = 262980000 초	100000 개월 = 262980000000 초
        7 개월 = 18408600 초	250 개월 = 657450000 초	250000 개월 = 657450000000 초
        8 개월 = 21038400 초	500 개월 = 1314900000 초	500000 개월 = 1314900000000 초
        9 개월 = 23668200 초	1000 개월 = 2629800000 초	1000000 개월 = 2629800000000 초
        */
        $divide_second = 7889400;   // 7889400 초 : 석달
        $protection_period = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - 7889400);
        $current_datetime = date("Y-m-d H:i:s");
        $divide_past_datetime_to_current_datetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - $divide_second);
		$sql = "
        SELECT
            top_tmp.*
          , cmdd.mem_id AS cmdd_mem_id
          , cmdd.mem_userid AS cmdd_mem_userid
          , cmdd.mdd_dormant_flag
          , cmdd.mdd_update_datetime
        FROM (
            SELECT
                cm.mem_id
              , cm.mem_userid
              , cm.mem_username
              , IFNULL(tmp.sum_qty, 0) as tmp_sum_qty
              , (SELECT cmre.mrg_recommend_mem_id FROM cb_member_register cmre WHERE cmre.mem_id=cm.mem_id) AS p_mem_id
              , (SELECT cm2.mem_username from cb_member_register cmre LEFT JOIN cb_member cm2 ON cmre.mrg_recommend_mem_id = cm2.mem_id WHERE cmre.mem_id=cm.mem_id) AS p_mem_name
            FROM
                cb_member cm
            LEFT JOIN (
                SELECT
                    mst_mem_id
                  , sum(mst_qty) AS sum_qty
                FROM
                    cb_wt_msg_sent
                WHERE (
                    (mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$divide_past_datetime_to_current_datetime."' AND '".$current_datetime."'))
                    OR
                    (mst_reserved_dt = '00000000000000' AND (mst_datetime BETWEEN '".$divide_past_datetime_to_current_datetime."' AND '".$current_datetime."'))
                )
                GROUP BY mst_mem_id
            ) AS tmp on cm.mem_id = tmp.mst_mem_id
            WHERE
                cm.mem_level = 1
                AND
                cm.mem_register_datetime < '".$protection_period."'
        ) AS top_tmp
        LEFT JOIN
            cb_member_dormant_dhn cmdd ON top_tmp.mem_id = cmdd.mem_id
        WHERE
            top_tmp.tmp_sum_qty < 100
        ";
        log_message("error", $sql);
        $result = $this->db->query($sql)->result();

        foreach($result as $a){
            $status = '1';
            if ($a->p_mem_id == '1309'){
                $status = '0';
            }
            if (empty($a->cmdd_mem_id)) {
                $sql = "
                INSERT INTO cb_member_dormant_dhn VALUES ('".$a->mem_id."', '".$a->mem_userid."', " . $status . ", '".$current_datetime."')
                ";
                $this->db->query($sql);
            }
            if ($status == '1'){
                if (!empty($a->cmdd_mem_id) && $a->mdd_dormant_flag == 0 && empty($a->mdd_update_datetime)) {
                    $sql = "
                        UPDATE
                            cb_member_dormant_dhn
                        SET
                            mdd_dormant_flag = " . $status . "
                        WHERE
                            mem_id = ".$a->mem_id."
                    ";
                    $this->db->query($sql);
                } else if (!empty($a->cmdd_mem_id) && $a->mdd_dormant_flag == 0 && date("Y-m-d H:i:s", strtotime(date($a->mdd_update_datetime)) + $divide_second) < date($current_datetime)){
                    $sql = "
                        UPDATE
                            cb_member_dormant_dhn
                        SET
                            mdd_dormant_flag = " . $status . "
                          , mdd_update_datetime = null
                        WHERE
                            mem_id = ".$a->mem_id."
                    ";
                    $this->db->query($sql);
                }
            }
        }

		log_message("ERROR", "/crontab > check_dormancy > 스케쥴 실행 (매일 00시 01분에 실행됨)  End");
	}
	
	public function temp_Check(){
	    log_message("ERROR","/crontab > temp_Check > 스케쥴 실행 (매일 02시 10분에 실행됨) start");
	    $sql = "select tpl_mem_id,tpl_profile_key,tpl_code from cb_wt_template_dhn where tpl_delyn = 'N'";
	    //$sql = "select tpl_mem_id,tpl_profile_key,tpl_code from cb_wt_template_dhn";
	    
	    $result = $this->db->query($sql)->result();
        
	    foreach ($result as $a){
	        if($a->tpl_mem_id==1 || $a->tpl_mem_id==0){
	            $data = array(
	                'senderKey' => $a->tpl_profile_key,
	                'senderKeyType' => 'G',
	                'templateCode' => $a->tpl_code
	            );
	        }else {
	            $data = array(
	                'senderKey' => $a->tpl_profile_key,
	                'senderKeyType' => 'S',
	                'templateCode' => $a->tpl_code
	            );
	        }
	        
	        $url = "https://bzm-center.kakao.com/api/v2/51d3ab9bc25dceeee44c6f472fd16691c8b49d65/alimtalk/template";
	        $url = $url.'?'.http_build_query($data,'','&');
	        
	        $ch = curl_init();
	        curl_setopt($ch,CURLOPT_URL,$url);
	        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	        
	        $response = curl_exec($ch);
	        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	        $error = curl_error($ch);
	        curl_close($ch);
	        
	        $responseData = json_decode($response);
	        
	        if($responseData->code=='200'){
	            //log_message("ERROR",$response);
	            
	            if ($responseData->data->block) {
	                $block_value = '1';
	                $reason = '(자동)차단된 템플릿입니다.';
	            }else{
	                $block_value = '0';
	                $reason = '';
	            }
	            
	            if ($responseData->data->dormant) {
	                $dormant_value = '1';
	                $reason = '(자동)휴면처리된 템플릿입니다.';
	            } else {
	                $dormant_value = '0';
	                $reason = '';
	            }
                if ($block_value == 1 || $dormant_value == 1) {
                    // tpl_use 변경
                    /*
                    $use = 'N';
                    $sqlupdate = "
                    update 
                        cb_wt_template_dhn 
                    set 
                        tpl_status='" . $responseData->data->status . "', 
                        tpl_block='" . $block_value . "', 
                        tpl_dormant='" . $dormant_value . "', 
                        tpl_use='" . $use . "', 
                        tpl_reason='" . $reason . "' 
                    where 
                        tpl_code='" . $a->tpl_code . "' 
                        and tpl_profile_key='" . $a->tpl_profile_key . "'";
                    */
                    
                    
                    // tpl_use 변경 x
                    $sqlupdate = "
                    update
                        cb_wt_template_dhn
                    set
                        tpl_status='" . $responseData->data->status . "',
                        tpl_block='" . $block_value . "',
                        tpl_dormant='" . $dormant_value . "',
                        tpl_reason='" . $reason . "'
                    where
                        tpl_code='" . $a->tpl_code . "'
                        and tpl_profile_key='" . $a->tpl_profile_key . "'";
	               $update_result = $this->db->query($sqlupdate);
                }

	            //log_message("ERROR",$sqlupdate);
	            
	        }elseif ($responseData->code=='508'){
	            // tpl_use 변경 
	            //$sqlupdate = "update cb_wt_template_dhn set tpl_use='N', tpl_reason='(자동)삭제된 템플릿입니다.', tpl_delyn = 'Y' where tpl_code='".$a->tpl_code."' and tpl_profile_key='".$a->tpl_profile_key."'";
	            
	            // tpl_use 변경 x
	            $sqlupdate = "
                update 
                    cb_wt_template_dhn 
                set 
                    tpl_reason='(자동)삭제된 템플릿입니다.', 
                    tpl_delyn = 'Y'
                 where 
                    tpl_code='".$a->tpl_code."' 
                and 
                    tpl_profile_key='".$a->tpl_profile_key."'";
	            
	            $update_result = $this->db->query($sqlupdate);
	        }
	        
	        
	    }
	    
	    log_message("ERROR","/crontab > temp_Check > 스케쥴 실행 (매일 02시 10분에 실행됨) end");
	}

}
?>
