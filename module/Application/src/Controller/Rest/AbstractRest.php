<?php
namespace Application\Controller\Rest;

use Zend\Mvc\Controller\AbstractRestfulController;
use Interop\Container\ContainerInterface;
use Zend\Json\Json;

abstract class AbstractRest extends AbstractRestfulController
{
	/**
	 * @var ContainerInterface
	 */
	public $serviceManager;
	
	/**
	 * Inicia o carregamento do controller
	 *
	 * @param ContainerInterface $serviceManager
	 */
	public function __construct(ContainerInterface $serviceManager){
		$this->serviceManager = $serviceManager;
		
		// libera as origins
		header('Access-Control-Allow-Origin: *');
		
		// libera os headers
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
		
		// libera os metodos http
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	}
	
	public function jsonDispatch($values = array()){
		echo Json::encode($values);
		die();
	}
	
	public function options()
	{
		$response = $this->getResponse();
		$headers  = $response->getHeaders();
	
		// Allow only retrieval and creation on collections
		$headers->addHeaderLine('Allow', implode(',', array(
			'GET',
			'POST',
			'PUT',
			'DELETE'
		)));
		
		return $response;
	}
	
}