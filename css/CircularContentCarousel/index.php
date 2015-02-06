<!DOCTYPE html>
<html lang="en">
    <head>
        
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.css" media="all" />
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow&v1' rel='stylesheet' type='text/css' />
    </head>
    <body>
		<div class="container">
			
			<div id="ca-container" class="ca-container">
				<div class="ca-wrapper">
					<?php for($i=1; $i<=100; $i++) {?>
					<div class="ca-item ca-item-1">
						<div class="ca-item-main">
							<div class="ca-icon"><img src="images/1.jpg"></div>
							<h5>Stop factory farming <?=$i; ?></h5>
							<h4><span>The greatness of a nation and its moral progress can be judged by the way in which its animals are treated.</span>
							</h4>
						</div>

					</div>
					<?php }?>

			</div>
		
		</div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
		<!-- the jScrollPane script -->
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="js/jquery.contentcarousel.js"></script>
		<script type="text/javascript">
			$('#ca-container').contentcarousel();
		    
 
		</script>
    </body>
</html>
