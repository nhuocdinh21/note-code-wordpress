var options = {
    valueNames: ['name', 'type', 'product_cat'],
    page: 10,
    pagination: true,
    plugins: [
        ListPagination({})
    ]
};

var userList = new List('search_documents', options);

var updateList = function () {
        var product_cat = new Array();

        jQuery("input:radio[name=product_cat]:checked").each(function () {
            product_cat.push(jQuery(this).val());
        });

        var values_productcat = product_cat.length > 0 ? product_cat : null;

        userList.filter(function (item) {
            var productcatTest;

            if(item.values().product_cat.indexOf('|') > 0){
                var productcatArr = item.values().product_cat.split('|');
                for(var i = 0; i < productcatArr.length; i++){
                    if(_(values_productcat).contains(productcatArr[i])){
                        typeTest = true;
                    }
                }
            }

            return (_(values_productcat).includes(item.values().product_cat) || !values_productcat || productcatTest)
        });
    }

var all_type = [];
var all_name = [];
var all_product_cat = [];

updateList();

jQuery(document).off("change", "input:radio[name=product_cat]");
jQuery(document).on("change", "input:radio[name=product_cat]", updateList);

jQuery('.term_parent > .nameContainer > a').click(function (e) {
    jQuery('.term_parent > .nameContainer > a').removeClass('active');
    jQuery(this).toggleClass('active');
});