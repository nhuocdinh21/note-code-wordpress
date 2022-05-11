// add registerNewAccount
function registerNewAccount() {
    jQuery.ajax({
        type: "post",
        url: the_ajax_script.ajaxurl,
        dataType : "json",
        data: {
            action: "ajax_register",
            new_email: jQuery("#emailregister").val(),
            new_password: jQuery("#passwordregister").val(),
            your_answer: jQuery("#your_answer").val(),
            answer: jQuery("#answer").val(),
            captcha: grecaptcha.getResponse(),
        },
        context: this,
        beforeSend: function () {
            // console.log("Loading ... ");
        },
        success: function (response) {
            if(response.success) {            
                if( response.data == ''){
                    window.location.reload();
                }
                else
                {
                    alert(response.data);
                } 
                grecaptcha.reset();              
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('The following error occured: ' + textStatus, errorThrown);
        }
    })

}

// add all_reply loading-screen
function get_question_by_category(id) {
    catId = parseInt(id);
    currPage = 1;
    jQuery("#all_reply").html("");
    jQuery(".loading-screen").show();

    jQuery.ajax({
        type: "post",
        url: the_ajax_script.ajaxurl,
        data: {
            action: "ajax_load_reply",
            category: id,
        },
        context: this,
        beforeSend: function () {
        },
        success: function (response) {
            jQuery("#all_reply").html(response);
            jQuery(".loading-screen").hide();
            // console.log(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    })
}

// add postQuestion
function postQuestion(id) {

    var content = jQuery('#content_reply_' + id).val();

    if (content == null || content == '') {
        alert("Vui lòng nhập nội dung");
        return;
    }

    jQuery("#btn_reply_" + id).text('Loading...');

    var user_id = jQuery("#btn_reply_" + id).attr('data-user');
    var time = jQuery("#total_traloi_" + id).val();

    jQuery.ajax({
        type: "post",
        url: the_ajax_script.ajaxurl,
        data: {
            action: "ajax_post_reply",
            content: content,
            parent_id: 0,
            question_id: id,
            user_id: user_id,
            time: time,
        },
        context: this,
        beforeSend: function () {
            // console.log("Loading ... ");
        },
        success: function (response) {
            jQuery('#add-reply-' + id).before(response);
            jQuery('#content_reply_' + id).val('');
            jQuery("#btn_reply_" + id).html('<i aria-hidden="true" class="fa fa-paper-plane"></i>\n' + 'Gửi'); 
            // window.location.reload();            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('The following error occured: ' + textStatus, errorThrown);
        }
    });

}

// add loadMoreQuestion
function loadMoreQuestion(ppp) {

    var page = jQuery("#loadmorequestion").attr('data-page');

    jQuery("#loadmorequestion").text('Loading...');

    jQuery.ajax({
        type: "post",
        url: the_ajax_script.ajaxurl,
        data: {
            action: "ajax_loadmore_question",
            offset: page * ppp,
            ppp: ppp
        },
        context: this,
        beforeSend: function () {
            // console.log("Loading ... ");
        },
        success: function (response) {           
            // jQuery("#all_reply").append(response);   
            jQuery(response).insertBefore( jQuery( '#all_reply .btn_loadmore' ) );                   
            page++; 
            jQuery("#loadmorequestion").attr('data-page',page);
            jQuery("#loadmorequestion").text('Xem thêm');

            var num_question = jQuery('#all_reply').find('.social-feed-box').length; 
            var total = jQuery("#loadmorequestion").attr('data-total');
            if( num_question == total ){
                jQuery("#all_reply .btn_loadmore").hide();
            }   
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('The following error occured: ' + textStatus, errorThrown);
        }
    });
}

// add loadMoreQuestionCategory
function loadMoreQuestionCategory(ppp,id) {

    var page = jQuery("#loadmorequestion").attr('data-page');

    jQuery("#loadmorequestion").text('Loading...');

    jQuery.ajax({
        type: "post",
        url: the_ajax_script.ajaxurl,
        data: {
            action: "ajax_loadmore_question_category",
            offset: page * ppp,
            ppp: ppp,
            category: id,
        },
        context: this,
        beforeSend: function () {
            // console.log("Loading ... ");
        },
        success: function (response) {           
            // jQuery("#all_reply").append(response);   
            jQuery(response).insertBefore( jQuery( '#all_reply .btn_loadmore' ) );                   
            page++; 
            jQuery("#loadmorequestion").attr('data-page',page);
            jQuery("#loadmorequestion").text('Xem thêm');

            var num_question = jQuery('#all_reply').find('.social-feed-box').length; 
            var total = jQuery("#loadmorequestion").attr('data-total');
            if( num_question == total ){
                jQuery("#all_reply .btn_loadmore").hide();
            }   
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('The following error occured: ' + textStatus, errorThrown);
        }
    });
}