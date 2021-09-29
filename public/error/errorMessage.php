<?php
use App\Core\Functions;
require_once TEMPLATE_PATH . 'header.php';
?>
<div class="page-header section-dark" style="background-image: url('<?php echo ASSETS_URL; ?>/img/antoine-barres.jpg')">
      <div class="filter"></div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-8 col-lg-6 col-xm-12 col-xl-5 ml-auto mr-auto">
        <div class="card card-register">
            <h3 class="title mx-auto">Message</h3>
        <?php
            $className = $data['status']?'success':'error';
            Functions::verifyEmail($className,$data['message'],$data['confirm']);
        ?>
        </div>
        </div>
    </div>
</div>
<div class="footer register-footer text-center">
<h6>
</h6>
</div>
<script src="<?php echo ASSETS_URL;?>/js/app/resend.js"></script>
<?php require_once TEMPLATE_PATH . 'footer.php';?>