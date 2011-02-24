<?php
class Cheese_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	protected $_username;
	protected $_password;

	public function __construct($username,$password)
	{
		$this->_username=$username;
		$this->_password=$password;
	}
	
	public function authenticate()
	{
		$users=array('asher','kartik', 'albert', 'muhammad', 'winnie', 'robert', 'hector' );
		$passwords = array('mickey');
		
		if(in_array($this->_username,$users) && in_array($this->password,$passwords)) 
		{
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_username, array());
		}
		
		return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->_username, array());
	}
}
?>
