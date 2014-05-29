<?php
class Router {

    protected $uri = '';
    protected $debug = false;

    public function rule() {

        try {

            $_GET['controller'] = isset($_GET['controller']) ? $_GET['controller'] : 'index';
            $_GET['action'] = isset($_GET['action']) ? $_GET['action'] : 'index';

            $uri = @$_SERVER['REQUEST_URI'];

            if('' !== (string) CHROOT) {
                $uri = explode(CHROOT, $uri);
                $uri = $uri[1];
            }

            $uri = explode('?', $uri);
            
            $uri[0] = explode('/', $uri[0]);
        	$count = count($uri[0]);

        	for($i = 0; $i < $count; $i++) {
                if('' !== (string) trim($uri[0][$i])) {
            		if(1 === $i) {
            			$_GET['controller'] = (string) filter_var($uri[0][$i], FILTER_SANITIZE_STRING);
            		} elseif(2 === $i) {
            			$_GET['action'] = (string) filter_var($uri[0][$i], FILTER_SANITIZE_STRING);
            		} elseif(2 < $i) {
            			if(0 != $i % 2) {
            				$_GET[ (string) filter_var($uri[0][$i], FILTER_SANITIZE_STRING)] = NULL;
            			} else {
            				$_GET[$uri[0][$i - 1]] = (string) filter_var($uri[0][$i], FILTER_SANITIZE_STRING);
            			}
            		}
                }
        	}

            $uri = @explode('&', $uri[1]);

            foreach($uri as $items) {
                $get = explode('=', $items);
                if('' != (string) trim($get[0]) && '' != (string) trim($get[1])) {
                    $_GET[ (string) filter_var($get[0], FILTER_SANITIZE_STRING)] = (string) filter_var($get[1], FILTER_SANITIZE_STRING);;
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
                throw new Exception("Failed opening '{$controller}' for inclusion");
            }

            include_once($controller);

            if(!class_exists($_GET['controller'])) {
                throw new Exception("Class '{$_GET['controller']}' not found");
            }

            eval('$controller = ' . "new {$_GET['controller']}();");

            if(!method_exists($controller, "action_{$_GET['action']}")) {
                throw new Exception("Call to undefined action action_{$_GET['action']}()");
            }

            eval('$controller->' . "action_{$_GET['action']}();");

            return true;

        } catch(Exception $e) {
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('controller' => $_GET['controller'], 'action' => $_GET['action'])) : false;
        }

    }

    public function js_controller() {

        try {

            $js_controller = JS_CONTROLLER . "/{$_GET['controller']}.js";

            if(!file_exists($js_controller)) {
                throw new Exception("Failed opening '{$js_controller}' for inclusion");
            }

            if(isset($_SERVER['argv'])) {

                foreach($_SERVER['argv'] as $key => $value) {

                    $value = json_encode($value);

                    if('' === trim($key)) {
                        throw new Exception("Is null");
                    } elseif('console_log' === (string) $key) {
                        echo "console.log(JSON.parse({$value}));\n";
                    } elseif(!is_numeric($key)) {
                        if('' === $value) {
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
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('params' => $_SERVER['js_controller'])) : false;
        }

    }

}
?>