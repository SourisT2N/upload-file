<?php require_once PUBLIC_PATH . "admin/template/header.php";
    use App\Core\Functions;
    if($data['page'] == 'edit')
        $user = $data['user'];
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
               <form id="form">
                    
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control" name="fullname" placeholder="Full Name" value="<?php echo $user['fullname']??'';?>">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control"  name="email" placeholder="Email" value="<?php echo $user['email']??''; ?>">
                    </div>
                </div>
                <?php if($data['page'] == 'add')
                {
                    echo '
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <h6 class="mb-0" >Password</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                    ';
                }
                ?>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Permission</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <select class="form-control" name="permission">
                            <?php
                                echo Functions::renderSelectOptions(['Member','Manager','Admin'],$user['permission']??'');
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Status</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <select class="form-control" name="status">
                            <?php echo Functions::renderSelectOptions(['No Active','Active'],$user['status'] ?? '');?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Blocked</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <select class="form-control" name="blocked">
                            <?php echo Functions::renderSelectOptions(['Blocked','Active'],$user['blocked'] ?? '');?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <?php 
                            if($data['page'] == 'add')
                                echo '<input type="submit" class="btn btn-primary px-4" id="create" value="Create">';
                            else
                            {
                                $id = $user['id'];
                                echo "
                                <a href='/@Administrator/users' class='btn btn-danger px-4'>Back</a>
                                <input type='submit' class='btn btn-primary px-4' id='edit' data-id='$id'  value='Edit'>";
                            }
                        ?>
                    </div>
                </div>
               </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?php echo ASSETS_URL;?>/img/new_logo.png" alt="logo" class="rounded-circle p-1 bg-primary" width="110">
                    <div class="mt-3" id="profile">
                        <h4><?php echo $user['fullname']??'';?></h4>
                        <p class="text-secondary mb-1"><?php echo $user['email']??'';?></p>
                    </div>
                </div>
                <hr class="my-4">
            </div>
        </div>
    </div>
</div>
<script src="<?php echo ASSETS_URL;?>/js/admin/user.js"></script>
<?php require_once PUBLIC_PATH . "admin/template/footer.php";?>
