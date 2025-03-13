<?php

namespace TryOto\SDK\Services;

use GuzzleHttp\Client;
use TryOto\SDK\Config\TryOtoConfig;
use TryOto\SDK\Exception\ValidationException;
use TryOto\SDK\Exception\ApiException;
use TryOto\SDK\Http\HttpClient;

/**
 * Kargo Gönderisi Servisi
 * 
 * Kargo gönderisi oluşturma, iptal etme ve güncelleme işlemlerini yönetir
 */
class ShipmentService
{
    /**
     * HTTP istemcisi
     *
     * @var HttpClient
     */
    private $httpClient;
    
    /**
     * Yapılandırma
     *
     * @var TryOtoConfig
     */
    private $config;

    /**
     * ShipmentService yapıcı
     *
     * @param Client $client Guzzle istemcisi
     * @param TryOtoConfig $config Yapılandırma
     */
    public function __construct(Client $client, TryOtoConfig $config)
    {
        $this->config = $config;
        $this->httpClient = new HttpClient($client, $config);
    }

    /**
     * Yeni bir kargo gönderisi oluşturur
     *
     * @param array $data Gönderi bilgileri
     * @return array Oluşturulan gönderi yanıtı
     * @throws ValidationException|ApiException
     */
    public function create(array $data): array
    {
        // Gerekli alanların kontrolü
        $this->validateShipmentData($data);
        
        return $this->httpClient->post('shipments', $data);
    }

    /**
     * Kargo gönderisini iptal eder
     *
     * @param string $trackingNumber Takip numarası
     * @return array İptal yanıtı
     * @throws ApiException
     */
    public function cancel(string $trackingNumber): array
    {
        if (empty($trackingNumber)) {
            throw new ValidationException('Takip numarası gereklidir.');
        }
        
        return $this->httpClient->delete('shipments/' . $trackingNumber);
    }

    /**
     * Kargo gönderisi bilgilerini günceller
     *
     * @param string $trackingNumber Takip numarası
     * @param array $data Güncellenecek veriler
     * @return array Güncelleme yanıtı
     * @throws ValidationException|ApiException
     */
    public function update(string $trackingNumber, array $data): array
    {
        if (empty($trackingNumber)) {
            throw new ValidationException('Takip numarası gereklidir.');
        }
        
        return $this->httpClient->put('shipments/' . $trackingNumber, $data);
    }

    /**
     * Kargo gönderisi verilerini doğrular
     *
     * @param array $data Doğrulanacak veriler
     * @return bool Doğrulama sonucu
     * @throws ValidationException
     */
    private function validateShipmentData(array $data): bool
    {
        // Zorunlu alanlar
        $requiredFields = [
            'alici_adi',
            'adres',
            'telefon',
            'kargo_firmasi'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("'{$field}' alanı gereklidir.");
            }
        }
        
        return true;
    }
}