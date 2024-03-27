<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dhnlurl {

    public function get_short($url) {
        // log_message("ERROR", " 호출 ");

        $headers = array(
            "Content-Type: application/json; charset=utf-8"
        );
        $parameters = array(
            "long" => $url
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://dhnl.kr/get_short");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($parameters));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        $out1 = "";
        //log_message("ERROR", $response);
        return $response;

        // $CI =& get_instance();
        //
        // $i = 0;
        //
        // while($i < 10) {
        //     $surl = array();
        //     $surl['url'] = $url;
        //     $db = $CI->load->database("ppu", TRUE);
        //
        //     if($db->insert('cb_url_short_url', $surl))
        //     {
        //         $id = $db->insert_id();
        //
        //         if($id >= 999999) {
        //           $str = '2'.sprintf("%06d", ($id % 999999) );
        //         } else {
        //           $str = '2'.sprintf("%06d", $id);
        //         }
        //
        //         $CI->load->library('Base62');
        //         $short_url = $CI->base62->encode($str);
        //
        //         $db->query("delete from cb_url_short_url where short_url = '".$short_url."'");
        //         $db->query("update cb_url_short_url set short_url = '".$short_url."' where id = ".$id);
        //
        //         $ins_data = $db
        //             ->select("*")
        //             ->from("cb_url_short_url")
        //             ->where("id", $id)
        //             ->get()->row();
        //
        //         $db2 = $CI->load->database("54", TRUE);
        //         $db2->set("id", $ins_data->id)
        //             ->set("short_url", $ins_data->short_url)
        //             ->set("url", $ins_data->url)
        //             ->set("creation_date", $ins_data->creation_date)
        //             ->insert("cb_url_short_url");
        //
        //         $i = 11;
        //     } else {
        //         $i = $i + 1;
        //     }
        // }
        //
        // return "dhnl.kr/".$short_url;

		//return str_replace("http://", "", $url);
    }

}
