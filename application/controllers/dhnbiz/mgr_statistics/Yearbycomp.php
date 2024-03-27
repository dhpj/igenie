<?php
class Yearbycomp extends CB_Controller {
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
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['startDate'] = date('Y-m', strtotime(date('Y-m-d')."-11month"));
		$startDateYmd = date('Ym', strtotime(date('Y-m-d')."-11month")).'01';
		$view['param']['endDate'] = date('Y-m');
		$view['param']['group_id'] = $this->input->post('group_id');
		$view['param']['pf_key'] = $this->input->post('pf_key');
		log_message("ERROR", "Yearbycomp.php this->input->post('pf_key') : ".$this->input->post('pf_key'));
		
		if (empty($view['param']['group_id'])) {
		    $view['param']['group_id'] = 3;
		}
		
		$view['company'] = $this->Biz_model->get_member_from_profile_key($view['param']['pf_key']);
		//$view['profile'] = $this->Biz_model->get_partner_profile();
		$sql = "
            select * from (
            SELECT mem_id, mem_userid, mem_username, mem_level,
            	(select count(0) cnt from cb_member_register c inner join cb_member d on c.mem_id = d.mem_id where c.mrg_recommend_mem_id = a.mem_id) as cnt
            FROM cb_member a 
            WHERE mem_level >= 30 and mem_useyn = 'Y' ) b
            where b.cnt > 0
            order by b.mem_username
            ";
		log_message("ERROR", "sql : ".$sql);
		$view['group'] = $this->db->query($sql)->result();
		//$view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
		$view['profile'] = $this->Biz_model->get_Children($view['param']['group_id']);
		
		if (empty($view['param']['pf_key']) || $this->input->post('groupChange') == "Y") {
		    $temp = $view['profile'][0];
		    $firstMem_id = $temp->mem_id;
		    $view['param']['pf_key'] = $firstMem_id;
		    //log_message("ERROR", "firstMem_id : ".$firstMem_id);
		} 
		
		//log_message("ERROR", "view['param']['pf_key'] : ".$view['param']['pf_key']);
		
		$sql = "
            select a.mem_id, a.mem_userid, a.mem_nickname, a.mem_phone, a.mem_emp_phone, a.mem_sales_name, DATE_FORMAT(a.mem_register_datetime, '%Y-%m-%d') as mem_register_datetime, 
                a.mem_full_care_type, a.mem_pf_cnt, a.mem_sales_name, a.mem_userid, b.mrg_recommend_mem_id as parent_id
            from cb_member a left join cb_member_register b on a.mem_id = b.mem_id 
            where a.mem_id = ".$view['param']['pf_key'];
		log_message("ERROR", "sql : ".$sql);
		$view['memberinfo'] = $this->db->query($sql)->row();
		
		$temp = $view['memberinfo'];
		$memUserId = $temp->mem_userid;
		$view['register_dt'] = $temp->mem_register_datetime;
		$memSalesName = $temp->mem_sales_name;
		$parentId = $temp->parent_id;
		
		if (empty($memSalesName)) {
		    $sql = "            
                select a.mem_id, a.mem_nickname, a.mem_userid
                from cb_member a
                where a.mem_id = ".$parentId;
		    
		    //log_message("ERROR", "sql : ".$sql);
		    $memSalesName = $this->db->query($sql)->row()->mem_nickname;
		}
		
		$view['mem_sales_name'] = $memSalesName;
		log_message("ERROR", "memSalesName : ".$memSalesName);
		
