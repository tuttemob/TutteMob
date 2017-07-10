<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Interop\Container\ContainerInterface;
use Zend\Json\Json;
use Zend\Mvc\MvcEvent;

abstract class ManagerController extends AbstractActionController
{
	/**
	 * @var ContainerInterface
	 */
	public $serviceManager;
	
	/**
	 * @var ArrayObject
	 */
	public $usuario; 
	
	/**
	 * @var boolean
	 */
	protected $requerAcessoAutenticado = false;
	
	/**
	 * Inicia o carregamento do controller
	 *
	 * @param ContainerInterface $serviceManager
	 */
	public function __construct(ContainerInterface $serviceManager){
		$this->serviceManager = $serviceManager;
	}
	
	public function onDispatch(MvcEvent $e){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$this->usuario = $modelPessoa->getUsuarioLogado();
		
		if($this->requerAcessoAutenticado && !$this->usuario){
			return $this->redirect()->toRoute('login');
		}
		
		$this->layout()->usuario = $this->usuario;
		
		return parent::onDispatch($e);
	}
	
	public function jsonDispatch($values = array()){
		echo Json::encode($values);
		die();
	}	
}