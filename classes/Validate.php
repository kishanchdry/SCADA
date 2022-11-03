<?php 
    class Validate{
        private $_passed = false,
                $_errors = array(),
                $_db = null;

        public function __construct()
        {
            $this->_db=DB::getInstance();
        }

        public function check($source, $items=array()){
            foreach($items as $item=>$rules){
                foreach($rules as $rule => $rule_value){
                    if (!is_array($source[$item])) {
                        $value = trim($source[$item]);
                    }
                    $item = escape($item);

                    if($rule === 'required' && empty($value)){
                        $this->addErrors("{$item} is required !");
                    }else if(!empty($value)){
                        switch($rule){
                            case 'min':
                                if(strlen($value) < $rule_value){
                                    $this->addErrors("{$item} must be a minimum of {$rule_value} characters !");
                                }
                            break;
                            case 'max':
                                if(strlen($value) > $rule_value){
                                    $this->addErrors("{$item} must be a maximum of {$rule_value} characters !");
                                }
                            break;
                            case 'matches':
                                if($value != $source[$rule_value]){
                                    $this->addErrors("{$rule_value} must be same as {$item} !");
                                }
                            break;
                            case 'unique':
                                $check = $this->_db->get($rule_value, array($item, '=', $value));
                                if($check->count()){
                                    $this->addErrors("{$item} already exists !");
                                }
                            break;
                            

                        }
                    }
                }
            }
            if(empty($this->_errors)){
                $this->_passed = true;
            }
            return $this;
        }

        private function addErrors($errors){
            $this->_errors[] = $errors;
        }

        public function errors(){
            return $this->_errors;
        }

        public function passed(){
            return $this->_passed;
        }





    }