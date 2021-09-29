<?php require_once __DIR__ . '/template/header.php';
?>

<form class="login-form" method="post">
    <div class="login-body">
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" name="email" placeholder="Email">
        </div> 
    </div>
    <div class="form-group mt-2" id="captcha">
        <div class="g-recaptcha" data-sitekey="6LdQ2dYaAAAAAMM6QOGPxNdS0kSp3IjvV8R_7JAu"></div>
    </div>
    <div class="form-group btn-submit">
        <button class="btn btn-danger btn-block btn-round mt-2" id="login">Send Email</button>
    </div>
</form>
<script src="<?php echo ASSETS_URL;?>/js/app/forgot.js" type="module"></script>
<?php require_once __DIR__ . '/template/footer.php';?>