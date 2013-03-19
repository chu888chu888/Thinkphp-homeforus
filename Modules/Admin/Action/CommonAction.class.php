<?php
class CommonAction extends Action{
	/**
	 * 初始化
	 */
	function _initialize(){
		if( !isset($_SESSION['username']) ){
			$this->assign('jumpUrl',U('Admin/Index/index'));
			$this->error('请登录');
		}
		//开启SESSION
		session_start();
		//设置输出字符集
		header("Content-Type:text/html; charset=utf-8");
		//传值到MODULE_NAME 到 top.html 判断url
		$this->assign('module_name',MODULE_NAME);		
	}
	
	/**
	 * 上传函数,得到上传成功后的信息
	 */
	function getUploadInfo(){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
		$upload->saveRule	=	uniqid;
		if( !$upload->upload() ) {
			// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{
			// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			return $info;
		}
	}
	
	/**
	 * 实例化Mongo,并默认选择数据库myhome
	 * @param  $collection	选择集合
	 * @param  $database	选择数据库
	 * @return coll  集合句柄
	 */
	function init_mongo($collection,$database='myhome'){
		//实例化mongo
		$m	=	new Mongo("localhost:27017", array("persist" => "x"));
		//选择数据库
		$db	=	$m->$database;
		//选择集合
		$coll	=	$db->$collection;
		return $coll;
	}
	
	/**
	 * Mongo的分页函数
	 * @param  	实例化 $m	mongodb的集合
	 * @param	排序 $order	array('datetime'=>-1)
	 * @param	查询 $search	array('name'=> 'aaa')
	 * @return 查找到的数据对象
	 */
	function mongo_paging($m,$order,$search=array()){
		//得到story的数据总数
		$count	=	$m->count($search);
		//得到总页数
		$total_page	=	ceil($count/C("PAGESIZE"));
		
		import("ORG.Util.Page");
		$Page	=	new Page($count,C('PAGESIZE'));
		$show	= $Page->show();
		
		if( empty($_GET['p']) || $_GET['p'] == 1 ){
			$p	=	0;
		}else{
			$p	=	(int)$_GET['p']	-	1;
		}
		
		//得到页的数据
		$obj	=	$m->find($search)->skip(C("PAGESIZE")*$p)->limit(C("PAGESIZE"));
		
		if( !empty($order) ){
			//排序
			$obj->sort($order);
		}
		
		$this->assign('show',$show);
		return $obj;
	}
	
	/**
	 * 查询所有数据并传给显示页
	 * @param 集合名  $coll
	 * @return 直接传输了list到模板，记得要自己使用$this->display()
	 */
	function mongo_selall($coll){
		//实例化mongo模型
		$m	=	$this->init_mongo($coll);
		
		//得到排序数据
		$so	=	$_GET['sort'];
		if( empty($so) ){
			//得到查询数据
			$searchName	=	trim($_GET['searchName']);
			$searchVal	=	trim($_GET['searchVal']);
			//如果存在查询数据
			if( !empty($searchName) ){
				$obj	=	$this->mongo_paging($m,array('_id'=>-1),array($searchName=>new MongoRegex("/{$searchVal}/i")));
			}else{
				//调用分页函数,默认按添加倒序排序
				$obj	=	$this->mongo_paging($m,array('_id'=>-1));
			}
		}else{
			//得到搜索数据
			if( $_GET['order'] == 'desc'){
				$or	=	-1;
			}else{
				$or	=	1;
			}
			//调用分页函数
			$obj	=	$this->mongo_paging($m,array($so => $or));
		}
		
		foreach ($obj	as	$arr){
			//将数据的_id插入数组中
			$arr1	=	array("_id"	=>	(string)$arr['_id']);
			$arr = array_merge($arr, $arr1);
			$list[]	=	$arr;
		}
		
		$this->assign('list',$list);
	}
	
	/**
	 * 因为删除数据格式差不多，所以写成一个函数即可
	 * @param 要删除数据的所属集合 $coll
	 * @param 删除成功或失败后转向的URL $url
	 */
	function mongo_deldata($coll,$url=''){
		$selected	=	$_POST['selected'];
		if( empty($selected) ){
			//如果url为空，则不指定
			if( !empty($url) ){
				$this->assign('jumpUrl',$url);
			}
			$this->error('请选择数据！');
		}else{
			$m	=	$this->init_mongo($coll);
			//得到所有的键值即(_id)值
			$ids	=	array_keys($selected);
			//因为ID是数组的格式，所以用foreach得到_id然后删除
			foreach ($ids as $_id){
				try {
					$m->remove(array('_id' => new MongoId($_id)), array("safe" => true));
				} catch (MongoCursorException $e) {
					$this->error('更新失败，请联系管理员！');
				}
			}
			if( !empty($url) ){
				$this->assign('jumpUrl',$url);
			}
			$this->success('删除数据成功！');
		}
	}
	
	/**
	 * mongo的数据编辑
	 * @param 需要编辑的数据所属集合  $coll
	 * @return 直接分配了查到的数据到页面,需要个人display
	 */
	function mongo_editdata($coll){
		$_id	=	$_GET['_id'];
		$where	=	array('_id'	=>	new MongoId($_id));
		$m	=	$this->init_mongo($coll);
		$list	=	$m->findOne($where);
		
		$this->assign('list',$list);
	}
	
	/**
	 * mongo的完成数据更新函数
	 * @param 要删除数据的所属集合 $coll
	 * @param 删除成功或失败后转向的URL $url
	 */
	function mongo_finish_edit($coll,$url=''){
		$_id	=	$_POST['_id'];
		//连续2次取出数组最后的2个值："_id"和"__hash__"
		array_pop($_POST);
		array_pop($_POST);
		
		$where	=	array("_id"	=>	new MongoId($_id));
		
		$m	=	$this->init_mongo($coll);
		try {
			$m->update($where,$_POST,array('safe'=>true));
			//如果url为空，则不指定
			if( !empty($url) ){
				$this->assign('jumpUrl',$url);
			}
			$this->success('更新成功');
		} catch (MongoCursorException $e) {
			$this->error('更新失败，请联系管理员！');
		}
	}
}
?>