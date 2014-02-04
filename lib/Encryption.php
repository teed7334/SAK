<?php
class Encryption {

    protected $debug = false;
    
    protected $delimiter = '.';
    protected $hash = array(
            array(1, 17, 13, 25, 44, 6, 33, 12),
            array(43, 58, 49, 17, 28, 47, 31, 45),
            array(14, 38, 30, 21, 50, 64, 56, 35),
            array(34, 54, 55, 51, 32, 40, 62, 16),
            array(9, 61, 37, 42, 57, 59, 53, 20),
            array(5, 60, 63, 3, 48, 29, 39, 8),
            array(26, 41, 22, 27, 18, 4, 52, 23),
            array(11, 19, 36, 24, 46, 7, 15, 2)
        );

    protected $parant_length = 0;
    protected $length = 0;
    protected $index = 0;
    protected $use = array();

    public function __construct() {
        $this->parent_length = count($this->hash);
        $this->index = ((int)date('Y') + (int)date('m') + (int)date('d')) % (int)$this->parent_length;
        $this->use = $this->hash[$this->index];
        $this->length = count($this->use);
    }

    public function debug($debug = false) {
        $this->debug = $debug;
    }

    public function setDelimiter($delimiter = '.') {
        $this->delimiter = $delimiter;
    }

    public function setHash($hash = array()) {

        try {

            $this->hash = $hash;
            $this->parent_length = count($this->hash);
            $this->index = ((int)date('Y') + (int)date('m') + (int)date('d')) % (int)$this->parent_length;
            $this->use = $this->hash[$this->index];
            $this->length = count($this->use);   

        } catch(Exception $e) {

        }

    } 

    public function encode_once($string = '') {

        try {

            return md5(sha1($this->encode($string)));

        } catch(Exception $e) {

        }

    }

    public function encode($string = '') {

        try {

            $string = base64_encode($string);
            $arr = str_split($string);

            $string = '';
            foreach($arr as $char) {
                $key = ord($char) % $this->length;
                $value = floor(ord($char) / $this->length);

                if($string == '') {
                    $string .= $this->use[$key] . $this->delimiter . $value;
                } else {
                    $string .= $this->delimiter . $this->use[$key] . $this->delimiter . $value;
                }
            }

            $string = gzdeflate($string, 9);
            $string = base64_encode($string);

            return $string;

        } catch(Exception $e) {

        }

    }

    public function decode($string = '') {

        try {

            $str = $string;
            $string = base64_decode($string);

            if(trim($string) == '') {
                return $this->debug ? array('message' => "error string {$str}(1)", 'status' => false, 'value' => array('string' => $str)) : false;
            }

            $string = @gzinflate($string);

            if(trim($string) == '') {
                return $this->debug ? array('message' => "can't uncompress {$str}", 'status' => false, 'value' => array('string' => $str)) : false;
            }            
        
            $arr = explode($this->delimiter, $string);
            $count = count($arr);

            if($count == 0) {
                return $this->debug ? array('message' => "error string {$str}(2)", 'status' => false, 'value' => array('string' => $str)) : false;
            }

            $string = '';
            $ascii = 0;
            for($i = 0; $i < $count; $i++) {

                if($i % 2 == 0) {
                    for($j = 0; $j < $this->length; $j++) {
                        if($this->use[$j] == $arr[$i]) {
                            $ascii = $j;
                        }
                    }
                } else {
                    $ascii = $arr[$i] * $this->length + $ascii; 
                }

                if($ascii > $this->length) {
                    $string .= chr($ascii);
                    $ascii = 0;
                }
            }

            $string = base64_decode($string);

            if(trim($string) == '') {
                return $this->debug ? array('message' => "error string {$str}(3)", 'status' => false, 'value' => array('string' => $str)) : false;
            }

            return $string;

        } catch(Exception $e) {

        }

    }

}
?>