# コーチテックフリマ

## 概要
このプロジェクトは、ユーザーが商品を出品・購入できるフリマアプリです。  
ユーザーは商品の検索、購入、コメント、いいねなどの機能を利用できます。

## 主な機能
- **ユーザー登録 & ログイン**
- **商品一覧の閲覧**
- **商品の出品**
- **商品の購入**
- **いいね機能**
- **コメント機能**
- **購入履歴の確認**
- **プロフィール編集**
- **配送先住所の変更**

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:H1g4s4/Pigly.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seedphp artisan storage:link
```

## 使用技術

- **PHP**:8.83.8
- **Laravel**:7.4.9
- **MySQL**:10.3.39

## テーブル設計
<img width="621" alt="スクリーンショット 2024-12-09 21 55 31" src="https://github.com/user-attachments/assets/8ad65635-ac3d-4e45-b272-94300e36c098">
<img width="739" alt="スクリーンショット 2024-12-09 21 55 52" src="https://github.com/user-attachments/assets/4fdb9617-d057-46a8-8090-2f5a85cd0acd">
<img width="744" alt="スクリーンショット 2024-12-09 21 56 04" src="https://github.com/user-attachments/assets/fcf67ce2-382f-427b-b029-d3ad42968c03">

## ER図
<img width="712" alt="スクリーンショット 2024-12-09 21 41 45" src="https://github.com/user-attachments/assets/d0882c3b-e27c-4617-bb06-333aab31e589">

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
