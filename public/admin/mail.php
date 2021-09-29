<?php require_once PUBLIC_PATH . "admin/template/header.php";?>
<div class="card">
        <div class="card-body row">
          <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
              <h2>Admin <strong>Upload File</strong></h2>
            </div>
          </div>
            <div class="col-7">
            <form action="/" method="post">
                <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" name="name" id="inputName" class="form-control">
                </div>
                <div class="form-group">
                <label for="inputEmail">E-Mail</label>
                <input type="email" name="email" id="inputEmail" class="form-control">
                </div>
                <div class="form-group">
                <label for="inputSubject">Subject</label>
                <input type="text" name="subject" id="inputSubject" class="form-control">
                </div>
                <div class="form-group">
                <label for="inputMessage">Message</label>
                <textarea id="inputMessage" name="body" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        Send Message
                    </button>
                </div>
            </form>
            </div>
        </div>
      </div>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script src="<?php echo ASSETS_URL;?>/js/admin/mail.js">
</script>
<?php require_once PUBLIC_PATH . "admin/template/footer.php";?>