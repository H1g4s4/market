# **COACHTECH フリマアプリ**

## **📌 概要**
COACHTECH フリマアプリは、ユーザーが商品を出品・購入できる Laravel 製のフリマアプリケーションです。以下の機能を提供します。

### **🛍️ 主な機能**
- **ユーザー認証**
  - ユーザー登録 / ログイン / ログアウト
- **商品管理**
  - 商品の出品
  - 商品一覧の表示
  - 商品詳細の表示
  - 出品商品の編集
- **いいね機能**
  - 商品への「いいね」
  - いいねした商品のリスト表示
- **購入機能**
  - 商品の購入
  - 配送先情報の変更
- **コメント機能**
  - 商品に対するコメント投稿

---

## **🛠️ セットアップ手順**
### **1. クローン & 移動**
```sh
git clone <リポジトリURL>
cd <リポジトリフォルダ>

### **2. 環境設定**
```sh
cp .env.example .env

データベース接続情報を.env設定
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coachtech_fleamarket
DB_USERNAME=root
DB_PASSWORD=

### **3.　パッケージのインストール**
```sh
composer install
npm install

### **4.　アプリケーションキーの生成**
```sh
php artisan key:generate

### **5.　データベースのマイグレーションとシード**
```sh
php artisan migrate --seed

## **🚀 開発環境の起動**
