;(function ( $, window, document, undefined ) {

    var pluginName = "imagegallery",
        defaults = {
            effect: 'fxSnapIn',
            effectlist: '.gallery-images',
            items: '.gallery-images > ul > li',
            nav: '.gallery-controls',
            navNext: '.next',
            navPrev: '.prev',
            postNumber: '',
            autoPlay: false,
            slideTime: 8000,
            enlargeClick: false
        },
        animEndEventNames = {
            'WebkitAnimation' : 'webkitAnimationEnd',
            'OAnimation' : 'oAnimationEnd',
            'msAnimation' : 'MSAnimationEnd',
            'animation' : 'animationend'
        },
        animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ],
        support = { animations : Modernizr.cssanimations };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = $(element);

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.isAnimating = false;
        this.current = 0;
        this.itemsCount = 0;
        this.cntAnims = 0;
        this.currentItem = 0;
        this.nextItem = 0;
        this.timer = false;
        this.columns = 1;
        this.isHybrid = false;

        this.init(this);
    }

    Plugin.prototype = {

        init: function(self) {
            this.itemsCount = this.element.find(this.options.items).length;
            if( support.animations ) this.changeEffect(this.element,this.options);
            this.element.on( 'click', this.options.navNext, function( ev ) { ev.preventDefault(); self.navigate( self.element, self.options, 'next' ); } );
            this.element.on( 'click', this.options.navPrev, function( ev ) { ev.preventDefault(); self.navigate( self.element, self.options, 'prev' ); } );
            this.element.find(this.options.nav).find('li').on( 'click', function( ev ) {
                ev.preventDefault();
                if( !$(this).hasClass('on') ) self.navigate( self.element, self.options, self.element.find(self.options.nav).find('li').index(this) );
            });
            if( this.options.autoPlay ) this.startTimer( self.element, self.options );
            if( this.options.enlargeClick ) this.element.find(this.options.items).find('a.enlarge').magnificPopup(magnific.options);

            this.element.find(this.options.nav).find('li:first').addClass('on');

            // HYBRID
            if( this.element.hasClass('hybrid-gallery') ) {
                this.isHybrid = true;
                this.columns = this.element.data().columns;
            }
        },

        changeEffect: function( el, options ) {
            el.find(options.effectlist).addClass(options.effect);
            $(this.options.items).first().addClass('navInNext');
            var anim = $(this.options.items).first().css(Modernizr.prefixed('animation')+'-name');
            if( anim.indexOf('none') == 0 ) {
                el.find(options.effectlist).removeClass(options.effect);
                el.find(options.effectlist).addClass(defaults.effect);
            }
            $(this.options.items).first().removeClass('navInNext');
        },

        startTimer: function( el, options ){
            var self = this;
            if( this.timer ) clearTimeout( this.timer );
            this.timer = setTimeout( function(){
                self.navigate( self.element, self.options, 'next' );
            }, options.slideTime );
        },

        navigate: function( el, options, dir ) {
            var self = this;
            if( this.isAnimating ) return false;
            this.isAnimating = true;
            this.cntAnims = 0;
            if( this.options.autoPlay ) this.startTimer( this.element, this.options );

            this.currentItem = el.find(options.items).eq( this.current );

            if( dir === 'next' ) {
                this.current = this.current < this.itemsCount - 1 ? this.current + 1 : 0;
            } else if( dir === 'prev' ) {
                this.current = this.current > 0 ? this.current - 1 : this.itemsCount - 1;
            } else {
                var dir2 = dir;
                dir =  dir < this.current ? 'prev' : 'next';
                this.current = dir2;
            }

            this.nextItem = el.find(options.items).eq( this.current );

            $('document').trigger('401GalleryAnimStart', ['i', this.current]);

            var onEndAnimationCurrentItem = function() {
                $(self.currentItem).off( animEndEventName, onEndAnimationCurrentItem );
                $(self.currentItem).removeClass( 'current' );
                $(self.currentItem).removeClass( 'navOutNext navOutPrev' );
                ++self.cntAnims;
                if( self.cntAnims === 2 ) {
                    self.isAnimating = false;
                    $('document').trigger('401GalleryAnimEnd', ['i', this.current]);
                }
            };

            var onEndAnimationNextItem = function() {
                $(self.nextItem).off( animEndEventName, onEndAnimationNextItem );
                $(self.nextItem).addClass( 'current' );
                $(self.nextItem).removeClass('navInNext navInPrev');
                ++self.cntAnims;
                if( self.cntAnims === 2 ) {
                    self.isAnimating = false;
                    $('document').trigger('401GalleryAnimEnd', ['i', this.current]);
                }
            };

            if( support.animations ) {
                $(this.currentItem).on( animEndEventName, onEndAnimationCurrentItem );
                $(this.nextItem).on( animEndEventName, onEndAnimationNextItem );
            } else {
                onEndAnimationCurrentItem();
                onEndAnimationNextItem();
            }

            $(this.currentItem).addClass( dir === 'next' ? 'navOutNext' : 'navOutPrev' );
            $(this.nextItem).addClass( dir === 'next' ? 'navInNext' : 'navInPrev' );

            el.find(options.postNumber).text((this.current+1)+' of '+this.itemsCount);

            el.find(options.nav).find('li').removeClass('on').eq( this.current ).addClass('on');

            // HYBRID
            if( this.isHybrid && this.itemsCount > this.columns ) {
                var l = 0;
                var offset = Math.ceil(this.columns/2);
                var move = this.columns - offset;
                var dif = this.current - move;

                if( this.current > move && this.current < this.itemsCount-(offset-1) ) {
                    l = ( 100/this.columns ) * -dif;
                } else if( this.current >= this.itemsCount-(offset-1) ) {
                    l = ( 100/this.columns ) * -(this.itemsCount-offset-move);
                }
                el.find(this.options.nav).css({left:l+'%'});
            }
        }

    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
