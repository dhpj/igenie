<?php
exit;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * Payment 담당하는 controller 입니다.
 */
class Inicis_test extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'cmall');
    private $CI;

    function __construct()
    {
        parent::__construct();
        
        $this->CI = & get_instance();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'email'));
    }

	public function index() {

        // 입금결과 처리
        $this->load->model(array('Deposit_model', 'Cmall_order_model'));
        $where = array(
//            'dep_id' => '2017122916370872',
            /*'dep_app_no' => $no_vacct,*/
            'dep_status' => 1,
        );
        $deposit = $this->Deposit_model->get_one('', '', $where);

        $receipt_time = $dt_trans . $tm_trans;

		  if (element('dep_id', $deposit)) {
				/*--------------------------*/
				/* 입금 업데이트 : 가상계좌 */
				/*--------------------------*/
				$this->load->model("Biz_free_model");
				$this->Biz_free_model->deposit_appr(element('mem_id', $deposit), element('dep_id', $deposit));
        }


        //************************************************************************************

        //위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 'OK'를 이니시스로
        //리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
        //(주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 'OK'를 수신할때까지 계속 재전송을 시도합니다
        //기타 다른 형태의 PRINT( echo )는 하지 않으시기 바랍니다

        if ($result) {
            echo 'OK'; // 절대로 지우지마세요
        } else {
            echo 'DB Error';
        }
    }
}