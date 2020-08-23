<?php


/**
 * Class Http  http服务器
 * 利用swoole提供的http服务器与thinkphp5.1框架
 * 进行融合使用
 */

class Ws
{


    private  $host; //主机ip地址


    private  $port; //端口


    private  $ws; //websocket 服务器

    //定义一张内存共享数据表
    private  $table;

    private  $user_id;

    private  $friend_user_id;
    /**
     * 构造方法
     * Http constructor.
     * @param string $host
     * @param int $port
     */
    public function  __construct($host="0.0.0.0",$port=80)
    {
        $this->host = $host; //设置服务器的ip地址

        $this->port = $port  ;// 设置服务器运行的端口

        //创建http服务器

        //清除redis 链接Key


        $this->ws = new Swoole\WebSocket\Server($this->host,$this->port);

        //配置http服务器
        $this->ws->set(
            [
                "worker_num" => 4 , //设置启动的 Worker 进程数,

                "task_worker_num" => 4,

                "daemonize " => 0 ,  //1 守护线程运行| 0 非守护线程运行

                "enable_static_handler" => true,

                "document_root" => __DIR__."/../public/static", //设置运行静态目录的根目录

                'upload_tmp_dir' => __DIR__.'/../public/static/upload', //设置上传文件的目录
             ]
        );

        //注册http服务器监听事件

        $this->ws->on("request",[$this,"onRequest"]);

         //此事件在 Worker 进程 / Task 进程启动时发生，这里创建的对象可以在进程生命周期内使用
        $this->ws->on("WorkerStart",[$this,"onWorkerStart"]);
        //注册客户端连接事件
        $this->ws->on("open",[$this,"onOpen"]);
        //注册客户端接收消息事件
        $this->ws->on("message",[$this,"onMessage"]);
        //注册t异步task事件
        $this->ws->on("task",[$this,"onTask"]);
        //注册finshed 完成事件
        $this->ws->on("finish",[$this,"onFinish"]);
        //注册客户端关闭事件
        $this->ws->on("close",[$this,"onClose"]);
        //启动http服务器
        $this->ws->start();




        require_once "../application/common/controller/redis/Predis.php";
        \app\common\controller\redis\Predis::del(config("live.live_list_key"));
    }

    /**
     * 此事件在 Worker 进程 / Task 进程启动时发生，这里创建的对象可以在进程生命周期内使用。
     * @param \Swoole\Server $server  服务器对象
     * @param $workerId 进程 id（非进程的 PID）
     */
     public  function  onWorkerStart(Swoole\Server $server,  $workerId)
     {
         define('APP_PATH', __DIR__ . '/../application/');
        // 加载thinkphp5.0 框架的基础文件
         // ThinkPHP 引导文件
         // 1. 加载基础文件
         //require __DIR__ . '/../thinkphp/base.php';

         require_once __DIR__ . '/../thinkphp/start.php';
         require_once  __DIR__."/../vendor/autoload.php";
         //创建共享内容数据表
         $this->table = new  Swoole\Table(1024);
         $this->table->column('fd', Swoole\Table::TYPE_INT, 4); //1,2,4,8
         $this->table->column('user_id', Swoole\Table::TYPE_INT, 4);
         $this->table->column('friend_user_id', Swoole\Table::TYPE_INT,4);
         $this->table->column('flag', Swoole\Table::TYPE_INT,1);
         $this->table->create();
     }


