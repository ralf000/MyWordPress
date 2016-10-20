;(function($) {

    $.expand = function(element, options) {

        var expand = this;

        expand.init = function(){
            expand.setup();
            expand.closeAll();
        };

        expand.setup = function(){
            var $el = $(element);
            $el.addClass('js').on('click','.ham-expand-btn',function(e){
                e.preventDefault();
                $el.toggleClass('open');
                expand.change();
            });
            $(window).on('resize',function(){
                expand.change();
            });
        }

        expand.change = function(){
            var $el = $(element);
            if( $el.hasClass('open') ) {
                $el.css({maxHeight:expand.getMaxHeight()});
            } else {
                $el.css({maxHeight:expand.getMinHeight()});
            }
        }

        expand.closeAll = function(){
            var $el = $(element);
            $el.removeClass('open').css({maxHeight:expand.getMinHeight()});
        }

        expand.getMinHeight = function(){
            var $el = $(element);
            return $el.find('.ham-expand-btn').outerHeight(true);
        }

        expand.getMaxHeight = function(){
            var $el = $(element);
            return $el.find('.ham-expand-content').outerHeight(true) + expand.getMinHeight();
        }

        expand.init();

    };


    $.fn.expand = function(options) {
        return this.each(function() {
            if (undefined === $(this).data('expand')) {
                var plugin = new $.expand(this, options);
                $(this).data('expand', plugin);
            }
        });
    }

})(jQuery);
