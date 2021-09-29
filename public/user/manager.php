<?php
require_once TEMPLATE_PATH . 'header.php';?>
  <!-- End Navbar -->
  <div class="page-header page-header-xs" data-parallax="true" style="background-image: url('<?php echo ASSETS_URL; ?>/img/fabio-mangione.jpg');">
    <div class="filter"></div>
  </div>
  <div class="section profile-content">
    <div class="container">
      <div class="owner">
        <div class="avatar">
          <img src="<?php echo ASSETS_URL; ?>/img/new_logo.png" alt="Circle Image" class="img-circle img-no-padding img-responsive">
        </div>
        <div class="name">
          <h4 class="title"><?php echo $_SESSION['user']['fullname']?>
            <br />
          </h4>
          <h5 class="description">ID: <?php echo str_shuffle(rand().$_SESSION['user']['id']);?></h5>
        </div>
      </div>
      <div class="text-center">
        <div class="col-lg-5 mx-auto">
          <span>Email: <?php echo $_SESSION['user']['email']?></span>
        </div>
        <div class="col-lg-5 mx-auto">
          <span>Password: *********</span>
          <span class="text-danger"  data-toggle="modal" data-target="#exampleModalCenter" id="c-password">change</span>
        </div>
        <div class="col-lg-5 mx-auto d-flex" style="align-items: center;">
          <span style="flex-basis: 20%;">API KEY: </span>
          <span id="api_key" style="flex-basis: 70%;overflow-x:hidden;"><?php echo $_SESSION['user']['api_key']?></span>
          <i class="far fa-copy icon-copy" style="flex-basis: 10%;"></i>
        </div>
      </div>
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="padding-right: 0 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%" id="form-change">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            </div>
            <div class="modal-body" id="text-modal">
              
            </div>
            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="6LdQ2dYaAAAAAMM6QOGPxNdS0kSp3IjvV8R_7JAu"></div>
            </div>
            <div class="modal-footer" style="padding: 10px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" data-id="">Save changes</button>
            </div>
          </div>
        </form>
        </div>
      </div>
      <br/>
      <div class="nav-tabs-navigation">
        <div class="nav-tabs-wrapper">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#table-files" role="tab">Files</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#vip" role="tab">Donate for me</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Tab panes -->
      <div class="tab-content following">
        <div class="tab-pane active" id="table-files" role="tabpanel">
        <table id="example" class="display" style="width:100%;text-align:center;">
        <thead>
            <tr>
                <th>Name File</th>
                <th>Extension</th>
                <th>Date Upload</th>
                <th>Date Expires</th>
                <th>Storages</th>
                <th>Control</th>
            </tr>
        </thead>
    </table>
        </div>
        <div class="tab-pane text-center" id="vip" role="tabpanel">
          <h3 class="text-muted">Donate</h3>
          <div class="row">
            <div class="card text-center col-sm-12 col-lg-12">
              <div class="card-header">Donate Via MOMO</div>
              <div class="card-body">
                <h5 class="card-title">Details</h5>
                <p class="card-text">
                  Donate me to have money to maintain the website.
                </p>
                <a href="#" class="btn btn-primary donate" id="momo">Donate</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<div id="message-box">
  <?php 
    if(isset($_SESSION['payment']))
    {
      $class = $_SESSION['payment']['status']?'success':'danger';
      $message = $_SESSION['payment']['message'];
      echo "<div class='alert alert-$class text-center' role='alert'>
        $message
      </div>";
      unset($_SESSION['payment']);
      echo "<script>setTimeout(() => $('#message-box').html(''),2000)</script>";
    }
  ?>
</div>
  <footer class="footer    ">
    <div class="container">
      <div class="credits text-right">
        <span class="copyright">
          
        </span>
      </div>
    </div>
  </footer>
  <div class="modal fade" id="dialog1" role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title text-warning">Warning</h5>
            </div>
            
            <div class="modal-body" id="modal-delete">
            </div>
            
            <div class="modal-footer" style="padding:10px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteFile" data-id>Delete</button>
            </div>
        </div>
    </div>
</div>
<?php require_once TEMPLATE_PATH . 'footer.php';?>
