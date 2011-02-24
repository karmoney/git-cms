<?php

class OffersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
	public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) 
        {            
                $this->_helper->redirector('auth\index');            
        }
    }

    public function indexAction()
    {
        $page = new Default_Model_Page('offers');
//        // Only set user if it is set
//        $user = 'hector';
//        $page->setUser($user);
        
        $data = $page->getData();
        $this->view->assign($data);
    }
}

