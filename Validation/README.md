Validation Class
=====
Basit veri doğrulama sınıfı.

#### Kullanımı
SInıf oluşturulurken verilerin ve kuralların atanması.
```php
$validation = new Validate($_POST, array(
    'name' => array(
        'required' => 'Lütfen adınızı ve soyadınızı yazın.',
        'minLength' => array(
            'value' => 3,
            'message' => 'Ad Soyad alanına çok kısa bir deger girdiniz.'
        )
    ),
    'mail' => array(
        'required' => 'Lütfen e-posta adresinizi yazın.',
        'email' => 'Lütfen geçerli bir e-posta adresinizi yazın.'
    ),
    'comment' => array(
        'minLength' => array(
            'value' => 3,
            'message' => 'Yorumunuz çok kısa.'
        )
    )
));

if ($validation->valid()) {
    //
}
```