<?php require_once PUBLIC_PATH . "admin/template/header.php";?>
<?php require_once PUBLIC_PATH . "admin/template/tables.php";?>
<div class="modal fade" id="form-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Donate Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Id:</label>
            <input type="text" class="form-control" name="id">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Transaction Id:</label>
            <input type="text" class="form-control" name="transaction_id">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Status:</label>
            <select class="form-control" name="status">
                <option value="0">Cancel</option>
                <option value="1">Success</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Amount:</label>
            <input type="text" class="form-control" name="amount">
            <span id="amount">0 VND</span>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Type:</label>
            <input type="text" class="form-control" name="type">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Email:</label>
            <input type="text" class="form-control" name="email">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Date Donate:</label>
            <input type="text" name="date" class="form-control" name="email" id="datepicker">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="add-btn">Add</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo ASSETS_URL?>/js/admin/tables/tables.js"></script>
<script src="<?php echo ASSETS_URL?>/js/admin/tables/donates.js"></script>
<?php require_once PUBLIC_PATH . "admin/template/footer.php";?>