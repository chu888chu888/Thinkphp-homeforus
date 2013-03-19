KindEditor.ready(function(K) {
	var options = {
		width:'700px',
		height:'300px',
		resizeType:0,
		items : [
		 		'source', 'template', 'code', '|', 'justifyleft', 'justifycenter', 'justifyright',
		 		'justifyfull', 'insertorderedlist', 'insertunorderedlist','clearhtml', 'quickformat', 'selectall', 
		 		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		 		'italic', 'underline',  '|', 'image', 'multiimage',
		 		'table',  'baidumap'
		 	],
		//出去找到Editor目录里面的包含文件
		uploadJson : '../../../Editor/upload_json.php',
		fileManagerJson : '../../../Editor/php/file_manager_json.php',
		allowFileManager : true,
	};
	var editor = K.create('textarea[name="content"]', options);
	
	//当点击提交保存的适合，同步编辑器里面的数据到表单，然后提交数据
	$(".save").click(function(){
		//同步数据,把数据传输给textarea
		editor.sync();
		$("#form").submit();
	});
	
});