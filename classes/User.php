<?php 
    class User{
        private $_db,
                $_data,
                $_sessionName,
                $_cookieName,
                $_isLoggedIn;

        public function __construct($user = null)
        {
            $this->_db = DB::getInstance();
            $this->_sessionName = Config::get('session/session_name');
            $this->_cookieName = Config::get('remember/cookie_name');
            
            if(!$user){

                if(Session::exists($this->_sessionName)){
                    $user = Session::get($this->_sessionName);
                    if($this->find($user)){
                        $this->_isLoggedIn=true;
                    }else{

                    }
                }
            }else{
                $this->find($user);
            }
        }

        public function register($fields = array()){
            if(!$this->_db->insert('users', $fields)){
                throw new Exception('There was a problem registering an user !');
            }
        }
        public function adding($table, $fields = array()){
            if(!$this->_db->insert($table, $fields)){
                throw new Exception('There was a problem !');
            }
        }

        public function getData($table, $where=array()){
            $data = $this->_db->get($table, $where);
            if($data->count()){
                return $this->_data = $data;
                
            }
        }

        public function updateData($table, $id, $col, $where=array()){
            $data = $this->_db->update($table, $id, $col, $where);
                return $this->_data = $data;
                
        }

        public function updateOrg($table, $id, $where=array()){
            $data = $this->_db->o_update($table, $id, $where);
                return $this->_data = $data;
                
        }

        public function selectAll($table){
            $data = $this->_db->getAll($table);
            if($data->count()){
                return $this->_data = $data;
                
            }
        }

        public function selectJoinAll($query){
            $data = $this->_db->joinAll($query);
            if(!empty($data) && $data->count() > 0){
                return $this->_data = $data;
                
            }
        }

        public function getTest($query){
            $data = $this->_db->joinAll($query);
                return $this->_data = $data;
        }

        public function SetQuery($query){
            $this->_db->joinAll($query);
        }

        
        
        public function find($user = null)
        {
            if($user){
                $field = (is_numeric($user)) ? 'user_id' : 'user_username';
                
                $data = $this->_db->get('users', array($field, '=', $user));
               
                if($data->count()){
                    $this->_data = $data->first();
                    return true;
                }
            }
            return false;
        }

        public function login($username = null, $password = null, $remember = false)
        {
                       
            if(!$username && !$password && $this->exists()){
                Session::put($this->_sessionName, $this->data()->id);
            }
            else{
                $user = $this->find($username);

                if($user){
                    if($this->data()->user_password === Hash::make($password, $this->data()->randSalt)){
                        Session::put($this->_sessionName, $this->data()->user_id);

                        if($remember){
                            
                            $hashCheck = $this->_db->get('users_sessions', array('user_id', '=', $this->data()->id));

                            if(!$hashCheck->count()){
                                $hash = Hash::unique();
                                $this->_db->insert('users_sessions', array(
                                    'user_id' => $this->data()->id,
                                    'hash'  => $hash
                                ));
                            }else{
                                $hash = $hashCheck->first()->hash;
                            }

                            Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                        }

                        return true;
                    }               
                }
            }
            return false;
        }

        public function logout(){

            $this->_db->delete('users_sessions', array('user_id', '=', $this->data()->id));

            Session::delete($this->_sessionName);
            Cookie::delete($this->_cookieName);
        }

        public function data(){
            return $this->_data;
        }

        public function isLoggedIn(){
            return $this->_isLoggedIn;
        }

        public function exists(){
            return (!empty($this->_data)) ? true : false;
        }


    }
    