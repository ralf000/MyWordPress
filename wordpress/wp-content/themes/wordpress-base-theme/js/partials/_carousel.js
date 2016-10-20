(function($){
    $.fn.homecarousel = function() {
        var carousel  = this,
            imgs      = this.find('dt'),
            txt       = this.find('dd'),
            controls  = this.find('.controls li'),
            navNext      = this.find('.carousel-nextprev .next'),
            navPrev      = this.find('.carousel-nextprev .prev'),
            current   = 0,
            animating = false,
            ct,
            time = 8500,
            timestart,
            remaining = false;
        
        return this.each(function(){
            controls.on('click', function(){
                if($(this).hasClass('on')) return;
                gotoSlide(controls.index(this));
            });
            carTimer();

            navNext.on('click', function(ev) {
                ev.preventDefault();
                gotoSlide(current+1);
            });

            navPrev.on('click', function(ev) {
                ev.preventDefault();
                gotoSlide(current-1);
            });

        });
        function gotoSlide(i) {
            if(i >= imgs.length) i = 0;
            if(animating) return;
            animating = true;
            controls.eq(i).addClass('on').siblings().removeClass('on');
            var pos = 0;

            imgs.eq(i).addClass('current').siblings('dt').removeClass('current').attr('style','');
            txt.eq(i).addClass('current').siblings('dd').removeClass('current').attr('style','');
            current = i;
            carTimer();
            animating = false;
        }

        function carTimer() {
            clearTimeout(ct);
            var how_long = remaining ? remaining : time;
            timestart = new Date();
            ct = window.setTimeout(function() { gotoSlide(current+1); }, how_long);
            remaining = false;
        }

    };
})(jQuery);