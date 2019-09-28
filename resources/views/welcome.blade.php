<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel='icon' href="/images/favicon.ico" mce_href='/favicon.ico' type='image/x-icon'>
	<link rel="stylesheet" href="css/fontawesome.min.css">
	<link rel="stylesheet" href="css/regular.min.css">
	<link rel="stylesheet" href="css/solid.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<title>金沙赌场-提交QQ专用资料</title>
</head>
<body>

	<div class="wrap">
		<div class="main">
			<div class="content">
				<h1>提交QQ专用资料</h1>
				<form id="survey" action="">
					<div class="item">
						<label for="userid">会员账号</label>
						<input type="text" name="userid" id="userid" value="" placeholder="必填" >
					</div>

					<div class="item">
						<label for="qq">QQ号码</label>
						<input type="text" name="qq" id="qq" value="" placeholder="必填">
					</div>
					
					<div class="item">
						<label for="creat_at">注册日期</label>
						<input type="text" name="creat_at" id="creat_at" value="" placeholder="必填">
					</div>
					
					<div class="btns">
						<button type="button" class="send_btn" onclick="sendData()">确认送出</button>
					</div>
				</form>

			</div>

		</div><!-- main END -->

	</div><!-- wrap END -->



	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript">
		function sendData(){
			var userid = document.getElementById('userid').value;
			var qq = document.getElementById('qq').value;
			var creat_at = document.getElementById('creat_at').value;
			
			var checkUserid = isNull(userid);
			var checkQq = isNull(qq);
			var checkCreat_at = isNull(creat_at);
			if(!checkUserid && !checkQq && !checkCreat_at){
				$.ajax({
					type: 'get',
					url: '/send',
					dataType: 'json',
					data: { userid , qq , creat_at },
					success: function(res){console.log(res);
						if (res.error == -1) {
							if(res.msg =='update'){
								alert("更新成功.");
							}else{
								alert("提交成功.");
							}
							location.reload();
						} else if (res.msg) {
							alert(res.msg);
							location.reload();
						} else {
							alert("发生未知的错误.");
							location.reload();
						}
					},
				});
			}else{
				var message = '未填写资料:';
				if(checkUserid){
					message = message + ' 会员账号 ';
				}
				if(checkQq){
					message = message + ' QQ号码 ';
				}
				if(checkCreat_at){
					message = message + ' 注册日期 ';
				}
				alert(message);
			}
		}
		function isNull( str ){
			if ( str == "" ) return true;
			var regu = "^[ ]+$";
			var re = new RegExp(regu);
			return re.test(str);
		}
		$(function() {
			var ajax_sent = false;
		
			{{-- Laravel - CSRF Protection --}}
			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				beforeSend: function() {
					ajax_sent = true;
				},
				error: function(jqXHR) {
					if (jqXHR.status == '419') {
						if (confirm("Session 已失效，请重新整理页面.")) {
							location.reload();
						}
					}
				},
				complete: function(jqXHR, textStatus) {
					ajax_sent = false;
				}
			});
		});
	</script>
</body>
</html>