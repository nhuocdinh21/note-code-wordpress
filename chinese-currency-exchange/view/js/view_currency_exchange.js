jQuery(function($) {
    // addd tab chart currrency exchange rate
	$('.tabs .tab-links a').on('click', function(e) {
        var currentAttrValue = $(this).data('href');

        // Show/Hide Tabs
        $('.tabs ' + currentAttrValue).addClass('active');
        $('.tabs ' + currentAttrValue).siblings().removeClass('active');
        $('.tabs ' + currentAttrValue).show().siblings().hide();

        // Change/remove current tab to active
        $(this).parent('li').addClass('active');
        $(this).parent('li').siblings().removeClass('active');

        e.preventDefault();
    });    

    // set time form currency exchange
    setInterval(function() {
        if( $('#converter_clock').length > 0 ){
            $('#converter_clock').text((new Date()).toLocaleTimeString('en-GB'));
        }          
        let current_date = new Date;
        printDate(current_date);
    }, 1000);

    // define function printDate
    printDate = (start) => {
        let elementDate = document.getElementById('converter_day');
        if(elementDate){
            let day = start.getDate();
            let month = start.getMonth() + 1;
            let year = start.getFullYear();
            elementDate.innerHTML = day + "/" + month+ "/" + year;
        }
    }

    // click change input currency
    $(document).on('click','.button__change > a',function(e){
        $('.header__right__cal').toggleClass('cny_active');
        $('#cny_input1, #cny_input2, #vnd_input1, #vnd_input2').val('');
        $('.change__detail-info').hide();
    });   

    formatNumber = (num) => {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    delay = (callback, ms) => {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    } 

    excute_form_exchange_rate = (method, currency, payment) => {    
        $('#cny_input1, #vnd_input1, #cny_input2, #vnd_input2').val('');

        let view_result = $('.change__detail-info');        
        view_result.hide();

        let payment_service_fee = $('.form_currency_exchange_wrapper .payment_service_fee_wrap');
        payment_service_fee.hide();

        let service_fee = $('.form_currency_exchange_wrapper input[name="service_fee"]').val() ? $('.form_currency_exchange_wrapper input[name="service_fee"]').val() : 0;
        if ( method === 'buy' ){
            payment_service_fee.show();
            $('#payment_change').html('0 <sup>đ</sup>');

            if( payment === true ){
                let str_data_rate = 'data_' + currency + '_sell';
                let data_rate = $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() ? $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() : 0;
                $('#money_exchange').html(formatNumber(data_rate));

                let data_payment_fee = $('.form_currency_exchange_wrapper input[name="payment_service_fee"]').val() ? $('.form_currency_exchange_wrapper input[name="payment_service_fee"]').val() : 100;

                $("#cny_input1").keydown(delay(function (e) {
                    let cnyInput = parseInt(this.value);
                    if( !isNaN(cnyInput) && cnyInput > 0 ){
                        let payment_fee = data_payment_fee * cnyInput;
                        let vndInput = cnyInput * parseInt(data_rate) + parseInt(service_fee) + parseInt(payment_fee);
                        $('#vnd_input1').val(formatNumber(vndInput));
                        $('#payment_change').html(formatNumber(payment_fee) + ' <sup>đ</sup>');
                        $('#number_receive').html(formatNumber(vndInput) + ' <sup>đ</sup>');
                        view_result.show();
                    }
                    else {
                        $('#vnd_input1').val('');
                        $('#payment_change').html('0 <sup>đ</sup>');
                        view_result.hide();
                    }                
                }, 200));

                $("#vnd_input2").keydown(delay(function (e) {
                    let vndInput = parseInt(this.value);
                    if( !isNaN(vndInput) && vndInput > 0 ){
                        let cnyInput = ( vndInput - parseInt(service_fee) ) / ( parseInt(data_rate) + parseInt(data_payment_fee) );
                        $('#cny_input2').val(parseFloat(cnyInput).toFixed(2));
                        $('#payment_change').html(formatNumber(data_payment_fee) + ' <sup>đ</sup>');
                        $('#number_receive').html(parseFloat(cnyInput).toFixed(2) + ' <sup>¥</sup>');
                        view_result.show();
                    }
                    else {
                        $('#cny_input2').val('');
                        view_result.hide();
                    }                
                }, 200));
            } 
            else {
                let str_data_rate = 'data_' + currency + '_sell';
                let data_rate = $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() ? $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() : 0;
                $('#money_exchange').html(formatNumber(data_rate));

                $("#cny_input1").keydown(delay(function (e) {
                    let cnyInput = parseInt(this.value);
                    if( !isNaN(cnyInput) && cnyInput > 0 ){
                        let vndInput = cnyInput * parseInt(data_rate) + parseInt(service_fee);
                        $('#vnd_input1').val(formatNumber(vndInput));
                        $('#payment_change').html('0 <sup>đ</sup>');
                        $('#number_receive').html(formatNumber(vndInput) + ' <sup>đ</sup>');
                        view_result.show();
                    }
                    else {
                        $('#vnd_input1').val('');
                        $('#payment_change').html('0 <sup>đ</sup>');
                        view_result.hide();
                    }                
                }, 200));

                $("#vnd_input2").keydown(delay(function (e) {
                    let vndInput = parseInt(this.value);
                    if( !isNaN(vndInput) && vndInput > 0 ){
                        let cnyInput = ( vndInput - parseInt(service_fee) ) / parseInt(data_rate);
                        $('#cny_input2').val(parseFloat(cnyInput).toFixed(2));
                        $('#number_receive').html(parseFloat(cnyInput).toFixed(2) + ' <sup>¥</sup>');
                        view_result.show();
                    }
                    else {
                        $('#cny_input2').val('');
                        view_result.hide();
                    }                
                }, 200));
            }                
        }
        else {
            payment_service_fee.hide();
            $('#payment_change').html('0 <sup>đ</sup>');

            let str_data_rate = 'data_' + currency + '_buy';
            let data_rate = $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() ? $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() : 0;
            $('#money_exchange').html(formatNumber(data_rate));

            $("#cny_input1").keydown(delay(function (e) {
                let cnyInput = parseInt(this.value);
                if( !isNaN(cnyInput) && cnyInput > 0 ){
                    let vndInput = cnyInput * parseInt(data_rate) - parseInt(service_fee);
                    $('#vnd_input1').val(formatNumber(vndInput));
                    $('#payment_change').html('0 <sup>đ</sup>');
                    $('#number_receive').html(formatNumber(vndInput) + ' <sup>đ</sup>');
                    view_result.show();  
                }
                else {
                    $('#vnd_input1').val('');
                    view_result.hide();
                }                
            }, 200));

            $("#vnd_input2").keydown(delay(function (e) {
                let vndInput = parseInt(this.value);
                if( !isNaN(vndInput) && vndInput > 0 ){
                    let cnyInput = ( vndInput + parseInt(service_fee) ) / parseInt(data_rate);
                    $('#cny_input2').val(parseFloat(cnyInput).toFixed(2));
                    $('#payment_change').html('0 <sup>đ</sup>');
                    $('#number_receive').html(parseFloat(cnyInput).toFixed(2) + ' <sup>¥</sup>');
                    view_result.show();
                }
                else {
                    $('#cny_input2').val('');
                    view_result.hide();
                }                
            }, 200));
        }
    }

    excute_form_exchange_rate_payment_fee = (method, currency, payment) => {    
        $('#vnd_input1, #cnd_input2').val('');

        let view_result = $('.change__detail-info');  

        let service_fee = $('.form_currency_exchange_wrapper input[name="service_fee"]').val() ? $('.form_currency_exchange_wrapper input[name="service_fee"]').val() : 0;

        if( payment === true ){
            let str_data_rate = 'data_' + currency + '_sell';
            let data_rate = $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() ? $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() : 0;
            $('#money_exchange').html(formatNumber(data_rate));

            let data_payment_fee = $('.form_currency_exchange_wrapper input[name="payment_service_fee"]').val() ? $('.form_currency_exchange_wrapper input[name="payment_service_fee"]').val() : 100;

            // excute when input has data
            let cnyInputDefault = $("#cny_input1").val();
            if( cnyInputDefault != '' ) {
                view_result.show();
                let payment_fee_default = data_payment_fee * cnyInputDefault;
                let vndInputCnyDefault = cnyInputDefault * parseInt(data_rate) + parseInt(service_fee) + parseInt(payment_fee_default);
                $('#vnd_input1').val(formatNumber(vndInputCnyDefault));
                $('#payment_change').html(formatNumber(payment_fee_default) + ' <sup>đ</sup>');
                $('#number_receive').html(formatNumber(vndInputCnyDefault) + ' <sup>đ</sup>');
            }

            let vndInputDefault = $("#vnd_input2").val();
            if( vndInputDefault != '' ) {
                view_result.show();
                let cnyInputVndDefault = ( vndInputDefault - parseInt(service_fee) ) / ( parseInt(data_rate) + parseInt(data_payment_fee) );
                $('#cny_input2').val(parseFloat(cnyInputVndDefault).toFixed(2));
                $('#payment_change').html(formatNumber(data_payment_fee) + ' <sup>đ</sup>');
                $('#number_receive').html(parseFloat(cnyInputVndDefault).toFixed(2) + ' <sup>¥</sup>');
            }

            // excute when keydown
            $("#cny_input1").keydown(delay(function (e) {
                let cnyInput = parseInt(this.value);
                if( !isNaN(cnyInput) && cnyInput > 0 ){
                    let payment_fee = data_payment_fee * cnyInput;
                    let vndInput = cnyInput * parseInt(data_rate) + parseInt(service_fee) + parseInt(payment_fee);
                    $('#vnd_input1').val(formatNumber(vndInput));
                    $('#payment_change').html(formatNumber(payment_fee) + ' <sup>đ</sup>');
                    $('#number_receive').html(formatNumber(vndInput) + ' <sup>đ</sup>');
                    view_result.show();
                }
                else {
                    $('#vnd_input1').val('');
                    $('#payment_change').html('0 <sup>đ</sup>');
                    view_result.hide();
                }                
            }, 200));

            $("#vnd_input2").keydown(delay(function (e) {
                let vndInput = parseInt(this.value);
                if( !isNaN(vndInput) && vndInput > 0 ){
                    let cnyInput = ( vndInput - parseInt(service_fee) ) / ( parseInt(data_rate) + parseInt(data_payment_fee) );
                    $('#cny_input2').val(parseFloat(cnyInput).toFixed(2));
                    $('#payment_change').html(formatNumber(data_payment_fee) + ' <sup>đ</sup>');
                    $('#number_receive').html(parseFloat(cnyInput).toFixed(2) + ' <sup>¥</sup>');
                    view_result.show();
                }
                else {
                    $('#cny_input2').val('');
                    view_result.hide();
                }                
            }, 200));
        } 
        else {
            let str_data_rate = 'data_' + currency + '_sell';
            let data_rate = $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() ? $('.form_currency_exchange_wrapper input[name="'+str_data_rate+'"]').val() : 0;
            $('#money_exchange').html(formatNumber(data_rate));

            // excute when input has data
            let cnyInputDefault = $("#cny_input1").val();
            if( cnyInputDefault != '' ) {
                view_result.show();
                let vndInputCnyDefault = cnyInputDefault * parseInt(data_rate) + parseInt(service_fee);
                $('#vnd_input1').val(formatNumber(vndInputCnyDefault));
                $('#payment_change').html('0 <sup>đ</sup>');
                $('#number_receive').html(formatNumber(vndInputCnyDefault) + ' <sup>đ</sup>');
            }

            let vndInputDefault = $("#vnd_input2").val();
            if( vndInputDefault != '' ) {
                console.log('vnd input', vndInputDefault);
                view_result.show();
                let cnyInputVndDefault = ( vndInputDefault - parseInt(service_fee) ) / parseInt(data_rate);
                $('#cny_input2').val(parseFloat(cnyInputVndDefault).toFixed(2));
                $('#payment_change').html('0 <sup>đ</sup>');
                $('#number_receive').html(parseFloat(cnyInputVndDefault).toFixed(2) + ' <sup>¥</sup>');
            }

            // excute when keydown
            $("#cny_input1").keydown(delay(function (e) {
                let cnyInput = parseInt(this.value);
                if( !isNaN(cnyInput) && cnyInput > 0 ){
                    let vndInput = cnyInput * parseInt(data_rate) + parseInt(service_fee);
                    $('#vnd_input1').val(formatNumber(vndInput));
                    $('#payment_change').html('0 <sup>đ</sup>');
                    $('#number_receive').html(formatNumber(vndInput) + ' <sup>đ</sup>');
                    view_result.show();
                }
                else {
                    $('#vnd_input1').val('');
                    $('#payment_change').html('0 <sup>đ</sup>');
                    view_result.hide();
                }                
            }, 200));

            $("#vnd_input2").keydown(delay(function (e) {
                let vndInput = parseInt(this.value);
                if( !isNaN(vndInput) && vndInput > 0 ){
                    let cnyInput = ( vndInput - parseInt(service_fee) ) / parseInt(data_rate);
                    $('#cny_input2').val(parseFloat(cnyInput).toFixed(2));
                    $('#number_receive').html(parseFloat(cnyInput).toFixed(2) + ' <sup>¥</sup>');
                    view_result.show();
                }
                else {
                    $('#cny_input2').val('');
                    view_result.hide();
                }                
            }, 200));
        }
    }

    let exchange_method = $('.form_currency_exchange_wrapper input[name="exchange_type"]').val();
    let currency = $('.form_currency_exchange_wrapper input[name="currency_name"]').val();
    let payment = $('.form_currency_exchange_wrapper input[name="option_payment_service_fee"]').is(':checked') ? true : false;
    excute_form_exchange_rate(exchange_method, currency, payment);

    $('.form_currency_exchange_wrapper input[name="exchange_type"]').change(function(){
        exchange_method = $(this).val();
        currency = $('.form_currency_exchange_wrapper input[name="currency_name"]:checked').val();
        payment = $('.form_currency_exchange_wrapper input[name="option_payment_service_fee"]').is(':checked') ? true : false;
        excute_form_exchange_rate(exchange_method, currency, payment);
    });

    $('.form_currency_exchange_wrapper input[name="currency_name"]').change(function(){        
        exchange_method = $('.form_currency_exchange_wrapper input[name="exchange_type"]:checked').val();
        currency = $(this).val();
        payment = $('.form_currency_exchange_wrapper input[name="option_payment_service_fee"]').is(':checked') ? true : false;
        excute_form_exchange_rate(exchange_method, currency, payment);
    });

    $('.form_currency_exchange_wrapper input[name="option_payment_service_fee"]').change(function(){        
        exchange_method = $('.form_currency_exchange_wrapper input[name="exchange_type"]:checked').val();
        currency = $('.form_currency_exchange_wrapper input[name="currency_name"]:checked').val();
        payment = this.checked ? true : false;
        excute_form_exchange_rate_payment_fee(exchange_method, currency, payment);
    });
});