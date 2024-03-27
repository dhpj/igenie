<?php
class Check_special_char extends CB_Controller {
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
	    $lmsText = $this->input->post("lmsText");
	    $returnCheck = 0;
	    $returnByte = 0;
	    $returnMsg = "";
	    
	    $data_cp949 = @iconv("UTF-8", "CP949", $lmsText);
	    
	    $checkByte = strlen($data_cp949);
	    if ($data_cp949 === false) {
	        $returnCheck = 0;
	        $tempPos = 0;
	        $tempSpecialChar = "";
	        $tempPreStr = "";
	        for ($pos = 0; $pos < strlen($lmsText); $pos++) {
	            if(@iconv("UTF-8", "CP949", mb_substr($lmsText, $pos, 1)) === false) {
	                $tempPos = $pos;
	                $tempSpecialChar = mb_substr($lmsText, $pos, 1);
	                break;
	            }
	        }
	        if ($pos < 5) {
	            if ($pos == 0) {
	                $tempPreStr = "첫번째 문자";
	            } else {
	                $tempPreStr = "'".mb_substr($lmsText, 0, $pos)."'의 뒷 문자";
	            }
	        } else {
	            $tempPreStr = "'".mb_substr($lmsText, $pos - 5, 5)."'의 뒷 문자";
	        }
	        $tempPreStr = str_replace("\n", "", $tempPreStr);
	        
	        $returnMsg = "메시지 내용에 지원하지 않는 문자가 있습니다.<br>".$tempPreStr." [".$tempSpecialChar."]는 자원하지 않는 문자입니다.";
	    } else {
	        $returnCheck = 1;
	        $returnByte = $checkByte;
	        $returnMsg = "OK";
	    }
	    
	    header('Content-Type: application/json');
	    echo '{"checkMode": "'.$returnCheck.'", "tatalBytes":"'.$returnByte.'", "checkMsg":"'.$returnMsg.'"}';
	}
	

}
?>

