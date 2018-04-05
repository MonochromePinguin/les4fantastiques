<?php
    namespace labyrace;

    require_once '../src/gameConstants.php';
    require_once '../src/functions.php';

    $errorFlag = false;

    # this page is NOT meant to be used with GET
    if ( 0 != count( $_GET ) )
    {
        $errorFlag = true;
        $errMsg = formatErrorMsg( 'Désolé, cette page est prévue pour être utilisée avec la méthode POST.');

    } else {

        # ** simple verification : players **
        $required = [ 'player1', 'player2' ];

        foreach ( $required as $r ) {
            if ( empty( $_POST[$r] ) )
            {
                $errorFlag = true;
                break;
            }

            #each player must contain enough informations
            foreach ( $requiredFields as $f ) {
                if ( empty( $_POST[$required][$f] ) )
                {
                    $errorFlag = true;
                    break;
                }
            }

        }

        # if we don't have a level set, create it now
        if ( empty( $_POST['level'] ) )
            $level = generateLevel(PLAYGROUNDDIM);
        else
            $level = $_POST['level'];
        $playground = generatePlayground( $level );


        if ( $errorFlag )
            $errMsg = formatErrorMsg( 'Désolé ! Requête mal formée !');
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

    </main>

  </body>
</html>