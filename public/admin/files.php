<?php require_once PUBLIC_PATH . "admin/template/header.php";?>

<?php require_once PUBLIC_PATH . "admin/template/tables.php";?>
<div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="file-upload">
                <div class="image-upload-wrap">
                </div>
                <div class="file-upload-content">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
<script src="<?php echo ASSETS_URL?>/js/admin/tables/tables.js"></script>
<script src="<?php echo ASSETS_URL?>/js/admin/tables/files.js"></script>
<?php require_once PUBLIC_PATH . "admin/template/footer.php";?>