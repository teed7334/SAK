<?php
class Encryption {

    protected $debug = false;

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

    protected function innateness($value = 0) {

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
        
        $top = ($this->y + $this->m + $this->d) % 8;
        $bottom = ($this->y + $this->m + $this->d + $this->h) % 8;

        $this->original_top = $top;
        $this->original_bottom = $bottom;

        $this->original = $top . $bottom;

        return array('top' => md5($this->innateness($top)), 'bottom' => md5($this->innateness($bottom)));
    }

    public function calc_change() {

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
    }

    public function inreversible_encode($string = '') {
    
        $string = $this->encode($string);
        $string = $string . PRIVATE_KEY;
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
    }

    public function encode($string = '') {

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
    }

    public function decode($string = '') {
        
        $string = urldecode($string);
        $string = base64_decode($string);

        $change = $this->calc_change();

        $string_length = strlen($string);
        $top_length = strlen($change['top']);
        $bottom_length = strlen($change['bottom']);

        if($string_length < ($top_length + $bottom_length)) {
            return $this->debug ? array('message' => "string {$string} can't decode" , 'status' => false, 'value' => $string) : false;
        }

        $string = substr($string, $top_length);
        $string_length = strlen($string);
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
            return $this->debug ? array('message' => "string {$string} can't decode" , 'status' => false, 'value' => $string) : false;
        }

        $string = substr($string, $top_length);
        $string_length = strlen($string);
        $string = substr($string, 0, $string_length - $bottom_length);

        $string = base64_decode($string);
        $string = json_decode($string);

        return $string;
    }

}
?>