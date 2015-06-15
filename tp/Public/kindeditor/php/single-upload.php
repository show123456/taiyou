<?php
	$htmlData = '';
	if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>KindEditor PHP</title>
	<link rel="stylesheet" href="../themes/default/default.css" />
	<link rel="stylesheet" href="../plugins/code/prettify.css" />
	<script charset="utf-8" src="../kindeditor.js"></script>
	<script charset="utf-8" src="../lang/zh_CN.js"></script>
	<script charset="utf-8" src="../plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content1"]', {
				cssPath : '../plugins/code/prettify.css',
				uploadJson : '../php/upload_json.php',
				fileManagerJson : '../php/file_manager_json.php',
				allowFileManager : true,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
				}
			});
			prettyPrint();
		});
   KindEditor.ready(function(K) {
    var editor = K.editor({
    uploadJson : '../php/upload_json.php',
	fileManagerJson : '../php/file_manager_json.php',
     allowFileManager : true
    });
    K('#J_selectImage').click(function() {
     editor.loadPlugin('multiimage', function() {
      editor.plugin.multiImageDialog({
       clickFn : function(urlList) {
        var div = K('#pw');
        div.html('');
		var str='';
        K.each(urlList, function(i, data) {
         div.append('<div class="img" id="img"><img src="' + data.url + '" border="0" /></div>');
		 str+=data.url+',';
        });
		document.getElementById('geturl').value=str;
        editor.hideDialog();
       }
      });
     });
    });
   });
   KindEditor.ready(function(K) {
    var editor2 = K.editor({
    uploadJson : '../php/upload_json.php',
	fileManagerJson : '../php/file_manager_json.php',
     allowFileManager : true
    });
    K('#upimg').click(function() {
     editor2.loadPlugin('image', function() {
      editor2.plugin.imageDialog({
       clickFn : function(data) {
        var div = K('#pw1');
        div.html('<div class="img" id="img"><img src="' +data+ '" border="0" /></div>');
		document.getElementById('onlyurl').value=data;
        editor2.hideDialog();
       }
      });
     });
    });
   });
	</script>
</head>
<body>
	<?php echo $htmlData; ?>
	<form name="example" method="post" action="demo.php">
		<textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea>
		<br />
		<input type="submit" name="button" value="提交内容" /> (提交快捷键: Ctrl + Enter)
	</form>
	<form>
		<input type="text" id="geturl" style="width:300px" readonly />
		<input type="button" id="J_selectImage" value="批量上传"/>
		<div id="pw"></div>
	</form>
	<br/>
	<form>
		<input type="text" id="onlyurl" style="width:300px" readonly />
		<input type="button" id="upimg" value="单张上传"/>
		<div id="pw1"></div>
	</form>
</body>
</html>

