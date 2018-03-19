
# 简介
* 基于laravel5.5 + beginneradmin0.2.5的后端管理系统基础框架，包含登录与权限管理模块。


# 运行环境

* Laravel5.5.*
* Nginx 1.8+
* PHP 7.1+
* Mysql 5.7+

# 开发环境部署/安装

* 1.克隆源代码
 * 克隆源代码到本地：git clone https://github.com/xlongqq/laravel5.5-beginneradmin0.2.5-.git

* 2.配置本地的环境
 * 复制根目录下面的.env.example 为 .env，修改.env中的相关参数（数据库连接等）

* 3.文件权限问题
 * chmod 777 -R public
 * chmod 777 -R storage

* 4.安装
 * a.先修改/app/providers/AuthServiceProvider.php，将boot()方法中的所有代码注释，只留一行$this->registerPolicies();
 * b.composer install;
 * c.php artisan migrate;
 * d.php artisan db:seed;
 * e.还原 /app/providers/AuthServiceProvider.php

* 5.后台登录
 * 配置虚拟主机地址：例如 www.lbadmin.com
 * 在浏览器访问 www.lbadmin.com/manager
 * 输入admin/123456
