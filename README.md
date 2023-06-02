# xhtkyy/hyperf-tools

## 快速开始

composer require xhtkyy/hyperf-tools

## 发布配置

php bin/hyperf.php vendor:publish xhtkyy/hyperf-tools

## 更新

### 20230602

- 增加服务支持Streaming模式
可参照健康检查watch方法的使用
```php
$stream = new Stream();
//支持流式 多次写入
$stream->write($response)
$stream->write($response)
$stream->write($response)
...
// 记得关闭，不然还继续走 普通响应
$stream->close();
```
- Grpc Health Watch 增加 Streaming 支持

### 20230536

- 移除overwrite 增加class_map覆盖代码，只需要配置 annotations 增加如下

```php
return [
    'scan' => [
        ...
        'class_map' => [
            //需要反射增加
            \Google\Protobuf\Internal\DescriptorPool::class => BASE_PATH.'/vendor/xhtkyy/hyperf-tools/class_map/protobuf/DescriptorPool.php',
            //需要nacos支持阿里nacos的需要增加
            \Hyperf\Nacos\AbstractProvider::class => BASE_PATH.'/vendor/xhtkyy/hyperf-tools/class_map/nacos/AbstractProvider.php',
        ]
    ],
];
```

- 增加支持错误响应携带状态码
  需要注意 这里将错误返回的字符串变成对象 可以加(string)$reply 转字符串
```php
//使用
\Xhtkyy\HyperfTools\Grpc\Exception\GrpcError::throw("错误信息", -1);
```

## 基础

### 容器

- 通过注入获取（推荐）
   ```php
  public function __construct(protected Xhtkyy\HyperfTools\App\ContainerInterface $container) {
   }
  //使用
  $this->container->get(...);
  // 或者 通过声明注入
  [Inject]
  protected Xhtkyy\HyperfTools\App\ContainerInterface
  ```
- 通过实例化(不推荐)
  Xhtkyy\HyperfTools\App\Container
- 通过助手函数
  di(class)

### 异常

异常是需要规范的，这里举例了 NotFoundException（找不到资源）、InvalidArgumentException(无效参数异常)
其他自定义异常 继承 Xhtkyy\HyperfTools\App\Exception\AppException::class 即可轻松捕抓

### CURD

将基础的CURD封装 继承 Xhtkyy\HyperfTools\CURDRepo\CURDRepo 即可，如

```php
    //继承
    class MessageRepo extends CURDRepo {
        //声明要操作的对象
        protected string $model = Message::class;
    }
    //开始使用
    class MessageController {
        #[Inject]
        protected MessageRepo $messageRepo;
        /**
         * 获取列表
         * @return array
         */
        public function getList(): array {
            $res = $this->messageRepo
                //支持关联
                ->with([
                    "app"      => function ($query) {
                        $query->selectRaw("id,app_name");
                    }
                ])
                //等比查询
                ->eqWhere("id,platform_id,app_id,channel_id,role,status,task_id") //要查询的字段 已经处理判断 请求对象中是否存在
                //范围查询
                ->betweenWhere("created_at")
                //模糊查询
                ->likeWhere("title,accept,user_name")
                //获取列表
                ->getList();
            return $res;
        }
    }
```

### 服务注册/发现

#### 1、发布配置

```
php bin/hyperf.php vendor:publish hyperf/service-governance
php bin/hyperf.php vendor:publish xhtkyy/hyperf-tools
```

#### 2、服务注册

自动发现配置中server_name配置的服务名称，在路由中对应服务名称，如以下 "grpc"

```php
Router::addServer('grpc', function () {
    // 注意添加健康检查
    Router::addGroup('/grpc.health.v1.Health', function () {
        Router::post('/Check', [\Xhtkyy\HyperfTools\Grpc\Health\HealthController::class, 'check']);
        Router::post('/Watch', [\Xhtkyy\HyperfTools\Grpc\Health\HealthController::class, 'watch']);
    },[
        "register" => false //配置不注册到服务发现中心,默认注册
    ]);
    ...
});
```

#### 3、服务发现

使用继承 \Xhtkyy\HyperfTools\GrpcClient\BaseGrpcClient 即可完成服务自动发现 可在配置中配置发现算法 默认轮询

### 链路追踪

使用 Xhtkyy\HyperfTools\Grpc\Middleware\GrpcTraceMiddleware 作用于grpc服务路由即可完成 grpc链路追踪