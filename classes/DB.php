<?php 
    class DB {
        private static $_instance=null;
        private $_pdo, 
                $_query, 
                $_error=false,
                $_results,
                $_count=0;
        
        private function __construct(){
            try{
                $this->_pdo=new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }  
        
        public static function getInstance(){
            if(!isset(self::$_instance)){
                self::$_instance=new DB();
            }
            return self::$_instance;
        }

        public function query($sql, $params=array()){
               $this->_error = false; 
            // echo $sql;
               if($this->_query=$this->_pdo->prepare($sql)){
                    $x=1;
                    if(count($params)){
                        foreach($params as $param){
                            $this->_query->bindValue($x, $param);
                         // echo $param;
                            $x++;
                        }
                    }
                    if($this->_query->execute()){
                        $this->_results=$this->_query->fetchAll(PDO::FETCH_OBJ);
                        $this->_count=$this->_query->rowCount();
                    }else{
                        $this->_error = true;
                    }
               }
               return $this;
        }

        private function action($action, $table, $where =array()){
            if(count($where)===3){
                $operators = array('=', '>', '<', '<=','>=', '!=');

                $field     = $where[0];
                $operator  = $where[1];
                $value     = $where[2];

                if(in_array($operator, $operators)){
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                    if(!$this->query($sql, array($value))->_error){
                        return $this;
                    }
                }
            }
            return false;
        }

        public function get($table, $where){
            return $this->action('SELECT *', $table, $where);
        }

        public function custom_query($sql){
            $this->_error = false; 
           
            $this->_query=$this->_pdo->prepare($sql);
                 if($this->_query->execute()){
                     $this->_results=$this->_query->fetchAll(PDO::FETCH_OBJ);
                     $this->_count=$this->_query->rowCount();
                    
                 }else{
                     $this->_error = true;
                 }
            
            return $this;
        }

        private function custom_action($action, $table){
            
                    $sql = "{$action} FROM {$table} ";

                    if(!$this->custom_query($sql)->_error){
                        return $this;
                    }
                
            return false;
        }


        private function Join_action($query){

            if(!$this->custom_query($query)->_error){
                return $this;
            }
        
            return false;
        }

        public function getAll($table){
            return $this->custom_action('SELECT *', $table);
        }

        public function joinAll($query){
            return $this->Join_action($query);
        }

        public function delete($table, $where){
            return $this->action('DELETE', $table, $where);
        }

        public function insert($table, $fields=array()){
          
                $keys=array_keys($fields);
                $values=null;
                $x=1;
                foreach ($fields as $field) {
                    $values .='?';
                    if ($x < count($fields)) {
                        $values .=', ';
                    }
                    $x++;
                }   

                $sql="INSERT INTO {$table} (`". implode('`, `', $keys) ."`) VALUES ({$values})";
                
              
                if(!$this->query($sql, $fields)->error()){
                  return true;
                }
            
            return false;
        }

        public function update($table, $id, $col, $fields){
            $set='';
            $x=1;

            foreach($fields as $name=>$values){
                $set .= "{$name}=?";
                if($x < count($fields)){
                    $set .=', ';
                }
                $x++;
            }

            $sql = "UPDATE {$table} SET {$set} WHERE {$col} = {$id}";
            
            if(!$this->query($sql, $fields)->error()){
                return true;
            }
            return false;
        }

        

        public function results(){
            return $this->_results;
        }

        public function first(){
            return $this->results()[0];
        }

        public function error(){
            return $this->_error;
        }

        public function count(){
            return $this->_count;
        }

        
        
    }