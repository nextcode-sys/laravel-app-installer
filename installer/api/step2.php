<?php
$params = $_POST;

$result = [
	'result' => false,
];

/**
 * バリデーション & エラーページを表示
 */
if ( ! isset( $params['submit'] ) || empty( $params['submit'] ) ) {
	$result['message'] = '実行リクエストが正しくありません';
    pageJson( $result );
}
if ( ! isset( $params['mysql_host'] ) || empty( $params['mysql_host'] ) ) {
    $result['message'] = '「MySQL ホスト」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mysql_port'] ) || empty( $params['mysql_port'] ) ) {
    $result['message'] = '「MySQL ポート」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mysql_database'] ) || empty( $params['mysql_database'] ) ) {
    $result['message'] = '「MySQL データベース」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mysql_username'] ) || empty( $params['mysql_username'] ) ) {
    $result['message'] = '「MySQL ユーザ名」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mysql_password'] ) || empty( $params['mysql_password'] ) ) {
    $result['message'] = '「MySQL パスワード」が指定されていません。';
    pageJson( $result );
}

if ( ! isset( $params['mail_driver'] ) || empty( $params['mail_driver'] ) ) {
    $result['message'] = '「メール ドライバー」が指定されていません。';
    pageJson( $result );
}
// メールドライバーがSMTPなら追加で検証
if ( $params['mail_driver'] === 'smtp' ) {
    if ( ! isset( $params['mail_host'] ) || empty( $params['mail_host'] ) ) {
        $result['message'] = '「メール ホスト」が指定されていません。';
        pageJson( $result );
    }
    if ( ! isset( $params['mail_port'] ) || empty( $params['mail_port'] ) ) {
        $result['message'] = '「メール ポート」が指定されていません。';
        pageJson( $result );
    }
    if ( ! isset( $params['mail_username'] ) || empty( $params['mail_username'] ) ) {
        $result['message'] = '「メール ユーザ名」が指定されていません。';
        pageJson( $result );
    }
    if ( ! isset( $params['mail_password'] ) || empty( $params['mail_password'] ) ) {
        $result['message'] = '「メール パスワード」が指定されていません。';
        pageJson( $result );
    }
//    if ( ! isset( $params['mail_encryption'] ) || empty( $params['mail_encryption'] ) ) {
//        $result['message'] = '「メール 暗号化方式」が指定されていません。';
//        pageJson( $result );
//    }
}
if ( ! isset( $params['mail_from_addr'] ) || empty( $params['mail_from_addr'] ) ) {
    $result['message'] = '「メール 送り元メールアドレス」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mail_from_name'] ) || empty( $params['mail_from_name'] ) ) {
    $result['message'] = '「メール 送り元名」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['mail_test_addr'] ) || empty( $params['mail_test_addr'] ) ) {
    $result['message'] = '「メール テストメール送信先アドレス」が指定されていません。';
    pageJson( $result );
}


/**
 * 処理
 */
try
{
    // PHPのバージョン取得
    $php = phpver();

    // MySQLの接続確認
    try {
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', $params['mysql_database'], $params['mysql_host'], $params['mysql_port']);
        $username = $params['mysql_username'];
        $password = $params['mysql_password'];
        $dbh = new PDO($dsn, $username, $password);
    } catch ( Exception $e ) {
        throw new Exception('データベースに接続が行えません。');
    }

    // 環境設定ファイルにMySQL情報を書き込み
    replaceEnvValue( PATH_ENV, 'DB_HOST', $params['mysql_host'] );
    replaceEnvValue( PATH_ENV, 'DB_PORT', $params['mysql_port'] );
    replaceEnvValue( PATH_ENV, 'DB_DATABASE', $params['mysql_database'] );
    replaceEnvValue( PATH_ENV, 'DB_USERNAME', $params['mysql_username'] );
    replaceEnvValue( PATH_ENV, 'DB_PASSWORD', $params['mysql_password'] );

    exec(sprintf('cd %s', PATH_BASE) . '&& ' . sprintf('%s artisan config:cache', $php));

    // 「migrate:refresh」を実行
    exec(sprintf('cd %s', PATH_BASE) . '&& echo yes | ' . sprintf('%s artisan migrate:fresh', $php));

    // メールの設定を保存
    replaceEnvValue( PATH_ENV, 'MAIL_DRIVER', $params['mail_driver'] );
    replaceEnvValue( PATH_ENV, 'MAIL_HOST', $params['mail_host'] );
    replaceEnvValue( PATH_ENV, 'MAIL_PORT', $params['mail_port'] );
    replaceEnvValue( PATH_ENV, 'MAIL_USERNAME', $params['mail_username'] );
    replaceEnvValue( PATH_ENV, 'MAIL_PASSWORD', $params['mail_password'] );
    replaceEnvValue( PATH_ENV, 'MAIL_ENCRYPTION', $params['mail_encryption'] );
    replaceEnvValue( PATH_ENV, 'MAIL_FROM_ADDRESS', $params['mail_from_addr'] );
    replaceEnvValue( PATH_ENV, 'MAIL_FROM_NAME', $params['mail_from_name'] );

    exec(sprintf('cd %s', PATH_BASE) . '&& ' . sprintf('%s artisan config:cache', $php));

    // メール送信確認
    // tinkerを使用して対話方で処理
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin は、子プロセスが読み込むパイプです。
        1 => array("pipe", "w"),  // stdout は、子プロセスが書き込むパイプです。
        2 => array("file", "/tmp/error-output.txt", "a") // はファイルで、そこに書き込みます。
    );
    $cwd = PATH_BASE;
    $process = proc_open( sprintf('%s artisan tinker', $php), $descriptorspec, $pipes, $cwd );

    if (is_resource($process)) {
        // $pipes はこの時点で次のような形を取っています。
        // 0 => 子プロセスの stdin に繋がれた書き込み可能なハンドル
        // 1 => 子プロセスの stdout に繋がれた読み込み可能なハンドル
        // すべてのエラー出力は /tmp/error-output.txt に書き込みされます。

        fwrite($pipes[0], sprintf(
            'Mail::raw("テストメール送信完了", function($message) { $message->from("%s", "%s")->to("%s")->subject("テストメール"); });',
            $params['mail_from_addr'],
            $params['mail_from_name'],
            $params['mail_test_addr']
        ));
        fclose($pipes[0]);

        $mail_result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // デッドロックを避けるため、proc_close を呼ぶ前に
        // すべてのパイプを閉じることが重要です。
        $return_value = proc_close($process);

        // メールが正常に遅れていない場合
        if ( strpos( $mail_result, '>>> => null' ) === false ) {
            throw new Exception('テストメール送信に失敗しました。');
        }
    } else {
        throw new Exception('テストメール送信の為の準備に失敗しました。');
    }

    //ステップの完了を保存
    completeStep( 'step2' );
}
catch ( Exception $e )
{
    $result['message'] = $e->getMessage();
    pageJson( $result );
}

$result['result'] = true;
$result['message'] = 'ステップ２が完了しました。７秒後に次のステップに進みます。';

pageJson( $result );
