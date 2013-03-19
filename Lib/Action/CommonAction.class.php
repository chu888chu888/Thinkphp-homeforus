<?php
class CommonAction extends Action {

	/**
	 * [_initialize description]
	 * @return [type] [自动加载]
	 */
	function _initialize()
	{
		//设置输出字符集
		header("Content-Type:text/html; charset=utf-8");
	}

    function index()
    {
		echo 'test';
    }
}