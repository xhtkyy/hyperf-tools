# xhtkyy/hyperf-tools

## 快速开始

composer require xhtkyy/hyperf-tools

## 发布配置

php bin/hyperf.php vendor:publish xhtkyy/hyperf-tools

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
    Xhtkyy\HyperfTools\App\Exception\AppException::class
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
    