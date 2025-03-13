<?php

namespace TryOto\SDK\Config;

/**
 * TryOto SDK Yapılandırma Sınıfı
 */
class TryOtoConfig
{
    /**
     * Varsayılan yapılandırma değerleri
     *
     * @var array
     */
    private $defaults = [
        'base_url' => 'https://apis.tryoto.com/',
        'timeout' => 30,
        'verify_ssl' => true,
        'debug' => false,
        'version' => 'v1',
    ];

    /**
     * Mevcut yapılandırma değerleri
     *
     * @var array
     */
    private $config = [];

    /**
     * TryOtoConfig yapıcı
     *
     * @param array $config Kullanıcı tanımlı yapılandırma
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->defaults, $config);
    }

    /**
     * API temel URL'sini alır
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return rtrim($this->config['base_url'], '/') . '/';
    }

    /**
     * Zaman aşımı değerini alır
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return (int) $this->config['timeout'];
    }

    /**
     * SSL doğrulama durumunu alır
     *
     * @return bool
     */
    public function getVerifySsl(): bool
    {
        return (bool) $this->config['verify_ssl'];
    }

    /**
     * Debug modunu alır
     *
     * @return bool
     */
    public function getDebug(): bool
    {
        return (bool) $this->config['debug'];
    }

    /**
     * API sürümünü alır
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->config['version'];
    }

    /**
     * Belirtilen yapılandırma değerini alır
     *
     * @param string $key Anahtar
     * @param mixed $default Varsayılan değer
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Belirtilen yapılandırma değerini ayarlar
     *
     * @param string $key Anahtar
     * @param mixed $value Değer
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }
}