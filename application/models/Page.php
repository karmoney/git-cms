<?php

class Default_Model_Page
{
    const REPO_ROOT = '/cms-data';
    
    protected $_uri;
    
    protected $_user;
    
    protected $_userRepo;
    
    protected $_masterRepo;
    
    protected $_error = null;
    
    public function __construct($uri = null)
    {
        $this->_uri = $uri;
    }
    
    public function setUser($user)
    {
        $this->_user = $user;
        $this->_getUserRepo();
    }
    
    public function setError($error) 
    {
    	$this->_error = $error;
    }
    
    public function getError() 
    {
    	return $this->_error;
    }
    
    public function save($data)
    {
        $repo = $this->_getUserRepo();
        if ($this->getError()!=null) return false;
        $json = $this->_toJson($data);
        $path = $this->_getPath();
        file_put_contents($path, $json);   
        // Git commit
        $res = $repo->commit("committing content changes");
        return true;
    }
    
    protected function _getUserRepo()
    {
        if (null === $this->_userRepo) {
            if (!$this->_user) {
                throw new Exception("No user specified!");
            }
            
            $userRepoPath = self::REPO_ROOT . '/' . $this->_user;
            
            if (!file_exists($userRepoPath)) {
                $this->_userRepo = Zend_Git_Repo::create_new($userRepoPath, self::REPO_ROOT . '/master');
            }
            else {
            	$this->_userRepo = Zend_Git::open($userRepoPath);
            	$this->_syncWithMaster();
            }
        }
        
        return $this->_userRepo;
    }
    
    protected function _syncWithMaster() 
    {
    	try {
            $ret = $this->_userRepo->run("pull");
    	} catch (Exception $e) {
//			$this->setError("Merge Conflict!");
			$this->_userRepo->run("reset --hard origin");
    	}
    }
    
    protected function _getMasterRepo()
    {
        if (null === $this->_masterRepo) {
            $this->_masterRepo = Zend_Git::open(self::REPO_ROOT . '/master');
        }
        return $this->_masterRepo;
    }
    
    public function share($user, $uri)
    {
        $target = new self($uri);
        $target->setUser($user);
        $targetRepo = $target->_getUserRepo();
        $result = $targetRepo->run("pull /cms-data/{$this->_user} master");	
        if(strrpos($result,"CONFLICT")!=false) {
            $this->setError("Merge Conflict!");
            $targetRepo->run("reset --hard origin");
        }
    	return true;
    }   
     
    public function publish()
    {
    	$repo = $this->_getUserRepo();
    	if ($this->getError() != null) return false;
    	
    	$master = $this->_getMasterRepo();
    	$result = $master->run("pull /cms-data/{$this->_user} master");
        if(strrpos($result,"CONFLICT")!=false) {
            $this->setError("Merge Conflict!");
            $masterRepo->run("reset --hard HEAD");
        }
    	
    	return true;
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