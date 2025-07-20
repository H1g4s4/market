# COACHTECHフリマ

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
1. `git clone git@github.com:H1g4s4/market.git
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
php artisan db:seed
php artisan storage:link
```

## 使用技術

- **PHP**:8.83.8
- **Laravel**:7.4.9
- **MySQL**:10.3.39

## テーブル設計
<img width="592" alt="スクリーンショット 2025-03-05 18 01 28" src="https://github.com/user-attachments/assets/577c23f8-3b04-4042-a5ff-36af6171b29d" />
<img width="592" alt="スクリーンショット 2025-03-05 18 01 49" src="https://github.com/user-attachments/assets/3348650e-345f-47c6-b0ed-9f21d6f885ec" />
<img width="592" alt="スクリーンショット 2025-03-05 18 02 06" src="https://github.com/user-attachments/assets/c52bafb6-9501-41eb-96d0-620fe639a6b3" />
<img width="592" alt="スクリーンショット 2025-03-05 18 02 18" src="https://github.com/user-attachments/assets/1964ab07-2a3f-4ef9-a2ed-bd2bee7f7789" />

## ER図
<img width="800" alt="スクリーンショット 2025-03-05 16 55 03" src="https://github.com/user-attachments/assets/835e279e-eea1-4c32-8c3b-7b1195cc1b41" />

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
>>>>>>> 18b4584d453f7e2e101f622d1a84d9d0b8cfffcb