		$sql = "
            select count(*) ftcnt 
            from cb_ab_".$memUserId." 
            where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = ".$view['param']['pf_key']." and b.phn = ab_tel)    
        ";
		//log_message("ERROR", "sql : ".$sql);
		$view['ftCount'] = $this->db->query($sql)->row()->ftcnt;
		
		$sql = "
            select b.yyyymm,
                IFNULL(c.mst_qty, 0) mst_qty,
                IFNULL(c.mst_at, 0) mst_at,
                IFNULL(c.mst_ft, 0) mst_ft,
	            IFNULL(c.mst_ft_img, 0) mst_ft_img,
	            IFNULL(c.mst_ft_w_img, 0) mst_ft_w_img,
	           (IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0)) as mst_ft_total, 
	           IFNULL(c.mst_2nd, 0) mst_2nd
            FROM (SELECT DISTINCT substr(DATE_FORMAT(DATE_ADD('".$startDateYmd."', INTERVAL seq-1 DAY), '%Y-%m'), 1, 7) as yyyymm
                FROM seq_1_to_365) b 
                    LEFT JOIN (
                        SELECT d.yyyymm,
                            sum(d.mst_qty) as mst_qty,
                            sum(d.mst_at) as mst_at,
                            sum(d.mst_ft) as mst_ft,
                            sum(d.mst_ft_img) as mst_ft_img,
                            sum(d.mst_ft_w_img) as mst_ft_w_img,
                            sum(d.mst_2nd) as mst_2nd
                        FROM (
                            SELECT substr((case when a.mst_reserved_dt <> '00000000000000' then STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) as yyyymm,
                                a.mst_qty, a.mst_at, a.mst_ft, 
                                case when mst_type1 = 'fti' then a.mst_ft_img else 0 end as mst_ft_img,
                                case WHEN mst_type1 = 'ftw' then a.mst_ft_img else 0 end as mst_ft_w_img,
                                (a.mst_grs + a.mst_grs_sms + a.mst_nas + a.mst_nas_sms + a.mst_smt) as mst_2nd
                            from cb_wt_msg_sent a
                            where a.mst_mem_id = ".$view['param']['pf_key']."  
                                and (CASE WHEN a.mst_reserved_dt = '00000000000000' THEN a.mst_datetime BETWEEN '".$view['param']['startDate']."-01 00:00:00' AND NOW() ELSE STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$view['param']['startDate']."-01 00:00:00' AND NOW() END )
                        ) d
                        GROUP by d.yyyymm
            ) c on b.yyyymm = c.yyyymm";
		
		log_message("ERROR", "sql : ".$sql);
		$view['list'] = $this->db->query($sql)->result();
		//$view['list'] = $this->Biz_model->get_send_report('month', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
		
		$sql = "select count(1) cnt from cb_service_history where sh_mem_id = ".$view['param']['pf_key']." and sh_type='H'";
		$view['memo_total'] = $this->db->query($sql)->row()->cnt;
		
		$sql = "select * from cb_service_history where sh_mem_id = ".$view['param']['pf_key']." and sh_type='H' order by sh_reg_date DESC limit 0, 5";
		log_message("ERROR", "sql : ".$sql);
		$view['memo_list'] = $this->db->query($sql)->result();
		
		$view['view']['canonical'] = site_url();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		* ���̾ƿ��� �����մϴ�
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/mgr_statistics',
			'layout' => 'layout',
			'skin' => 'yearbycomp',
			'layout_dir' => $this->cbconfig->item('layout_main'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
			'use_sidebar' => $this->cbconfig->item('sidebar_main'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
			'skin_dir' => $this->cbconfig->item('skin_main'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
	
	public function happycall_save() {
	    $memo = $this->input->post('memo');
	    $mem_id = $this->input->post('mem_id');
	    $memo_kind = $this->input->post('memo_kind');
	    
	    $data = array();
	    $data['sh_mem_id'] = $mem_id;
	    $data['sh_reg_date'] = cdate('Y-m-d H:i:s');
	    $data['sh_text'] = $memo;
	    $data['sh_type'] =  $memo_kind;
	    
	    $this->db->insert("service_history", $data);
    
	    header('Content-Type: application/json');
	    echo '{"message":"success", "code":"success"}';
	}
	
	public function happycall_list() {
	    //log_message("ERROR", "happycall_list call");
	    $mem_id = $this->input->post('mem_id');
	    $memo_kind = $this->input->post('memo_kind');
	    
	    $sql = "select count(1) cnt from cb_service_history where sh_mem_id = ".$mem_id." and sh_type='".$memo_kind."'";
	    $memo_total = $this->db->query($sql)->row()->cnt;
	    
	    $sql = "select * from cb_service_history where sh_mem_id = ".$mem_id." and sh_type='".$memo_kind."' order by sh_reg_date DESC limit 0, 5";
	    //log_message("ERROR", "sql : ".$sql);
	    $memo_list = $this->db->query($sql)->result();
	    
    
	    header('Content-Type: application/json');
	    echo '{"message":"success", "code":"success", "memo_total_count":"'.$memo_total.'", "memo_list" : '.json_encode($memo_list).'}';
	}
}
?>