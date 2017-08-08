# 服务授权检查扩展

## 安装
```
composer require linghit/service-gateway
```

## 使用

注册服务提供者
```
#file: config/app.php

'services' => [
    \FastD\ServiceProvider\RouteServiceProvider::class,
    \FastD\ServiceProvider\LoggerServiceProvider::class,
    \FastD\ServiceProvider\DatabaseServiceProvider::class,
    \FastD\ServiceProvider\CacheServiceProvider::class,
    \Exts\ServiceGateway\Providers\GatewayServiceProvider::class,
],
```

## 模型

提供简单 CRUD 的模型, 例子:
```

use Exts\ServiceAuthenticate\Models\Model;

class Users extends Model
{
    protected $table = 'users';
    
    protected $primaryKey = 'id';
    
    protected $perPage = 15;
}

```

## 消费者
创建一个 ConsumerBuilder, 实现 `Exts\ServiceGateway\Contracts\ConsumerBuilderInterface`
```
namespace Support;

use Exts\ServiceGateway\Contracts\ConsumerBuilderInterface;

class ConsumerBuilder implements ConsumerBuilderInterface
{
    public function apply($id)
    {
        return database()->get('apps', '*', [
            'id' => $id
        ]);
    }
}
```

在控制器中, 获取调用服务的消费者
```
namespace Controller;

class IndexController
{
    public function index()
    {
        return json(gateway_consumer()->getArrayCopy());
    }
}
```