<?php

namespace Coreproc\Enom;

class Customer
{

    private $client;

    public function __construct(Enom $enom)
    {
        $this->client = $enom->getClient();
    }

    public function updatePreferences(array $params)
    {
        $response = $this->parseXMLObject($this->doGetRequest('UpdateCusPreferences', $params));

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getPreferences()
    {
        $response = $this->parseXMLObject($this->doGetRequest('GetCusPreferences', $params));

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }


    private function doGetRequest($command, $additionalParams = [])
    {
        $params = [
            'command' => $command,
        ];

        if (count($additionalParams)) {
            $params = array_merge($params, $additionalParams);
        }

        $res = $this->client->get('', ['query' => $params])->xml();

        if ($this->enom->debug) {
            // check if running under Codeigniter
            if (function_exists('log_message')) {
                log_message('error', print_r($res, true));
            } else {
                fwrite(STDERR, print_r($res, true) . PHP_EOL);
            }
        }

        return $res;
    }

    private function parseXMLObject($object)
    {
        return json_decode(json_encode($object));
    }
}