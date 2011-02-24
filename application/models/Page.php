<?php

class Default_Model_Page
{
    protected $_uri;
    
    protected $_repo;
    
    protected $_branch;
    
    protected $_user;
    
    protected $_data;
    
    protected $_userRepo;
    
    public function __construct($uri)
    {
        $this->_uri = $uri;
    }
    
    public function setUser($user)
    {
        $this->_user = $user;
    }
    
    public function save($data)
    {
        $json = $this->_toJson($data);
    }
    
    public function publish()
    {
        
    }
    
    public function getData()
    {
        $user = ($this->_user ? $this->_user : 'master');
        $path = "/cms-data/{$user}/{$this->_uri}/page.json";
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    
    public function _toJson($data)
    { 
        $tab = "  "; 
        $new_json = ""; 
        $indent_level = 0; 
        $in_string = false; 
        
        $json = json_encode($data); 
        $len = strlen($json); 
    
        for($c = 0; $c < $len; $c++) 
        { 
            $char = $json[$c]; 
            switch($char) 
            { 
                case '{': 
                case '[': 
                    if(!$in_string) 
                    { 
                        $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1); 
                        $indent_level++; 
                    } 
                    else 
                    { 
                        $new_json .= $char; 
                    } 
                    break; 
                case '}': 
                case ']': 
                    if(!$in_string) 
                    { 
                        $indent_level--; 
                        $new_json .= "\n" . str_repeat($tab, $indent_level) . $char; 
                    } 
                    else 
                    { 
                        $new_json .= $char; 
                    } 
                    break; 
                case ',': 
                    if(!$in_string) 
                    { 
                        $new_json .= ",\n" . str_repeat($tab, $indent_level); 
                    } 
                    else 
                    { 
                        $new_json .= $char; 
                    } 
                    break; 
                case ':': 
                    if(!$in_string) 
                    { 
                        $new_json .= ": "; 
                    } 
                    else 
                    { 
                        $new_json .= $char; 
                    } 
                    break; 
                case '"': 
                    if($c > 0 && $json[$c-1] != '\\') 
                    { 
                        $in_string = !$in_string; 
                    } 
                default: 
                    $new_json .= $char; 
                    break;                    
            } 
        } 
    
        return $new_json; 
    } 
}