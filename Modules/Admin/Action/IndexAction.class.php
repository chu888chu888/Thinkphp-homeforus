<?php
class IndexAction extends Action{
	/**
	 * 插入数据到mongodb
	 */
	function insert_db(){
		$coll	=	$this->init_mongo('users');	
			
		$obj	=	array('username'=>'xiaozhe5','password'=>md5('xiaozhe5'));
		$coll->insert($obj);
	}
	
    public function index(){
    	$this->display();
    }
    
    /**
     * 登陆
     */
    function login(){
    	$verify	=	strtolower($_SESSION['verify']);
    	
    	//get post data
    	$post_verify	=	strtolower($this->_post('verify','trim'));
    	$post_username	=	$this->_post('username','trim');
    	$post_password	=	$this->_post('password','trim');
    	
    	//get mongodb data
    	$m	=	new Mongo("localhost:27017", array("persist" => "x"));
    	//选择数据库
    	$db	=	$m->myhome;
    	//选择集合
    	$coll	=	$db->users;
    	
    	$where	=	array(
    			'username'	=>	$post_username,
    			'password'	=>	md5($post_password),
    			);
    	$cursors	=	$coll->findOne($where);
    	if( count($cursors) > 0 ){
    		//缓存用户名
    		$_SESSION['username']	=	$post_username;
    		$this->redirect('Admin/Manage/index');
    	}else{
    		$this->error('用户名密码错误');
    	}
    }
    
    /**
     * 登出
     */
    function logout(){
    	unset($_SESSION['username']);
    	session_destroy();
    	$this->redirect('Admin/Index/index');
    }
    
    /**
     * 创建验证码
     */
    function verify(){
    	create_verify(210,40);
    }
    
    /**
     * 显示后台首页
     */
    function manage(){
    	$this->display();
    }
    

    
}