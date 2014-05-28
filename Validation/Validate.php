<?php


class Validate
{
    private $data = array();
    private $rules = array();
    private $errors = array();

    private $isValidated = false;


    /**
     * Validate nesnesi kulları ve verileri atanır.
     *
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data = array(), array $rules = array())
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Kurallanı ayrı dosyadan yükler.
     *
     * @param $path
     */
    public function loadRulesFile($path)
    {
        $this->rules = include($path . '.php');
    }


    /**
     * Validate doğrulama kontrolünü yapar.
     *
     * @return bool
     */
    public function valid()
    {
        if (! $this->isValidated) {

            $this->parseRules();

            foreach ($this->rules as $name => $rules) {
                $this->checkRequired($name);

                if (ValidateRule::required($this->getValue($name))) {
                    foreach ($this->getRules($name) as $rule => $parameters ) {
                        $this->validate($name, $rule, $parameters);
                    }
                }
            }
        }

        return count($this->errors) > 0 ? false : true;
    }


    private function parseRules()
    {
        foreach ($this->rules as &$rules) {
            foreach ($rules as &$parameters ) {
                if (is_string($parameters)) {
                    $parameters = array('message' => $parameters);
                }
            }
        }
    }

    /**
     * Kurala göre değeri doğrulamaya çalışır.
     *
     * @param string $name
     * @param string $rule
     * @param array $parameters
     * @return bool
     */
    private function validate($name, $rule, array $parameters)
    {
        if (! ValidateRule::$rule($this->getValue($name), $parameters)) {
            $this->addError($name, $parameters);

            return false;
        }
        return true;
    }

    /**
     * Zorunluluk kontrolü yapar. Zorunluluk varsa hata kayıt eder diğer kurallar çalıştırılmaz.
     *
     * @param string $name
     */
    private function checkRequired($name)
    {
        $rules = $this->getRules($name);

        if (! array_key_exists('required', $rules)) {
            return;
        }

        if (! $this->validate($name, 'required', $rules['required'])) {
            $this->rules[$name] = array();
        }

    }

    /**
     * Belirtilen alanın kurallarını döndürür.
     *
     * @param $name
     * @return array
     */
    private function getRules($name)
    {
        return isset($this->rules[$name]) ? $this->rules[$name] : array();
    }

    /**
     * Belirtilen alana yeni kural atar.
     *
     * @param array|string $name
     * @param $rule
     * @param $parameters
     */
    public function setRule($name, $rule, $parameters)
    {
        if (is_array($name)) {
            $this->rules = array_merge($this->rules, $name);
        } else {
            $this->rules[$name][$rule] = $parameters;
        }

    }

    /**
     * Belirtilen alanın veri değerini döndürür.
     *
     * @param $name
     * @return null
     */
    public function getValue($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Belirtilen alana veri değeri atar.
     *
     * @param array|string $name
     * @param mixed $value
     */
    public function setValue($name, $value = null)
    {
        if (is_array($name)) {
            $this->data = array_merge($this->data, $name);
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * Belirtilen alana hata mesajı kayıt eder.
     *
     * @param $name
     * @param array $parameters
     */
    private function addError($name, array $parameters)
    {
        $message = "'$name' alanı doğrulanmadı.";

        if (isset($parameters['message'])) {
            $message = $parameters['message'];
        }
        $this->errors[$name][] = $message;
    }

    /**
     * Belirtilen alanın hata mesajlarını döndürür.
     *
     * @param $name
     * @return null
     */
    public function getError($name)
    {
        return isset($this->errors[$name]) ? $this->errors[$name] : array();
    }

    /**
     * Belirtilen alanın hatasının varlığını kontrol eder.
     *
     * @param $name
     * @return bool
     */
    public function hasError($name)
    {
        return isset($this->errors[$name]) ? true : false;
    }

    /**
     * Belirtilen alanın hata mesajlarını html tagları arasında göndürür.
     *
     * @param $name
     * @param string $tagOpen
     * @param string $tagClose
     * @return string
     */
    public function getErrorHtml($name, $tagOpen = '<div>', $tagClose = '</div>')
    {
        $html = '';

        foreach ($this->getError($name) as $message){
            $html .= $tagOpen . $message . $tagClose;
        }
        return $html;
    }

    /**
     * Tüm hataları döndürür.
     *
     * @return array
     */
    public function getAllError()
    {
        return $this->errors;
    }

    /**
     * Tüm hataları html tagları arasında döndürür.
     *
     * @param string $tagOpen
     * @param string $tagClose
     * @return string
     */
    public function getAllErrorHtml($tagOpen = '<div>', $tagClose = '</div>')
    {
        $html = '';

        foreach ($this->getAllError() as $errors){
            foreach ($errors as $message) {
                $html .= $tagOpen . $message . $tagClose;
            }
        }
        return $html;
    }
} 