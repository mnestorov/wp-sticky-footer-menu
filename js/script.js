document.addEventListener("scroll", function() {
    var menu = document.getElementById('mn-footer-menu');
    if(window.scrollY > 100) {
        menu.classList.add('show');
    } else {
        menu.classList.remove('show');
    }
});
