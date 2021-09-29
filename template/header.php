<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo ASSETS_URL; ?>/img//apple-icon.png">
  <link rel="shortcut icon" href="<?php echo ASSETS_URL; ?>/img//favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="description" content="Upload File">
  <title>
    Upload Files
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--     Fonts and icons     -->
  <link type="text/css" href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/all.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/paper-kit.css?v=2.2.0" rel="stylesheet" />
  <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/style.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
  <script src="<?php echo ASSETS_URL; ?>/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo ASSETS_URL; ?>/js/core/popper.min.js" type="text/javascript"></script>
  <script src="<?php echo ASSETS_URL; ?>/js/core/bootstrap.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/app/function.js"></script>
  <!-- Control Center for Paper Kit: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo ASSETS_URL; ?>/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <!--Start of Tawk.to Script-->
  <script>
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function tawk(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/60a1f93cb1d5182476b975b7/1f5sbb590';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
    })();
  </script>
  
<!--End of Tawk.to Script-->
  <!-- CSS Just for demo purpose, don't include it in your project -->
</head>

<body class="index-page sidebar-collapse">
  <!-- Navbar -->
  	<nav class="navbar navbar-expand-lg fixed-top navbar-transparent " color-on-scroll="300">
    	<div class="container">
	      	<div class="navbar-translate">
	        	<a class="navbar-brand" href="/" rel="tooltip" data-placement="bottom">
	           Upload Files
	        	</a>
	        	<button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
	          		<span class="navbar-toggler-bar bar1"></span>
	          		<span class="navbar-toggler-bar bar2"></span>
	          		<span class="navbar-toggler-bar bar3"></span>
	        	</button>
	      	</div>
	      	<?php require_once TEMPLATE_PATH . "navbar.php"; ?>
    	</div>
  	</nav>