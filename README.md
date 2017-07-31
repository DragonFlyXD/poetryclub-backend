## Intro

[诗词小筑](http://www.dragonflyxd.com)的网站后台页面与后端代码，基于**laravel**+**vue.js**。

> [诗词小筑的前台页面](https://github.com/DragonFlyXD/poetryclub-frontend)

## 技术栈

laravel5.4 + passport + vue2 + element-ui + axios + stylus

## How To Use

```
git clone https://github.com/DragonFlyXD/poetryclub-backend

cd poetryclub-backend

composer install 

php artisan migration

chmod -R 777 storage

/**
 * 将生成的 ID 和 Secret 添加进 .env 文件中
 * 如:
 * PASSWORD_CLIENT_ID=3
 * PASSWORD_CLIENT_SECRET=OdIzVLnICs7dXYz3QEe9xeo1ITr2ugpdrwR1xvGb
 **/
php artisan passport:client --passport
```

* 配置连接数据库信息，默认采用**mysql**。

  ```
  # .env

  DB_DATABASE=your_database
  DB_USERNAME=your_username
  DB_PASSWORD=your_password
  ```

* 配置邮件发送信息，采用**sendclound**。

  ```
  # .env

  MAIL_FROM_ADDRESS=your_address // 如：dragonfly920130@outlook.com
  MAIL_FROM_NAME=your_name // 如：诗词小筑
  SEND_CLOUD_USER=your_user
  SEND_CLOUD_KEY=your_key
  ```

* 配置搜索功能信息，采用**algolia**。

  ```
  # .env

  ALGOLIA_APP_ID=your_id
  ALGOLIA_SECRET=your_secret
  ```

* 配置图片存储功能信息，采用**七牛云**。

  ```
  # .env

  QINIU_ACCESS_KEY=your_access_key
  QINIU_SECRET_KEY=your_secret_key
  QINIU_BUCKET=your_bucket
  QINIU_DOMAIN=your_domain
  ```

## Screenshot

![](https://github.com/DragonFlyXD/poetryclub-backend/blob/master/screenshots/profile.png)

![](https://github.com/DragonFlyXD/poetryclub-backend/blob/master/screenshots/category.png)