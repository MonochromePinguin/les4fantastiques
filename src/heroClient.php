<?php

Use \GuzzleHttp\Client as Client;
use \GuzzleHttp\EntityBody as Body;

class heroClient
{

    private $client;

    public function __construct() {
        $this->client = new Client([ 'base_uri' =>
                 'https://akabab.github.io/superhero-api/api/']);
    }

    public function get( string $path ) {
        return json_decode(
                    $this->client->request( 'GET', $path)
                                        ->getBody()->getContents());
    }

}



