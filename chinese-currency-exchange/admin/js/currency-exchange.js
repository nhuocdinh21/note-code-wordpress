jQuery(document).ready(function($) {

    // add custom tab
    $.noConflict();
    $('#tab_currency_exchange a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $('#tab_currency_exchange > li > a').on('shown.bs.tab', function (e) {
        var id = $(e.target).attr('href');
        window.history.pushState('', '', id);
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#tab_currency_exchange a[href="' + hash + '"]').tab('show');


    // add new currency exchange
    if( $('.addnew_currency_exchange_wrap').length > 0 ){
        $(document).on('click','.addnew_currency_exchange_wrap .btn_addnew a',function() {
            $('.addnew_currency_exchange_wrap .addnew_currency_exchange').toggleClass('opened');
            $('.addnew_currency_exchange_wrap .addnew_currency_exchange').slideToggle();
            if( !$('.addnew_currency_exchange_wrap .addnew_currency_exchange').hasClass('opened') ){
                $('.frm_addnew_currency_exchange').validate().destroy();
            }            
        });
    }

    // add new data currency exchange
    if( $('.frm_addnew_currency_exchange').length > 0 ){
        $('.frm_addnew_currency_exchange').validate({
            rules: {
                type: 'required',
                date: 'required',
                timeline: 'required',
                alipay_chinese: 'required',
                alipay_vietnamese: 'required',
                card: 'required',
            },
            messages: {
                type: {
                    required: 'Bạn phải chọn Phương thức.',
                },
                date: {
                    required: 'Bạn phải chọn Ngày.',
                },
                timeline: {
                    required: 'Bạn phải chọn Mốc thời gian.',
                },
                alipay_chinese: {
                    required: 'Bạn phải nhập dữ liệu.',
                    min: 'Vui lòng nhập giá trị >= 0.',
                    number: 'Vui lòng nhập một số hợp lệ.'
                },
                alipay_vietnamese: {
                    required: 'Bạn phải nhập dữ liệu.',
                    min: 'Vui lòng nhập giá trị >= 0.',
                    number: 'Vui lòng nhập một số hợp lệ.'
                },
                card: {
                    required: 'Bạn phải nhập dữ liệu.',
                    min: 'Vui lòng nhập giá trị >= 0.',
                    number: 'Vui lòng nhập một số hợp lệ.'
                },
            },
            submitHandler: function(form) {
                let type                = $(form).find('select[name="type"]').val();
                let date                = $(form).find('input[name="date"]').val();
                let timeline            = $(form).find('select[name="timeline"]').val();
                let alipay_chinese      = $(form).find('input[name="alipay_chinese"]').val();
                let alipay_vietnamese   = $(form).find('input[name="alipay_vietnamese"]').val();
                let card                = $(form).find('input[name="card"]').val();
                console.log(type + '_' + date + '_' + timeline + '_' + alipay_chinese + '_' + alipay_vietnamese + '_' + card);
                addnew_currency_exchange(type, date, timeline, alipay_chinese, alipay_vietnamese, card);
                return false;
            }
        });
    }

    // function add new currency exchange
    addnew_currency_exchange = (type, date, timeline, alipay_chinese, alipay_vietnamese, card) => {
        let data_addnew = new FormData();

        data_addnew.append( 'action', 'addnew_data_table' );
        data_addnew.append( 'nonce', currency_exchange_params.nonce );
        data_addnew.append( 'type', type);
        data_addnew.append( 'date', date);
        data_addnew.append( 'timeline', timeline);
        data_addnew.append( 'alipay_chinese', alipay_chinese);
        data_addnew.append( 'alipay_vietnamese', alipay_vietnamese);
        data_addnew.append( 'card', card);

        fetch(currency_exchange_params.ajaxurl, {
            method: "POST",
            credentials: 'same-origin',
            body: data_addnew
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.status === true) {
                alert('Thêm mới dữ liệu thành công.');
                window.location.reload(true);
            }
            else {
                alert('Lỗi thêm mới.');
            }
        })
        .catch((error) => {
            console.error(error);
        });
    }


    // show data table
    if( $('.list_currency_exchange').length > 0 ){
        $('.list_currency_exchange').each(function() {
            $(this).DataTable( {
                order: [[0, 'desc']],
                dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    'pdfHtml5'
                ]
            });
        });
    }  

    // update value data table
    if( $('.frm_update_data_table').length > 0 ){
        $('.frm_update_data_table').each(function() {
            $(this).validate({
                rules: {
                    field_value: 'required',
                },
                messages: {
                    field_value: {
                        required: 'Bạn phải nhập dữ liệu.',
                        min: 'Vui lòng nhập giá trị >= 0.',
                        number: 'Vui lòng nhập một số hợp lệ.'
                    },
                },
                submitHandler: function(form) {
                    let field_id    = $(form).data('id');
                    let field_type  = $(form).data('type');
                    let field_name  = $(form).data('fieldname');
                    let field_value = $(form).find('input[name="field_value"]').val();
                    update_data_table(field_id, field_type, field_name, field_value);
                    return false;
                }
            });
        }); 
    }

    // reset validation when close popup
    if( $('.update_data_table').length > 0 ){
        $('.update_data_table').fancybox({
            afterShow: function() {
                let popup_content = this['src'];
                $(popup_content).find('.title').text('Cập nhật dữ liệu');
            },
            afterClose: function() {
                let popup_content = this['src'];
                $(popup_content).find('.frm_update_data_table').validate().destroy();
                let field_value = $(popup_content).find('.frm_update_data_table').data('price');
                $(popup_content).find('input[name="field_value"]').val(field_value);

                $(document).on('click','.frm_update_data_table input[type="submit"]',function() {
                    let field_id    = $(this).parents('.frm_update_data_table').data('id');
                    let field_type  = $(this).parents('.frm_update_data_table').data('type');
                    let field_name  = $(this).parents('.frm_update_data_table').data('fieldname');
                    let field_value = $(this).parents('.frm_update_data_table').find('input[name="field_value"]').val();
                    update_data_table(field_id, field_type, field_name, field_value);
                    return false;
                });
            }
        }); 
    }

    // function update data table
    update_data_table = (id, type, key, value) => {
        data_update = new FormData();

        data_update.append( 'action', 'update_data_table' );
        data_update.append( 'nonce', currency_exchange_params.nonce );
        data_update.append( 'id', id);
        data_update.append( 'type', type);
        data_update.append( 'key', key);
        data_update.append( 'value', value);

        fetch(currency_exchange_params.ajaxurl, {
            method: "POST",
            credentials: 'same-origin',
            body: data_update
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.update_status === true) {
                alert('Cập nhật dữ liệu thành công.');
                window.location.reload(true);
            }
            else {
                alert('Lỗi cập nhật.');
            }
        })
        .catch((error) => {
            console.error(error);
        });
    }

    // delete row table
    if( $('.frm_delete_data_row').length > 0 ){
        $(document).on('click','.frm_delete_data_row button[type="submit"]',function() {  
            let text = 'Bạn có chắc chắn muốn xóa dữ liệu không?';
            if (confirm(text) == true) {
                let row_id   = $(this).parents('.frm_delete_data_row').data('id');
                let row_type = $(this).parents('.frm_delete_data_row').data('type');
                delete_data_row(row_id, row_type);
            }                    
            return false;
        });
    }

    // function delete data row
    delete_data_row = (id, type) => {
        let data_delete = new FormData();

        data_delete.append( 'action', 'delete_data_row' );
        data_delete.append( 'nonce', currency_exchange_params.nonce );
        data_delete.append( 'id', id);
        data_delete.append( 'type', type);

        fetch(currency_exchange_params.ajaxurl, {
            method: "POST",
            credentials: 'same-origin',
            body: data_delete
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.delete_status === true) {
                alert('Xóa dữ liệu thành công.');
                window.location.reload(true);                
            }
            else {
                alert('Lỗi xóa dữ liệu.');
            }
        })
        .catch((error) => {
            console.error(error);
        });
    }
       
} );