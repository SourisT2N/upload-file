<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/plugins/moment.min.js"></script>
<script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/plugins/bootstrap-datetimepicker.js"></script>
<div class="content-wrapper">
<table id="example" class="display" style="width:100%;text-align:center;">
    <thead>
        <tr>
        </tr>
    </thead>
</table>
<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Notification</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <h4>Do you want to delete?</h4>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-danger" id="delete-accept">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>