<?php

namespace Coreproc\Enom;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class Enom
{

    protected $client;
    public $debug;

    public $base_params;

    public function __construct($userId, $password, $base_uri, $verify_ssl = true, $debug = false)
    {
        $this->client = new Client([
            'base_uri' => $base_uri,
            'verify' => $verify_ssl,
        ]);

        $this->base_params = [
            'uid' => $userId,
            'pw' => $password,
            'responsetype' => 'xml'
        ];

        $this->debug = $debug;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setResponseType($type)
    {
        $this->base_params['responsetype'] = $type;

    }

    /**
     * @param $command
     * @param $additionalParams
     * @param $raw
     * @return string|\SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException|EnomApiException
     * @throws \Exception
     */
    public function doGetRequest($command, $additionalParams = [], $raw = false)
    {
        $params = [
            'command' => $command,
        ];

        $params = array_merge($this->base_params, $params, $additionalParams);


        if ($raw) {
            $this->setResponseType('raw');
        } else {
            $this->setResponseType('xml');
        }

        $response = $this->client->get('', ['query' => $params], true);

        if ($response->getStatusCode() !== 200) {
            throw new EnomApiException($response->getStatusCode().' - '.$response->getReasonPhrase());
        }

        $body = $response->getBody()->getContents();

        if (!$raw) {
            $body = new \SimpleXMLElement($body);
            return $this->ValidateXML($body);
        }

        return $body;
    }

    public function ValidateXML($object)
    {
        return json_decode(json_encode($object));
    }
}
