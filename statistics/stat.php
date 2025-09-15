<?php
require_once('functions.php'); 
if (isset($_REQUEST['c_id'])) {
  $c_id = $_REQUEST['c_id'];
  $c_name = get_c_name($c_id);
  $c_stat_list = get_stat_list($c_id);
}else{
  die("缺少社区参数");
}
if (isset($_REQUEST['creatdate'])) {
  $creatdate = $_REQUEST['creatdate'];
  $show_info = "";
}else{
  $show_info = "none";
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
    <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-lg px-4 py-2 bg-white shadow">
        <a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead">
          <i class="fas fa-align-left"></i></a>
          <a href="#" class="navbar-brand font-weight-bold text-uppercase text-base">永定路街道 - 社区登记表</a>
        
      </nav>
    </header>
    <div class="d-flex align-items-stretch">
      <div id="sidebar" class="sidebar py-3">
        <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
        <ul class="sidebar-menu list-unstyled">
              <li class="sidebar-list-item"><a href="index.php?c_id=<?php _e($c_id)?>" class="sidebar-link text-muted"><i class="o-survey-1 mr-3 text-gray"></i><span>人员登记表</span></a></li>
              <li class="sidebar-list-item"><a href="stat.php?c_id=<?php _e($c_id)?>" class="sidebar-link text-muted active"><i class="o-table-content-1 mr-3 text-gray"></i><span>社区统计表</span></a></li>
             
         
             
        </ul>
        
      </div>
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <section class="py-5">
            <div class="row">
              <div class="col-lg-12 mb-5">
                <div class="card">
                  <!-- 社区每日统计列表 -->
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0"><?php _e($c_name)?> 登记表</h6>
                  </div>
                  <div class="card-body">                          
                    <table class="table table-striped table-sm card-text">
                      <thead>
                        <tr>
                          <th>日期</th>
                          <th>已登记人数</th>
                          <th>未登记人数</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach($c_stat_list as $key => $value):?>
                        <tr>
                          <td><a href="stat_info.php?c_id=<?php _e($c_id)?>&creatdate=<?php _e($value->creatdate)?>"><?php _e($value->creatdate)?></a></td>
                          <td><?php _e($value->count_people)?></td>
                          <td><?php
                          if ($value->today_all_people - $value->count_people == 0) {
                            _e($value->today_all_people - $value->count_people);
                          }else
                          {
                           echo "<font color='red'>".($value->today_all_people - $value->count_people)."</font>";
                          }
                          ?>
                          </td>
                        </tr>
                      <?php endforeach?>
                      </tbody>
                    </table>
                  </div>
                  <!-- 社区每日统计列表 -->
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