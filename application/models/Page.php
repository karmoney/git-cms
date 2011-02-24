<?php

class Default_Model_Page
{
    const PAGE = 'offers/page.xml';
    
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
        if (!$this->_user) {
            
        }
        $userRepo = $this->_getUserRepo();
        
    }
    
    public function getData()
    {
        if (null === $this->_data) {
            
        }
    }
}