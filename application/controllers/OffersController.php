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
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->editable = true;
            $page->setUser($auth->getIdentity());
        }
        $data = $page->getData();
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
    		
    		$data = $this->array_extend($savedData, $dataToSave);
    		//$dataToSave = $dataToSave + $savedData;
//    		$data = array();
//    		foreach ($dataToSave as $i => $d) {
//    		    $data[$i] = array_merge($dataToSave[$i], $savedData[$i]);
//    		}
    		if (!$page->save($data)) {
    			header("Status: 404 Not Found");
    			echo $page->getError();
    		}
    	}
    	exit;
    }
    
    protected function array_extend($a, $b) {
        foreach($b as $k=>$v) {
            if( is_array($v) ) {
                if( !isset($a[$k]) ) {
                    $a[$k] = $v;
                } else {
                    $a[$k] = $this->array_extend($a[$k], $v);
                }
            } else {
                $a[$k] = $v;
            }
        }
        return $a;
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
