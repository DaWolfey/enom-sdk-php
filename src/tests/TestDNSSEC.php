<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use \Coreproc\Enom\Enom as Enom;
use \Coreproc\Enom\Domain as Domain;

class TestDNSSEC extends BaseTestCase
{
    protected function setUp()
    {
        include('config.php');
        $this->domain = 'testdom-' . self::randomnumber(10) . '.com';
        $this->exDate = '';
        $this->enom = new Enom(
            $config['uid'],
            $config['pw'],
            $config['base_url'],
            $config['verify_ssl'],
            $config['debug']
        );
        $this->domain_instance = new Domain($this->enom);

        list($sld, $tld) = self::apex_split($this->domain);
        
        $params = [
            'DomainPassword' => 'D_main123456',
            'NumYears' => 1,
            'IgnoreNSFail' => 'Yes'
        ];

        $params = array_merge($params, self::$generic_contact);
        
        $this->domain_instance->purchase($sld, $tld, $params);
    }

    protected function tearDown()
    {
        // no way to delete domains :(
    }

    public function test_add_dnssec()
    {
        $ds_data = array(
            'alg' => '253',
            'keyTag' => '32121',
            'digestType' => '1',
            'digest' => '54F42A9ACB3BFCE44B416AC83735D3405EEA825A'
        );

        list($sld, $tld) = self::apex_split($this->domain);

        $response = $this->domain_instance->addDNSSEC($sld, $tld, $ds_data['alg'], $ds_data['digest'], $ds_data['digestType'], $ds_data['keyTag']);

        $this->assertEquals('200', $response->DnsSecData->Result->ResponseCode);

        $response = $this->domain_instance->getDNSSEC($sld, $tld);

        $this->assertEquals($ds_data['digest'], $response->Digest);
    }

    public function test_delete_dnssec()
    {
        $ds_data = array(
            'alg' => '253',
            'keyTag' => '32121',
            'digestType' => '1',
            'digest' => '54F42A9ACB3BFCE44B416AC83735D3405EEA825A'
        );

        list($sld, $tld) = self::apex_split($this->domain);

        $response = $this->domain_instance->addDNSSEC($sld, $tld, $ds_data['alg'], $ds_data['digest'], $ds_data['digestType'], $ds_data['keyTag']);

        $this->assertEquals('200', $response->DnsSecData->Result->ResponseCode);

        $response = $this->domain_instance->getDNSSEC($sld, $tld);

        $this->assertEquals($ds_data['digest'], $response->Digest);

        $response = $this->domain_instance->deleteDNSSEC($sld, $tld, $ds_data['alg'], $ds_data['digest'], $ds_data['digestType'], $ds_data['keyTag']);

        $this->assertEquals('200', $response->DnsSecData->Result->ResponseCode);

        $response = $this->domain_instance->getDNSSEC($sld, $tld);

        $this->assertEquals('0', $response->DnsSecDataCount);
    }
}