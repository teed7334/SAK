<?php
class Encryption {

    public function encode_once($string = '', $key = '') {
        return md5(sha1($string . $key . date('Y-m-d')));
    }

    public function encode($string = '') {
        $string = json_encode($string);
        $string = base64_encode($string);

        return $string;
    }

    public function decode($string = '') {
        $string = base64_decode($string);
        $string = json_decode($string);
        
        return $string;
    }

}
?>