# laravel-work-exception
使用企业微信通知你的系统异常

## 特色
* 支持多个项目不一样的通知群，使你的bug通知更加精准到位
* 自带创建群聊命令，创建好你只负责把相关负责人拉到群里即可
* 一段时间内多个一样bug的触发不会通知多次

## 安装

```
composer install hanson/wechat-work-exception:dev-master -vvv
```

## 配置

生成 `wor_exception.php` 配置
```
php artisan vendor:publish --tag=work-exception
```
修改 `work` 底下的配置 （需要在企业微信后台创建“自建应用”）

执行
```
php artisan work:chat
```
根据提示一步一步去创建群聊，并且复制创建成功后的 chat id，黏贴到 `work.chatid` 的配置


## 使用

 在 laravel 的 Handler 类下
 
 ```
use Hanson\WorkException\WorkExceptionHelper;

// ...

public function report(Exception $exception)
{
    // 不输出 trace 信息
    (new WorkExceptionHelper())->handle($exception);
    
    // 输出 trace 信息
    (new WorkExceptionHelper())->withTrace()->handle($exception);

    parent::report($exception);
}
 ```