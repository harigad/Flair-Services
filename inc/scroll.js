$flair.scroll = {

	

	startTime: null,

	startY: null,



	init: function () {	

		this.canvas = document.getElementById('canvas');

		this.container = document.getElementById('main');

		this.scroller = document.getElementById('scroller');

	

	//	this.canvas.addEventListener('touchstart', this.touchstart, false);

	//	this.canvas.addEventListener('touchmove', this.touchmove, false);

	//	this.canvas.addEventListener('touchend', this.touchend, false);			

		

	},	



	touchstart: function (e) {   

		

		that = $flair.scroll;

		

		that.startY = e.touches[0].clientY;

		that.startTime = e.timeStamp;

		e.preventDefault();    

	},



	touchmove: function (e){

		

		that = $flair.scroll;

			

		e.preventDefault();

		var posY = e.touches[0].pageY;



		// Set previous position

		$i.vars.oldY = $i.vars.oldY || posY;



		// Set a top value (if none exists)

		if (!that.scroller.style.top) {

			that.scroller.style.top = 0 + "px";

		}

    

		// Make sure we don't scroll past boundaries

		var value;

		var boundary = (that.container.offsetHeight - that.scroller.offsetHeight);



		// If current position is greater than old position

		if (posY > $i.vars.oldY) {

			// Value = current position + (Y-point - old position)

			value = parseFloat(that.scroller.style.top) + (posY - $i.vars.oldY);

			// If value is negative

			if (value <= 0) {

				// We're good

				that.scroller.style.top = value + "px";

			// Otherwise, we're over the limit

			} else {

				// So mimic the 'snap' to top

				that.scroller.style.top = (value * 0.9) + "px";

			}



		// If current position is less than old position

		} else if (posY < $i.vars.oldY) {

			value = parseFloat(that.scroller.style.top) - ($i.vars.oldY - posY);

			that.scroller.style.top = value + "px";

		}



		// Done with function, current position is now old

		$i.vars.oldY = posY;		

	},



	touchend: function (e) {

	

		that = $flair.scroll;

    

		hideBar();

		// Log current Y-point

		endY = e.changedTouches[0].clientY;



		// Log timestamp

		endTime = e.timeStamp;



		// Log current Y offset

		var posY = parseFloat(that.scroller.style.top);



		// If offset is greater than 0



		if (posY > 0) {



			// Scroll to 0

			$i.utils.scrollToY(0);

    

		} else {



			// Do all the math

			var distance = that.startY - endY;

			var time = endTime - that.startTime;

			var speed = Math.abs(distance / time);



			// y = current position - (distance * speed)

			var y = parseFloat(that.scroller.style.top) - (distance * speed);



			if ((time < 600) && distance > 50) {

				// Flicks should go farther

				y = y + (y*0.7);

			}



			// Set boundary

			// alert(y + "--" + container.offsetHeight +  "--" + content.offsetHeight)

			var boundary = ((that.container.offsetHeight-45) - that.scroller.offsetHeight);



			// Make sure y does not exceed boundaries

			y = (y <= boundary) ? boundary : (y > 0) ? 0 : y;



			if(that.scroller.offsetHeight<=that.container.offsetHeight){

				if(y>>0){

					y=0;

				}

			}



			// Scroll to specified point

			$i.utils.scrollToY(y);

		}



		// Clean up after ourselves

		delete $i.vars.oldY;

	},

	

	scrollTo: function (y) {

		this.scroller.style.top = y + "px";	

	},

		

	enableLinksOnTap: function (thisDivName) {	

		var items = document.querySelectorAll("#" + thisDivName + " a");

		for (var i = 0, j = items.length; i < j; i++) {

			var item = items[i];			

			item.addEventListener("touchstart", this.tapStart, false);

			item.addEventListener("touchmove", this.tapMove, false);

			item.addEventListener("touchend", this.tapEnd, false);			

		}	

	},

	

	tapStart: function (e) {			

		e.preventDefault();		

		that = $flair.scroll;

		that.tapCancel=false;

		

		_this = this;

		

		this.timeout = setTimeout(function() {

//		   $i.utils.addClass(_this, "highlighted");

    	}, 150);

	

	},

	

	tapMove: function (e) {

		e.preventDefault();

		that = $flair.scroll;		

		clearTimeout(this.timeout);

		that.tapCancel = true;    	

	},

	

	tapEnd: function (e) {

		e.preventDefault();

		that = $flair.scroll;	



		//$i.utils.removeClass(this,"highlighted");

		   

		if (!that.tapCancel) {

			//thisTargetPageNameArray=this.href.split("/");

			//thisTargetPageName=thisTargetPageNameArray[thisTargetPageNameArray.length-1];



			if(this.onclick!=undefined){

				this.onclick();

			}else{

				$flair.go.url(this.href);

			}

		}



		// Reset flag

		that.tapCancel = false;	

	}

	

}











































// Local shorthand variable

var $i = this;



// Shared variables

$i.vars = {};



// Shared utilities

$i.utils = {



    // Adds class name to element

    addClass : function(element, elClass) {

        var curr = element.className;

        if (!new RegExp(("(^|\\s)" + elClass + "(\\s|$)"), "i").test(curr)) {

            element.className = curr + ((curr.length > 0) ? " " : "") + elClass;

        }



        // $i.utils.oldBackground=element.style.backgroundColor;



        //$i.utils.oldColor=element.style.color;

        //element.style.background="#0874e1";

        //element.style.color="#ffffff";

        return element;

    },



    // Removes class name from element

    removeClass : function(element, elClass) {

        if (elClass) {

            element.className = element.className.replace(elClass, "");

        } else {

            element.className = "";

            element.removeAttribute("class");

        }

        //element.style.backgroundColor="#ffffff";

        //element.style.color="#666666";

        return element;

    },



    // Hide the annoying load bar

    hideURLBar : function() {

        setTimeout(function() {

            window.scrollTo(0, 1);

        }, 0);

    },



    // updateOrientation checks the current orientation, sets the body's class attribute to portrait,

    updateOrientation : function() {

        var orientation = window.orientation;



        switch (orientation) {



            // If we're horizontal

            case 90:

            case -90:



                // Set orient to landscape

                document.body.setAttribute("orient", "landscape");

                break;



            // If we're vertical

            default:



                // Set orient to portrait

                document.body.setAttribute("orient", "portrait");

                break;

        }



    },



    scrollToY : function(y) {

	

		that = $flair.scroll;

	

        var ms = 700; // number of milliseconds

        var content = that.scroller;



        // Grab current offset

        var top = parseFloat(content.style.top);



        // Convert negative to positive if need be

        var currentTop = (top < 0) ? -(top) : top;



        // Divide offset by 250 (more offset = more scroll time)

        var chunks = (currentTop / 100);



        // Calculate total time

        var totalTime = (ms * chunks);



        // Make sure time does not exceed 750ms

        totalTime = (totalTime > 750) ? 750 : totalTime;



        // Prep for animation

        content.style.webkitTransition = "top " + totalTime + "ms cubic-bezier(0.1, 0.25, 0.1, 1.0)";



        // Animate to specified Y point

        content.style.top = y + "px";

        ypx=y + "px";

       //$(content).animate({top:ypx},700,"easeInOutQuad");

        // Clean up after ourselves

        setTimeout(function() {

            content.style.webkitTransition = "none";

        }, totalTime);

    }

};

