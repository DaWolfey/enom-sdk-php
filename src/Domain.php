<?php

namespace Coreproc\Enom;

class Domain
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

    public function check($sld, $tld)
    {
        $response = $this->doGetRequest('check', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function check_list($domain_list)
    {
        $response = $this->doGetRequest('check', [
            'DomainList' => $domain_list,
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getNameSpinner($sld, $tld, array $options = [])
    {
        $response = $this->doGetRequest('NameSpinner', [
            'sld'        => $sld,
            'tld'        => $tld,
            'UseHyphens' => $options['useHyphens'] ?: true,
            'UseNumbers' => $options['useNumbers'] ?: true,
            'MaxResults' => $options['maxResults'] ?: 10,
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response->namespin;
    }

    public function getExtendedAttributes($tld)
    {
        $response = $this->doGetRequest('GetExtAttributes', [
            'tld' => $tld,
        ]);

        $response = $this->parseXMLObject($response);

        if ( ! isset($response->Attributes)) {
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

        $response = $this->doGetRequest('Purchase', $params);

        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('PurchaseServices', $params);
        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('RenewServices', $params);
        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('GetWPPSInfo', $params);
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function extend($sld, $tld, $period)
    {
        $response = $this->doGetRequest('extend', [
            'sld' => $sld,
            'tld' => $tld,
            'NumYears' => $period,
            'OverrideOrder' => 1
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getStatus($sld, $tld, $orderId)
    {
        $response = $this->doGetRequest('GetDomainStatus', [
            'sld'       => $sld,
            'tld'       => $tld,
            'orderid'   => $orderId,
            'ordertype' => 'purchase',
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getList()
    {
        $response = $this->doGetRequest('GetDomains');

        return $response;
    }

    public function getExpired()
    {
        $response = $this->doGetRequest('GetExpiredDomains');

        return $response;
    }

    public function getInfo($sld, $tld)
    {
        $response = $this->doGetRequest('GetDomainInfo', [
            'sld' => $sld,
            'tld' => $tld
        ]);

        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('Contacts', $params);

        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('modifyns', $params);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getContactInformation($sld, $tld)
    {
        $response = $this->doGetRequest('GetContacts', [
            'sld' => $sld,
            'tld' => $tld,
        ], true);

        $response = parse_ini_string($response);

        if (intval($response['ErrCount']) > 0) {
            throw new EnomApiException(array(
                'Err1' => $response['Err1'],
                'Err2' => $response['Err2'],
                'Err2' => $response['Err3']
            ));
        }

        $registrant = array();
        $admin = array();
        $tech = array();
        $billing = array();

        foreach ($response as $k => $v) {
            if (strpos($k, "Registrant") !== false) {
                $registrant[substr($k, strlen("Registrant"))] = $v;
            } elseif (strpos($k, "Tech") !== false) {
                $tech[substr($k, strlen("Tech"))] = $v;
            } elseif (strpos($k, "Admin") !== false) {
                $admin[substr($k, strlen("Admin"))] = $v;
            } elseif (strpos($k, "Billing") !== false) {
                $billing[substr($k, strlen("Billing"))] = $v;
            }
        }

        $data = array(
            'registrant' => (object) $registrant,
            'admin' => (object) $admin,
            'tech' => (object) $tech,
            'billing' => (object) $billing
        );

        return (object) $data;
    }

    public function getWhoIsContactInformation($sld, $tld)
    {
        $response = $this->doGetRequest('GetWhoIsContact', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getNSInformation($sld, $tld)
    {
        $response = $this->doGetRequest('GetDNS', [
            'sld' => $sld,
            'tld' => $tld,
        ]);

        $response = $this->parseXMLObject($response);

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

        if (count($extendedAttributes)) {
            $params = array_merge($params, $extendedAttributes);
        }
        
        $response = $this->doGetRequest('TP_CreateOrder', $params);
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function setRegLock($sld, $tld, $lock = '0')
    {
        $params = [
            'sld' => $sld,
            'tld' => $tld,
            'unlockregistrar' => $lock
        ];

        $response = $this->doGetRequest('SetRegLock', $params);
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function checkNameserver($nameserver)
    {
        $response = $this->doGetRequest('CheckNSStatus', ['CheckNSName' => $nameserver]);
        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('RegisterNameServer', $params);
        $response = $this->parseXMLObject($response);

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

        $response = $this->doGetRequest('UpdateNameServer', $params);
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function deleteNameserver($nameserver)
    {
        $response = $this->doGetRequest('DeleteNameServer', ['NS' => $nameserver]);
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function getBalance()
    {
        $response = $this->doGetRequest('GetBalance');
        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    private function doGetRequest($command, $additionalParams = [], $raw = false)
    {
        $params = [
            'command' => $command,
        ];

        if (count($additionalParams)) {
            $params = array_merge($params, $additionalParams);
        }

        if ($raw) {
            $this->enom->setResponseType('raw');
            $res = $this->client->get('', ['query' => $params], true)->getBody()->getContents();
            $this->enom->setResponseType('xml');
            return $res;
        }

        return $this->client->get('', ['query' => $params])->xml();
    }

    private function parseXMLObject($object)
    {
        return json_decode(json_encode($object));
    }
}
