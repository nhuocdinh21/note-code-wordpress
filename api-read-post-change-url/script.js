document.addEventListener("DOMContentLoaded", function(){
    document.querySelectorAll('.sidebar_new .nav-link-parent').forEach(function(element){
    
        element.addEventListener('click', function (e) {

            let nextEl = element.nextElementSibling;
            let parentEl  = element.parentElement;    

            if(nextEl) {
                e.preventDefault(); 
                let mycollapse = new bootstrap.Collapse(nextEl);
                
                if(nextEl.classList.contains('show')){
                  mycollapse.hide();
                } else {
                    mycollapse.show();
                    // find other submenus with class=show
                    var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                    // if it exists, then close all of them
                    if(opened_submenu){
                      new bootstrap.Collapse(opened_submenu);
                    }
                }
            }
        }); // addEventListener
    }) // forEach
}); 

/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}

// show detail post
async function getPostDetail(post_id) {
    try {
        const response = await fetch('<?php echo site_url(); ?>/wp-json/wp/v2/posts?include='+post_id);
        if (!response.ok) {
          throw new Error(`HTTP error: ${response.status}`);
        }
        const data = await response.json();
        const post_title = data[0].title['rendered'];
        const post_date = new Date(data[0].date);
        const post_content = data[0].content['rendered'];

        const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        let date = post_date.getDate();
        let month = months[post_date.getMonth()];
        let year = post_date.getFullYear();

        let post_title_detail = document.getElementById('post_title_detail');
        let post_breadcrumb_detail = document.getElementById('last_breadcrumb');
        let post_meta_detail = document.getElementById('post_meta_detail');
        let post_content_detail = document.getElementById('post_content_detail'); 

        if( post_breadcrumb_detail ){
            if( post_title && post_title !== '' ){
                post_breadcrumb_detail.innerHTML = post_title;
            }                    
        }

        if( post_title_detail ){
            if( post_title && post_title !== '' ){
                post_title_detail.innerHTML = post_title;
            }                    
        }

        if( post_meta_detail ){
            if( post_date && post_date !== '' ){
                post_meta_detail.innerHTML = date + ' ' + month + ' ' + year;
            }                    
        }

        if( post_content_detail ){
            if( post_content && post_content !== '' ){
                post_content_detail.innerHTML = post_content;
            }                    
        }
    }
    catch(error) {
        console.error(`Could not get products: ${error}`);
    }
}

let post_element = document.getElementsByClassName('post_link_detail');
for (let i = 0; i < post_element.length; i++) { 
    post_element[i].addEventListener("click", function() {
        if( this.dataset.id && this.dataset.id !== '' ){
            getPostDetail(this.dataset.id);
        }
        if( this.dataset.href && this.dataset.href !== '' ){
            const nextURL = this.dataset.href;
            const nextTitle = '';
            const nextState = { additionalInformation: 'Updated the URL with JS' };
            window.history.pushState(nextState, nextTitle, nextURL);
        }
    });
}
// console.log(post_element);