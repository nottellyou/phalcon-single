<?php
class IndexController extends BaseController {

    // 默认Action
    public function indexAction() {

        $ok  = $this->dispatcher->getParam('ok');
        $ok2 = $this->dispatcher->getParam('nook');

        $ok3 = $this->request->get('ok');
        $ok4 = $this->request->get('ok2');

        $time= date('Y-m-d H:i:s');

        //print_x($ok, $ok2, $ok3, $ok4);

        //return $this->response->setContent('Hello world');
        echo "<h1>Hello Word!</h1>";

		//include 'PDOMysql.class.php';
        //$where = ['id'=>16];
		//$c = M('log')->where($where)->find();
		//print_r($c);
		//echo "<hr>";

        $this->view->setVar('ok1', $ok);
        $this->view->setVar('ok2', $ok2);
        $this->view->setVar('ok3', $ok3);
        $this->view->setVar('ok4', $ok4);
        $this->view->setVar('time', $time);
		//logs(M('log')->_sql());
    }

    public function signupAction(){

    	echo 'hi, here is signup action, no html!';

        $time= date('Y-m-d H:i:s');
        $this->view->setVar('signup_time', $time);


    	$this->view->pick("Index/signup");
    	// $this->view->cache(
     //        array(
     //            "data"     => "myCache",
     //            "lifetime" => 86400,
     //            "key"      => "resume-cache"
     //        )
     //    );
    	//$this->view->disable();
    }

    public function testAction()
    {

    	//使用模型来取DB数据
    	$log = Log::findFirst(16);
    	echo '<hr>'.$log->act_name.'<hr>';


		$robots = Log::find(["act_name" => "QQ测试"]);
		foreach ($robots as $robot) {
		    echo $robot->id, "\n";
		}
		echo '<hr>';

    	//$log = Log::findFirstByName(["qq" => "502251257"]);
    	//echo  $log->id.'<hr>';

		$log = Log::count('act_name="QQ测试"');
    	echo  $log.'<hr>';


    }
	public function testviewcacheAction()
	{
		//if( $this->view->getCache()->exists( __CLASS__ . __FUNCTION__ ) )//检查缓存是否存在
		//{
		//	return $this->response->setContent( $this->view->getCache()->get(  __CLASS__ . __FUNCTION__ ));	//取缓存
		//}

		$iSum = date('Y-m-d H:i:s');

		$this->view->setVar( 'sum', $iSum );

		//$this->view->cache( array( 'lifetime' => 240, 'key' => __CLASS__ . __FUNCTION__ ) );//设置缓存
    }

    public function upload_formAction(){
        //自动加载视图
    }


    //测试上传
    public function myuploadAction(){
        $uploadfile = '';
        if($this->request->hasFiles()){
            // 上传配置
            $upload = new Upload($this->request, '/logs');

            // 开始上传
            $upload->uploadfile();

            // 判断上传状态  true标识没有上传成功  false 标识上传成功
            if(!$upload->errState()){
                // 返回文件保存真实路径
                $uploadfile = $upload->getFileRealPath();
            }else{
                // 打印错误信息
                $uploadfile =  $upload->errInfo();
            }
        }

        $content = $this->request->getPost('name');

        print_x($content, $uploadfile);
    }





}
?>