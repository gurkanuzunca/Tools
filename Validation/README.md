Validation Class
=====
Basit veri doğrulama sınıfı.

#### Kullanımı
Sınıf oluşturulurken verilerin ve kuralların atanması.
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

Sınıf oluşturulduktan sonra verilerin ve kuralların atanması.
```php
$validation = new Validate();

// Veriler key->value şeklinde olmalıdır.
$validation->setData($_POST);

$validation->setRule('name', 'required', 'Lütfen adınızı ve soyadınızı yazın.');
$validation->setRule('name', 'minLength', array(
    'value' => 3,
    'message' => 'Ad Soyad alanına çok kısa bir deger girdiniz.'
));

// Tüm kurallar tek seferde de atanabilir.
$validation->setRule(array(
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

Sınıf oluşturulduktan sonra verilerin farklı bir dosyadan atanması.
```php
$validation = new Validate();

// Dosya yolu belirtilir. Dosya php olmalıdır ve değer döndürmelidir. Uzantı yazılmasına herek yoktur.
$validation->loadRulesFile('forms/rule');

if ($validation->valid()) {
    //
}
```
forms/rule.php
```php
return array(
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
)
```

#### Mesajlar
```php
// Alan için tüm hataları dizi olarak döndürür.
$error = $validate->getError('name');
print_r($error);

// Tüm hataları dizi olarak döndürür.
$error = $validate->getAllError('name');
print_r($error);

// Alan için hata olup olmadığını kontrol eder.
if ($validate->hasError('name')) {
    //
}

// Alan için her hatayı div tagı arasında string olarak döndürür.
echo $validation->getErrorHtml('name');

// Açılış ve kapanıs tagları belirtilirse her hatayı belirtilen tag içinde döndürür.
echo $validation->getErrorHtml('name', '<li>', '</li>');

// Bütün hataların her birini div tagı arasında string olarak döndürür.
echo $validation->getAllErrorHtml('name');

// Açılış ve kapanıs tagları belirtilirse bütün hataların her birini belirtilen tag içinde döndürür.
echo $validation->getAllErrorHtml('name', '<li>', '</li>');
```

#### Kurallar
Kurallar içerisinde "required" kullanılmadıysa belirtilen alan değer taşımadığında sonraki kurallar işleme konmaz.
Alanda değer var ise kurallar işleme konur.
```php
array(
    'name' => array(
        'alpha' => 'Sadece harf girilebilir.',
        'alphaNumeric' => 'Sadece harf ve rakam girilebilir.',
        'minLength' => array(
            'value' => 3,
            'message' => 'En az az 3 karakter girilebilir.'
        ),
        'maxLength' => array(
            'value' => 20,
            'message' => 'En fazla 20 karakter girilebilir.'
        ),
        'betweenLength' => array(
            'value' => array('min' => 3, 'max' => 20),
            'message' => 'En az 3 en fazla 20 karakter girilebilir.'
        ),
        'minNumber' => array(
            'value' => 10,
            'message' => '10 dan daha küçük rakam girilemez.'
        ),
        'maxNumber' => array(
            'value' => 10,
            'message' => '10 dan daha büyük bir rakam girilemez.'
        ),
        'betweenNumber' => array(
            'value' => array('min' => 3, 'max' => 20),
            'message' => '3 ile 20 arasında bir rakam girilebilir'
        ),
        'required' => 'Zorunlu alan. Bir değer girilmelidir.',
        'email' => 'Geçerli bir email adresi girilmelidir.',
        'url' => 'Geçerli bir url girilmelidir.'
    )
)
```