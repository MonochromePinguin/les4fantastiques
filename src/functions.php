<?php
namespace labyrace;

require_once 'gameConstants.php';

function formatErrorMsg( string $msg ) : string {
    return '<div class="error"><p>' . $msg . "<p>\n"
        . '<input method="GET" action="/" value="Recommencer" class="btn btn-danger" ></p>';
}

function generateLevel( int $dim ) : array {
    global $itemsToPlace;

    $level = [];

    #populate the array of 0
    for ($row = $dim; ( $row ); --$row )
        $level[] = array_fill(0, $dim, 0);

    #Place the items at random places – no overwrite test !
#TODO: add an overwrite test
    foreach( $itemsToPlace as $item => $nb )
    {
        for( $i = $nb; ($i); --$i )
        {
            $x = rand(0, $dim);
            $y = rand(0, $dim);

            $level[$y][$x] = $item;
        }
    }

    return $level;

}


function generatePlayground( array $level ) : string {
    $result = '';

    foreach( $level as $row ) {
        $result .= "<div class=\"row\">\n<div class=\"col-1\"></div>\n";

        foreach( $row as $cell ) {
            $result .= '<div class="col-1">' . $cell . "</div>\n";
        }

        $result .= "<div class=\"col-1\"></div>\n</div>    <!-- .row -->\n";
    }

    return $result;
}