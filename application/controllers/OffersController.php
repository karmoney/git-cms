<?php

class OffersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $page = new Default_Model_Page('offers');
        $data = $page->getData();
        $auth = Zend_Auth::getInstance();
        $this->view->editable = $auth->hasIdentity();
        $this->view->data = $data;
    }
    
    public function saveAction()
    {
    	if ($this->getRequest()->isPost())
    	{
    		$dataToSave = $this->getRequest()->getPost('data');
    		$page = new Default_Model_Page('offers');
    		$auth = Zend_Auth::getInstance();
    		$page->setUser($auth->getIdentity());
    		$savedData = $page->getData();
    		$dataToSave = array_merge($dataToSave, $savedData);
    		if (!$page->save($dataToSave)) {
    			header("Status: 404 Not Found");
    			echo $page->getError();
    		}
    	}
    	exit;
    }
    
    public function saveTestAction()
    {
        $page = new Default_Model_Page('offers');
        $page->setUser('hector');
        $data = $page->getData();
        $data['name'] = 'updated name ' . time();
        $page->save($data);
        Zend_Debug::dump($data); exit;
    }
    
    public function pushAction() 
    {
        $page = new Default_Model_Page('offers');
        $page->setUser('muhammad');
        $page->publish();
        Zend_Debug::dump($data); exit;
    
    }
}
