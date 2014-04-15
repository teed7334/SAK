<?php
class Encryption {

    protected $debug = false;
    protected $key = '';

    public function debug($debug = false) {
        $this->debug = $debug;
    }

    public function addKey($key = '') {
        $this->key = $key;
    }

    public function encode($plain_code = '') {
        
        try {

            $string = json_encode($plain_code);

            if(trim($string) == '') {
                throw new Exception("Can't use json encode '{$plain_code}'");
            }

            $string = base64_encode($string);

            if(trim($string) == '') {
                throw new Exception("Can't use base64 encode '{$plain_code}'");
            }

            return $string;

        } catch(Exception $e) {
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('from' => $from, 'to' => $to)) : false;
        }

    }

    public function decode($code_signal = '') {

        try {

            $string = base64_decode($code_signal);

            if(trim($string) == '') {
                throw new Exception("Can't use base64 decode '{$code_signal}'");
            }

            $string = json_decode($string, true);

            if(!$string) {
                throw new Exception("Can't use json decode '{$code_signal}'");
            }

            return $string;

        } catch(Exception $e) {
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('from' => $from, 'to' => $to)) : false;
        }

    }

    public function token($plain_code = '') {
        return md5(sha1(date('Ymd') . $plain_code . $this->key));
    }
}
?>
