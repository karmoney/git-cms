<?php

class AuthController extends Zend_Controller_Action
{
	public function init()
    {
        /* Initialize action controller here */
    }
    
	public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) 
        {
            if ('logout' != $this->getRequest()->getActionName()) 
            {
                $this->_helper->redirector('index', 'index');
            }
        } 
        else 
        {
            if ('logout' == $this->getRequest()->getActionName()) 
            {
                $this->_helper->redirector('login');
            }
        }
    }
    
	public function indexAction()
    {
        $this->view->form = new LoginForm();
    }
    
	public function loginAction()
    {
    	$params = $this->_getAllParams(); 
  		if(!empty($params['submit']) && $params['submit'] == 'submit') 
  		{
  			$username = $_POST['username'];
  			$password = $_POST['password'];
  			$auth = Zend_Auth::getInstance();
			$result= $auth->authenticate(new Cheese_Auth_Adapter($username,$password));        	 
  			if($result->isValid())
  			{
  				$$storage = new Zend_Auth_Storage_Session();
                $storage->write($authAdapter->getResultRowObject());
  				// redirect to a magical place
  			}  			
        	else 
        	{
	          $this->view->message = 'Invalid login. Please try again.';
        	}
  		}        
    }
    
	public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index'); // back to login page
    }
}