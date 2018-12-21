<?php

namespace Coreproc\Enom;

class Tld
{

    /**
     * @var Enom
     */
    private $enom;

    private $client;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;
        $this->client = $enom->getClient();
    }

    public function authorize(array $tlds)
    {
        $response = $this->doGetRequest('AuthorizeTLD', [
            'domainlist' => implode(',', $tlds),
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->tldlist;
    }

    public function remove(array $tlds)
    {
        $response = $this->doGetRequest('RemoveTLD', [
            'domainlist' => implode(',', $tlds),
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->tldlist;
    }

    public function getList()
    {
        $response = $this->doGetRequest('GetTLDList');

        $response = $this->parseXMLObject($response);

        return $response->tldlist;
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