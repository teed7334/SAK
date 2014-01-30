<?php
class Encryption {

    protected $use = 'innateness';
    protected $key = '';

    public $debug = false;

    protected $y = 0;
    protected $m = 0;
    protected $d = 0;
    protected $h = 0;

    protected $original = '';
    protected $original_top = '';
    protected $original_bottom = '';

    protected $change = '';
    protected $change_top = '';
    protected $change_bottom = '';

    public function __construct() {

        $this->y = (int)date('Y');
        $this->m = (int)date('m');
        $this->d = (int)date('d');
        $this->h = ($this->y + $this->m + $this->d) % 12;

    }

    public function import_key($key) {

        try {

            $this->use = 'postnatal';
            $this->key = md5(sha1($key));

        } catch(Exception $e) {

        }

    }

    protected function innateness($value = 0) {

        try {

            $val = false;

            if((int)$value == 0) {
                $val = $this->qian();
            } elseif((int)$value == 1) {
                $val = $this->dui();
            } elseif((int)$value == 2) {
                $val = $this->li();
            } elseif((int)$value == 3) {
                $val = $this->zhen();
            } elseif((int)$value == 4) {
                $val = $this->xun();
            } elseif((int)$value == 5) {
                $val = $this->kan();
            } elseif((int)$value == 6) {
                $val = $this->gen();
            } else {
                $val = $this->kun();
            }

            return $val;

        } catch(Exception $e) {

        }

    }

    protected function postnatal($value = 0) {

        try {

            $val = false;

            if((int)$value == 0) {
                $val = $this->zhen();
            } elseif((int)$value == 1) {
                $val = $this->xun();
            } elseif((int)$value == 2) {
                $val = $this->li();
            } elseif((int)$value == 3) {
                $val = $this->kun();
            } elseif((int)$value == 4) {
                $val = $this->dui();
            } elseif((int)$value == 5) {
                $val = $this->qian();
            } elseif((int)$value == 6) {
                $val = $this->kan();
            } else {
                $val = $this->gen();
            }

            return $val;

        } catch(Exception $e) {

        }

    }
        
    protected function qian() {
        return '111';
    }

    protected function dui() {
        return '011';
    }

    protected function li() {
        return '101';
    }

    protected function zhen() {
        return '001';
    }

    protected function xun() {
        return '110';
    }

    protected function kan() {
        return '010';
    }

    protected function gen() {
        return '100';
    }

    protected function kun() {
        return '000';
    }

    protected function calc_original() {

        try {
        
            $top = ($this->y + $this->m + $this->d) % 8;
            $bottom = ($this->y + $this->m + $this->d + $this->h) % 8;

            $this->original_top = $top;
            $this->original_bottom = $bottom;

            $this->original = $top . $bottom;

            return $this->use == 'innateness' ? array('top' => md5($this->innateness($top)), 'bottom' => md5($this->innateness($bottom))) : array('top' => md5($this->postnatal($top)), 'bottom' => md5($this->postnatal($bottom)));

        } catch(Exception $e) {

        }

    }

    public function calc_change() {

        try {

            $this->change = '';

            $postition = ($this->y + $this->m + $this->d + $this->h) % 6;
            $tmp = str_split($this->original);

            foreach($tmp as $items) {
                $this->change .= $this->innateness($items);
            }

            $tmp = str_split($this->change);

            if((int)$tmp[$postition] == 0) {
                $tmp[$postition] = '1';
            } else {
                $tmp[$postition] = '0';
            }

            $top = '';
            $bottom = '';
            $this->change = $postition;

            for($i = 0; $i <= 5; $i++) {
                if($i <=2 ) {
                    $top .= $tmp[$i];
                } else {
                    $bottom .= $tmp[$i];
                }
            }

            $this->change_top = $top;
            $this->change_bottom = $bottom;

            return array('top' => md5($top), 'bottom' => md5($bottom));

        } catch(Exception $e) {

        }

    }

    public function inreversible_encode($string = '', $private_key = '') {

        try{
    
            $string = $this->encode($string);
            $string = $string . $private_key;
            $change = $this->change_top . $this->change_bottom;
            $change = str_split($change);

            foreach($change as $items) {
                if((int)$items == 0) {
                    $string = sha1($string);
                } else {
                    $string = md5($string);
                }
            }

            return $string;

        } catch(Exception $e) {

        }

    }

    protected function innateness_encode($string = '') {

        try {

            $string = base64_encode(json_encode($string));
            $original = $this->calc_original();
            $string = $original['top'] . $string . $original['bottom'];
            $string = urlencode($string);

            $num = ceil(strlen($string) / 2);
            $string = substr($string, $num) . substr($string, 0, $num);
            $string = base64_encode($string);
            
            $change = $this->calc_change();
            $string = $change['top'] . $string . $change['bottom'];

            $string = base64_encode($string);

            $string = urlencode($string);

            return $string;

        } catch(Exception $e) {

        }

    }

