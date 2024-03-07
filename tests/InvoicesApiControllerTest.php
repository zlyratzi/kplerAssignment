<?php

namespace Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoicesApiControllerTest extends WebTestCase
{

    public function testGetCustomerInvoices(): void
    {
        $client = static::createClient();
        // Assuming the customer_id is valid
        $client->request('GET', '/invoices/2c92a0076390d4590163a4cea58951b4');
        // Assert that the response is successful
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that the response is in JSON format
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        $expectedContent = '[{"id":1,"invoice_id":"8a129623828e052601829901e9d00f49","amount":567.16,"invoice_date":"13-08-2022","is_paid":true},{"id":577,"invoice_id":"2c92a0a77129d01d0171378b3e133bc7","amount":4200,"invoice_date":"01-04-2020","is_paid":true},{"id":1153,"invoice_id":"2c92a0a678afb5ff0178b348feb02692","amount":588,"invoice_date":"08-04-2021","is_paid":true},{"id":1729,"invoice_id":"8a12892d8214cbe6018216a317654c56","amount":572.5,"invoice_date":"19-07-2022","is_paid":true}]';
        // Assert that the response content matches the expected content
        $this->assertEquals($expectedContent, $client->getResponse()->getContent());
        
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        // Assuming the response contains expected properties like id, invoice_date
        $this->assertArrayHasKey('id', $responseData[0]);  
        $this->assertArrayHasKey('invoice_date', $responseData[0]);
       
        $client->request('GET', '/invoices/333');
        // Assuming the customer_id is NOT valid
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetCustomerInvoicesWithInvalidQueryParams(): void
    {
        $client = static::createClient();
        // Assuming valid customer_id but invalid query parameters
        $client->request('GET', '/invoices/2c92a0076390d4590163a4cea58951b4', ['from_date' => '27-10-2020']);
        // Assert that the response indicates an error
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        // Assert that the response contains an appropriate error message
        $expectedContent = '{"error":"An error occurred: Invalid query parameters: from_date"}';
        $this->assertEquals($expectedContent, $client->getResponse()->getContent());
    }

    public function testGetInvoice(): void
    {
        $client = static::createClient();
        // Assuming the invoice_id is valid
        $client->request('GET', '/invoices/8a129623828e052601829901e9d00f49/info');
        
        // Assert that the response is successful
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that the response is in JSON format
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        $expectedContent = '{"id":1,"invoice_id":"8a129623828e052601829901e9d00f49","amount":567.16,"invoice_date":"13-08-2022","is_paid":true}';
        $this->assertEquals($expectedContent, $client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
      
        $this->assertEquals('1', $responseData['id']);
        $this->assertEquals('567.16', $responseData['amount']);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('invoice_date', $responseData);
    }

    public function testGetInvoices(): void
    {
        $client = static::createClient();

        // Make a request to the endpoint
        $client->request('GET', '/invoices');

        // Assert that the response is successful (status code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that the response is in JSON format
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('invoice_id', $responseData[0]);
        $this->assertArrayHasKey('invoice_date', $responseData[0]);
    }
}