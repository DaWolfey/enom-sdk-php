<?php

namespace Coreproc\Enom;

use GuzzleHttp\Exception\GuzzleException;

class Transfer
{
    /**
     * @var Enom
     */
    private $enom;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;
    }

    /**
     * @throws GuzzleException
     * @throws EnomApiException
     */
    public function GenerateEPP(string $sld, string $tld, bool $EmailEPP, bool $RunSynchAutoInfo)
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
            'EmailEPP' => $this->enom->BoolToString($EmailEPP),
            'RunSynchAutoInfo' => $this->enom->BoolToString($RunSynchAutoInfo)
        ];

        $response = $this->enom->doGetRequest('SynchAuthInfo', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }
}