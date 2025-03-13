# TryOto Kargo API SDK (PHP)

Bu SDK, [TryOto Kargo API](https://apis.tryoto.com/) ile entegrasyon sağlayan PHP tabanlı bir kütüphanedir. CodeIgniter 4 ile tam uyumlu olmasının yanı sıra, diğer PHP projelerinde de kullanılabilir.

## Özellikler

- Kargo gönderisi oluşturma
- Kargo takibi yapma
- Kargo iptali
- Fiyat hesaplama
- Desteklenen kargo firmalarını listeleme
- Webhook desteği
- Barkod ve etiket üretme (PDF/PNG)
- JSON ve Array desteği
- Composer ile kolay kurulum
- Detaylı Türkçe dokümantasyon

## Kurulum

Composer kullanarak TryOto SDK'yı projenize ekleyin:

```bash
composer require mistikdiyebiri/tryoto-sdk
```

## Kullanım

### SDK'yı Başlatma

```php
use TryOto\SDK\TryOtoClient;

// API anahtarı ile SDK'yı başlatın
$tryoto = new TryOtoClient('API_KEY_BURAYA');

// İsteğe bağlı yapılandırma ile başlatma
$tryoto = new TryOtoClient('API_KEY_BURAYA', [
    'base_url' => 'https://apis.tryoto.com/',
    'timeout' => 30,
    'debug' => false
]);
```

### Kargo Gönderisi Oluşturma

```php
try {
    $response = $tryoto->createShipment([
        'alici_adi' => 'Mehmet Yılmaz',
        'adres' => 'İstanbul, Beşiktaş No:123 D:5',
        'telefon' => '5551234567',
        'urun_detay' => 'Laptop',
        'kargo_firmasi' => 'yurtici'
    ]);
    
    echo "Takip numarası: " . $response['tracking_number'];
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Kargo Takibi

```php
try {
    $trackingInfo = $tryoto->trackShipment('123456789');
    
    echo "Kargo durumu: " . $trackingInfo['status'];
    echo "Güncel konum: " . $trackingInfo['current_location'];
    // Diğer bilgiler...
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Kargo İptali

```php
try {
    $response = $tryoto->cancelShipment('123456789');
    
    if ($response['success']) {
        echo "Kargo başarıyla iptal edildi.";
    }
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Kargo Fiyatı Hesaplama

```php
try {
    $price = $tryoto->calculatePrice([
        'kargo_firmasi' => 'yurtici',
        'agirlik' => 2.5, // kg
        'boyut' => [
            'en' => 30, // cm
            'boy' => 20, // cm
            'yukseklik' => 10 // cm
        ],
        'gonderen_il' => 'İstanbul',
        'alici_il' => 'Ankara'
    ]);
    
    echo "Hesaplanan fiyat: " . $price['price'] . " TL";
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Desteklenen Kargo Firmalarını Listeleme

```php
try {
    $couriers = $tryoto->listCouriers();
    
    foreach ($couriers as $courier) {
        echo "Kargo firması: " . $courier['name'] . "\n";
        echo "Kod: " . $courier['code'] . "\n";
        echo "Durum: " . ($courier['active'] ? 'Aktif' : 'Pasif') . "\n";
        echo "----------------------------\n";
    }
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Barkod ve Etiket Üretme

```php
try {
    // PDF formatında etiket üretme
    $pdfContent = $tryoto->generateBarcode('123456789', 'pdf');
    