<?php

namespace Coreproc\Enom;

class Cert
{
    private $enom;

    private $client;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;
        $this->client = $enom->getClient();
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
        } else {
            $res = $this->client->get('', ['query' => $params])->xml();
        }

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

    public function purchaseServices($service, $num_years){
        $params = array(
            'Service'=>$service,
            'NumYears'=>$num_years
        );
        $response = $this->doGetRequest('PurchaseServices', $params);

        $response = $this->parseXMLObject($response);
        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function configure_cert(
        $certid, 
        $domains=array(), 
        $emails=array(), 
        $webserver, 
        $csr, 
        $admin_contact, 
        $tech_contact=null, 
        $billing_contact=null
        ){     
        if ($tech_contact ==null ){
            $tech_contact = $admin_contact;
        }
        if ($billing_contact ==null ){
            $billing_contact = $admin_contact;
        }
        
        $params = array(
            'CertID'=>$certid,
            'WebServerType'=>$webserver,
            'CSR'=>$csr,
        );
        if(sizeof($domains)>0){
            $params['DomainListNumber'] = sizeof($domains);
        }
        $i = 1;
        foreach($domains as $domain){
            $params['UCCDomainList'.$i] = strtolower($domain);
            $i = $i + 1;
        }
        $i = 1;
        foreach($emails as $email){
            $params['UCCEmailList'.$i] = strtolower($email);
            $i = $i + 1;
        }   


        foreach ($admin_contact as $key => $value) {
            $params['Admin'.$key] = $value;
        }

        if ($tech_contact!=null){
            foreach ($tech_contact as $key => $value) {
                $params['Tech'.$key] = $value;
            }
        }
        if ($billing_contact!=null){
            foreach ($billing_contact as $key => $value) {
                $params['Billing'.$key] = $value;
            }
        }
        
        
        $response = $this->doGetRequest('CertConfigureCert', $params);
        
        $response = $this->parseXMLObject($response);
        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function get_cert_detail($certid){
        $params = array(
            'CertID'=>$certid,
        );
        $response = $this->doGetRequest('CertGetCertDetail', $params);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function get_certs(){
        
        $response = $this->doGetRequest('CertGetCerts', [
            
        ]);

        $response = $this->parseXMLObject($response);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    private function parseXMLObject($object)
    {
        return json_decode(json_encode($object));
    }

}