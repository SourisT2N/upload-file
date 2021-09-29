<?php require_once TEMPLATE_PATH . '/admin_header.php'; 
?>

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                <img src="../../images/logo.svg" alt="logo">
                </div>
                <?php
                    if(!empty($data['message']))
                    {
                        $message = $data['message'];
                        echo "
                        <script id='exam'>
                            toastr.error('$message');
                            setTimeout(() => $('#exam').remove(),3000);
                        </script>
                        ";
                    }
                ?>
                <h4>Hello! let's get started</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>
                <form class="pt-3" id="login-form">
                <div class="form-group">
                    <input type="email" class="form-control form-control-lg" name="email" id="exampleInputEmail1" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="password" id="exampleInputPassword1" autocomplete placeholder="Password">
                </div>
                <div class="form-group mt-2">
                    <div class="g-recaptcha" data-sitekey="6LdQ2dYaAAAAAMM6QOGPxNdS0kSp3IjvV8R_7JAu"></div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="<?php echo ASSETS_URL;?>/js/admin/login.js"></script>
<?php require_once TEMPLATE_PATH . '/admin_footer.php'; ?>