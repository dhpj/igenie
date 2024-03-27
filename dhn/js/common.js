window.onload = function(){
	/* 모바일 메뉴 펼침 */
    $( document ).ready(function() {
    	$('.hamburger-menu').on('click', function() {
    		$('.bar').toggleClass('animate');
    		$('.backdrop').toggleClass('visible');
        var mobileNav = $('.mobile-nav');
        mobileNav.toggleClass('show');
    	});
    });

    $(function () {
	  var Accordion = function (el, multiple) {
	    this.el = el || {};
	    this.multiple = multiple || false;

	    // Variables privadas
	    var links = this.el.find('.link');
	    // Evento
	    links.on('click', { el: this.el, multiple: this.multiple }, this.dropdown);
	  };

	  Accordion.prototype.dropdown = function (e) {
	    var $el = e.data.el;
	    $this = $(this),
	    $next = $this.next();

	    $next.slideToggle(300);
	    $this.parent().toggleClass('open');

	    if (!e.data.multiple) {
	      $el.find('.submenu').not($next).slideUp(300).parent().removeClass('open');
	    };
	  };

	  var accordion = new Accordion($('#accordion'), false);
	});
}
