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
if ( ! isset( $params['php_version'] ) || empty( $params['php_version'] ) ) {
    $result['message'] = '「使用するPHPのバージョン」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['app_name'] ) || empty( $params['app_name'] ) ) {
    $result['message'] = '「アプリケーション名」が指定されていません。';
    pageJson( $result );
}
if ( ! isset( $params['app_url'] ) || empty( $params['app_url'] ) ) {
    $result['message'] = '「アプリケーションURL」が指定されていません。';
    pageJson( $result );
}


/**
 * 処理
 */
try
{
    // PHPのバージョン確認
    if ( (float) phpversion() < 7.2 ) {
        throw new Exception('PHPのバージョンを7.2以上にして下さい。レンタルサーバーを使用の場合、切り替えが出来ていない可能性があります。');
    }

    // PHPのバージョン取得
    exec('php -v', $output1, $response1);
    exec(sprintf('php%s -v', $params['php_version']), $output2, $response2);
    if ( count( $output1 ) <= 0 && count( $output2 ) <= 0 ) {
        throw new Exception('PHPのバージョン取得に失敗しました。');
    }

    $php = '';
    if ( count( $output1 ) > 0 ) $php = 'php';
    if ( count( $output2 ) > 0 ) $php = sprintf('php%s', $params['php_version']);

    // PHPのバージョンを保存
    phpver( $php );

    // 「composer dump-aut」　の実行
    exec(sprintf('cd %s', PATH_BASE) . ' && ' . sprintf('%s composer.phar dump-auto', $php));

    // .envのコピー
    if ( ! is_file( PATH_ENV ) ) {
        copy( PATH_BASE . '/.env.example', PATH_ENV );
        if ( ! is_file( PATH_ENV ) ) {
            throw new Exception('「' . PATH_ENV . '」の作成に失敗しました。');
        }
    }

    // psyshのconfigファイルのホームディレクトリを設定
    replaceEnvValue( PATH_ENV, 'XDG_CONFIG_HOME', PATH_BASE . '/.config' );

    // 「key:generate」の実行
    if ( ! getEnvValue( PATH_ENV, 'APP_KEY' ) ) {
        exec(sprintf('cd %s', PATH_BASE) . ' && ' . sprintf('%s artisan key:generate', $php));
    }

    // 「storage:link」と同等の内容の実行
    if ( ! @readlink(PATH_PUBLIC . '/storage') ) {
        @symlink( PATH_STORAGE . '/app/public', PATH_PUBLIC . '/storage' );
        if ( ! @readlink(PATH_PUBLIC . '/storage') ) {
            throw new Exception('「' . PATH_PUBLIC . '/storage' . '」のシンボリックリンク作成に失敗しました。');
        }
    }

    // アプリケーション名の設定
    replaceEnvValue( PATH_ENV, 'APP_NAME', $params['app_name'] );

    // アプリケーションURLの設定
    replaceEnvValue( PATH_ENV, 'APP_URL', rtrim( $params['app_url'], '/' ) );

    //ステップの完了を保存
    completeStep( 'step1' );
}
catch ( Exception $e )
{
    $result['message'] = $e->getMessage();
    pageJson( $result );
}

$result['result'] = true;
$result['message'] = 'ステップ１が完了しました。７秒後に次のステップに進みます。';

pageJson( $result );
