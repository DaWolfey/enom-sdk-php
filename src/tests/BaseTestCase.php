<?php
namespace AgileGeeks\tests;

use PHPUnit\Framework\TestCase as TestCase;

class BaseTestCase extends TestCase
{
    protected static $generic_contact = [
        'RegistrantOrganizationName' => '',
        'RegistrantFirstName' => 'Ultra',
        'RegistrantLastName' => 'Geek',
        'RegistrantAddress1' => 'Some street number and location',
        'RegistrantAddress2' => '',
        'RegistrantCity' => 'Bucuresti',
        'RegistrantStateProvince' => 'Bucuresti',
        'RegistrantPostalCode' => '051512',
        'RegistrantCountry' => 'RO',
        'RegistrantEmailAddress' => 'offie@agilegeeks.ro',
        'RegistrantPhone' => '+40.762365542',
        'RegistrantFax' => ''
    ];

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    protected static function randomstring($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected static function randomnumber($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected static function apex_split($apex_domain)
    {
        return explode(".", $apex_domain, 2);
    }
}
