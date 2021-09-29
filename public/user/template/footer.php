<?php if($data['title'] !== 'Reset Password'){?>
<div class="forgot">
            <a href="/user/forgot" class="btn btn-link btn-danger">Forgot password?</a>
            </div>
<?php }?>
        </div>
        </div>
    </div>
</div>
<div class="footer register-footer text-center">
    <h6>
    </h6>
</div>
</div>
<script type="module" src="<?php echo ASSETS_URL?>/js/app/validation.js"></script>
<?php require_once TEMPLATE_PATH . 'footer.php';?>