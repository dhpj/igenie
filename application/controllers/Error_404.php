<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Faq class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * FAQ 페이지를 보여주는 controller 입니다.
 */
class Error_404 extends CB_Controller
{

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('pagination', 'querystring'));
    }


    /**
     * FAQ 페이지입니다
     */
    public function index($fgr_key = '')
    {
        $this->load->view('errors/html/error_404');
    }
}
