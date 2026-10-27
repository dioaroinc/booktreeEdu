<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	function __construct() {
        
		parent::__construct();
		$this -> load -> model('common_model');
        $this -> load -> helper(array('url', 'date', 'form'));

	}

    function index(){

        if($_SESSION['num']){
            echo "
                <script>
                    location.href='/main/index';
                </script>
            ";
            return;
        }

        $this -> load -> view('web/index');

    }

    function find(){

        $this -> load -> view('web/find');

    }

    function auth(){

        // 계정의 유효성 확인
        // booktreei 본서버에서 로그인을 한다.

        $id = $_POST['id'];
        $pass = $_POST['pass'];

        $bdb = $this -> load -> database('booktree',TRUE);
		$sql = "select * from user where id = '$id' and password = '$pass' ";
		$query = $bdb -> query($sql);
		$chk = $query -> row();
       
        if(!$chk){
            echo "failed";
        }
        else{

            if($chk -> status == 1){

                // 원래는 pending
                //echo "pending";

                $_SESSION['num'] = $chk -> num;
                $_SESSION['name'] = $chk -> name;
                $_SESSION['type'] = $chk -> type;
                
                echo "success";
            }
            else{

                $_SESSION['num'] = $chk -> num;
                $_SESSION['name'] = $chk -> name;
                $_SESSION['type'] = $chk -> type;

                echo "success";
            }
            
        }
    }

    
    function logout(){

        $this->session->sess_destroy();

        echo "
            <script>
                location.href='/web';
            </script>
        ";
    }


}
