<?php

require_once TEMPLATE_PATH . 'header.php';
?>
<div class="page-header section-dark" style="background-image: url('<?php echo ASSETS_URL; ?>/img/antoine-barres.jpg')">
      <div class="filter"></div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-8 col-lg-6 col-xm-12 col-xl-5 ml-auto mr-auto">
        <div class="card card-register">
            <h3 class="title mx-auto"><?php echo $data['title'];?></h3>
            <?php if($data['title'] !== 'Reset Password'){?>
            <div class="social-line text-center">
            <a href="<?php echo $data['urlFb'];?>" class="btn btn-neutral btn-facebook btn-just-icon">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="<?php echo $data['urlGG'];?>" class="btn btn-neutral btn-google btn-just-icon" target="_parent">
                <i class="fab fa-google"></i>
            </a>
            </div>
            <?php }?>
        <p class='form-notify'></p>