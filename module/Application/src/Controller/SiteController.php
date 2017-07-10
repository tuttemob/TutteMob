<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class SiteController extends ManagerController {
	
	public function indexAction()
	{
		return new ViewModel(array());
	}
	
}