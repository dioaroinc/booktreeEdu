<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Academy extends CI_Controller {

	function __construct() {
        
		parent::__construct();
		$this -> load -> model('common_model');
        $this -> load -> helper(array('url', 'date', 'form'));

        if(!$_SESSION['num']){
            echo "접근권한이 없습니다 <a href='/web/logout'>돌아가기</a>";
            exit;
        }

	}

    function lecture(){

        $num = $this -> uri -> segment(3);

        $info = $this -> common_model -> rowfinder("lecture","*"," WHERE num = $num");
        $data['info'] = $info;

        $heer['now_title'] = $info -> name;
        $heer['now_loc'] = "class_".$num;

        $this -> load -> view('layout/header', $heer);
        $this -> load -> view('jsc');
        $this -> load -> view('academy/lecture', $data);
        $this -> load -> view('layout/footer', $heer);

    }

    function lecture_view(){

        $num = $this -> uri -> segment(3);

        
        $this -> load -> view('layout/header_no', $heer);
        $this -> load -> view('jsc');
        $this -> load -> view('academy/lecture_view', $data);
        $this -> load -> view('layout/footer_no', $heer);

    }

    function lecture_test(){

        $num = $this -> uri -> segment(3);


        $this -> load -> view('layout/header_no', $heer);
        $this -> load -> view('jsc');
        $this -> load -> view('academy/lecture_test', $data);
        $this -> load -> view('layout/footer_no', $heer);

    }
    
    

}
