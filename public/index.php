<?php
use Phalcon\Loader;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Session\Adapter\Files;

use Phalcon\Cache\Frontend\Output as PhCacheFront;
use Phalcon\Cache\Backend\File as PhCacheBackFile;

//路由用
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;


use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

defined('APPLICATION_PATH')  || define('APPLICATION_PATH', realpath(dirname(__DIR__)));
defined('UPLOAD_DIR')  || define('UPLOAD_DIR', dirname(__DIR__).'/public/static/uploadfile');
defined('STATIC_DIR')  || define('STATIC_DIR', dirname(__DIR__).'/public/static');


$config  = new \Phalcon\Config\Adapter\Ini(APPLICATION_PATH . "/apps/config/config.ini");


    // 创建自动加载(AutoLoaders)实例
    $loader = new Loader();

    // 通过自动加载加载控制器(Controllers)
    $loader->registerDirs([
        // 控制器所在目录
        '../apps/controllers/',
        '../apps/models/',
        '../apps/libs/',//好像没用
    ],true);
    $loader->setExtensions([
            "php",
            "Lib.class.php",
    ]);
    $loader->registerFiles([
        '../apps/libs/PDOMysql.lib.class.php',
    ]);

    $loader->register();


    // 创建一个DI实例
    $di = new FactoryDefault();

    // 实例化View 赋值给DI的view
    $di->setShared('view', function () use($config) {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../apps/views/');
        $view->registerEngines(
                [
                    //".phtml" => 'Phalcon\Mvc\View\Engine\Php',
                    //'.phtml' => '\Phalcon\Mvc\View\Engine\Volt',
                    '.phtml' => function($view, $di) use ($config) {
                        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

                        $volt->setOptions(['compiledPath'      => $config->application->compiledPath,
                                           'compiledExtension' => '.compiled',
                                           'compiledSeparator' => '_',
                                           'compileAlways'     => true
                        ]);

                        $compiler = $volt->getCompiler();
                        $compiler->addFilter('floor', 'floor');
                        $compiler->addFunction('range', 'range');

                        return $volt;
                    },
                    '.volt'  => function($view, $di) use ($config) {
                        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                    
                        $volt->setOptions(['compiledPath'      => $config->application->compiledPath,
                                           'compiledExtension' => '.compiled',
                                           'compiledSeparator' => '_',
                                           'compileAlways'     => true
                        ]);
                    
                        $compiler = $volt->getCompiler();
                        $compiler->addFilter('floor', 'floor');
                        $compiler->addFunction('range', 'range');
                    
                        return $volt;
                    },
                ]
        );
        //$view->disableLevel(
        //        array(
        //            View::LEVEL_LAYOUT      => true,
        //            View::LEVEL_MAIN_LAYOUT => true
        //        )
        //);
        return $view;
    });


    // 设置数据库服务
    $di->set('db', function () use ($config) {
        $dbconfig   = $config->get('database')->toArray();

        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql([
            "host"     => $dbconfig['host'],
            "port"     => $dbconfig['port'],
            "username" => $dbconfig['username'],
            "password" => $dbconfig['password'],
            "dbname"   => $dbconfig['dbname'],
            "charset"  => $dbconfig['charset']]
        );

        return $connection;
    });


    $di->setShared('dispatcher', function(){
         //创建一个事件管理
        $eventsManager = new EventsManager();
 
        //附上一个侦听者
        $eventsManager->attach("dispatch:beforeDispatchLoop", function($event, $dispatcher) {
            $keyParams = array();
            $params = $dispatcher->getParams();         
            //用奇数参数作key，用偶数作值
            foreach ($params as $number => $value) {
                if($number & 1){
                    $keyParams[$params[$number - 1]] = $value;
                }
                //$parts   = explode(':', $value);
                //$keyParams[$parts[0]] = $parts[1];
            }
            //重写参数
            $dispatcher->setParams($keyParams);
        });
 
        $dispatcher = new MvcDispatcher();
        $dispatcher->setEventsManager($eventsManager);
 
        return $dispatcher;
    });

    //注册session
    $di->setShared('session', function () {
        $session = new Session();
        $session->start();
        return $session;
    });

    //注册视图缓存
    $di->setShared('viewCache', function () use ($config) {
        // Get the parameters
        $frontCache      = new PhCacheFront(['lifetime' => $config->cache->lifetime]);

        //$backEndOptions  = ['html_cache' => $config->cache->htmlCacheDir];
        $backEndOptions  = ['cacheDir' => $config->cache->cacheDir];
        $cache           = new PhCacheBackFile($frontCache, $backEndOptions);

        return $cache;
    });
    $di->setShared('cacheData', function () use ($config) {
        $frontendOptions = ['lifetime' => 60 * 60];
        $frontCache      = new PhCacheFront($frontendOptions);
        $backEndOptions  = ['cacheDir' => $config->cache->cacheDir];
        $cache           = new PhCacheBackFile($frontCache, $backEndOptions);

        return $cache;
    });

    $di->setShared('html_cache_rules', function() use($config) {
        return include($config->application->configDir.'html_cache_rules.php');
    });


    $di->set("crypt", function () {
        $crypt = new Crypt();
        // Set a global encryption key
        $crypt->setKey("%31.1e$i86e$f!8jz");
        return $crypt;
    }, true);




try {
    // 处理请求
    $application = new Application($di);
    // 输出请求类容
    //echo $application->handle()->getContent();

    $application->handle()->send();


} catch (\Phalcon\Exception $e){
    // 异常处理
    echo "PhalconException: ", $e->getMessage();
}
?>