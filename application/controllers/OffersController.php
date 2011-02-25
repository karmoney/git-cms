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
        $this->view->data = $data;
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
