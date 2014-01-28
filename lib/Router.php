<?php
class Router {

	protected $uri = '';
    protected $debug = false;

    public function rule() {

        try {

            $_GET['controller'] = 'index';
            $_GET['action'] = 'index';

        	$uri = explode('?', $_SERVER['REQUEST_URI']);

            if(count($uri) == 1) {
                
            	$count = count($uri);

            	for($i = 0; $i < $count; $i++) {
                    if(trim($uri[$i]) != '') {
                		if($i == 1) {
                			$_GET['controller'] = (string) filter_var($uri[$i], FILTER_SANITIZE_STRING);
                		} elseif($i == 2) {
                			$_GET['action'] = (string) filter_var($uri[$i], FILTER_SANITIZE_STRING);
                		} elseif($i > 2) {
                			if($i % 2 != 0) {
                				$_GET[(string) filter_var($uri[$i], FILTER_SANITIZE_STRING)] = NULL;
                			} else {
                				$_GET[$uri[$i - 1]] = (string) filter_var($uri[$i], FILTER_SANITIZE_STRING);
                			}
                		}
                    }
            	}

            }

            $uri = explode('?', $_SERVER['REQUEST_URI']);
            $uri = explode('&', $uri[1]);

            foreach($uri as $items) {
                $get = explode('=', $items);
                if(trim($get[0]) != '' && trim($get[1]) != '') {
                    $_GET[(string) filter_var($get[0], FILTER_SANITIZE_STRING)] = (string) filter_var($get[1], FILTER_SANITIZE_STRING);;
                }
            }

            return true;

        } catch(Exception $e) {
        
        }

    }

    public function debug($debug = false) {
        $this->debug = $debug;
    }

    public function controller() {

        try {

            $controller = CONTROLLER . "/{$_GET['controller']}.php";

            if(!file_exists($controller)) {
                return $this->debug ? array('message' => "Failed opening '{$controller}' for inclusion", 'status' => false, 'value' => $controller) : false;
            }

            include_once($controller);

            if(!class_exists($_GET['controller'])) {
                return $this->debug ? array('message' => "Class '{$_GET['controller']}' not found", 'status' => false, 'value' => $_GET['controller']) : false;
            }

            eval('$controller = ' . "new {$_GET['controller']}();");

            if(!method_exists($controller, "action_{$_GET['action']}")) {
                return $this->debug ? array('message' => "Call to undefined action action_{$_GET['action']}()", 'status' => false, 'value' => $_GET['action']) : false;
            }

            eval('$controller->' . "action_{$_GET['action']}();");

            return true;

        } catch(Exception $e) {
        
        }

    }

    public function js_controller() {

        try {

            $js_controller = JS_CONTROLLER . "/{$_GET['controller']}.js";

            if(!file_exists($js_controller)) {
                return $this->debug ? array('message' => "Failed opening '{$js_controller}' for inclusion", 'status' => false, 'value' => $js_controller) : false;
            }

            if(isset($_SERVER['js_controller'])) {

                foreach($_SERVER['js_controller'] as $key => $value) {

                    $value = json_encode($value);

                    if(trim($key) == '') {
                        return $this->debug ? array('message' => "Is null", 'status' => false, 'value' => $key) : false;          
                    } elseif($key == 'console_log') {
                        echo "console.log(eval({$value}));\n";
                    } else {
                        if($value == '') {
                            echo "var {$key} = '';\n";    
                        } else {
                            echo "var {$key} = $value;\n";
                        }
                    }

                }
                
            }

            include_once($js_controller);
            echo "\n";

            unset($_SERVER['js_controller']);

            return true;

        } catch(Exception $e) {
        
        }

    }

}
?>