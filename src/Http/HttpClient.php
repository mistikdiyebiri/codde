<?php

namespace TryOto\SDK\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use TryOto\SDK\Exception\ApiException;
use TryOto\SDK\Config\TryOtoConfig;
use TryOto\SDK\Helpers\ResponseHelper;

/**
 * HTTP İstemci Sınıfı
 * 
 * API çağrıları için HTTP isteklerini yönetir
 */
class HttpClient
{
    /**
     * Guzzle HTTP istemcisi
     *
     * @var \GuzzleHttp\Client
     */
    private $client;
    
    /**
     * Yapılandırma
     *
     * @var \TryOto\SDK\Config\TryOtoConfig
     */
    private $config;

    /**
     * HttpClient yapıcı
     *
     * @param Client $client Guzzle istemcisi
     * @param TryOtoConfig $config Yapılandırma
     */
    public function __construct(Client $client, TryOtoConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * GET isteği gönderir
     *
     * @param string $endpoint API endpoint'i
     * @param array $params Sorgu parametreleri
     * @return array API yanıtı
     * @throws ApiException
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $params]);
    }

    /**
     * POST isteği gönderir
     *
     * @param string $endpoint API endpoint'i
     * @param array $data Gövde verileri
     * @return array API yanıtı
     * @throws ApiException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * PUT isteği gönderir
     *
     * @param string $endpoint API endpoint'i
     * @param array $data Gövde verileri
     * @return array API yanıtı
     * @throws ApiException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * DELETE isteği gönderir
     *
     * @param string $endpoint API endpoint'i
     * @return array API yanıtı
     * @throws ApiException
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * HTTP isteği gönderir
     *
     * @param string $method HTTP metodu
     * @param string $endpoint API endpoint'i
     * @param array $options Guzzle istek seçenekleri
     * @return array API yanıtı
     * @throws ApiException
     */
    private function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $endpoint = ltrim($endpoint, '/');
            
            // Debug modu açıksa seçeneklere debug parametresi eklenir
            if ($this->config->getDebug()) {
                $options['debug'] = true;
            }
            
            // SSL doğrulama ayarı
            $options['verify'] = $this->config->getVerifySsl();
            
            // İstek gönderimi
            $response = $this->client->request($method, $endpoint, $options);
            
            // Yanıtı işle
            return ResponseHelper::parseResponse($response);
        } catch (GuzzleException $e) {
            throw new ApiException('API isteği başarısız: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}