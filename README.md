# laravel-app-installer
Laravelを使用したアプリのインストーラー  
Wordpressの様にアプリ本体（パッケージ）をFTPでサーバーにアップロードを行い、インストールを行える様にするといった物になります。  
なお、公開している物はサンプルになりますので、各々の開発したアプリに合わせてlaravel-app-installer本体を変更して下さい。

---

## 前提
* PHPのバージョンは「７」以上
* Laravelのバージョンは「６」
* composerやnpmでインストールした物（vendorやnode_modules）はパッケージに含めた状態とする
* ```.env```は削除し、```.env.example```を用意する
* 確認済みの動作環境はXserverのみ
* laravel-app-installerの改変は必須


## 使用方法
1. Laravelアプリ（開発したアプリ）のpublic直下に「installer」を配置する
2. 終わり


## インストールの流れ   
1. FTPで開発したアプリをサーバーにアップロード
2. http://○○○○.○○/installerにアクセス
3. ステップに沿ってインストール実行
4. 終わり

---

### サンプルが内部で行っている事
* ```.env```ファイルの作成
* ```composer dump-auto```の実行
* ```php artisan key:generate```の実行
* ```php artisan storage:link```の実行
* DBの接続確認、及び設定を```.env```へ書き込み
* ```php artisan migrate:fresh```の実行
* メールのテスト送信、及び設定を```.env```へ書き込み
* 管理者アカウントの追加
* インストール完了後のインストーラーの削除

管理者アカウントの追加部分はテーブル名が異なると思われるので、必ず```installer/api/step3.php```を変更して下さい。

---

ご自身での変更が難しい場合は、変更依頼承ります。  
[https://next-code.jp/contact](https://next-code.jp/contact)　からお問い合わせ下さい。