<?php

class Default_Model_Page
{
    const REPO_ROOT = '/cms-data';
    
    protected $_uri;
    
    protected $_user;
    
    protected $_userRepo;
    
    protected $_masterRepo;
    
    public function __construct($uri = null)
    {
        $this->_uri = $uri;
    }
    
    public function setUser($user)
    {
        $this->_user = $user;
        $this->_getUserRepo();
    }
    
    public function save($data)
    {
        $repo = $this->_getUserRepo();
        
//        $branch = $this->_user ? $this->_user : 'master';
//        $userRepo = $this->_getUserRepo();
        $json = $ths->_toJson($data);
        $path = $this->_getPath();
        file_put_contents($path, $json);
        
        // Git commit
    }
    
    protected function _getUserRepo()
    {
        if (null === $this->_userRepo) {
            if (!$this->_user) {
                throw new Exception("No user specified!");
            }
            
            $userRepoPath = self::REPO_ROOT . '/' . $this->_user;
            
            if (!file_exists($userRepoPath)) {
                // clone master into repo
                $masterRepo = $this->_getMasterRepo();
                $masterRepo->clone_to($userRepoPath);
            }
            
            $this->_userRepo = Zend_Git::open($userRepoPath);
        }
        
        return $this->_userRepo;
    }
    
    protected function _getMasterRepo()
    {
        if (null === $this->_masterRepo) {
            $this->_masterRepo = Zend_Git::open(self::REPO_ROOT . '/master');
        }
        return $this->_masterRepo;
    }
    
    public function publish()
    {
        
    }
    
    public function getData()
    {
        $path = $this->_getPath();
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    
    protected function _getPath()
    {
        $user = ($this->_user ? $this->_user : 'master');
        $path = "/cms-data/{$user}/{$this->_uri}/page.json";
        return $path;
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