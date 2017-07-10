<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Model;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
    	return [
    		'factories' => [
    			'Model\Pessoa' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\Pessoa($adapter);
    				return $table;
    			},
    			'Model\BriefingCozinha' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingCozinha($adapter);
    				return $table;
    			},
    			'Model\BriefingDormitorioCasal' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingDormitorioCasal($adapter);
    				return $table;
    			},
    			'Model\BriefingDormitorioSolteiro' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingDormitorioSolteiro($adapter);
    				return $table;
    			},
    			'Model\BriefingDormitorioInfantil' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingDormitorioInfantil($adapter);
    				return $table;
    			},
    			'Model\BriefingBanheiro' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingBanheiro($adapter);
    				return $table;
    			},
    			'Model\BriefingAreaServico' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingAreaServico($adapter);
    				return $table;
    			},
    			'Model\BriefingCloset' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingCloset($adapter);
    				return $table;
    			},
    			'Model\BriefingHomeTheater' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingHomeTheater($adapter);
    				return $table;
    			},
    			'Model\BriefingHomeOffice' =>  function($sm) {
    				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$table = new Model\BriefingHomeOffice($adapter);
    				return $table;
    			}
    		]
    	];
    }
}
