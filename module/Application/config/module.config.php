<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

//use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
//use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
        		
        	'home' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[:action[/:params]]',
                	'constraints' => array(
                		'action' => '[a-zA-Z\-]*',
                		'params' => '.*'	
                	),
                	'defaults' => [
                        'controller' => Controller\SiteController::class,
                		'action'	 => 'index'
                    ],
                ],
            ],
        		
        	'clientes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/clientes[/:action[/:params]]',
                	'constraints' => array(
                		'action' => '[a-zA-Z]*',
                		'params' => '.*'
                	),
                	'defaults' => [
                        'controller' => Controller\ClientesController::class,
                		'action'	 => 'index'
                    ],
                ],
            ],

        	'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login/[:action[/:ctoken]]',
                	'constraints' => array(
                		'action' => '[a-zA-Z\-]*',
                		'ctoken' => '.*'
                	),
                	'defaults' => [
                        'controller' => Controller\LoginController::class,
                		'action'	 => 'index'
                    ],
                ],
            ],
        	
        	'logout' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/logout',
                	'constraints' => array(),
                	'defaults' => [
                        'controller' => Controller\LoginController::class,
                		'action'	 => 'logout'
                    ],
                ],
            ],
        	
        	'clientes_rest' => [
        		'type'    => Segment::class,
        		'options' => [
        			'route'    => '/rest/clientes[/:id]',
        			'constraints' => array(
        				'id' => '.*'	
        			),
        			'defaults' => [
        				'controller' => Controller\Rest\Clientes::class
       				],
       			],
        	]
        ],
    ],
    'controllers' => [
        'factories' => [
        	Controller\Rest\Clientes::class => Controller\ControllerFactory::class,
        	Controller\SiteController::class => Controller\ControllerFactory::class,
        	Controller\LoginController::class => Controller\ControllerFactory::class,
        	Controller\ClientesController::class => Controller\ControllerFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => false,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
