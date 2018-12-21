<?php

namespace Coreproc\Enom;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
class Enom
{

    protected $client;
    public $debug;

    public function __construct($userId, $password, $base_url, $verify_ssl = true, $debug = false)
    {
        $this->client = new Client([
            'base_url' => $base_url,
            'defaults' => [
                "query" => [
                    'uid'          => $userId,
                    'pw'           => $password,
                    'responsetype' => 'xml'
                ],
                'verify' => $verify_ssl,
            ]
        ]);
        $this->debug = $debug;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setResponseType($type)
    {
        $options = $this->client->getDefaultOption();
        $query =  $options['query'];
        $query['responsetype'] = $type;
        $this->client->setDefaultOption('query', $query);
    }
}
