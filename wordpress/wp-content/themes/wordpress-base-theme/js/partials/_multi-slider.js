(function($){
    $.fn.multislider = function() {
        var ms  = this,
            mask        = this.find('.slide-mask'),
            items       = this.find('.slide'),
            nav         = this.find('.multi-slider-nav'),
            cols        = this.data('cols'),
            msHeight    = false,
            tablet      = 550,
            phone       = 400

        return this.each(function(){
            addData();
            placementFixes();

            $(window).on('resize',function(){
                placementFixes();
            });

            $(document).on('click','.'+nav.find('li:first-child button').attr('class'),function(e){move('right')});
            $(document).on('click','.'+nav.find('li:last-child button').attr('class'),function(e){move('left')});

            var h = new Hammer(ms[0]);

            h.on('swipeleft', function(e) {
                move('left');
            });

            h.on('swiperight', function(e) {
                move('right');
            });

        });

        function addData(){
            ms.attr('data-factor',0);
            ms.attr('data-total',items.length);
        }

        function placementFixes(){
            reset();
            mobilize();
            fixHeight();
            placeItems();
        }

        function reset(){
            ms.attr('data-factor',0);
            items.attr('style','');
        }

        function mobilize(){
            var w = $(window).width();
            if(w < phone){
                ms.attr('data-cols',1);
            }else if(w < tablet){
                ms.attr('data-cols',Math.ceil(cols/2));
            } else {
                ms.attr('data-cols',cols);
            }
        }

        function fixHeight(){
            msHeight = 0;
            items.each(function(){
                msHeight = $(this).outerHeight() > msHeight ? $(this).outerHeight() : msHeight;
            });
            mask.css('min-height',msHeight+'px');
        }

        function placeItems(){
            var place = 0;
            items.each(function(){
                $(this).css('left',place+'px');
                place += $(this).outerWidth(true);
            });
        }

        function move(dir){
            var f = parseInt(ms.attr('data-factor'));

            switch(dir){
                case 'left':
                    f--;
                    break;
                case 'right':
                    f++;
                    break;
            }

            // No Support for wrap around yet.
            if(f > 0){
                f = parseInt(ms.attr('data-cols')) - parseInt(ms.attr('data-total'));
            } else if(parseInt(ms.attr('data-cols')) + Math.abs(f) > parseInt(ms.attr('data-total'))){
                f = 0;
            }

            var v = 100 * f;
            items.each(function(){
                var m = parseInt($(this).css('margin-right')) * f;
                $(this).css({
                    '-webkit-transform' : 'translateX(calc('+v+'% + '+m+'px))',
                    '-moz-transform'    : 'translateX(calc('+v+'% + '+m+'px))',
                    '-ms-transform'     : 'translateX(calc('+v+'% + '+m+'px))',
                    '-o-transform'      : 'translateX(calc('+v+'% + '+m+'px))',
                    'transform'         : 'translateX(calc('+v+'% + '+m+'px))'
                });
            });
            ms.attr('data-factor',f);
        }
    };
})(jQuery);


/*
SAMPLE HTML MARKUP
SET DATA-COLS TO COLUMN NUMBER ON DESKTOP

<div class="multi-slider" data-cols="3">
    <div class="slide-mask">
        <div class="slide">
            <a href="#" class="slide-image"><img src="#"></a>
            <div class="slide-content">
                Content
            </div>
        </div>
    </div>
    <ul class="multi-slider-nav">
        <li><button class="btn-prev" type="button">Previous</button></li>
        <li><button class="btn-next" type="button">Next</button></li>
    </ul>
</div><!--multi-slider-->
 */