<div class="collapse navbar-collapse justify-content-end" id="navigation">
	<ul class="navbar-nav">
		<?php if(!isset($_SESSION['user'])){?>
		<li class="nav-item">
			<a class="nav-link" rel="tooltip"  data-placement="bottom" href="/user/login">
	  			<p>Login</p>
			</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" rel="tooltip" data-placement="bottom" href="/user/register">
	  			<p>Register</p>
	    	</a>
	  	</li>
		  <?php }else{?>
	<!-- Logged -->
		<li class="nav-item d-lg-flex align-items-center info">
		  	<img src="<?php echo ASSETS_URL; ?>/img/new_logo.png" alt="new_logo.png" class="rounded-circle img-icon">
		  	<p class="nav-link"><?php echo $_SESSION['user']['fullname']?></p>
		</li>
		<li class="nav-item menu-box d-lg-block">
		  	<a href="/user/manager" class="nav-link">Managers</a>
			<?php if($_SESSION['user']['permission'] != 0)
				echo '<a href="/@Administrator" class="nav-link">Admin</a>';
			?>
				
		  	<a href="/user/logout" class="nav-link">Logout</a>
		</li>
		<?php }?>
	</ul>
</div>