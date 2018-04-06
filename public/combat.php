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

    if ( isset( $_POST['player1Id'] ) || isset( $_POST['player2Id'] )
         || empty($_SESSION['level'])
       )
    {
        # ** simple verification : players **
        foreach ( $requiredPlayers as $r ) {
            if ( empty( $_POST[$r] ) )
            {
                $errorFlag = true;
                $errBlock = formatErrorMsg( 'Désolé ! Requête mal formée !');
                break;
            }
        }

        if ( ! $errorFlag )
        {
            $api = new heroClient();

//TODO : $nbplayers could vary on day !
            $nbPlayers = 2;

            $playersId[0] = $_POST['player1Id'];
            $playersId[1] = $_POST['player2Id'];

            #store characters's characteristics into a session variable
            for ($i = 0; $i < $nbPlayers; ++$i)
            {
                $resp = $api->get('id/' . $playersId[$i] . '.json');

                $players[$i] = [
                    'name' => $resp->name,
                    'life' => MAXLIFE,
                    'strength' => $resp->powerstats->strength,
                    'durability' => $resp->powerstats->durability,
                    'images' =>  $resp->images
                ];
                $resp = null;
            }

            positionPlayers($players, $nbPlayers);

            $_SESSION['nbPlayers'] = &$nbPlayers;
            $_SESSION['players'] = &$players;

            #create the $nbTurns and $playingId counters
            $_SESSION['nbTurns'] = 0;
            $_SESSION['playingId'] = 0;

            #once we have players, we generate the level
            if ( empty( $_SESSION['level'] ) )
                $_SESSION['level'] = generateLevel();
        }

    }

    #create some shortcuts
    $nbPlayers = &$_SESSION['nbPlayers'];
    $players = &$_SESSION['players'];
    $playId = &$_SESSION['playingId'];
    $nbTurns = &$_SESSION['nbTurns'];
    $level = &$_SESSION['level'];


    #a movement has occured. Let's update.
    if ( isset( $_POST['actionOne'] ) ) {

        $pos = &$players[$playId]['pos'];
        $newPos = $pos;

        $canMove = false;

        switch ( $_POST['actionOne'] ) {
            case 'up':
                $newPos[0] = max( $pos[0] -1, 0 );
                break;
            case 'right':
                $newPos[1] = min( $pos[1] +1, PLAYGROUNDDIM -1);
                break;
            case 'down':
                $newPos[0] = min( $pos[0] +1, PLAYGROUNDDIM -1);
                break;
            case 'left':
                $newPos[1] = max( $pos[1] -1, 0 );
                break;

            case 'pass':
        }

        if ( null == isPlayerinCell( $players, $newPos ) )
        {
            switch ( $level[ $newPos[1] ][ $newPos[0] ] ) {
                case 0:
                    $canMove = true;
                    break;
                case I_TRAP:
                    $canMove = true;
                    break;
                case I_ROCK:
                    //cannot move into
                    break;
                case I_HEART:
                    $canMove = true;
                    break;
                case I_BOOSTER:
                    $canMove = true;
            }
        }

        if ($canMove)
        {
            $pos[0] = $newPos[0];
            $pos[1] = $newPos[1];
        }

        #final movement test
        if ( ( WINCELLPOS == $pos[0] ) && ( WINCELLPOS == $pos[1] ) )
        {
            $winnerId = $playId;
#TODO : make it smarter
            redirect( 'end.php');
        }
    }

    #update counters
    ++$nbTurns;
    if ( ++$playId >= $nbPlayers )
        $playId = 0;

    #redraw the grid
    $_SESSION['playground'] =
    $playground = generatePlayground( $_SESSION['level'], $players, $playId );
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
            <h1><?= $players[$playId]['name'] . ' joue' ?></h1>
            <h2><?= 'tour n°' . $nbTurns ?></h2>
            <p> debug : pos <?= $players[$playId]['pos'][0] ?>,
                        <?= $players[$playId]['pos'][1] ?>
            </p>
            <form method="POST" action="#">
                <fieldset name="movements">
                    <legend>mouvements</legend>

                    <label>
                        gauche
                        <span class="glyphicon glyphicon-arrow-left"></span>
                        <input type="radio" name="actionOne" value="left">
                    </label>
                    <label>
                        haut
                        <span class="glyphicon glyphicon-arrow-up"></span>
                        <input type="radio" name="actionOne" value="up">
                    </label>
                    <label>
                        bas
                        <span class="glyphicon glyphicon-arrow-down"></span>
                        <input type="radio" name="actionOne" value="down">
                    </label>
                    <label>
                        droite
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        <input type="radio" name="actionOne" value="right">
                    </label>
                </fieldset>

                <fieldset name="powers">
                    <legend>Actions spéciales</legend>

                    <label>
                        Passer le tour
                        <span class="glyphicon glyphicon-remove"></span>
                        <input type="radio" name="actionOne" value="pass">
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
        if ( isset( $_SESSION['winnerId'] ) ) {
    ?>
        <section class="">
            <p>
                <?= $players[ $winnerId ]['name'] ?> a gagné&nbsp;!
            </p>
        </section>

    <?php
        }
    ?>

<?php
    }
?>
    </main>

  </body>
</html>