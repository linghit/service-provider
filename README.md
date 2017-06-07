# 服务授权检查扩展

## 安装
```
composer require exts/service-authenticate
```

## 配置
```
#file: config/config.php

return [
    'service' => [
        'name' => 'xxx',
        'app' => Xxxx::class, // 可选
    ],
];

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
    \Exts\ServiceAuthenticate\Providers\AuthServiceProvider::class,
],
```

上报路由
```
php bin/console service:report-routes
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

## RESTful 路由

提供了一个定制的 `Router` 用以方便地注册 RESTful 路由.

要使用, 需要去掉系统默认的 `LoggerServiceProvider`, 并使用这个包中提供的 `LoggerServiceProvider`

```

    \FastD\ServiceProvider\RouteServiceProvider::class,
//  \FastD\ServiceProvider\LoggerServiceProvider::class,
    \FastD\ServiceProvider\DatabaseServiceProvider::class,
    \FastD\ServiceProvider\CacheServiceProvider::class,
    \Exts\ServiceAuthenticate\Providers\AuthServiceProvider::class,
    \Exts\ServiceAuthenticate\Providers\RouteServiceProvider::class,
```

控制器需要实现 `Exts\ServiceAuthenticate\Controllers\ResourceInterface`

路由配置

```
#file: app/routes.php

route()->resource('users', 'UsersController');
```

## 应用接入

类似于支付服务, 内部需要一套 app 机制来识别接入的业务, 可直接使用 app 服务中的 app.

例如:
```
use Exts\ServiceAuthenticate\AbstractServiceApp;

class TradingApp extends AbstractServiceApp
{
    protected function getAttributes()
    {
        return model('apps')->find($this->app->id());
    }
    
    public function totalPayments()
    {
        return models('payments')->count([
            'app_id' => $this->app->id(),
        ]);
    }
}
```

然后添加配置
```
#file: config/config.php

return [
    'service' => [
        'name' => 'trading',
        'app' => TradingApp::class,
    ],
];

```

应用请求服务时, 会将 `app_id` 放在 request header, 用作认证. 认证完成后, 在控制器中可以通过 `auth()->app()->id()` 获取到 `app_id`

## TODO
- 提供统一签名生成及验签工具
- 