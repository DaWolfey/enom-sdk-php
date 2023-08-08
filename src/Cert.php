<?php

namespace Coreproc\Enom;

class Cert
{
    private $enom;

    private $client;

    public function __construct(Enom $enom)
    {
        $this->enom = $enom;

    }


    public function purchaseServices($service, $num_years)
    {

        $params = [
            'Service' => $service,
            'NumYears' => $num_years
        ];

        $response = $this->enom->doGetRequest('PurchaseServices', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function configure_cert(
        $certid,
        array $domains,
        array $emails,
        $webserver,
        $csr,
        $admin_contact,
        $tech_contact = null,
        $billing_contact = null
    )
    {
        if ($tech_contact == null) {
            $tech_contact = $admin_contact;
        }
        if ($billing_contact == null) {
            $billing_contact = $admin_contact;
        }

        $params = [
            'CertID' => $certid,
            'WebServerType' => $webserver,
            'CSR' => $csr,
        ];
        if (count($domains) > 0) {
            $params['DomainListNumber'] = count($domains);
        }

        $i = 1;
        foreach ($domains as $domain) {
            $params['UCCDomainList' . $i] = strtolower($domain);
            $i = $i + 1;
        }

        $i = 1;
        foreach ($emails as $email) {
            $params['UCCEmailList' . $i] = strtolower($email);
            $i = $i + 1;
        }

        foreach ($admin_contact as $key => $value) {
            $params['Admin' . $key] = $value;
        }

        if ($tech_contact != null) {
            foreach ($tech_contact as $key => $value) {
                $params['Tech' . $key] = $value;
            }
        }
        if ($billing_contact != null) {
            foreach ($billing_contact as $key => $value) {
                $params['Billing' . $key] = $value;
            }
        }


        $response = $this->enom->doGetRequest('CertConfigureCert', $params);

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function get_cert_detail($certid)
    {
        $params = [
            'CertID' => $certid,
        ];
        $response = $this->enom->doGetRequest('CertGetCertDetail', $params);


        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }

    public function get_certs()
    {
        $response = $this->enom->doGetRequest('CertGetCerts');

        if ($response->ErrCount > 0) {
            throw new EnomApiException($response->errors);
        }

        return $response;
    }


}