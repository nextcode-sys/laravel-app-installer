/**
 * ローダーの表示
 */
function showLoader()
{
    $('.loader-wrapper').show().addClass('show');
}

/**
 * ローダーの非表示
 */
function hideLoader()
{
    $('.loader-wrapper').hide().removeClass('show');
}

/**
 * エラーメッセージの表示
 * @param message
 */
function errorMessage( message )
{
    messageClear();

    $('.error-message-wrapper .txt-error-message').text( message );
    $('.error-message-wrapper').show();

    window.scrollTo(0, 0, {
        behavior: 'smooth'
    });
}

/**
 * 成功メッセージの表示
 * @param message
 */
function successMessage( message )
{
    messageClear();

    $('.success-message-wrapper .txt-success-message').text( message );
    $('.success-message-wrapper').show();

    window.scrollTo(0, 0, {
        behavior: 'smooth'
    });
}

function messageClear()
{
    $('.error-message-wrapper .txt-error-message').text( '' );
    $('.error-message-wrapper').hide();

    $('.success-message-wrapper .txt-success-message').text( '' );
    $('.success-message-wrapper').hide();
}
