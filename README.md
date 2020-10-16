#使用laravel 6.0LTS 版本进行开发的定制版CRM

#安装方法

composer insatll

#如果添加了新的包

#执行
composer update


#复制.env.example重命名为.env，.env中 修改或添加APP_LOCALE=cn

#生成key 
php artisan key:generate

#更改数据库等配置信息

#数据操作

#发布权限控制数据迁移
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

#队列失败任务
php artisan queue:failed-table

php artisan migrate

#发布权限控制配置文件
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

php artisan db:seed

#队列开启
php artisan queue:work

#计划任务crond
* * * * * cd /phpadmin && php artisan schedule:run > /dev/null 2>&1


### 清除权限缓存
php artisan permission:cache-reset


### Supervisor
Supervisor 的配置文件通常位于 /etc/supervisor/conf.d 目录下

queue:work 默认只执行一次队列请求，当请求执行完成后就终止；
queue:listen 监听队列请求，只要运行着，就能一直接受请求，除非手动终止；
queue:work --daemon 同 listen 一样，只要运行着，就能一直接受请求，不一样的地方是在这个运行模式下，当新的请求到来的时候，不重新加载整个框架 , 而是直接 fire 动作.
能看出来，queue:work --daemon 是最高级的，一般推荐使用这个来处理队列监听.
