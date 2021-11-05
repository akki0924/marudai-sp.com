;
(function ($) {
    /**
     * テキスト入力が確定したとき指定されたイベントハンドラを呼び出します。
     *
     * @param    handler     {function}  イベントハンドラ。イベントハンドラは jQuery.Event を引数にとる。
     * @return   {jQuery}    jQueryオブジェクト
     */
    $.fn.completeTest = function (handler) {
        console.log('keypress_start');
        var ENTER_KEY = 13;
        var ESC_KEY = 27;
        var keypressed = false;


        $('.check_css').val('check');

        $(this).click(function () {
            console.log('click_start_OK?');
        });


        $(this).click(function () {
            console.log('click_start_OK?');
        });


        $(this).keypress(function () {
            console.log('keypress_start_OK?');
            return false;
        });


    };

    $('.edit_item').completeTest();
    $('.edit_item').on('completeTest');
    $('.edit_item').on('completeTest', function (event) {
        console.log('complete_act2');
    });
    $(document).on('completeTest', '.edit_item', function (event) {
        console.log('complete_actX');
    });

})(jQuery);