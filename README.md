#### 在SAE标准环境动态执行生成的PHP脚本示例

##### 脚本说明

- push_data.php 写入一个动态的页面示例
- route.php 路由脚本，将制定的页面都路由到这个脚本处理，先查找memcached的缓存，如果没有缓存，则穿透到数据库查询。

##### 服务依赖

- 需要开启memcached服务，如果需要缓存的页面较多，建议开启较大容量
- 需要开启共享型数据库服务，创建的sql为`dynamic.sql`


##### 默认的访问路由

xxx.applinzi.com/dynamic/route_key.html 这个会去请求`route_key`的页面，具体可以修改`config.yaml`中的rewrite规则；当前的规则为：

```
handle:
- rewrite: if(path ~ "/dynamic/(.*)\.html$") goto "/route.php?route_key=$1&%{QUERY_STRING}"
```

将所有的/dynamic/下的后缀为.html路径的请求都转发到根目录下的`route.php`处理了。

##### 示例

1、先访问http://XXXXX.applinzi.com/push_data.php (XXXX是你的应用名)，将执行phpinfo()的PHP程序写到数据库；
2、再访问http://XXXXX.applinzi.com/dynamic/phpinfo.html 就能看到phpinfo的页面了。
