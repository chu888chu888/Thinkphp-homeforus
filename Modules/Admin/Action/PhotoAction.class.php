<?php
class PhotoAction extends CommonAction{
	/**
	 * 相册管理首页
	 */
	function index(){
		$this->mongo_selall('photo');
		$this->display();
	}
	
	/**
	 *图片 插入数据库 
	 */
	function finish_add_photo(){
		$m	=	$this->init_mongo('photo');
		//取出数组里面最后的HASH值
		array_pop($_POST);
		//判断POST过来的字符不为空
		foreach ($_POST as $val){
			if ( $val	==	'' ){
				$this->error('内容不能为空');
			}
		}
		//新增图片数据和创建时间
		$_POST['datetime']	=	date('Y-m-d H:i:s');
		$photoInfo	=	$this->getUploadInfo();
		$filename	=	$photoInfo[0]['savename'];
		$_POST['filename']	=	$filename;
		try {
			$m->insert($_POST,array("safe" => true));
			$this->success('添加成功！');
		} catch(MongoCursorException $e) {
			$this->error('添加失败！');
		} 
	}
	
	/**
	 * 删除图片
	 */
	function del_photo(){
		$this->mongo_deldata('photo',U('Admin/Photo/index'));
	}
	
	/**
	 * 得到编辑图片的数据
	 */
	function edit_photo(){
		$this->mongo_editdata('photo');
		$this->display();
	}
	
	/**
	 * 完成 编辑图片的数据
	 */
	function finish_edit_photo(){
		$_id	=	$_POST['_id'];
		//连续2次取出数组最后的2个值："_id"和"__hash__"
		array_pop($_POST);
		array_pop($_POST);
		
		//如果是重新上传
		$reupload	=	$_POST['reupload'];
		if( $reupload ){
			//我们就需要重新得到图片的信息
			$fileinfo	=	$this->getUploadInfo();
			$filename	=	$fileinfo[0]['savename'];
			//更新上传的文件名
			$_POST['filename']	=	$filename;
			//然后删除掉reupload数组值
			unset($_POST['reupload']);
		}
		
		//否则没有更新上传图片，只有数据，直接更新数据
		$where	=	array("_id"	=>	new MongoId($_id));
		
		$m	=	$this->init_mongo('photo');
		try {
			$m->update($where,$_POST,array('safe'=>true));
			$this->assign('jumpUrl',U("Admin/Photo/index"));
			$this->success('更新成功');
		} catch (MongoCursorException $e) {
			$this->error('更新失败，请联系管理员！');
		} 
	}
}