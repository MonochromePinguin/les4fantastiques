<?php
  $_SESSION = [];
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>page de test pour la page combats</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- pour désactiver le mode
     «compatibilité avec le vieil et défaillant IE » de edge -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

  </head>

  <body>
    <header>
        <h1>Cette page sert juste à passer à la page «Combats» avec les bons paramètres</h1>
    </header>

    <main>
        <form method="POST" action ="/combat.php">
            <input type="number" name="player1Id" min="1" max="20">
            <input type="number" name="player2Id" min="1" max="20">
            <input type=submit value="Passer à la page combat">
        </form>
    </main>

  </body>
</html>