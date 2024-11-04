<?php

namespace Coreproc\Enom;

use GuzzleHttp\Exception\GuzzleException;

class Domain
{

    /**
     * @var Enom
     */
    private $enom;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;
    }

    public function check($sld, $tld)
    {
        $response = $this->enom->doGetRequest('check', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function check_list($domain_list)
    {
        $response = $this->enom->doGetRequest('check', [
            'DomainList' => $domain_list,
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getNameSpinner($sld, $tld, array $options = [])
    {
        $response = $this->enom->doGetRequest('NameSpinner', [
            'sld' => $sld,
            'tld' => $tld,
            'UseHyphens' => $options['useHyphens'] ?? true,
            'UseNumbers' => $options['useNumbers'] ?? true,
            'MaxResults' => $options['maxResults'] ?? 10,
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->namespin;
    }

    public function getExtendedAttributes($tld)
    {
        $response = $this->enom->doGetRequest('GetExtAttributes', [
            'tld' => $tld,
        ]);

        if (!isset($response->Attributes)) {
            throw new \Exception('Invalid TLD');
        }

        return $response->Attributes;
    }

    public function purchase($sld, $tld, array $extendedAttributes = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
        ];

        if (count($extendedAttributes)) {
            $params = array_merge($params, $extendedAttributes);
        }

        $response = $this->enom->doGetRequest('Purchase', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function purchaseService($sld, $tld, $extendedAttributes = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld
        ];

        if (count($extendedAttributes)) {
            $params = array_merge($params, $extendedAttributes);
        }

        $response = $this->enom->doGetRequest('PurchaseServices', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function renewService($sld, $tld, $extendedAttributes = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld
        ];

        if (count($extendedAttributes)) {
            $params = array_merge($params, $extendedAttributes);
        }

        $response = $this->enom->doGetRequest('RenewServices', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getWPPSInfo($sld, $tld)
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld
        ];

        $response = $this->enom->doGetRequest('GetWPPSInfo', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function extend($sld, $tld, $period)
    {
        $response = $this->enom->doGetRequest('extend', [
            'sld' => $sld,
            'tld' => $tld,
            'NumYears' => $period,
            'OverrideOrder' => 1
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function extendRGP($sld, $tld)
    {
        $response = $this->enom->doGetRequest('Extend_RGP', [
            'sld' => $sld,
            'tld' => $tld
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }


    public function updateExpiredDomains($domain, $period)
    {
        $response = $this->enom->doGetRequest('UpdateExpiredDomains', [
            'DomainName' => $domain,
            'NumYears' => $period
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getStatus($sld, $tld, $orderId)
    {
        $response = $this->enom->doGetRequest('GetDomainStatus', [
            'sld' => $sld,
            'tld' => $tld,
            'orderid' => $orderId,
            'ordertype' => 'purchase',
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    /**
     * @param $tab
     * @param $domain
     * @return \SimpleXMLElement|string
     * @throws EnomApiException
     * @throws GuzzleException
     * @deprecated Use GetAllDomains instead
     */
    public function getList($tab = 'IOwn', $domain = '')
    {

        $response = $this->enom->doGetRequest('GetDomains', [
            'Tab' => $tab,
            'Domain' => $domain
        ]);

        return $response;
    }

    /**
     * @throws GuzzleException
     * @throws EnomApiException
     */
    public function GetAllDomains(): array
    {
        //We only get a max of 100 domains per call with AdvancedDomainSearch, so we make a loop.
        //NextPosition is 1 when there are no more domains to receive, but if there is only one domain in total then we're done too.

        $domains = [];
        $position = 1;
        do {
            $response = $this->enom->doGetRequest('AdvancedDomainSearch', ['StartPosition' => $position]);
            if ($response->ErrCount > 0) {
                throw new EnomApiException($response->errors);
            }

            $totalresults = $response->DomainSearch->TotalResults;
            $domains = array_merge($domains, (array) $response->DomainSearch->Domains->Domain);
            $position = $response->DomainSearch->NextPosition;
        } while ($totalresults == 1 || $position != 1);

        return $domains;
    }


    public function getExpiredDomain($fqdn)
    {
        $response = $this->enom->doGetRequest('GetDomains', [
            'Tab' => 'ExpiredDomains',
            'Domain' => $fqdn
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getExpired()
    {
        $response = $this->enom->doGetRequest('GetExpiredDomains');

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getInfo($sld, $tld)
    {
        $response = $this->enom->doGetRequest('GetDomainInfo', [
            'sld' => $sld,
            'tld' => $tld
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function setContactInformation($sld, $tld, array $contactInfo = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
        ];

        $params = array_merge($params, $contactInfo);

        $response = $this->enom->doGetRequest('Contacts', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function ModifyNameservers($sld, $tld, array $extendedAttributes = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
        ];

        if (count($extendedAttributes)) {
            $params = array_merge($params, $extendedAttributes);
        }

        $response = $this->enom->doGetRequest('modifyns', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getContactInformation($sld, $tld)
    {
        $response = $this->enom->doGetRequest('GetContacts', [
            'sld' => $sld,
            'tld' => $tld,
        ], false);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getWhoIsContactInformation($sld, $tld)
    {
        $response = $this->enom->doGetRequest('GetWhoIsContact', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getNSInformation($sld, $tld)
    {
        $response = $this->enom->doGetRequest('GetDNS', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function transferIn($sld, $tld, $extendedAttributes = [])
    {
        $params = [
            'sld1' => $sld,
            'tld1' => $tld,
            'domaincount' => '1',
            'ordertype' => 'autoverification'
        ];

        $params = array_merge($params, $extendedAttributes);

        $response = $this->enom->doGetRequest('TP_CreateOrder', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function setRegLock($sld, $tld, bool $lock)
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
            'unlockregistrar' => $this->enom->BoolToIntString($lock)
        ];

        $response = $this->enom->doGetRequest('SetRegLock', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function checkNameserver($nameserver)
    {
        $response = $this->enom->doGetRequest('CheckNSStatus', ['CheckNSName' => $nameserver]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function registerNameserver($nameserver, $ip)
    {
        $params = [
            'Add' => 'true',
            'NSName' => $nameserver,
            'IP' => $ip
        ];

        $response = $this->enom->doGetRequest('RegisterNameServer', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function updateNameserver($nameserver, $old_ip, $new_ip)
    {
        $params = [
            'OldIP' => $old_ip,
            'NewIP' => $new_ip,
            'NS' => $nameserver
        ];

        $response = $this->enom->doGetRequest('UpdateNameServer', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function deleteNameserver($nameserver)
    {
        $response = $this->enom->doGetRequest('DeleteNameServer', ['NS' => $nameserver]);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function addDNSSEC($sld, $tld, $alg, $digest, $digestType, $keyTag, $additionalParams = [])
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
            'Alg' => $alg,
            'Digest' => $digest,
            'DigestType' => $digestType,
            'KeyTag' => $keyTag
        ];


        $params = array_merge($params, $additionalParams);

        $response = $this->enom->doGetRequest('AddDnsSec', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getDNSSEC($sld, $tld)
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld
        ];

        $response = $this->enom->doGetRequest('GetDnsSec', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function deleteDNSSEC($sld, $tld, $alg, $digest, $digestType, $keyTag)
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
            'Alg' => $alg,
            'Digest' => $digest,
            'DigestType' => $digestType,
            'KeyTag' => $keyTag
        ];

        $response = $this->enom->doGetRequest('DeleteDnsSec', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getBalance()
    {
        $response = $this->enom->doGetRequest('GetBalance');

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

}
