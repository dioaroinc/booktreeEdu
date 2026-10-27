<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
        
		parent::__construct();
		$this -> load -> model('common_model');
        $this -> load -> helper(array('url', 'date', 'form'));

        if($_SESSION['type'] != 100){
            echo "접근권한이 없습니다 <a href='/web/logout'>돌아가기</a>";
            exit;
        }

	}

    function index(){


    }

  

}
