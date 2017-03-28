<?php

namespace Coreproc\Enom;

use GuzzleHttp\Client;

class Enom
{

    protected $client;

    public function __construct($userId, $password, $base_url)
    {
        $this->client = new Client([
            'base_url' => $base_url,
            'defaults' => [
                "query" => [
                    'uid'          => $userId,
                    'pw'           => $password,
                    'responsetype' => 'xml'
                ]
            ]
        ]);
    }

    public function getClient()
    {
        return $this->client;
    }
}