    /**
     * 监听请求事件
     * @param $request 请求对象
     * @param $response 响应对象
     */
    public function  onRequest($request,$response)
    {
        //处理跨域请求问题
        $response->header("Content-Type","application/json;charset=utf8");
        $response->header("Access-Control-Allow-Origin","*");
        $response->header("Access-Control-Allow-Headers","*");

        $_GET=[];
        if (isset($request->get)) {
            $_GET = $request->get;
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_POST = [];
        if (isset($request->post)) {

            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }

        }
        $_SERVER = [];
        if (isset($request->header)) {
              foreach ($request->header as $k=> $v) {
                  $_SERVER[$k] = $v;
              }
        }
        $_COOKIE = [];
        if (isset($request->cookie)) {
            foreach ($request->cookie as $k => $v ) {
                $_COOKIE[$k] = $v;
            }
        }
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v ) {
                $_FILES[$k] = $v;
            }
        }
        //传递ws服务器对象
        $_POST['ws'] = $this->ws;
        ob_start();
        ob_clean();
        // 执行应用并响应
        try {
            // 2. 执行应用
            \think\App::run()->send();

        } catch (Exception $e) {
           $json = [
               "code" => $e->getCode()?$e->getCode():500,
               "msg" => $e->getMessage()
           ];
           echo json_encode($json,JSON_UNESCAPED_UNICODE);
        }

        $res = ob_get_contents();
      //  ob_end_clean();

        $response->end($res);


    }
    /**
     * 异步投递任务事件
     * @param $serv  服务器对象
     * @param $task_id 投递任务id
     * @param $src_worker_id  进程id
     * @param $data 来自客户端的数据
     */
    public  function  onTask( $serv,  $task_id,  $src_worker_id,  $data)
    {
        echo "task 进程开始工作....".json_encode($data).PHP_EOL;
        $task = new \app\common\controller\task\Task();
        $method = $data['method'];
        $task->$method($data);
        //task 任务分发
        return $task_id;
    }

    /**
     * 投递任务完成事件
     * @param $serv 服务器对象
     * @param $task_id 任务id
     * @param $data  异步任务投递返回来的数据
     */

    public function  onFinish( $serv,  $task_id,  $data)
    {

        echo "投递任务完成taskId:{$task_id}";
    }
    /**
     * 监听客户端发送消息事件
     * @param $ws
     * @param $frame
     */
    public function  onMessage($ws,$frame)
    {

        $catch_data = $this->table->get(strval($frame->fd));
        //接收倒的数据
        $receive_data = $frame->data;

        $receive_data_array = json_decode($receive_data,true);

         $friend_user_id = $receive_data_array['friend_user_id'];
         $user_id = $receive_data_array['user_id'];


        if (!$catch_data) {
            $catch_data['flag'] = 1;
            $catch_data['user_id'] = $receive_data_array['user_id'];
            $catch_data['friend_user_id'] = $friend_user_id;
            $catch_data['fd'] = $frame->fd;
            $this->table->set(strval($frame->fd),$catch_data);
            $fd = $frame->fd;
            $user_key = \app\common\controller\redis\ManageRedisKey::friendKey($user_id);
            \app\common\controller\redis\Predis::getInstance()->set($user_key,strval($fd),24*3600);
        } else {
            $replay_content = $receive_data_array['data'];
            var_dump($replay_content);
            echo PHP_EOL;
            $friend_key = \app\common\controller\redis\ManageRedisKey::friendKey($friend_user_id);
            $friend_fd = \app\common\controller\redis\Predis::getInstance()->get($friend_key);
            var_dump($friend_fd);
            if ($replay_content) {
                $ws->push($friend_fd,$replay_content);
            }

        }


    }
    /**
     * websocekt 客户端连接事件
     * @param $ws 服务端对象
     * @param $request 请求对象
     */
    public  function onOpen($ws,$request)
    {
        echo "客户端:{$request->fd}:连接了\n";
       // $this->table->set(strval($request->fd),["fd"=>$request->fd]);
        \app\common\controller\redis\Predis::getInstance()->sAdd(config("live.live_list_key"),$request->fd);

    }


    /**
     * 监听客户端关闭事件
     * @param $ws
     * @param $fd
     */
    public  function  onClose($ws,$fd)
    {
        echo "客户端:{$fd}:关闭连接\n";
        \app\common\controller\redis\Predis::getInstance()->sRem(config("live.live_list_key"),$fd);
       //删除对应的内存数据
        $catch_data = $this->table->get(strval($fd));
        $user_id = $catch_data['user_id'];
        $friend_key = \app\common\controller\redis\ManageRedisKey::friendKey($user_id);
        \app\common\controller\redis\Predis::getInstance()->del($friend_key);
        $this->table->del($fd);
    }
}

//创建http服务器
$ws = new Ws();