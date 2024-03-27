<?php
class Sql extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

	/**
	* 헬퍼를 로딩합니다
	*/
	protected $helpers = array('form', 'array');

	function __construct(){
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'funn'));
	}

	//관리자 > 쿼리실행
	public function index(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		/*
		//회원정보 조회
		$sql = "
		SELECT * 
		FROM cb_member 
		WHERE IFNULL(mem_site_key,'') = '' /* 사이트Key */
		ORDER BY mem_id ASC ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $_SERVER['REQUEST_URI'] ." > Query : ". $sql ."<br>";
		$datas = $this->db->query($sql)->result();
		if(!empty($datas)){
			//echo "datas : ". $datas ."<br>";
			$no = 0;
			foreach($datas as $r){
				$no++;
				$mem_id = $r->mem_id; //파트너 회원번호
				$mem_userid = $r->mem_userid; //파트너 회원ID
				$mem_site_key = $r->mem_site_key; //파트너 사이트연결 Key
				//echo "[". $no ."] mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", mem_site_key : ". $mem_site_key ."<br>";
				//사이트연결 Key가 없는 경우
				if($mem_site_key == ""){ //사이트연결 Key가 없는 경우
					$site_key = $mem_id . $mem_userid . cdate('YmdHis');
					$mem_site_key = $this->funn->encrypt($site_key);
					//echo "site_key : ". $site_key .", mem_site_key : ". $mem_site_key ."<br>";
					//사이트연결 Key 업데이트
					$sql = "
					UPDATE cb_member SET
						mem_site_key = '". $mem_site_key ."'
					WHERE mem_id = '". $mem_id ."'
					AND mem_userid = '". $mem_userid ."' ";
					$this->db->query($sql);
					echo "[". $no ."] sql : ". $sql ."<br>";
				}
			}
		}
		*/
		
		/*
		//회원별 정산테이블 조회
		$sql = "SHOW TABLE STATUS like 'cb_amt_%' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $_SERVER['REQUEST_URI'] ." > Query : ". $sql ."<br>";
		$datas = $this->db->query($sql)->result();
		if(!empty($datas)){
			//echo "datas : ". $datas ."<br>";
			$no = 0;
			foreach($datas as $r){
				$no++;
				$tbl_nm = $r->Name; //테이블명
				$userid = str_replace ("cb_amt_", "", $tbl_nm); //회원아이디
				//echo "userid : ". $userid .", tbl_nm : ". $tbl_nm ."<br>";

				//컬럼추가
				$sql = "ALTER TABLE ". $tbl_nm ." ADD amt_deduct TINYINT(4) NULL DEFAULT '1' COMMENT '+1.증가, -1.차감'; ";
				//echo "[". $no ."] 컬럼추가 : ". $sql ."<br>";
				//$this->db->query($sql);
				//echo $sql ."<br>";

				//기존 트리거 DROP
				$sql = "DROP TRIGGER ins_". $tbl_nm ."; ";
				//echo "[". $no ."] 기존 트리거 DROP : ". $sql ."<br>";
				//$this->db->query($sql);
				//echo $sql ."<br>";

				//신규 트리거 생성
				$sql = "
    			CREATE TRIGGER `ins_cb_amt_".$userid."` BEFORE INSERT ON `cb_amt_".$userid."`
    			  FOR EACH ROW
    			BEGIN
    				if NEW.amt_kind='1' then
    					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='".$userid."';
    					set NEW.amt_deposit := NEW.amt_amount;
    				else
    					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='".$userid."';
    					set @nPoint := 0;
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
    							set @nPoint := 0;
    							set NEW.amt_point := NEW.amt_amount;
                                set @nDeposit := @nDeposit + NEW.amt_amount;
    						end if;
    					end if;
    					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='".$userid."';
    				END IF;
    			END; ";
				//echo "[". $no ."] 신규 트리거 생성 : ". $sql ."<br><br>";
				//$this->db->query($sql);
				//echo $sql ."<br>";

				//신규 트리거 생성
				$sql = "
				CREATE TRIGGER ins_". $tbl_nm ." BEFORE INSERT ON ". $tbl_nm ." FOR EACH ROW 
				BEGIN
					if NEW.amt_kind IN ('1', '3', '4', '5', '6', '9') then
						set NEW.amt_deduct := 1;
					ELSE
						set NEW.amt_deduct := -1;
					END if;
				END; ";
				//echo "[". $no ."] 신규 트리거 생성 : ". $sql ."<br><br>";
				//$this->db->query($sql);
				//echo $sql ."<br>";

				//증감 업데이트
				$sql = "UPDATE ". $tbl_nm ." SET amt_deduct = (CASE WHEN amt_kind IN ('1', '3', '4', '5', '6', '9') THEN 1 ELSE -1 END); ";
				//echo "[". $no ."] 증감 업데이트 : ". $sql ."<br>";
				//$this->db->query($sql);
				//echo $sql ."<br>";
				
			}
		}else{
			echo "datas : null<br>";
		}
		*/
	}

}
?>
