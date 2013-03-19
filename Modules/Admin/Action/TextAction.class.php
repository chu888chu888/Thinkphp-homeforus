<?php
class TextAction extends CommonAction{
	/**
	 * 故事管理
	 */
	function story(){
		//实例化mongo模型
		$m	=	$this->init_mongo('story');
		
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
		$this->display();
	}
	
	/**
	 * 创建故事 插入数据库
	 */
	function finish_add_story(){
		$m	=	$this->init_mongo('story');
		//取出数组里面最后的HASH值
		array_pop($_POST);
		$_POST['datetime']	=	date('Y-m-d H:i:s');
		//判断字符不为空
		foreach ($_POST as $val){
			if ( $val	==	'' ){
				$this->error('内容不能为空');
			}
		}
		try {
	   		$m->insert($_POST,array("safe" => true));
	   		$this->success('添加成功！');
		} catch(MongoCursorException $e) {
			$this->error('添加失败！');
		}
	}
	
	/**
	 * 删除故事
	 */
	function del_story(){
		$this->mongo_deldata('story',U('Admin/Text/story'));
	}
	
	/**
	 * 编辑故事
	 */
	function edit_story(){
		$this->mongo_editdata('story');
		$this->display();
	}
	
	/**
	 * 完成编辑的更新
	 */
	function finish_edit_story(){
		$this->mongo_finish_edit('story',U('Admin/Text/story'));
	}
	
	/**
	 * 显示个人资料
	 */
	function profile(){
		//实例化mongo模型
		$m	=	$this->init_mongo('profile');
		
		//得到排序数据
		$so	=	$_GET['sort'];
		//如果不存在排序数据，则进行搜索数据的查询
		if( empty($so) ){
			//得到查询数据
			$searchName	=	trim($_GET['searchName']);
			$searchVal	=	trim($_GET['searchVal']);
			//如果存在查询数据
			if( !empty($searchName) ){
				$obj	=	$this->mongo_paging($m,array('_id'=>-1),array($searchName=>new MongoRegex("/{$searchVal}/i")));
			}else{
				//否则，直接调用分页函数,默认按添加倒序排序
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
		$this->display();
	}
	
	/**
	 * 新增个人资料插入数据库
	 */
	function finish_add_profile(){
		$m	=	$this->init_mongo('profile');
		//取出数组里面最后的HASH值
		array_pop($_POST);
		//判断字符不为空
		foreach ($_POST as $val){
			if ( $val	==	'' ){
				$this->error('内容不能为空');
			}
		}
		try {
			$m->insert($_POST,array("safe" => true));
			$this->success('添加成功！');
		} catch(MongoCursorException $e) {
			$this->error('添加失败！');
		}
	}
	
	/**
	 * 删除个人资料
	 */
	function del_profile(){
		$this->mongo_deldata('profile',U('Admin/Text/profile'));
	}
	
	/**
	 * 编辑个人资料
	 */
	function edit_profile(){
		$this->mongo_editdata('profile');
		$this->display();
	}
	
	/**
	 * 完成个人资料的编辑
	 */
	function finish_edit_profile(){
		$this->mongo_finish_edit('profile',U('Admin/Text/profile'));
	}
	
	/**
	 * 显示联系我首页
	 */
	function contact(){
		//实例化mongo模型
		$m	=	$this->init_mongo('contact');
		
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
		$this->display();
	}
	
	/**
	 * 完成添加用户联系方式
	 */
	function finish_add_contact(){
		$m	=	$this->init_mongo('contact');
		//取出数组里面最后的HASH值
		array_pop($_POST);
		//判断字符不为空
		foreach ($_POST as $val){
			if ( $val	==	'' ){
				$this->error('内容不能为空');
			}
		}
		//添加创建时间
		$_POST['datetime']	=	date('Y-m-d H:i:s');
		try {
			$m->insert($_POST,array("safe" => true));
			$this->success('添加成功！');
		} catch(MongoCursorException $e) {
			$this->error('添加失败！');
		}
	}
	
	/**
	 * 编辑用户联系方式
	 */
	function edit_contact(){
		$this->mongo_editdata('contact');
		$this->display();
	}
	
	/**
	 * 完成 编辑用户联系方式
	 */
	function finish_edit_contact(){
		$this->mongo_finish_edit('contact',U('Admin/Text/contact'));
	}
	
	/**
	 * 联系我 数据删除
	 */
	function del_contact(){
		$this->mongo_deldata('contact',U('Admin/Text/contact'));
	}
	
	/**
	 * 查看comment
	 */
	function comment(){
			//实例化mongo模型
		$m	=	$this->init_mongo('comment');
		
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
		$this->display();
	}
	
	/**
	 * 删除评论
	 */
	function del_comment(){
		$this->mongo_deldata('comment',U('Admin/Text/comment'));
	}
}
?>