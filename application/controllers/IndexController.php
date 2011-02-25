<?php	

class IndexController extends Zend_Controller_Action
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
        // action body
		$this->_helper->redirector('index', 'offers');

    }  
}

