(function($){
	$.fn.blinker = function(options){
		options = $.extend({}, $.fn.blinker.defaultOptions, options);
		
		$(this).each(function(){
			var data = {
				el: $(this),
				firstTrigger: true,
				blinkerStep: 0,
				timer: null,
				timeHidden: options.timeHidden,
				intervalRangeStart: options.intervalRangeStart,
				intervalRangeStop: options.intervalRangeStop,
				blinkagain: function(){
					// first step: hide
					if(this.blinkerStep == 0){
						// dont blink on the first call
						if(this.firstTrigger != true){
							this.el.css("visibility", "hidden");
						}
						this.blinkerStep = 1;
						this.timer = setTimeout(
							$.proxy(
								function(){
									this.blinkagain();
								},
								this
							),
							this.timeHidden
						);
					// second step: show
					} else if(this.blinkerStep == 1){
						// dont blink on the first call
						if(this.firstTrigger != true){
							this.el.css("visibility", "visible");
						} else {
							this.firstTrigger = false;
						}
						this.blinkerStep = 0;
						var randomTime = Math.round(this.intervalRangeStart + (Math.random() * (this.intervalRangeStop - this.intervalRangeStart)));
						this.timer = setTimeout(
							$.proxy(
								function(){
									this.blinkagain();
								},
								this
							),
							randomTime
						);
					}
				},
				pause: function(){
					clearTimeout(this.timer);
					this.blinkerStep = 1; // will start back at the waiting part
					this.el.css("visibility", "visible");
				}
			};
			$(this).data("blinker", data);
			data.blinkagain();
		});
		
		return $(this);
	};
	
	$.fn.blinker.defaultOptions = {
		timeHidden: 100,
		intervalRangeStart: 500,
		intervalRangeStop: 2000
	};
})(jQuery);