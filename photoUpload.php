<?php

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Take Photo and just now upload to server</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <!-- this js import already in bottom -->
  <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script -->

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="webcam/webcam.js"></script>
  <!-- CSS -->
<style>
#my_camera{
 width: 320px;
 height: 240px;
 border: 1px solid black;
}
.imgLists{
	width: 100px;
	height: auto;
	margin: 5px;
}
progress[value]::-webkit-progress-bar {
  background-color: #eee;
  border-radius: 2px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25) inset;
}
</style>
</head>

<body>
<div class="container-fluid" style="margin-top: 80px">
	<div class="row pt-5 pb-2 pl-2 pr-2">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 pb-3">
			<h2><b>1 - Take Snapshot</b></h2>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div id="my_camera"></div>
			<input type=button class="btn btn-outline-primary btn-lg mt-2" value="Take Snapshot" onClick="take_snapshot()">
			<input type=button class="btn btn-outline-danger btn-lg mt-2" value="Clear List" onClick="clearList()">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div id="results" ></div>
		</div>
	</div>
</div>
<div class="container-fluid pt-2">
	<div class="row pt-5 pb-5 pl-2 pr-2">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 pb-3">
			<h2><b>2 - Upload</b></h2>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 pt-1">
			<input type=button class="btn btn-outline-success btn-lg mt-2" value="Upload" onClick="uploadToserver()">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<p id="progMsg">Progress</p>
			<div>
			<progress class="" id="progressBar" value="0" max="100" style="width:60%;height: 30px;box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25) inset"></progress>
			</div>

		</div>
	</div>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
 Webcam.set({
  width: 320,
  height: 240,
  image_format: 'jpeg',
  jpeg_quality: 90
 });
 Webcam.attach( '#my_camera' );
 var snapaRRAY=[];
 var imgc=0;

<!-- Code to handle taking the snapshot and displaying it locally -->
function take_snapshot() {
	document.getElementById("progressBar").value=0;
	document.getElementById("progMsg").style="color:black;";
	document.getElementById("progMsg").innerHTML="Progress.";
 // take snapshot and get image data
 Webcam.snap( function(data_uri) {
 	snapaRRAY.push(data_uri);
  // display results in page
  loadImgList();
  
  } );
}
function loadImgList(){
	document.getElementById('results').innerHTML=null;
	
	for (var i = snapaRRAY.length - 1; i >= 0; i--) {
		document.getElementById('results').innerHTML +='<img id="imageprev'+imgc+'" class="imgLists" src="'+snapaRRAY[i]+'"/>';
		imgc++;
	}
}
function clearList() {
	document.getElementById('results').innerHTML=null;
	//Webcam.reset();
	//Webcam.ShowCam();;
	snapaRRAY.length = 0;
}
function uploadToserver() {
	var imgcount = snapaRRAY.length;
	
	for (var i=0;i<imgcount; i++) {
		Webcam.upload(snapaRRAY[i], 'webcam/upload.php', function(code, text) {
			console.log('Save successfully');
			console.log(text);
		});
		//snapaRRAY[i].remove();
	}
	//Webcam.reset();
	//Webcam.ShowCam();
	
	if (snapaRRAY.length ==0) {
		document.getElementById("progMsg").style="color:red;";
		document.getElementById("progMsg").innerHTML="Please Take Photo first.";
	}
	else{
		snapaRRAY.length = 0;
		progressMove();
	}
	document.getElementById('results').innerHTML=null;
}
function progressMove() {
  document.getElementById("progMsg").style="color:red;";
  document.getElementById("progMsg").innerHTML="Processing.....";
  var elem = document.getElementById("progressBar");   
  var width = 0;
  var id = setInterval(frame, 10);
  function frame() {
    if (width == 100) {
      clearInterval(id);
      document.getElementById("progMsg").style="color:green;";
      document.getElementById("progMsg").innerHTML="Completed.";
    } else {
      width++; 
      elem.value = width; 
    }
  }
}

window.onload= ShowCam();
</script>

</body>
</html>
