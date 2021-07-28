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
if ( ! isset( $params['admin_username'] ) || empty( $params['admin_username'] ) ) {
    $result['message'] = '「管理者アカウント　ユーザ名」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['admin_email'] ) || empty( $params['admin_email'] ) ) {
    $result['message'] = '「管理者アカウント　メールアドレス」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['admin_password'] ) || empty( $params['admin_password'] ) ) {
    $result['message'] = '「管理者アカウント　パスワード」が指定されていません。';
    pageJson( $result );
}


/**
 * 処理
 */
try
{
    // PHPのバージョン取得
    $php = phpver();

    // tinkerを使用して対話方で処理
    $descriptorspec = array(
        0 => ['pipe', 'r'],  // stdin は、子プロセスが読み込むパイプです。
        1 => ['pipe', 'w'],  // stdout は、子プロセスが書き込むパイプです。
        8 => ['file', '/tmp/error-output.txt', 'a'] // はファイルで、そこに書き込みます。
    );
    $cwd = PATH_BASE;
    $process = proc_open( sprintf('%s artisan tinker', $php), $descriptorspec, $pipes, $cwd );

    if (is_resource($process)) {
        // $pipes はこの時点で次のような形を取っています。
        // 0 => 子プロセスの stdin に繋がれた書き込み可能なハンドル
        // 1 => 子プロセスの stdout に繋がれた読み込み可能なハンドル
        // すべてのエラー出力は /tmp/error-output.txt に書き込みされます。

        $now = date('Y-m-d H:i:s');
        fwrite($pipes[0], sprintf(
            'App\Models\AdminMember::insert(["name" => "%s", "email" => "%s", "password" => encrypt("%s"), "remember_token" => Str::random(60), "updated_at" => "%s", "created_at" => "%s"])',
            $params['admin_username'],
            $params['admin_email'],
            $params['admin_password'],
            $now,
            $now
        ));
        fclose($pipes[0]);

        $admin_insert_result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // デッドロックを避けるため、proc_close を呼ぶ前に
        // すべてのパイプを閉じることが重要です。
        $return_value = proc_close($process);

        if ( strpos( $admin_insert_result, '>>> => true' ) === false ) {
            throw new Exception('管理者アカウントの追加に失敗しました。');
        }
    } else {
        throw new Exception('管理者アカウント作成の為の準備に失敗しました。');
    }

    // 最後にキャッシュをクリア
    exec(sprintf('cd %s', PATH_BASE) . '&& ' . sprintf('%s artisan cache:clear', $php));

    //ステップの完了を保存
    completeStep( 'step3' );

    // インストーラーのディレクトリを削除（バックグラウンドで実行）
    exec(sprintf('(sleep 10; rm -rf %s > /dev/null) &', PATH_INSTALLER));
}
catch ( Exception $e )
{
    $result['message'] = $e->getMessage();
    pageJson( $result );
}

$result['result'] = true;
$result['message'] = 'ステップ３が完了しました。７秒後にホームに進みます。';

pageJson( $result );
