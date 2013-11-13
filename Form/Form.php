<?php

class Form 
{
    
    private $base = '../';
    private $posts = array();
    private $rules = array();
    private $vars = array('fromName', 'fromMail', 'to', 'reply', 'subject');
    private $fromName;
    private $fromMail;
    private $to;
    private $reply;
    private $subject;
    private $template;
    private $errors = array();
    private $success;

    
    function __construct()
    {
        session_start();
        
        if (count($_POST) > 0) {
            $this->posts = $_POST;
        }
    }
    

    /**
     * Doğrulama kurallarını tanımlar.
     * 
     * @param string $name Form elemanı ismi.
     * @param string $message Gösterilecek hata mesajı.
     * @param string $validates email,phone
     * @return \Form
     */
    public function setRule($name, $message, $validates = null)
    {
        if (!is_null($validates)) {
            $validates = explode(',', $validates);

            foreach ($validates as &$validate) {
                $validate = trim($validate);
            }
        }

        $this->rules[$name] = array('name' => $name, 'message' => $message, 'validates' => $validates);

        return $this;
    }

    /**
     * Gönderen bilgilerini tanımlar.
     * 
     * @param string $name Gönderen ismi.
     * @param string $mail
     * @return \Form
     */
    public function from($name, $mail)
    {
        $this->fromName = $name;
        $this->fromMail = $mail;
        return $this;
    }

    /**
     * Alıcı bilgilerini tanımlar.
     * 
     * @param string $mail
     * @return \Form
     */
    public function to($mail)
    {
        $this->to = $mail;
        return $this;
    }

    /**
     * Yanıtlama adresi bilgisini tanımlar.
     * 
     * @param string $mail
     * @return \Form
     */
    public function reply($mail)
    {
        $this->reply = $mail;
        return $this;
    }

    /**
     * Mail konusunu tanımlar.
     * 
     * @param string $string
     * @return \Form
     */
    public function subject($string)
    {
        $this->subject = $string;
        return $this;
    }

    /**
     * Mail şablonu dosyasını tanımlar.
     * 
     * @param string $file
     * @return \Form
     */
    public function template($file)
    {
        $file = $this->base . $file .'.php';
        
        if (! file_exists($file)) {
            $this->error('Mail şablonu bulunamadı.');
        }
        
        $this->template = $file;
        return $this;
    }
    
    /**
     * Form doğrulamasını yaparak mail olarak gönderir.
     * 
     * @return boolean
     */
    public function send($success, $error)
    {
        if (count($this->posts) === 0) {
            return false;
        }
        
        $this->checkRules();
        $this->checkFormValues();
        
        $_SESSION['ishuman'] = null;
        
        if (count($this->errors) == 0) {
            require_once 'PHPMailer.php';
            
            $mailer = new PHPMailer;
            $mailer->From       = $this->fromMail;
            $mailer->FromName   = $this->fromName;
            $mailer->WordWrap   = 50;
            $mailer->CharSet    = 'utf-8';
            $mailer->Subject    = $this->subject;
            $mailer->Body       = $this->loadTemplate();
            $mailer->IsHTML(true);  
            $mailer->AddAddress($this->to);
            $mailer->AddReplyTo($this->reply);

            if ($mailer->Send()) {
                $this->success = $success;
                $this->posts = array();
            } else {
                $this->error($error);
            }
        }


    }
    
    /**
     * Hata mesajlarını döndürür.
     * 
     * @param string $open
     * @param string $close
     * @return string
     */
    public function showAlert($open = '<div>', $close = '</div>')
    {
        $output = '';
        
        if (count($this->errors) > 0) {
            foreach ($this->errors as $error) {
                $output .= $open . $error . $close;
            }
        } else {
            $output = $this->success;
        }
        
        return $output;
    }
    
    /**
     * Herhangi bir mesajın olup olmadığını döndürür.
     * 
     * @return boolean
     */
    public function isAlert()
    {
        return (count($this->errors) > 0 || ! empty($this->success));
    }
	
	/**
     * Hatanın olup olmadığını döndürür.
     * 
     * @return boolean
     */
    public function isError()
    {
        return count($this->errors) > 0 ? true : false;
    }
	
	/**
     * İşlemin başarılı olup olmadığını döndürür.
     * 
     * @return boolean
     */
    public function isSuccess()
    {
        return ! empty($this->success) ? true : false;
    }
    
    /**
     * Doğrulama işlemleri
     * 
     * @param string $type number,total,diff
     * @return int
     */
    public function isHuman($type)
    {
        if (empty($_SESSION['ishuman'])) {
            $number = rand(1,10);
            $total = rand(1,10) + $number;
            $_SESSION['ishuman'] = array('number' => $number, 'total' => $total, 'diff' => $total - $number);
        }
        
        
        return $_SESSION['ishuman'][$type];
    }

        /**
     * Hata mesajı ekler.
     * 
     * @param string $message
     */
    private function error($message, $fatal = false)
    {
        if ($fatal === true) {
            die($message);
        }
        $this->errors[] = $message;
    }
    
    public function loadTemplate()
    {
        extract($this->posts);

        ob_start();
        include($this->template);
        $buffer = ob_get_contents();
        @ob_end_clean();

        return $buffer;
    }
    
    /**
     * Form elemanının derğerini döndürür.
     * 
     * @param string $name
     * @return boolean
     */
    public function post($name)
    {
        if (! empty($this->posts[$name])) {
            return $this->posts[$name];
        }
        return false;
    }
    
    /**
     * Html kodlarını ve \n kodlarını replace eder.
     * 
     * @param string $string
     * @return string
     */
    public function htmlcode($string)
    {
        return nl2br(htmlspecialchars($string));
    }

    /**
     * Form doğrulama kontrollerini yapar.
     */
    private function checkRules()
    {
        foreach ($this->rules as $rule) {
            if (! $this->post($rule['name'])) {
                $this->error($rule['message']);
            } elseif (is_array($rule['validates'])) {
                foreach ($rule['validates'] as $validate) {
                    if (! $this->check($this->post($rule['name']), $validate)) {
                        $this->error($rule['message']);
                    }
                }
            }
        }
    }
    
    
    private function checkFormValues()
    {
        if (count($this->errors) > 0) {
            return false;
        }
        
        foreach ($this->vars as $var) {
            if (substr($this->$var, 0, 1) == '@') {
                $this->$var = $this->post(trim($this->$var, '@'));
            }
        }
            
        if (! $this->check($this->fromMail, 'email')) {
            $this->error('Gönderen e-mail adresi hatalı.', true);
        }

        if (empty($this->fromName)) {
            $this->error('Gönderen ismi boş.', true);
        }

        if (! $this->check($this->to, 'email')) {
            $this->error('Alıcı e-mail adresi hatalı.', true);
        }

        if (! $this->check($this->reply, 'email')) {
            $this->error('Yanıtlama e-mail adresi hatalı.', true);
        }

        if (empty($this->subject)) {
            $this->error('Mail konusu boş.', true);
        }

        if (empty($this->template)) {
            $this->error('Mail şablonu yolu boş.', true);
        }
    }
    
    
    

    /**
     * Verilerin kontrolunu yapar.
     * 
     * @param string $string
     * @param string $type
     * @return boolean
     */
    private function check($string, $type)
    {
        switch ($type) {
            case 'ishuman':
                return $this->post('ishuman') == $this->isHuman('diff');
                
            default:
                $patterns['email'] = '/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/';

                if (preg_match($patterns[$type], $string)) {
                    return true;
                }
        }
        
        return false;
    }

    
    
    
    
}
