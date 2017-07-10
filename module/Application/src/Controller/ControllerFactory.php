<?php
namespace Application\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ControllerFactory implements FactoryInterface {
	/**
	 * Inicia as chamadas dos controllers da aplicação
	 * 
	 * {@inheritDoc}
	 * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
		// retorna a instancia do controller solicitado
		return new $requestedName($container);
	}
}