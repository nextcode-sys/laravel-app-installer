<?php
require_once './api/init.php';

$params = $_GET;

// モードが存在しなければ、404を表示
if ( ! isset( $params['mode'] ) ) {
	page404();;
}

// モードによって読み込むファイルを変更
switch ( $params['mode'] )
{
	case 'step1':
		require PATH_API . '/step1.php';
		break;
    case 'step2':
        require PATH_API . '/step2.php';
		break;
    case 'step3':
        require PATH_API . '/step3.php';
        break;
	default:
		page404();
		break;
}
