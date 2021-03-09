phpmeet
===============
phpmeet是一款专注于web和api开发的轻量php框架，具备常用的mvc架构模式。摆脱传统重量级框架所带来的性能损耗，一切由简而出

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)

主要特性:
  + 内置路由
  + 依赖注入
  + 服务治理服务
  + 实例注册
  + 数据连接服务
  + 控制器前置和后置策略
  + 命令行访问支持

## 安装
#### 1、composer快速安装
~~~
composer create-project phpmeet/src phpmeet --prefer-dist
~~~
#### 2、git安装
~~~
git clone https://gitee.com/phpmeet/src.git
~~~
## 目录结构
~~~
├─app              应用目录
│  ├─common        公共模块方法
├─bin              命令行模块目录
├─framework        核心框架
│  ├─cache         缓存
│  ├─config        配置目录
│  ├─library       核心类库
│  ├─route         路由目录
├─public           静态目录
├─vendor           composer目录
~~~
## 部署
#### 1、nginx部署
~~~
server {
        listen       80 default_server;
        listen       [::]:80 default_server;
        server_name  localhost;
        root         /www/src;

        # Load configuration files for the default server block.
        include /etc/nginx/default.d/*.conf;

        location / {
            # 这里改动了 定义首页索引文件的名称
            index index.php index.html index.htm;
        }

        error_page 404 /404.html;
            location = /40x.html {
        }

        error_page 500 502 503 504 /50x.html;
            location = /50x.html {
        }

        location ~ \.php$ {
            # 设置监听端口
            fastcgi_pass   127.0.0.1:9000;
            # 设置nginx的默认首页文件(上面已经设置过了，可以删除)
            fastcgi_index  index.php;
            # 设置脚本文件请求的路径
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            # 引入fastcgi的配置文件
            include        fastcgi_params;
        }
    }

~~~
#### 2、apache部署
~~~
<VirtualHost *:80>
    DocumentRoot "/www/src"
    ServerName zhibo.video_php.com
    ServerAlias gohosts.com
  <Directory "/www/src">
      Options FollowSymLinks ExecCGI
      AllowOverride All
      Order allow,deny
      Allow from all
     Require all granted
  </Directory>
</VirtualHost>
~~~

## 联系
 + 邮箱 873026940@qq.com