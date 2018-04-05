<?php

require_once '../vendor/autoload.php';
require_once '../src/gameConstants.php';
require_once '../src/functions.php';

//TODO : make autoloading work with this
require_once '../src/heroClient.php';
// use heroClient;

session_start();

$errorFlag = false;

# this page is NOT meant to be used with GET
if ( 0 != count( $_GET ) )
{
    $errorFlag = true;
    $errBlock = formatErrorMsg( 'Désolé, cette page est prévue pour être utilisée avec la méthode POST.');

} else {

    $requiredPlayers = [ 'player1Id', 'player2Id' ];

    if ( empty($_SESSION['player1Id'])
         || empty($_SESSION['player2Id'])
         || empty($_SESSION['level'])
       )
    {
        # ** simple verification : players **
        foreach ( $requiredPlayers as $r ) {
            if ( empty( $_POST[$r] ) )
            {
                $errorFlag = true;
                break;
            }
        }

        if ( ! $errorFlag )
        {
            $api = new heroClient();

            $playersId[0] = $_POST['player1Id'];
            $playerId[1] = $_POST['player2Id'];

            #position the players at random corners
            $possiblePositions = [
                [0, 0],
                [0, PLAYGROUNDDIM],
                [PLAYGROUNDDIM, PLAYGROUNDDIM],
                [PLAYGROUNDDIM, 0]
            ];
            $availablePositions = [ true, true, true, true ];
            $pPos = count( $possiblePositions );

            #store characters's characteristics into a session variable
            foreach ($playerId as $p)
            {
                $resp = $api->get('id/' . $p . '.json');

                $players[$p] = [
                    'name' => $resp->name,
                    'life' => MAXLIFE,
                    'strength' => $resp->powerstats->strength,
                    'durability' => $resp->powerstats->durability,
                    'images' =>  $resp->images
                ];
                $resp = null;

                #settle the player in a random corner
                do {
                    $n = rand(0, $pPos-1);
                } while ( $availablePositions[$n] = false );

                $players[$p]['pos'] = $possiblePositions[$n];
            }

            $_SESSION['players'] = $players;


            #once we have players, we generate the level
            if ( empty( $_SESSION['level'] ) )
                $_SESSION['level'] = generateLevel(PLAYGROUNDDIM);

        }

    }
var_dump($_SESSION['players']);

    # regenerate the playground each time needed
    if ( ! $errorFlag )
    {
        if ( empty( $_SESSION['playground'] ) )
            $playground = generatePlayground( $_SESSION['level'] );
    } else
        $errBlock = formatErrorMsg( 'Désolé ! Requête mal formée !');

}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Laby-race</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- bootstrap links -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous" defer></script>

    <link rel="stylesheet" href="assets/css/combat.css">

  </head>

  <body>
    <header class="container-fluid">
        <div class="row">
            <img src="" alt="">
            <p> Vs </p>
            <img src="" alt="">
        </div>
    </header>

    <main>

<?php
    if ( $errorFlag )
        # error Messages to be shown ? don't show the normal part
        echo $errBlock;
    else {
?>

        <section class="container-fluid">
            <?= $playground ?>
        </section>

        <section class="controls container-fluid">
            <form method="POST" action="#">
                <fieldset name="movements">
                    <legend>mouvements</legend>

                    <label>
                        haut
                        <span class="glyphicon glyphicon-arrow-up"></span>
                        <input type="radio" name="actionOne" value="up">
                    </label>
                    <label>
                        droite
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        <input type="radio" name="actionOne" value="right">
                    </label>
                    <label>
                        bas
                        <span class="glyphicon glyphicon-arrow-down"></span>
                        <input type="radio" name="actionOne" value="down">
                    </label>
                    <label>
                        gauche
                        <span class="glyphicon glyphicon-arrow-left"></span>
                        <input type="radio" name="actionOne" value="left">
                    </label>
                </fieldset>

                <fieldset name="powers">
                    <legend>Actions spéciales</legend>

                    <label>
                        Passer le tour
                        <span class="glyphicon glyphicon-remove"></span>
                        <input type="radio" name="actionOne" value="noAction">
                    </label>

<!--                     <label>
                        <input type="radio" name="actionOne" value="power1">
                    </label>

                    <label>
                        <input type="radio" name="actionOne" value="power2">
                    </label>
                    <label>
                        <input type="radio" name="actionOne" value="power3">
                    </label>
 -->                </fieldset>

                <fieldset name="controls">
                    <input type="submit" name="go" ="submitGo">
                </fieldset>
            <form>
        </section>
<?php
    }
?>
    </main>

  </body>
</html>