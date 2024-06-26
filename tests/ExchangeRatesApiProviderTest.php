<?php

require_once 'vendor/autoload.php';

use App\CurlRequestHandler;
use App\ExchangeRatesApiProvider;
use PHPUnit\Framework\TestCase;

class ExchangeRatesApiProviderTest extends TestCase
{

    protected function setUp(): void {
        $this->curlRequestHandler = new CurlRequestHandler();
        $this->exchangeRatesApiProvider = new ExchangeRatesApiProvider($this->curlRequestHandler);
    }

    public function testGetExchangeRates() {
        $response = $this->exchangeRatesApiProvider->getExchangeRates();

        $this->assertIsArray($response);

        $this->assertArrayHasKey('success', $response);
        $this->assertArrayHasKey('timestamp', $response);
        $this->assertArrayHasKey('base', $response);
        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('rates', $response);

        $this->assertTrue($response['success']);
        $this->assertIsInt($response['timestamp']);
        $this->assertEquals($response['base'], "EUR");
        $this->assertIsString($response['base']);
        $this->assertIsArray($response['rates']);

    }

    public function testGetExchangeRate() {
        $response = $this->exchangeRatesApiProvider->getExchangeRate('EUR');
        $this->assertNotFalse($response);
        $this->assertIsInt($response);

        $response = $this->exchangeRatesApiProvider->getExchangeRate('USD');
        $this->assertNotFalse($response);
        $this->assertIsFloat($response);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Exchange rate not found for currency: XYZ');
        $this->exchangeRatesApiProvider->getExchangeRate('XYZ');
    }
}