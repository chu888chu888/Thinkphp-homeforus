<?php
class IndexAction extends CommonAction 
{
	/**
	 * 首页内容
	 */
    function index()
    {
    	$storyModel = new MongoModel("Story");
    	$list = $storyModel->where(array('active'=>'1'))->order('_id desc')->select();

    	$this->assign('list',$list);
    	$this->display();
    }


     /**
     * ajax得到内容
     */
    function getstory()
    {
    	if($this->isPost())
    	{
    		$storyModel = new MongoModel("Story");
    		$_id	=	$_POST['_id'];
	    	$list = $storyModel->where(array('_id'=>$_id))->find();
	    	unset($list['_id']);
			echo json_encode($list);
    	}
    }

 	/**
     * 查看相册
     */
    function photo()
    {
    	$photoModel = new MongoModel("Photo");
    	if( empty($_GET['p']) || $_GET['p'] == 1 )
    	{
			$p	=	0;
		}else
		{
			$p	=	(int)$_GET['p']	-	1;
		}
    	//瀑布流的分页
    	$list = $photoModel->limit($p*C('SHOWIMG'),C('SHOWIMG'))->select();

    	$this->assign('list',$list);
    	$this->display();
    }
      

    /**
     * 个人资料
     */
    function profile()
    {
    	//获得Evaluate数据库内容
    	$evaluateModel = new MongoModel('Evaluate');
    	$evaluate = array();
    	$list = $evaluateModel->select();
    	foreach ($list as $val){
    		$evaluate[$val['type']] = $val;
    	}
    	
    	
     	$profileModel = new MongoModel('Profile');
     	$list = $profileModel->order('_id desc')->select();
     	//设置一个分类的数组
     	$infoArr = array();
    	foreach($list as $val){
    		//设置一个数组，键值为type
    		$infoArr[$val['type']][]	=	$val;
    	}

    	//传值过去
    	$this->assign('listOpt1',$infoArr['opt1']);
    	$this->assign('listOpt2',$infoArr['opt2']);
    	$this->assign('listOpt3',$infoArr['opt3']);
    	$this->assign('listOpt4',$infoArr['opt4']);
    	$this->assign('eva',$evaluate); 
    	$this->display();
    }

    /**
     * 显示联系我
     */
    function contact()
    {
    	$contactModel = new MongoModel("Contact");
    	$list = $contactModel->select();
    	$tmpArr = array();
    	foreach ($list as $key => $value) 
    	{
    		$tmpArr[$value['type']] = $value;
    	}
    	$this->assign('list',$tmpArr);
    	$this->display();
    }

    /**
     * 查看所有故事
     */
    function showall()
    {
    	$storyModel = new MongoModel("Story");
    
    	import("ORG.Util.Page");
    	$count = $storyModel->where(array('active'=>'1'))->count();
		$Page	=	new Page($count,C('PAGESIZE'));
		$show	= $Page->show();

		//这个分页使用的是分页类和page方法的实现
		$list = $storyModel->where(array('active'=>'1'))->order('_id desc')->page($_GET['p'],C('PAGESIZE'))->select();

    	$this->assign('list',$list);
    	$this->assign('show',$show);
    	$this->display();
    }
	
}