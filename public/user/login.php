<?php require_once __DIR__ . '/template/header.php';
?>
        <form class="login-form" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="1" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group mt-2">
                <div class="g-recaptcha" data-sitekey="6LdQ2dYaAAAAAMM6QOGPxNdS0kSp3IjvV8R_7JAu"></div>
            </div>
            <div class="form-group btn-submit">
                <button class="btn btn-danger btn-block btn-round" id="login">Login</button>
            </div>
        </form>
<script type="module" src="<?php echo ASSETS_URL?>/js/app/login.js"></script>

<?php require_once __DIR__ . '/template/footer.php';?>
           