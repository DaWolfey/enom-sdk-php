<?php

namespace Coreproc\Enom;

class Customer
{

    private $client;
    private $enom;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;
    }

    public function updatePreferences(array $params)
    {
        $response = $this->enom->doGetRequest('UpdateCusPreferences', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getPreferences()
    {
        $response = $this->enom->doGetRequest('GetCusPreferences');

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }


}