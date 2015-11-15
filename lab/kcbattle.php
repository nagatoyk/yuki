
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<script src="file/js/jquery-1.11.3.min.js"></script>
	<script src="file/js/kcSHIPDATA.js"></script>
	<script src="file/js/kcEQDATA.js"></script>
	<script src="file/js/test2.js"></script>
	<script src="file/js/pixi.js"></script>
	<script src="file/js/steganography.js"></script>
	<script>
		$(function(){
			$('#code').val(APIsample);
			loadCode();
		});
		var previewFile = function() {
			var preview = document.querySelector('img');
			var file    = document.querySelector('input[type=file]').files[0];
			var reader  = new FileReader();
			$('#error').text('Loading');

			preview.onload = function() {
				var msg = steganography.decode(reader.result);
				document.getElementById('code').value = msg;
				loadCode();
				$('#error').text('');
			};

			reader.onloadend = function(e) {
				preview.src = reader.result;
			}
			if (file) {
				reader.readAsDataURL(file);
			} else {
				preview.src = '';
			}
		}
	</script>
</head>
<body>
	<div>
		<div>
			<div style="width:150px;float:left">

			</div>
			<div style="float:left">
				<!--<input type="button" value="&lt; Back" onclick="clickedBack()" />-->
				<input type="button" value="Pause" onclick="if(started)PAUSE=!PAUSE;" />
				<!--<input type="button" value="Skip &gt;" onclick="clickedSkip()" />-->
				<input type="button" value="Restart" onclick="if(started)reset(true)" />
			</div>
		</div>
		<div style="width:800px;height:100px;padding-top:20px;clear:both">
			<div style="float:left;width:240px">
				Detection: <span id="plDet1"></span><br><br>
				Air Battle: <span id="plAS1" style="font-weight:bold;font-size:20px"></span><br><br>
				<canvas id="plHP1" width="240px" height="10px" style="border:1px solid black;border-radius:5px"></canvas>
			</div>
			<div style="float:left;width:320px;text-align:center">
				<br><br>
				Engagement:<br>
				<span id="plEngage" style="font-size:20px"></span> <span id="plEngageT" style="font-size:18px;font-weight:bold"></span>
			</div>
			<div style="float:right;width:240px;align:right">
				<br><br>
				Air Battle: <span id="plAS2" style="font-weight:bold;font-size:20px"></span><br><br>
				<canvas id="plHP2" width="240px" height="10px" style="border:1px solid black;border-radius:5px"></canvas>
			</div>
		</div>
		<!--<input type="button" id="startb" value="Start" onClick="startPlayer()" />
		<input type="button" id="hideb" value="Show" onClick="hidePlayer()" />-->
		<div id="battlespace"></div>
		<script src="file/js/player.js"></script>
	</div>
	<div>
		<span style="font-size:12px;margin-left:750px">FPS: <span id="FPS"></span></span><br>
		<span>Load from image: </span><input type="file" onchange="previewFile()"><br>
		<img src="" height="200" alt="Image preview..."><br>
		<br>
		<span>Load from text:</span><br>
		<textarea id="code" cols="40" rows="5"></textarea>
		<br>
		<input id="codeb" type="button" value="Load" onClick="loadCode()" />
		<input type="button" value="Paste Sample Code 1" onclick="$('#code').val(APIsample);"/>
		<!-- <input type="button" value="Paste Sample Code 2" onclick="$('#code').val(APIsample2);"/> -->
		<br><span id="error" style="color:red;width:100px"></span>

	</div>
	<br/><br/>
</body>
</html>
