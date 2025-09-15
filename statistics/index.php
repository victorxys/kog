<?php

  $name = $id_num = $tmp = $tmp_2 = $chest = $cough = "";
  if (isset($_POST['sub_type'])) {
    setcookie("user_name",$_POST['name'],time()+1864000);
    setcookie("user_id_num",$_POST['id_num'],time()+1864000);

  }
  if ($_REQUEST['debug']) {
    echo "<pre>";
    echo $_REQUEST['user_name'];
  }
  
  $user_name = $_COOKIE['user_name'];
  $id_num = $_COOKIE['user_id_num'];
  $tmp = $_POST['tmp'];
  $tmp_2 = $_POST['tmp_2'];
  
 
require_once('functions.php'); 
if (isset($id_num) && !empty($id_num)) {
    $today_info = get_today_info_id_num($id_num);
    if (!empty($today_info)) {
      $tmp = $today_info->tmp;
      $tmp_2 = $today_info->tmp_2;
    }
    
}
$err_name ="";
$err_id_num = "";
$err_tmp = "";
if (isset($_REQUEST['c_id'])) {
  $c_id = $_REQUEST['c_id'];
  $c_name = get_c_name($c_id);
}else{
  die("缺少社区参数");
}

if (isset($_POST['sub_type'])) {
  $user_name = $_POST['name'];
  $id_num = $_POST['id_num'];
  $tmp = $_POST['tmp'];
  // $chest = $_POST['chest'];
  // $cough = $_POST['cough'];

  if (empty($_POST['name'])) {
    $err_name = "请填写姓名";
  }
  elseif (empty($_POST['id_num'])) {
    $err_id_num = "请填写身份证号";
  }
  elseif (empty($_POST['tmp']) && empty($_POST['tmp_2'])) {
    $err_tmp = "至少填写一个体温";
  }
  else{
    insert_stat_info($_POST);  
  }
  
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://www.jq22.com/jquery/bootstrap-4.2.1.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="css/orionicons.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.png?3">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="d-flex align-items-stretch">
      
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <section class="py-5">
            <div class="row">
              <!-- Basic Form-->
              <div class="col-lg-12 mb-5">
                <div class="card">
                  <div class="card-header">
                    <h3 class="h6 text-uppercase mb-0">人员隔离信息表</h3>
                  </div>
                  <div class="card-body">
                    <p>所属社区：<?php _e($c_name)?></p>
                    <form method="post">
                      <div class="form-group">
                        <label class="form-control-label text-uppercase">姓名<font color="red">*<?php _e($err_name)?></font></label>
                        <input type="text" name="name"  class="form-control" value="<?php _e($user_name)?>">
                      </div>
                      <div class="form-group">       
                        <label class="form-control-label text-uppercase">身份证号<font color="red">*<?php _e($err_id_num)?></font></label>
                        <input type="text" name="id_num" placeholder="" class="form-control" value="<?php _e($id_num)?>">
                      </div>
                      <!-- <div class="form-group">       
                        <label class="form-control-label text-uppercase">今日体温 &#8451<font color="red">*<?php _e($err_tmp)?></font></label>
                        <input type="text" name="tmp" placeholder="" class="form-control" value="<?php _e($tmp)?>">
                      </div>
                      <div class="form-group">       
                        <label class="form-control-label text-uppercase">下午体温 &#8451<font color="red">*<?php _e($err_tmp_2)?></font></label>
                        <input type="text" name="tmp_2" placeholder="" class="form-control" value="<?php _e($tmp_2)?>">
                      </div> -->
                      <div class="form-group row">
                        <div class="col-md-12">
                          <label class="form-control-label text-uppercase">今日体温 &#8451<font color="red">*<?php _e($err_tmp)?></font></label>
                          <div class="form-group">
                            <div class="input-group mb-3">
                              <div class="input-group-prepend"><span class="input-group-text">上午</span></div>
                               <input type="text" name="tmp" placeholder="" class="form-control" value="<?php _e($tmp)?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="input-group mb-3">
                              <div class="input-group-prepend"><span class="input-group-text">下午</span></div>
                              <input type="text" name="tmp_2" placeholder="" class="form-control" value="<?php _e($tmp_2)?>">
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- <div class="form-group">  
                        <label class="form-control-label text-uppercase">是否胸闷</label>
                        <select  name="chest" class="form-control">
                            <option>否</option>
                            <option>是</option>
                          </select>
                      </div>
                      <div class="form-group">  
                        <label class="form-control-label text-uppercase">是否咳嗽</label>
                        <select  name="cough" class="form-control">
                            <option>否</option>
                            <option>是</option>
                          </select>
                      </div> -->
                      <input type="hidden" name="c_id" value="<?php _e($c_id)?>">
                      <div class="form-group">       
                        <button type="submit" name="sub_type" value ="sub" class="btn btn-primary">提交表格</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              
            </div>
          </section>
        </div>
        <footer class="footer bg-white shadow align-self-end py-3 px-xl-5 w-100">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 text-center text-md-left text-primary">
                <p class="mb-2 mb-md-0">永定路街道 &copy; 2019-2020</p>
              </div>
              
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="https://www.jq22.com/jquery/jquery-1.10.2.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="https://www.jq22.com/jquery/bootstrap-4.2.1.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="https://cdn.bootcss.com/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="js/js.cookie.min.js"></script>
    <script src="js/front.js"></script>
  </body>
</html>