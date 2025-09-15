

<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title>Playing!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<link rel="stylesheet" href="modules/circle/src/s8CyrcleInfoBox.css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="modules/circle/css/style.css" media="screen" charset="utf-8">
	
	<script src="assets/js/jquery-3.2.1.min.js" ></script>
	<script src="assets/js/layer/layer.js" ></script>


	
	
	<style type="text/css">
	a{
		color: #000;
	}
	.cell1-3{
		float: left;
		width: 33%;
		text-align: center;
	}
	.row-full{
		float: left;
		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
		text-align: center;
		padding-bottom: 5px;
	}
	.row-full-win{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		font-size: 1.5em;

	}
	.row-full-bottom{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		border-bottom-style: solid;
		border-bottom-width: 1px;
		font-size: 1.5em;

	}
	bottom
	
	.row-full-boder-bottom{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		border-top-style: solid;
		border-color: #8a8a8a;
		border-width: 1px
		padding-right:5px;
		padding-left: 5px;
	}
	.cell1-5{
		float: left;
		width: 20%;
		line-height: 82px;
	}
	.cell3-5{
		float: left;
		width: 60%;
	}
	.content{
		padding-top: 20px;
	}
	.cell1-5-heigh{
		float: left;
		width: 20%;
		line-height:30px;
	}
	.cell1-5-heigh-content{
		float: left;
		width: 20%;
		line-height:30px;
		color: #999;
	}
	.div-1-2{
		float: left;
		width: 50%;
	}
	.div-1-4-title{
		float: left;
		width: 25%;
		text-align: right;
		color: #ccc;
	}
	.div-1-4-content{
		float: left;
		width: 25%;
		text-align: left;
		padding-left: 10px;
		color: #ccc;
	}
	.div-3-4-content{
		float: left;
		width: 75%;
		text-align: left;
		padding-left: 10px;
		color: #ccc;
	}
	.div-tag{
		float: right;
		width: 18%;
		height: 20px;
		line-height: 20px;
		border:1px solid;
		border-radius:5px;
		text-align: center;
		font-size: 0.5em;
		margin-right: 3px;
		background-color: #03c4eb;
		color:#fff;
		-moz-border-radius:5px; /* Old Firefox */
	}
	.div{
		background: #fff;
		border: 1px solid #ccc;
		box-shadow: 0 0 0 3px #eee;
		margin: 40px auto;
		width: 40%;
		min-width: 800px;
		min-height: 500px;
		border-radius: 3px;
		padding: 0 0 30px;
		position: relative;
	}
	.div h1{
		display: block;
		font-size: 1.5em;
		-webkit-margin-before: 0.83em;
		-webkit-margin-after: 0.83em;
		-webkit-margin-start: 0px;
		-webkit-margin-end: 0px;
		font-weight: bold;
		margin: 20px;
	}
	.div2{
		padding: 20px;
		background: #fafafa;
		border: 1px solid #ddd;
		border-left: 0;
		border-right: 0;
		font-size: 14px;
		line-height: 20px;
	}
	.div3{
		padding: 20px;
		background: #2b2f3b;
		font-size: 12px;
		color:#fff;
		line-height: 25px;
	}
	input[type=button]{
		background: #4081BE;
		padding: 6px 12px;
		cursor: pointer;
		border: 0;
		color: #fff;
		border-radius: 3px;
		text-transform: uppercase;
		font-weight: bold;
		font-size: 13px;
	}
	.div3 .fff{
		color:#fff;
	}
	
	input[type=button]:hover{
		background:#3772A8;
	}
	.yushe{
		background-color: #555!important;
		color:#fff!important;
	}
	.yushe .box-title{
		color:#fff!important;
	}
</style>

</head>
<!-- <a type="button" id="test1" data-gid="16" name="" value="16">aaaaaaa</a> -->
<body>
	<!-- <input type="text" value="" name="ending" id="end_chips" class="div-input-small" required="required" 
	onkeyup ="value=value.replace(/[^\d\.]/g,'');SumNum(this.value);">
	<div id="final_chips">123</div> -->
	
	<div id="full" class="content" style="max-width: 600px; text-align: center;"> 
		
		<div><!-- 座位图 -->
			

					<!--Circle 2-->
					
						
						<div class="circle2">
							<ul class="circleWrapper wStyle2">
								<li class="1">
									<div class="circleFeature fStyle2" data-cyrcleBox="cf1"><span
										class="fa fa-cloud-upload fa-2x"></span>
									</div>
									<div class="circleBox innerStyle2" id="cf1">
									</div>
								</li>
								<li class="1">
									<div class="circleFeature fStyle2" data-cyrcleBox="cf2"><span
										class="fa fa-envelope fa-2x"></span>
									</div>
									<div class="circleBox innerStyle2" id="cf2"><strong>box2</strong><br>Lorem ipsum donsectetur
										adipisicing elit. Natus, omnis.
									</div>
								</li>
								<li class="1">
									<div class="circleFeature fStyle2" data-cyrcleBox="cf3"><span
										class="fa fa-desktop fa-2x"></span>
									</div>
									<div class="circleBox innerStyle2" id="cf3"><strong>box3</strong><br>Lorem ipsum met.
										consectetur adipisicing elit. Natus, omnis.
									</div>
								</li>
								<li class="1">
									<div class="circleFeature fStyle2" data-cyrcleBox="cf4"><span class="fa fa-coffee fa-2x"></span>
									</div>
									<div class="circleBox innerStyle2" id="cf4"><strong>box4</strong><br>Lorem ipsum dolor sit amet,
										cons ectetur adipisicing elit. Natus, omnis.
									</div>
								</li >
								<li class="1">
									<div class="circleFeature fStyle2" data-cyrcleBox="cf5"><span class="fa fa-cog fa-2x"></span>
									</div>
									<div class="circleBox innerStyle2" id="cf5"><strong>box5</strong><br>Lorem lor sit amet,
										consectetur adipisicing elit. Natus, omnis.
									</div>
								</li>

							</ul>
						</div>
					
	
		</div> <!-- 座位图-end -->
		<script src="modules/circle/js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="modules/circle/src/s8CyrcleInfoBox.js" type="text/javascript"></script>
		<script>$(".circle1").s8CircleInfoBox()</script>
		<script>$(".circle2").s8CircleInfoBox({
			autoSlide: false,
			action: "click"
		})</script>
		
		
	</body>
	</html>