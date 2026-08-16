# todo

## 概要
COACHTECH 旧教材 Laravel演習講座「Todoアプリ初級編」「Todoアプリ中級編」で作成した成果物です。
TodoとカテゴリーのCRUD機能、検索機能を実装しました。

## 使用技術
- PHP 8.2
- Laravel 10.0

## 環境構築・起動方法
### 1.リポジトリのクローン
```bash
git clone https://github.com/hakua411/todo.git
cd todo
```
### 2.環境変数の設定
`.env.example`をコピーして`.env`を作成します。
```bash
cp .env.example .env
```
`.env`内の`DB_HOST`を`mysql`に変更、`DB_USERNAME`を`sail`に変更し、`DB_PASSWORD`に`password`を記述します。
### 3.Laravel Sailのインストール
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    composer require laravel/sail --dev
```
### 4.Sailの設定ファイルをパブリッシュ（MySQLを選択）
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    php artisan sail:install --with=mysql
```
### 5.Sailの起動
```bash
./vendor/bin/sail up -d
```
### 6.アプリケーションキーの生成
```bash
./vendor/bin/sail artisan key:generate
```
### 7.マイグレーションを実行
```bash
./vendor/bin/sail artisan migrate
```

すべて完了したら、ブラウザで`http://localhost`にアクセスします。
