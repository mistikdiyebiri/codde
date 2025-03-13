<?php

namespace TryOto\SDK\Services;

use GuzzleHttp\Client;
use TryOto\SDK\Config\TryOtoConfig;
use TryOto\SDK\Exception\ValidationException;
use TryOto\SDK\Exception\ApiException;
use TryOto\SDK\Http\HttpClient;

/**
 * Kargo Takip Servisi
 * 
 * Kargo takibi işlemlerini yönetir
 */
class TrackingService
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
     * TrackingService yapıcı
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
     * Kargo takibi yapar
     *
     * @param string $trackingNumber Takip numarası
     * @return array Takip yanıtı
     * @throws ValidationException|ApiException
     */
    public function track(string $trackingNumber): array
    {
        if (empty($trackingNumber)) {
            throw new ValidationException('Takip numarası gereklidir.');
        }
        
        return $this->httpClient->get('tracking/' . $trackingNumber);
    }

    /**
     * Birden fazla kargoyu aynı anda takip eder
     *
     * @param array $trackingNumbers Takip numaraları
     * @return array Takip yanıtları
     * @throws ValidationException|ApiException
     */
    public function multiTrack(array $trackingNumbers): array
    {
        if (empty($trackingNumbers)) {
            throw new ValidationException('En az bir takip numarası gereklidir.');
        }
        
        return $this->httpClient->post('tracking/batch', [
            'tracking_numbers' => $trackingNumbers
        ]);
    }

    /**
     * Tarih aralığına göre kargo takibi yapar
     *
     * @param string $startDate Başlangıç tarihi (Y-m-d)
     * @param string $endDate Bitiş tarihi (Y-m-d)
     * @param int $page Sayfa numarası
     * @param int $limit Sayfa başına kayıt sayısı
     * @return array Takip yanıtları
     * @throws ValidationException|ApiException
     */
    public function trackByDateRange(string $startDate, string $endDate, int $page = 1, int $limit = 50): array
    {
        if (empty($startDate) || empty($endDate)) {
            throw new ValidationException('Başlangıç ve bitiş tarihleri gereklidir.');
        }
        
        return $this->httpClient->get('tracking/date-range', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'page' => $page,
            'limit' => $limit
        ]);
    }
}