    protected function innateness_decode($string = '') {

        try {

            $value = $string;
            
            $string = urldecode($string);
            $string = base64_decode($string);

            $change = $this->calc_change();

            $string_length = strlen($string);
            $top_length = strlen($change['top']);
            $bottom_length = strlen($change['bottom']);

            if($string_length < ($top_length + $bottom_length)) {
                return $this->debug ? array('message' => "string {$value} length error(1)" , 'status' => false, 'value' => $value) : false;
            }

            $tmp = explode($change['top'], $string);
            if(count($tmp) < 2 || !isset($tmp[0]) || $tmp[0] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(1)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, $top_length);
            $string_length = strlen($string);

            $tmp = explode($change['bottom'], $string);
            if(count($tmp) < 2 || !isset($tmp[1]) || $tmp[1] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(2)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, 0, $string_length - $bottom_length);

            $string = base64_decode($string);
            $num = ceil(strlen($string) / 2);
            $string = substr($string, $num) . substr($string, 0, $num);

            $string = urldecode($string);

            $original = $this->calc_original();

            $string_length = strlen($string);
            $top_length = strlen($original['top']);
            $bottom_length = strlen($original['bottom']);

            if($string_length < ($top_length + $bottom_length)) {
                return $this->debug ? array('message' => "string {$value} length error(2)" , 'status' => false, 'value' => $value) : false;
            }

            $tmp = explode($original['top'], $string);
            if(count($tmp) < 2 || !isset($tmp[0]) || $tmp[0] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(3)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, $top_length);
            $string_length = strlen($string);

            $tmp = explode($original['bottom'], $string);
            if(count($tmp) < 2 || !isset($tmp[1]) || $tmp[1] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(4)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, 0, $string_length - $bottom_length);

            $string = base64_decode($string);
            $string = json_decode($string);

            return $string;

        } catch(Exception $e) {

        }
    }

    protected function postnatal_encode($string = '') {

        try {

            $string = base64_encode(json_encode($string));
            $original = $this->calc_original();
            $string = $original['top'] . $string . $original['bottom'];
            $string = urlencode($string);

            $num = ceil(strlen($string) / 2);
            $string = substr($string, $num) . substr($string, 0, $num) . $this->key;
            $string = base64_encode($string);
            
            $change = $this->calc_change();
            $string = $string . $change['top'] . $change['bottom'];

            $string = base64_encode($string);

            $string = urlencode($string);

            return $string;

        } catch(Exception $e) {

        }

    }

    protected function postnatal_decode($string = '') {

        try {

            $value = $string;
            
            $string = urldecode($string);
            $string = base64_decode($string);

            $change = $this->calc_change();

            $string_length = strlen($string);
            $top_length = strlen($change['top']);
            $bottom_length = strlen($change['bottom']);

            if($string_length < ($top_length + $bottom_length)) {
                return $this->debug ? array('message' => "string {$value} length error(1)" , 'status' => false, 'value' => $value) : false;
            }

            $tmp = explode($change['top'], $string);
            if(count($tmp) < 2 || !isset($tmp[1]) || $tmp[1] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(1)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, 0, $string_length - $top_length - $bottom_length);

            $string = base64_decode($string);

            $tmp = explode($this->key, $string);
            if(count($tmp) < 2 || !isset($tmp[1]) || $tmp[1] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(2)" , 'status' => false, 'value' => $value) : false;
            }

            $string_length = strlen($string);
            $public_key_length = strlen($this->key);

            $string = substr($string, 0, $string_length - $public_key_length);

            $num = ceil(strlen($string) / 2);
            $string = substr($string, $num) . substr($string, 0, $num);

            $string = urldecode($string);

            $original = $this->calc_original();

            $string_length = strlen($string);
            $top_length = strlen($original['top']);
            $bottom_length = strlen($original['bottom']);

            if($string_length < ($top_length + $bottom_length)) {
                return $this->debug ? array('message' => "string {$value} length error(2)" , 'status' => false, 'value' => $value) : false;
            }

            $tmp = explode($original['top'], $string);
            if(count($tmp) < 2 || !isset($tmp[0]) || $tmp[0] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(3)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, $top_length);
            $string_length = strlen($string);

            $tmp = explode($original['bottom'], $string);
            if(count($tmp) < 2 || !isset($tmp[1]) || $tmp[1] != '') {
                return $this->debug ? array('message' => "string {$value} can't decode(4)" , 'status' => false, 'value' => $value) : false;
            }

            $string = substr($string, 0, $string_length - $bottom_length);

            $string = base64_decode($string);
            $string = json_decode($string);

            return $string;

        } catch(Exception $e) {

        }

    }

    public function encode($string = '') {

        try {
            return $this->use == 'innateness' ? $this->innateness_encode($string) : $this->postnatal_encode($string);
        } catch(Exception $e) {

        }
        
    }

    public function decode($string = '') {

        try {
            return $this->use == 'innateness' ? $this->innateness_decode($string) : $this->postnatal_decode($string);
        } catch(Exception $e) {

        }

    }

}
?>
