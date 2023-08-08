<?php

namespace Coreproc\Enom;

class Tld
{

    /**
     * @var Enom
     */
    private $enom;


    public function __construct(Enom $enom)
    {
        $this->enom = $enom;

    }

    public function authorize(array $tlds)
    {
        $response = $this->enom->doGetRequest('AuthorizeTLD', [
            'domainlist' => implode(',', $tlds),
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->tldlist;
    }

    public function remove(array $tlds)
    {
        $response = $this->enom->doGetRequest('RemoveTLD', [
            'domainlist' => implode(',', $tlds),
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->tldlist;
    }

    public function getList()
    {
        $response = $this->enom->doGetRequest('GetTLDList');


        return $response->tldlist;
    }
}