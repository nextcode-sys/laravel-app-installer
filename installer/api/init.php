<?php
define('PATH_BASE', dirname(dirname(dirname(__DIR__))));
define('PATH_PUBLIC', dirname(dirname(__DIR__)));
define('PATH_STORAGE', PATH_BASE . '/storage');
define('PATH_INSTALLER', PATH_PUBLIC . '/installer');
define('PATH_API', PATH_INSTALLER . '/api');

define('PATH_ENV', PATH_BASE . '/.env');

/**
 * 404ページを表示
 */
function page404() : void
{
    header( "HTTP/1.1 404 Not Found" );
    exit;
}

/**
 * JSON出力を行う
 *
 * @param array $output
 * @return void
 */
function pageJson( array $output )
{
    header("Content-Type: text/javascript; charset=utf-8");
    echo json_encode( $output );
    exit;
}

/**
 * ステップの完了をファイルの保存
 * @param string $key
 * @return void
 */
function completeStep( string $key )
{
    file_put_contents( PATH_INSTALLER . '/.comp_step', $key );
}

/**
 * PHPのバージョンを取得したり、保存する
 * @param string|null $value
 * @return string|void
 */
function phpver( string $value = null )
{
    $php_version_path = PATH_INSTALLER . '/.php_version';

    if ( is_null( $value ) ) {
        return file_get_contents( $php_version_path );
    } else {
        file_put_contents( $php_version_path, $value );
    }
}



/**
 * 環境設定の値を取得
 * @param string $path
 * @param string $param
 * @return false|mixed
 */
function getEnvValue( string $path, string $param )
{
    preg_match( sprintf('/%s=(.*)\n/', $param), file_get_contents( $path ), $match );
    return $match[1] ?? false;
}

/**
 * 環境設定の値を変更
 * @param string $path
 * @param string $param
 * @param mixed $value
 * @return void
 */
function replaceEnvValue( string $path, string $param, $value )
{
    file_put_contents(
        $path,
        str_replace(
            $param.'='.getEnvValue( $path, $param ),
            $param.'='.$value,
            file_get_contents( $path )
        )
    );
}
