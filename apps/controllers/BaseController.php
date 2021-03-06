<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class BaseController extends Controller {

    // 默认Action
    public function onConstruct() {
        //$dispatcher->getControllerName() and $dispatcher->getActionName() is null;
        //$dispatcher = new Dispatcher();
        //print_x($dispatcher, $dispatcher->getControllerName(), $dispatcher->getActionName());
        //if(!method_exists($dispatcher->getControllerName().'Controller', $dispatcher->getActionName().'Action')){
        //    header("HTTP/1.0 404 Not Found");//这样判断是行不通的
        //    exit;
        //}
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        //$key  = $dispatcher->getControllerName() . '-' . $dispatcher->getActionName() . '-' . implode('-' , $dispatcher->getParams());


        //check if this route is in html_cache_rules
        $arr_html_cache_rules = $this->html_cache_rules;
        if($arr_html_cache_rules['HTML_CACHE_ON'] == 'on'){
            $rule1  = ucfirst($dispatcher->getControllerName()) . ':';
            $rule2  = $rule1 . $dispatcher->getActionName();

            $arr_rules = $arr_html_cache_rules['HTML_CACHE_RULES'];

            //match the Index:index
            if(!empty($arr_rules[$rule2])){
                $cached_time = $this->cacheData->getLifetime();
                $max_age     = $arr_rules[$rule2][1] - $cached_time;
                header('Cache-Control:max-age='.$max_age,true);
                //print_x($this->viewCache->isFresh(),$this->view->getCache()->exists($arr_rules[$rule2][0]));
                if ($this->view->getCache()->exists($arr_rules[$rule2][0]) && $this->viewCache->isFresh()) {
                    return $this->view->getContent();
                }else{
                    $this->view->cache(['key'=>$arr_rules[$rule2][0], 'lifetime'=>$arr_rules[$rule2][1] ]);
                    return true;
                }
            }elseif(!empty($arr_rules[$rule1])){ //match all action in Index:
                $cached_time = $this->cacheData->getLifetime();
                $max_age     = $arr_rules[$rule1][1] - $cached_time;
                header('Cache-Control:max-age='.$max_age,true);
                if ($this->view->getCache()->exists($arr_rules[$rule1][0])) {
                    return $this->view->getContent();
                }else{
                    $this->view->cache(['key'=>$arr_rules[$rule1][0], 'lifetime'=>$arr_rules[$rule1][1]]);
                    return true;
                }
            }
        }

        return true;
    }

    public function afterExecuteRoute($dispatcher)
    {
        // 在找到的action后执行，注意：找到后，不会执行模板引擎
        //echo 'no this action';
        //exit;
    }

}
?>
