<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[:controller[/:action]][/]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'params' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[:controller[/:action]][/:params][/]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'params'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'filter-country' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/country[/:country][/]',
                    'constraints' => array(
                        'country'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Product',
                        'action'     => 'index'
                    ),
                ),
            ),
            'recycler-index' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/recycler/[page/:page[/]]',
                    'constraints' => array(
                        'page'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Recycler',
                        'action'     => 'index'
                    ),
                ),
            ),
            'product-index' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/[page/:page[/]]',
                    'constraints' => array(
                        'page'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Product',
                        'action'     => 'index'
                    ),
                ),
            ),
            'product-filter-country' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/filter/[country/:country[/]][recycler-country/:recycler-country[/]][page/:page[/]]',
                    'constraints' => array(
                        'country'      => ':country',
                        'page'      => ':page',
                        'recycler-country'      => ':recycler-country',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Product',
                        'action'     => 'filter',
                    ),
                ),
            ),
            'recycler-detail' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/recycler/detail[/id/:id/page/:page[/]]',
                    'constraints' => array(
                        'id'      => '(.*)',
                        'params'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Recycler',
                        'action'     => 'detail'
                    ),
                ),
            ),
            'recycler-detail-upload' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/recycler/detail[/id/:id/upload/success/page/:page[/]]',
                    'constraints' => array(
                        'id'      => '(.*)',
                        'upload'      => 'success',
                        'page'      => '(.*)',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Recycler',
                        'action'     => 'detail',
                        'upload'    => 'success'
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Role' => 'Application\Controller\RoleController',
            'Application\Controller\Admin' => 'Application\Controller\AdminController',
            'Application\Controller\Recycler' => 'Application\Controller\RecyclerController',
            'Application\Controller\Product' => 'Application\Controller\ProductController',
            'Application\Controller\Exchange' => 'Application\Controller\ExchangeController',
            'Application\Controller\System' => 'Application\Controller\SystemController',
            'Application\Controller\Help' => 'Application\Controller\HelpController',
            'Application\Controller\Login' => 'Application\Controller\LoginController',
            'Application\Controller\Logout' => 'Application\Controller\LogoutController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Resource' => 'Application\Controller\ResourceController',
            'Application\Controller\Condition' => 'Application\Controller\ConditionController',
            'Application\Controller\Country' => 'Application\Controller\CountryController',
            'Application\Controller\Soap' => 'Application\Controller\SoapController',
            'Application\Controller\Language' => 'Application\Controller\LanguageController',
            'Application\Controller\Cron' => 'Application\Controller\CronController',
            'Application\Controller\Reindex' => 'Application\Controller\ReindexController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/login'            => __DIR__ . '/../view/layout/login.phtml',
            'layout/empty'            => __DIR__ . '/../view/layout/empty.phtml',
            'layout/xml'            => __DIR__ . '/../view/layout/xml.phtml',
            'admin/breadcrumbs'       => __DIR__ . '/../view/partial/breadcrumbs.phtml',
            'admin/topmenu'           => __DIR__ . '/../view/partial/menu.phtml',
            'paginator_style_1'           => __DIR__ . '/../view/partial/pagination.phtml',
            'paginator_style_2'           => __DIR__ . '/../view/partial/pagination2.phtml',
            'paginator_style_3'           => __DIR__ . '/../view/partial/pagination3.phtml',
            'paginator_style_4'           => __DIR__ . '/../view/partial/pagination4.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'application/admin/index' => __DIR__ . '/../view/application/admin/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
