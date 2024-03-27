<?php
class Friend extends CB_Controller {
    /**
     * ���� �ε��մϴ�
     */
    protected $models = array('Board', 'Biz');
    
    /**
     * ���۸� �ε��մϴ�
     */
    protected $helpers = array('form', 'array');
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * ���̺귯���� �ε��մϴ�
         */
        $this->load->library(array('querystring'));
    }
    
    public function index()
    {
    }
    
    public function load_customer()
    {
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
    
    public function all()
    {
        
        /*
         $templi_cont = $this->input->post('templi_cont');
         $msg = $this->input->post('msg');
         $kind = $this->input->post('kind');
         $senderBox = $this->input->post('senderBox');
         $tit = $this->input->post('tit');
         $pf_key = $this->input->post('pf_key');
         $img_url = $this->input->post('img_url');
         $img_link = $this->input->post('img_link');
         $btn1 = $this->input->post('btn1');
         $btn2 = $this->input->post('btn2');
         $btn3 = $this->input->post('btn3');
         $btn4 = $this->input->post('btn4');
         $btn5 = $this->input->post('btn5');
         $smsOnly = $this->input->post('smsOnly');
         $tel_number = $this->input->post('tel_number');
         */
        
        //���߼���
        //https://dev-alimtalk-api.sweettracker.net
        //�����
        //https://alimtalk-api.sweettracker.net
        //path : /v1/{profile_key}/sendMessage
        //profile_key : �÷���ģ���� �̿��Ͽ� �߽��������� ����� �߱޵Ǵ� �߽�������Ű(alpha-numeric 40��)
        //method : POST
        //header
        //Content-type: Application/json
        //parameter(JSON Array) - �ִ� 100�� ���� ���ÿ� �߼� ����
        
        /* Request : �޽��� ���� ��û */
        /*
         $data = array();
         $data['msgid'] = '';						// - text(20) Y �޽��� �Ϸù�ȣ(�޽����� ���� ������ ���̾�� ��)
         $data['message_type'] = '';			// - text(2) N �޽��� Ÿ��(at: �˸���, ft: ģ����)
         $data['message_group_code'] = '';	// -  text(30) N �޽��� ������ �����ϱ� ���� ��
         $data['profile_key'] = '';				// -  text(40) Y �߽�������Ű(�޽��� �߼� ��ü�� �÷���ģ���� ���� Ű)
         $data['template_code'] = '';			// -  text(10) Y �޽��� ������ Ȯ���� ���ø� �ڵ�(������ ���ε� ���ø��� �ڵ�)
         $data['receiver_num'] = '';			// -  text(15) Y ����� ��ȭ��ȣ(�����ڵ�(���ѹα�:82)�� ������ ��ȭ��ȣ)
         $data['message'] = '';					// -  text(1000) Y ����ڿ��� ���޵� �޽���(���� ���� 1000��)
         $data['reserved_time'] = '';			// -  text(14) Y �޽��� ����߼��� ���� �ð� �� (yyyyMMddHHmmss) (������� : 00000000000000, �������� :20160310210000)
         $data['sms_only'] = '';					// -  text(1) N īī�� ����޽��� �߼۰� ���� ���� ������ SMS�߼ۿ�û
         $data['sms_message'] = '';				// -  text(2000) N īī�� ����޽��� �߼��� �������� �� SMS��ȯ�߼��� ���� �޽���
         $data['sms_title'] = '';				// -  text(120) N LMS�߼��� ���� ����
         $data['sms_kind'] = '';					// -  text(1) N ��ȯ�߼� �� SMS/LMS ����(SMS : S, LMS : L, �߼۾��� : N) SMS ��ü�߼��� ������� �ʴ� ��� : N
         $data['sender_num'] = '';				// -  text(15) N SMS�߽Ź�ȣ
         $data['parcel_company'] = '';			// -   text(2) N �ù�� �ڵ�(�η� �ù�� �ڵ� ����)
         $data['parcel_invoice'] = '';			// -   text(100) N ������ȣ
         $data['s_code'] = '';					// -   text(2) N ���θ� �ڵ�(�η� ���θ� �ڵ� ����)
         //$data['btn_name'] = '';					// -   text(200) N �޽����� ÷���� ��ư �̸�(���ø� �˼�)
         //$data['btn_url'] = '';					// -   text(200) N �޽����� ÷���� ��ư�� url(�Ϻ� ���� ����)
         $data['image_url'] = '';				// -   text(2083) N ģ���� �޽����� ÷���� �̹��� url
         $data['image_link'] = '';				// -   text(2083) N �̹��� Ŭ���� �̵��� url
         $data['ad_flag'] = '';					// -   text(1) N ģ���� �޽����� ���� �޽��� �ʼ� ǥ�� ������ ���� (���� ���� Y/N, �⺻�� Y)
         $data['button1'] = '';					// -   json N �޽����� ÷���� ��ư 1
         $data['button2'] = '';					// -   json N �޽����� ÷���� ��ư 2
         $data['button3'] = '';					// -   json N �޽����� ÷���� ��ư 3
         $data['button4'] = '';					// -   json N �޽����� ÷���� ��ư 4
         $data['button5'] = '';					// -   json N �޽����� ÷���� ��ư 5
         */
        
        
        /* Response : �޽��� ���� ��� */
        /*
         $data['msgid'] = '';						// -  text(20) Y �޽��� �Ϸù�ȣ(�޽����� ���� ������ ��)
         $data['result'] = '';					// -  text(1) Y ó����� ���� ��(Y:����, N:����)
         $data['code'] = '';						// -  text(4) Y ó����� �ڵ�
         $data['error'] = '';						// -  text N ���� �޽���
         $data['kind'] = '';						// -  text(1) N �߼���å(K: īī�� ����޽���, M: ���ڸ޽���, P: ����Ʈ�ù� �� Ǫ�þ˸�)
         
         
         echo '<pre> :: ';print_r($tel_number);
         */
        
        /* Request : �޽��� ���� ��� Ȯ�� ��û */
        //path : /v1/ft/{profile_key}/upload_image
        //profile_key : �÷���ģ���� �̿��Ͽ� �߽��������� ����� �߱޵Ǵ� �߽�������Ű(alpha-numeric 40��)
        //method : POST
        //parameter
        //		$data['msgid'] = '';						// -   text(20) Y �޽��� �Ϸù�ȣ(�޽����� ���� ������ ��)
        
        /* Response : ��� ���� */
        /*
         $data['msgid'] = '';						// -   text(20) Y �޽��� �Ϸù�ȣ(�޽����� ���� ������ ��)
         $data['result'] = '';					// -   text(1) Y ó����� ���� ��(Y:����, N:����)
         $data['code'] = '';						// -   text(4) Y ó����� �ڵ�
         $data['error'] = '';						// -   text N ���� �޽���
         $data['kind'] = '';						// -   text(1) N �߼���å(K: īī�� ����޽���, M: ���ڸ޽���, P: ����Ʈ�ù� �� Ǫ�þ˸�)
         */
        
        $data = array();
        $data['mst_mem_id'] = $this->member->item('mem_id');
        $data['mst_template'] = ($msgType=="at") ? $this->input->post('templateCode') : '';
        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
        $data['mst_kind'] =  "ft";
        $data['mst_content'] =  $this->input->post('templi_cont');
        $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_lms_send'] =  $this->input->post('kind');
        $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_sms_content'] =  '';
        $data['mst_lms_content'] =  $this->input->post('msg');
        $data['mst_mms_content'] =  '';
        $data['mst_img_content'] =  $this->input->post('img_url');
        $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
        $data['mst_sms_callback'] =  $this->input->post('senderBox');
        $data['mst_status'] = '1';
        $data['mst_qty'] =  count($this->input->post('tel_number'));
        $data['mst_amount'] =  ($this->Biz_model->price_ft * count($this->input->post('tel_number')));
        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        
        $this->load->library('sweettracker');
        $ok = $this->sweettracker->sendAgent("ft");
        if($ok >0) { $this->sweettracker->sendAgentSendCache("ft", $gid); }
        
        header('Content-Type: application/json');
        echo '{"message": "", "code": "success"}';
        
    }
}
?>