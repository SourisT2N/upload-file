<?php require_once TEMPLATE_PATH . "header.php"; ?>
<!-- End Navbar -->
<div class="page-header section-dark" style="background-image: url('<?php echo ASSETS_URL; ?>/img/antoine-barres.jpg')">
      <div class="filter"></div>
	<div class="content-center abs">
  	<div class="container">
    	<div class="title-brand">
        <div class="center">
          <div class="title">
             <h1>Drop file to upload</h1>
            <!-- <h1>File Not Exits</h1> -->
          </div>
          <div class="dropzone">
            <img src="<?php echo ASSETS_URL;?>/img/upload.svg" class="upload-icon" alt="upload.svg" />
            <input type="file" class="upload-input" name="file[]" id="file" />
          </div>
          <div class="btn-s btn-group">
            <span class="btn-content">0%</span>
            <div class="btn-progress"></div>
          </div>
        </div>
        
      	<div class="fog-low">
      		<img src="<?php echo ASSETS_URL; ?>/img/fog-low.png" alt="fog-low.png">
      	</div>
      	<div class="fog-low right">
        	<img src="<?php echo ASSETS_URL; ?>/img/fog-low.png" alt="fog-low.png">
      	</div>
    	</div>
  	</div>
	</div>
	<div class="moving-clouds" style="background-image: url('<?php echo ASSETS_URL; ?>/img/clouds.png'); "></div>
  <h6 class="category category-absolute">
	</h6>
</div>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/app/upload.js"></script>
<?php require_once TEMPLATE_PATH . "footer.php"; ?>