// add event for page_template_scrollto_section
if($('.page_template_scrollto_section').length > 0){
    $(window).scroll(function() {
        var TopVal = $(".scroll_topbar").position().top;
        if ($(this).scrollTop() > TopVal) {
            $('.page_template_scrollto_section').addClass("active");
            $('.page_template_scrollto_section').css("top", top_offset);
        } else {
            $('.page_template_scrollto_section').removeClass("active");
            $('.page_template_scrollto_section').css("top", 'unset');

        }
    });

    scroll_to_section();
}	

// add scroll_to_section
function scroll_to_section() {

    $('.page_template_scrollto_section ul li a').click(function(){
        $(this).parent('.page_template_scrollto_section ul li').addClass('active');
        $('html, body').animate({
            scrollTop: $( $(this).attr('href') ).offset().top - top_offset - 50
        }, 2000);
        return false;
    });

	// Cache selectors
    var topMenu = $(".page_template_scrollto_section"),
        topMenuHeight = topMenu.outerHeight()+15,
        // All list items
        menuItems = topMenu.find("a"),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
            var item = $($(this).attr("href"));
            if (item.length) { return item; }
        });

	// Bind to scroll
    $(window).scroll(function(){
        // Get container scroll position
        var fromTop = $(this).scrollTop()+topMenuHeight;

        // Get id of current scroll item
        var cur = scrollItems.map(function(){
            if ($(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length-1];
        var id = cur && cur.length ? cur[0].id : "";
        // Set/remove active class
        menuItems
            .parent().removeClass("active")
            .end().filter("[href='#"+id+"']").parent().addClass("active");
    });
}