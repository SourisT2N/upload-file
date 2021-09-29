<?php require_once PUBLIC_PATH . 'admin/template/header.php';?>
<div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome <?php echo $_SESSION['user']['fullname'];?></h3>
            </div>
            <div class="col-12 col-xl-4">
              <div class="justify-content-end d-flex">
              <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <span class="btn btn-sm btn-light bg-white">
                  Today (<?php echo date('d M Y');?>)
                </span>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card tale-bg">
            <div class="card-people mt-auto">
              <img src="<?php echo ASSETS_URL?>/img/people.svg" alt="people">
              <div class="weather-info">
                <div class="d-flex">
                  <div>
                    <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i><span id="weather-deg"></span><sup>C</sup></h2>
                  </div>
                  <div class="ml-2">
                    <h4 class="location font-weight-normal"></h4>
                    <h6 class="font-weight-normal"></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Users</p>
                  <p class="fs-30 mb-2" id="users">0</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Files</p>
                  <p class="fs-30 mb-2" id="files">0</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Donates</p>
                  <p class="fs-30 mb-2" id="donates">0</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Amount</p>
                  <p class="fs-30 mb-2" id="amounts">0 VND</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5 stretch-card grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Pie chart</h4>
              <canvas id="pieChart"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-7 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <p class="card-title mb-0">New Files</p>
              <div class="table-responsive">
                <table class="table table-striped table-borderless">
                  <thead>
                    <tr>
                      <th>Name File</th>
                      <th>Storages</th>
                      <th>Date Upload</th>
                      <th>User Upload</th>
                    </tr>  
                  </thead>
                  <tbody>
                  <?php 
                  if(!empty($data['files']))
                    foreach($data['files'] as $val)
                    {
                      $name = $val['_nameFile'];
                      $storages = $val['storages'];
                      $date_upload = $val['date_upload'];
                      $nameUser = $val['fullname']?:"No User";
                      echo "
                      <tr>
                        <td>${name}</td>
                        <td class='font-weight-bold'>${storages}</td>
                        <td>${date_upload}</td>
                        <td class='font-weight-medium'>${nameUser}</td>
                      </tr>";
                    }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <p class="card-title mb-0">New Users</p>
              <div class="table-responsive">
                <table class="table table-striped table-borderless">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Date Created</th>
                      <th>Status</th>
                    </tr>  
                  </thead>
                  <tbody>
                  <?php
                    if(!empty($data['users']))
                      foreach($data['users'] as $val)
                      {
                        extract($val);
                        $class = $status?"success":"danger";
                        $nameStatus = $status?"Active":"No Active";
                        echo "
                        <tr>
                          <td>$fullname</td>
                          <td class='font-weight-bold'>$email</td>
                          <td>$date_created</td>
                          <td class='font-weight-medium'><div class='badge badge-$class'>$nameStatus</div></td>
                        </tr>";
                      }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5 stretch-card grid-margin">
          <div class="card">
            <div class="card-body">
              <p class="card-title mb-0">New Donates</p>
              <div class="table-responsive">
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th class="pl-0  pb-2 border-bottom">Name User</th>
                      <th class="border-bottom pb-2">Amount</th>
                      <th class="border-bottom pb-2">Date Donate</th>
                      <th class="border-bottom pb-2">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                    if(!empty($data['donates']))
                      foreach($data['donates'] as $val)
                      {
                        extract($val);
                        $amount = number_format($amount);
                        $class  = $status?"success":"danger";
                        $nameStatus = $status?"Success":"Cancel";
                        echo "
                        <tr>
                          <td class='pl-0'>$fullname</td>
                          <td><p class='mb-0'><span class='font-weight-bold mr-2'>$amount</span>VND</p></td>
                          <td class='text-muted'>$date_donate</td>
                          <td class='font-weight-medium'><div class='badge badge-$class'>$nameStatus</div></td>
                        </tr>";
                      }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- partial:partials/_footer.html -->
    <script src="<?php echo ASSETS_URL;?>/js/admin/js/Chart.min.js"></script>
    <script src="<?php echo ASSETS_URL;?>/js/admin/chart.js"></script>
      <!-- main-panel ends -->
<?php require_once PUBLIC_PATH . 'admin/template/footer.php';?>