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
        $this->view->assign($data);
    }
}