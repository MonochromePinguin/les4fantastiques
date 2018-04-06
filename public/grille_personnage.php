<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="david.css">
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:400,500,600" rel="stylesheet">
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-xs12 col-sm-12">
				<h1 class="text-center"> Player #1 </h1>
			</div>
		</div>

		<div class="row">


<?php

require_once '../vendor/autoload.php';
require_once '../src/heroClient.php';
$client=new heroClient ();

$all = $client->get('all.json');

//var_dump($all);


for ( $i = 0; $i < 12; ++$i) {
	$hero = $all[rand (1,45)];
 ?>


		    <div class="col-xs-2 col-sm-2">
		    	<div class="thumbnail" data-toggle="modal" data-target="#exampleModalLong<?php echo $hero->id; ?>">
		    		<img class="image" src="<?= $hero->images->sm ?>" >
					<div class="modal fade" id="exampleModalLong<?php echo $hero->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $hero->name; ?></h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">

								<img class="image" src="<?= $hero->images->sm ?>" >
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

					      </div>
					    </div>
					  </div>
					</div>

		    		<div class="caption text-center">
		    			<h3><?php echo $hero->name; ?></h3>
		    		</div>
		    	</div>
		    </div>


		    <?php } ?>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<h2 class="text-center"><img class="pacman" src="assets/images/joystick.png"> Select a character <img class= "joystick" src="assets/images/joystick.png"></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-12 text-center">
				<a href="http://localhost:8000/grille_personnage2.php"><button type="button" class="btn btn-danger btn-lg "> Choose ! </button></a>
			</div>

		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
