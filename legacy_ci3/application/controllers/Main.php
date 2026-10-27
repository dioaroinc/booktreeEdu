<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
        
		parent::__construct();
		$this -> load -> model('common_model');
        $this -> load -> helper(array('url', 'date', 'form'));

        if(!$_SESSION['num']){
            echo "접근권한이 없습니다 <a href='/web/logout'>돌아가기</a>";
            exit;
        }

	}

    function index(){


        $heer['now_title'] = "메인";

        $this -> load -> view('layout/header', $heer);
        $this -> load -> view('jsc');
        $this -> load -> view('main/index', $data);
        $this -> load -> view('layout/footer', $heer);

    }
    
    

}
