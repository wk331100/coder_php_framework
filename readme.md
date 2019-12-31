@(Coder1.0)[简单|高性能|开发效率高|学习成本低|不依赖其他程序]

**现状：** 目前市面上PHP的框架数不胜数，FPM类的框架主要分为两大类：C编写的扩展，PHP编写的框架。 C编写的扩展目前比较流行的有： 鸟哥的Yaf，国外的Phalcon，和360 MISC的 ASF，这类框架主要特点就是运行速度快，比较适合用于逻辑简单的、并发要求比较高的业务场景。PHP编写的框架，目前Laravel系列半壁江山，还有其他的Yii、Symfony、TP、CI等。这类的框架有一个特点：大而全。或许这些的框架开发者们觉得，从速度上比不过C编写的框架，那么在功能上一定要胜出。所以，这类框架都有着非常丰富的组件，依赖注入等设计模式。但是随着版本的迭代，这类的框架发展的像是一个航母一样，越来越像Zend Framework。

**C编写的扩展：** 虽然运行速度比较快，但是安装繁琐，既要根据操作系统选择版本，也要编译安装PHP的扩展，操作起来麻烦。而且一旦业务系统大量操作数据库、缓存、API、等操作。这类框架的提升的那点性能，几乎就可以忽略不计。

**Composer：** 目前PHP最火的包管理器，目前大多数新框架都依赖于Composer。 

**所以：**  能否有一个PHP框架，除了PHP不依赖于任何扩展和程序，像很多年以前的框架那样，下载下来就能运行？ 
能否：没有那么多大多数场景都用不到的组件和功能，简单、快捷、开发效率高、学习成本低？

> Coder  就是这样的框架：没有其他依赖下载即用，简单学习成本低，开发效率高，运行性能强，服务化，API专用

#安装

## 服务器要求

对于高性能的要求，框架采用PHP7的语法。建议使用php7.2及以上。Openssl用于接口加密开发时候，内置的非对称加密RSA和对称加密RES等， DB类采用PDO Prepare的方式操作数据库，这样可以有效防止Sql注入。系统使用mb_开头的函数，因此需要安装Mbstring扩展。 以上这些，基本是目前市面上最常用的扩展。

- PHP >= 7.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension

## 下载

官网下载：
> http://getcoder.cn/download

Github下载：
> https://github.com/wk331100/coder_php_framework

下载完成后，直接解压到网站目录即可。


## Nginx配置
在Nginx配置文件中，Server{} 里面配置：
```nginx
location / {
        try_files $uri $uri/ /index.php?_url=$uri&$args;
}

location ~ \.php$ {
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index /index.php;

        include fastcgi_params;
        fastcgi_param env            "dev";   #配置系统环境为开发，线上部署配置为："production"
        fastcgi_param PATH_INFO       $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

```
