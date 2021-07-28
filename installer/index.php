<?php
/**
 * 表示する画面の選別
 */
$current_step = @file_get_contents('.comp_step');

ob_start();
switch ( $current_step ) {
    default:
        include("contents/step1.php");
        break;
    case 'step1':
        include("contents/step2.php");
        break;
    case 'step2':
        include("contents/step3.php");
        break;
    case 'step3':
        header('Location: /');
        exit;
        break;
}
$contents = ob_get_contents();
ob_end_clean();
?>


<?php
/**
 * 画面の表示
 */
include 'common/header.php';
echo $contents;
include 'common/footer.php';
?>

