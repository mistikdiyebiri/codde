<?php

namespace TryOto\SDK;

use GuzzleHttp\Client as HttpClient;
use TryOto\SDK\Config\TryOtoConfig;
use TryOto\SDK\Services\ShipmentService;
use TryOto\SDK\Services\TrackingService;
use TryOto\SDK\Services\CourierService;
use TryOto\SDK\Services\PricingService;
use TryOto\SDK\Services\BarcodeService;
use TryOto\SDK\Services\WebhookService;
use TryOto\SDK\Exception\TryOtoException;

/**
 * TryOto API istemcisi
 * 
 * TryOto Kargo API'si ile entegrasyon için ana sınıf
 */
class TryOtoClient
{
    /**
     * API anahtarı
     *
     * @var string
     */
    private $apiKey;
    
    /**
     * API base URL
     *
     * @var string
     */
    private $baseUrl;
    
    /**
     * HTTP istemcisi
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;
    
    /**
     * Kargo hizmeti
     *
     * @var \TryOto\SDK\Services\ShipmentService
     */
    private $shipmentService;
    
    /**
     * Kargo takip hizmeti
     *
     * @var \TryOto\SDK\Services\TrackingService
     */
    private $trackingService;
    
    /**
     * Kargo firmaları hizmeti
     *
     * @var \TryOto\SDK\Services\CourierService
     */
    private $courierService;
    
    /**
     * Fiyatlandırma hizmeti
     *
     * @var \TryOto\SDK\Services\PricingService
     */
    private $pricingService;
    
    /**
     * Barkod hizmeti
     *
     * @var \TryOto\SDK\Services\BarcodeService
     */
    private $barcodeService;
    
    /**
     * Webhook hizmeti
     *
     * @var \TryOto\SDK\Services\WebhookService
     */
    private $webhookService;
    
    /**
     * Yapılandırma
     *
     * @var \TryOto\SDK\Config\TryOtoConfig
     */
    private $config;

    /**
     * TryOtoClient yapıcı
     *
     * @param string $apiKey API anahtarı
     * @param array $config Yapılandırma parametreleri
     */
    public function __construct(string $apiKey, array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->config = new TryOtoConfig($config);
        $this->baseUrl = $this->config->getBaseUrl();
        
        $this->httpClient = new HttpClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => $this->config->getTimeout(),
        ]);
        
        $this->initializeServices();
    }
    
    /**
     * Tüm servis sınıflarını başlatır
     *
     * @return void
     */
    private function initializeServices(): void
    {
        $this->shipmentService = new ShipmentService($this->httpClient, $this->config);
        $this->trackingService = new TrackingService($this->httpClient, $this->config);
        $this->courierService = new CourierService($this->httpClient, $this->config);
        $this->pricingService = new PricingService($this->httpClient, $this->config);
        $this->barcodeService = new BarcodeService($this->httpClient, $this->config);
        $this->webhookService = new WebhookService($this->httpClient, $this->config);
    }
    
    /**
     * Yeni bir kargo gönderisi oluşturur
     *
     * @param array $data Gönderi verileri
     * @return array Oluşturulan gönderi bilgileri
     * @throws TryOtoException
     */
    public function createShipment(array $data): array
    {
        return $this->shipmentService->create($data);
    }
    
    /**
     * Kargo takibi yapar
     *
     * @param string $trackingNumber Takip numarası
     * @return array Kargo takip bilgileri
     * @throws TryOtoException
     */
    public function trackShipment(string $trackingNumber): array
    {
        return $this->trackingService->track($trackingNumber);
    }
    
    /**
     * Kargo gönderisini iptal eder
     *
     * @param string $trackingNumber Takip numarası
     * @return array İptal sonucu
     * @throws TryOtoException
     */
    public function cancelShipment(string $trackingNumber): array
    {
        return $this->shipmentService->cancel($trackingNumber);
    }
    
    /**
     * Kargo ücretini hesaplar
     *
     * @param array $data Hesaplama parametreleri
     * @return array Hesaplama sonucu
     * @throws TryOtoException
     */
    public function calculatePrice(array $data): array
    {
        return $this->pricingService->calculate($data);
    }
    
    /**
     * Desteklenen kargo firmalarını listeler
     *
     * @return array Desteklenen kargo firmaları
     * @throws TryOtoException
     */
    public function listCouriers(): array
    {
        return $this->courierService->list();
    }
    
    /**
     * Barkod veya etiket üretir
     *
     * @param string $trackingNumber Takip numarası
     * @param string $format Çıktı formatı (pdf|png)
     * @param array $options Opsiyonel parametreler
     * @return string Barkod veya etiket içeriği
     * @throws TryOtoException
     */
    public function generateBarcode(string $trackingNumber, string $format = 'pdf', array $options = []): string
    {
        return $this->barcodeService->generate($trackingNumber, $format, $options);
    }
    
    /**
     * Webhook verilerini işler
     *
     * @param array $data Webhook verileri
     * @return array İşlenmiş webhook sonucu
     * @throws TryOtoException
     */
    public function handleWebhook(array $data): array
    {
        return $this->webhookService->handle($data);
    }
}