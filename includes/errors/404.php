<?php
require_once 'init.php';
use app\Router;
use app\Layouts;

?>
<?php include_once  Layouts::includes('layouts.head') ?>

<body class="hold-transition">
	<div class="error-body">
      <div class="error-page">

        <div class="error-content">
         	<div class="container">
         	
        		<h2 class="headline text-primary"> 404</h2>
        		
			    <h3 class="margin-top-0"><i class="fa fa-exclamation-triangle text-primary"></i> PAGE NOT FOUND !</h3>

                <p>
                    YOU SEEM TO BE TRYING TO FIND HIS WAY HOME
                </p>

				<div class="text-center">
				  <a href="<?= Router::route('index') ?>" class="btn btn-primary btn-block margin-top-10">Back to Home</a>
				</div>
          </div>
        </div>
        <!-- /.error-content -->
        <footer class="main-footer">
        	Copyright &copy; 2017.
		</footer>
 
      </div>
      <!-- /.error-page -->
     </div> 
  



	<!-- jQuery 3 -->
	<script src="../../../assets/vendor_components/jquery/dist/jquery.min.js"></script>
	
	<!-- popper -->
	<script src="../../../assets/vendor_components/popper/dist/popper.min.js"></script>
	
	<!-- Bootstrap 4.0-->
	<script src="../../../assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>


</body>
</html>
