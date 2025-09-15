<?php
require_once('functions.php'); 
require_once "kog_header.php";
?>
<!-- =========================================================
* Argon Dashboard PRO v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro
* Copyright 2019 Creative Tim (https://www.creative-tim.com)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 -->
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Argon Dashboard PRO - Premium Bootstrap 4 Admin Template</title>
  <!-- Favicon -->
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
</head>

<body>
  
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row justify-content-center">
        <div class="col-lg-8 card-wrapper ct-example">
          <!-- Styles -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Styles</h3>
            </div>
            <div class="card-body">
              <button class="btn btn-primary" type="button">Button</button>
              <button class="btn btn-icon btn-primary" type="button">
                <span class="btn-inner--icon"><i class="ni ni-bag-17"></i></span>
                <span class="btn-inner--text">With icon</span>
              </button>
              <button class="btn btn-icon btn-primary" type="button">
                <span class="btn-inner--icon"><i class="ni ni-atom"></i></span>
              </button>
            </div>
          </div>
          <!-- Colors -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Colors</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-default">Default</button>
              <button type="button" class="btn btn-primary">Primary</button>
              <button type="button" class="btn btn-secondary">Secondary</button>
              <button type="button" class="btn btn-info">Info</button>
              <button type="button" class="btn btn-success">Success</button>
              <button type="button" class="btn btn-danger">Danger</button>
              <button type="button" class="btn btn-warning">Warning</button>
            </div>
          </div>
          <!-- Outline -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Outline</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-outline-default">Default</button>
              <button type="button" class="btn btn-outline-primary">Primary</button>
              <button type="button" class="btn btn-outline-secondary">Secondary</button>
              <button type="button" class="btn btn-outline-info">Info</button>
              <button type="button" class="btn btn-outline-success">Success</button>
              <button type="button" class="btn btn-outline-danger">Danger</button>
              <button type="button" class="btn btn-outline-warning">Warning</button>
            </div>
          </div>
          <!-- Sizes -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Sizes</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-primary btn-lg">Large button</button>
              <button type="button" class="btn btn-secondary btn-lg">Large button</button>
              <hr />
              <button type="button" class="btn btn-primary btn-sm">Small button</button>
              <button type="button" class="btn btn-secondary btn-sm">Small button</button>
              <hr />
              <button type="button" class="btn btn-primary btn-lg btn-block">Block level button</button>
              <button type="button" class="btn btn-secondary btn-lg btn-block">Block level button</button>
            </div>
          </div>
          <!-- Group -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Group</h3>
            </div>
            <div class="card-body">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary">Left</button>
                <button type="button" class="btn btn-secondary active">Middle</button>
                <button type="button" class="btn btn-secondary">Right</button>
              </div>
              <hr />
              <div class="btn-group">
                <button type="button" class="btn btn-info active">1</button>
                <button type="button" class="btn btn-info">2</button>
                <button type="button" class="btn btn-info">3</button>
                <button type="button" class="btn btn-info">4</button>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-default">5</button>
                <button type="button" class="btn btn-default">6</button>
                <button type="button" class="btn btn-default">7</button>
              </div>
            </div>
          </div>
          <!-- Social -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Social</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-facebook btn-icon my-2">
                <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
                <span class="btn-inner--text">Facebook</span>
              </button>
              <button type="button" class="btn btn-twitter btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-twitter"></i></span>
                <span class="btn-inner--text">Twitter</span>
              </button>
              <button type="button" class="btn btn-google-plus btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-google-plus-g"></i></span>
                <span class="btn-inner--text">Google +</span>
              </button>
              <button type="button" class="btn btn-instagram btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
                <span class="btn-inner--text">Instagram</span>
              </button>
              <button type="button" class="btn btn-pinterest btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-pinterest"></i></span>
                <span class="btn-inner--text">Pinterest</span>
              </button>
              <button type="button" class="btn btn-youtube btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-youtube"></i></span>
                <span class="btn-inner--text">Youtube</span>
              </button>
              <button type="button" class="btn btn-vimeo btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-vimeo-v"></i></span>
                <span class="btn-inner--text">Vimeo</span>
              </button>
              <button type="button" class="btn btn-slack btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-slack"></i></span>
                <span class="btn-inner--text">Slack</span>
              </button>
              <button type="button" class="btn btn-dribbble btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-dribbble"></i></span>
                <span class="btn-inner--text">Dribbble</span>
              </button>
              <hr />
              <button type="button" class="btn btn-facebook btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
              </button>
              <button type="button" class="btn btn-twitter btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-twitter"></i></span>
              </button>
              <button type="button" class="btn btn-google-plus btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-google-plus-g"></i></span>
              </button>
              <button type="button" class="btn btn-instagram btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
              </button>
              <button type="button" class="btn btn-pinterest btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-pinterest"></i></span>
              </button>
              <button type="button" class="btn btn-youtube btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-youtube"></i></span>
              </button>
              <button type="button" class="btn btn-vimeo btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-vimeo-v"></i></span>
              </button>
              <button type="button" class="btn btn-slack btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-slack"></i></span>
              </button>
              <button type="button" class="btn btn-dribbble btn-icon-only">
                <span class="btn-inner--icon"><i class="fab fa-dribbble"></i></span>
              </button>
              <hr />
              <button type="button" class="btn btn-facebook btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
              </button>
              <button type="button" class="btn btn-twitter btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-twitter"></i></span>
              </button>
              <button type="button" class="btn btn-google-plus btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-google-plus-g"></i></span>
              </button>
              <button type="button" class="btn btn-instagram btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
              </button>
              <button type="button" class="btn btn-pinterest btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-pinterest"></i></span>
              </button>
              <button type="button" class="btn btn-youtube btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-youtube"></i></span>
              </button>
              <button type="button" class="btn btn-vimeo btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-vimeo-v"></i></span>
              </button>
              <button type="button" class="btn btn-slack btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-slack"></i></span>
              </button>
              <button type="button" class="btn btn-dribbble btn-icon-only rounded-circle">
                <span class="btn-inner--icon"><i class="fab fa-dribbble"></i></span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; 2019 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="assets/js/argon.js?v=1.1.0"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="assets/js/demo.min.js"></script>
</body>

</html>