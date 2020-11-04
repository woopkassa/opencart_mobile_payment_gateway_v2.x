<div class="buttons">
    <div id="error-container" class="pull-left">
        <h4 id="error-block" style="color: red; font-weight: bold"></h4>
    </div>
    <div class="pull-right">
        <a id="send-code" onclick="sendSMS()" class="btn btn-primary">Отправить код подтверждения</a>
        <input id="input-confirmation-code" type="text" maxlength="6" minlength="6" class="form-control"
               style="display: none" name="confirmation_code" placeholder="Код подтверждения">
        <a id="create-invoice" onclick="createInvoice()" class="btn btn-primary"
           style="display: none; margin-top:10px; float: right"><?php echo $button_confirm; ?></a>
    </div>
</div>
<script type="text/javascript">
    function sendSMS() {
        if ($("#input-payment-telephone").length > 0){
            var phone = $("#input-payment-telephone").val();
            phone = phone.replace(/[\s+\+]/g, '');
            if (phone.length != 11) {
                $("#error-block").text("Некорректный телефонный номер. Требуется номер длинной в 11 цифр.");
                return false;
            }
        }
        $.ajax({
            url: 'index.php?route=extension/payment/wooppayMobile/sendSms',
            type: 'post',
            data: {phone: phone},
            success: function (response) {
                if (response == 603) {
                    $("#error-container").css('display', 'inline-block');
                    $("#error-block").text("Недопустимый сотовый оператор для оплаты с мобильного телефона. Допустимые операторы Activ, Kcell, Beeline.");
                    $("#error-container").delay(7000).fadeOut(300);
                }
                if (response == 222) {
                    $("#error-container").css('display', 'inline-block');
                    $("#error-block").text("Вы уже запрашивали код подтверждения на данный номер в течение предыдущих 5 минут.");
                    $("#error-container").delay(7000).fadeOut(300);
                }
                if (response == 999) {
                    $("#error-container").css('display', 'inline-block');
                    $("#error-block").text("Произошла непредвиденная ошибка. Пожалуйста свяжитесь с администратором сайта.");
                    $("#error-container").delay(7000).fadeOut(300);
                }
                if (response == 1) {
                    $("#error-block").text("");
                    $("#send-code").css('display', 'none');
                    $("#input-confirmation-code").css('display', 'inline-block');
                    $("#create-invoice").css('display', 'inline-block');
                }
            }
        });
    }

    function createInvoice() {
        var code = $("#input-confirmation-code").val();
        if (code.length != 6) {
            $("#error-block").text("Код подтверждения должен состоять из 6 цифр.");
        }
        else {
            $.ajax({
                url: 'index.php?route=extension/payment/wooppayMobile/invoice',
                type: 'post',
                data: {code: code},
                success: function (response) {
                    if (response == 603) {
                        $("#error-container").css('display', 'inline-block');
                        $("#error-block").text("Недопустимый сотовый оператор для оплаты с мобильного телефона. Допустимые операторы Activ, Kcell, Beeline.");
                        $("#error-container").delay(7000).fadeOut(300);
                    }
                    else if (response == 223) {
                        $("#error-container").css('display', 'inline-block');
                        $("#error-block").text("Неверный код подтверждения.");
                        $("#error-container").delay(7000).fadeOut(300);
                    }
                    else if (response == 224) {
                        $("#error-container").css('display', 'inline-block');
                        $("#error-block").text("Вы ввели неверный код подтверждения слишком много раз. Попробуйте через 5 минут.");
                        $("#error-container").delay(7000).fadeOut(300);
                    }
                    else if (response == 226) {
                        $("#error-container").css('display', 'inline-block');
                        $("#error-block").text("У вас недостаточно средств на балансе мобильного телефона.");
                        $("#error-container").delay(7000).fadeOut(300);
                    }
                    else {
                        window.location = response;
                    }
                }
            });
        }
    }
</script>