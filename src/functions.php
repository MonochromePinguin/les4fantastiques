<?php

require_once 'gameConstants.php';


function formatErrorMsg( string $msg ) : string {
    return '<div class="error"><p>' . $msg . "<p>\n"
        . '<input method="GET" action="/" value="Recommencer" class="btn btn-danger" ></p>';
}


function redirect( $url ) {
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}



function generateLevel() : array {
    global $itemsToPlace;

    #populate the array of 0
    for ($row = PLAYGROUNDDIM; $row > 0; --$row )
        $level[] = array_fill(0, PLAYGROUNDDIM, 0);

#   Place the items at random places – no overwrite test !
#TODO: add an overwrite test
    foreach( $itemsToPlace as $item => $nb )
    {
        for( $i = $nb; ($i); --$i )
        {
            $x = rand(0, PLAYGROUNDDIM -1);
            $y = rand(0, PLAYGROUNDDIM -1);

            $level[$y][$x] = $item;
        }
    }

    #Place the center target – no overwrite test !
#TODO: add an overwrite test
   $level[WINCELLPOS][WINCELLPOS] = B_TARGET;

    return $level;

}


function generatePlayground( array $level, array $players, $playId ) : string {
    global $tilesCSS;
    $result = '';

    $fillTable = [
        0 => [ 'cell-grav', null ],
        I_TRAP => [ 'cell-grav', null ],
//TODO : automatically take the better image format/file
        I_BOMB => [ 'cell-grav', 'bomb-32' ],
        I_ROCK => [ 'cell-grav', 'rock-128' ],
        I_HEART => [ 'cell-grav', 'heart-64' ],
        I_BOOSTER => [ 'cell-grav', 'booster' ],
        B_TARGET => [ 'cell-stone', null ],
    ];

    for ( $y = 0; $y < PLAYGROUNDDIM; ++$y)
    {
        $result .= "<div class=\"row\">\n";
        for ( $x = 0; $x < PLAYGROUNDDIM; ++$x )
        {
            #set the background image in $style,
            # and the included <img> tag in $content
            $v = $level[$y][$x];

            $style = $fillTable[$v][0];
            $content = $fillTable[$v][1];
            $imgClass = null;

            if ( isset($content) )
                $content = 'assets/images/' . $content . '.png';

            $numPlayer = isPlayerInCell( $players, array( $y,$x ) );
            if ( null !== $numPlayer )
            {
                 $content = $players[$numPlayer]['images']->xs;

                if ( $numPlayer == $playId )
                    $imgClass = ' playNow';
            }

            if ( isset($content) )
                $content = '<img class="itemized' . $imgClass
                                 . '" src="' . $content . '" alt="">';

            $result .= '<div class="col-1 ' . $style . '">' . $content . "</div>\n";
        }

        $result .= "</div>    <!-- .row -->\n";
    }
    return $result;
}


#position the players at random corners
function positionPlayers( array &$players, int $nbPlayers ) {
    $possiblePositions = [
        [0, 0],
        [0, PLAYGROUNDDIM -1],
        [PLAYGROUNDDIM -1, PLAYGROUNDDIM -1],
        [PLAYGROUNDDIM -1, 0]
    ];
    $availablePositions = [ true, true, true, true ];
    $pPos = count( $possiblePositions );

    #settle the player in a random corner
    for ( $i = 0; $i < $nbPlayers; ++$i) {

        do {
            $n = rand(0, $pPos-1);
        } while ( $availablePositions[$n] == false );
        $players[$i]['pos'] = $possiblePositions[$n];
    }
}


/**
* Returns the id of the player in $players when this one is
*  in the corresponding cell, or null
* @return int|null
*/
function isPlayerInCell( array $players, array $pos ) {
    $l = count($players);

    for ( $i = 0; $i < $l; ++$i )
    {
        $posP = $players[$i]['pos'];

        if ( ( $posP[0] == $pos[0] ) && ( $posP[1] == $pos[1] ) )
            return $i;
    }

    return null;
}
