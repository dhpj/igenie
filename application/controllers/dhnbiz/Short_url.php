<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Short_url extends CB_Controller {  
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('google_url_api');
        $this->load->library('base62');
    }
    
    function index()
    {
        $url = $this->input->post('long_url');
        if(!$url) {
            header('Content-Type: application/json');
            echo '{"short_url": ""}';
            return;
        }
        
        $sql = "select short_url, count(1) as cnt from cb_short_url where url = '".$url."' group by short_url";
        //log_message('ERROR', 'Short Url : '.$sql.' / URL : '.$url);
        $short = $this->db->query($sql)->row();
        
       // //log_message('ERROR', 'short : '.$short);
        
        if($short->cnt == 0)
        {
            $short_url_code = $this->base62->encode(cdate('YmdHis'));
            
            $shorturl = array();
            $shorturl['short_url'] = $short_url_code;
            $shorturl['url'] = $url;
            
            $this->db->insert('short_url', $shorturl);
            
            //log_message('ERROR', 'Short Url : '.$short_url_code.' / URL : '.$url);
            
            $short_url = 'http://'. $_SERVER['HTTP_HOST'] .'/ad/'.$short_url_code;
        } else {
            $short_url = 'http://'. $_SERVER['HTTP_HOST'] .'/ad/'.$short->short_url;
        }
        header('Content-Type: application/json');
        echo '{"short_url": "'.$short_url.'"}';
    }
    
    private function _dump($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '<pre>';
    }

    private function _print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '<pre>';
    }    
}

/* End of file google_url.php */
/* Location: ./application/controller/google_url.php */
