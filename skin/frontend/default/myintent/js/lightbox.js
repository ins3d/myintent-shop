// -----------------------------------------------------------------------------------
//
//    Lightbox v2.04
//    by Lokesh Dhakar - http://www.lokeshdhakar.com
//    Last Modification: 2/9/08
//
//    For more information, visit:
//    http://lokeshdhakar.com/projects/lightbox2/
//
//    Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
//      - Free for use in both personal and commercial projects
//        - Attribution requires leaving author name, author link, and the license info intact.
//    
//  Thanks: Scott Upton(uptonic.com), Peter-Paul Koch(quirksmode.com), and Thomas Fuchs(mir.aculo.us) for ideas, libs, and snippets.
//          Artemy Tregubenko (arty.name) for cleanup and help in updating to latest ver of proto-aculous.
//
// -----------------------------------------------------------------------------------

var Lightbox = Class.create();

Lightbox.prototype = {
    imageArray: [],
    activeImage: undefined,
    options: {
        overlayOpacity: 0.8,   // controls transparency of shadow overlay
        animate: true,         // toggles resizing animations
        resizeSpeed: 7,        // controls the speed of the image resizing animations (1=slowest and 10=fastest)
        borderSize: 10,
        // When grouping images this is used to write: Image # of #.
        // Change it for non-english localization
        labelImage: "Image",
        labelOf: "of"
    },
    
    // initialize()
    // Constructor runs on completion of the DOM loading. Calls updateImageList and then
    // the function inserts html at the bottom of the page which is used to display the shadow 
    // overlay and the image container.
    //
    initialize: function(params) {
        Object.extend(this.options, params || {});
        this.animating = false;
        this.keyboardAction = this.keyboardAction.bindAsEventListener(this);
        
        if (this.options.resizeSpeed > 10) this.options.resizeSpeed = 10;
        if (this.options.resizeSpeed < 1)  this.options.resizeSpeed = 1;

        this.resizeDuration = this.options.animate ? ((11 - this.options.resizeSpeed) * 0.15) : 0;
        this.overlayDuration = this.options.animate ? 0.2 : 0;  // shadow fade in/out duration

        // When Lightbox starts it will resize itself from 250 by 250 to the current image dimension.
        // If animations are turned off, it will be hidden as to prevent a flicker of a
        // white 250 by 250 box.
        var size = (this.options.animate ? 250 : 1) + 'px';
        
        this.pageScroll = document.viewport.getScrollOffsets();
        this.pageHeight = document.viewport.getHeight();
        

        // Code inserts html at the bottom of the page that looks similar to this:
        //
        //  <div id="overlay"></div>
        //  <div id="lightbox">
        //      <div id="outerImageContainer">
        //          <div id="imageContainer">
        //              <img id="lightboxImage">
        //              <div style="" id="hoverNav">
        //                  <a href="#" id="prevLink"></a>
        //                  <a href="#" id="nextLink"></a>
        //              </div>
        //              <div id="loading">
        //                  <a href="#" id="loadingLink">
        //                      <img src="images/loading.gif">
        //                  </a>
        //              </div>
        //          </div>
        //      </div>
        //      <div id="imageDataContainer">
        //          <div id="imageData">
        //              <div id="imageDetails">
        //                  <span id="caption"></span>
        //                  <span id="numberDisplay"></span>
        //              </div>
        //              <div id="bottomNav">
        //                  <a href="#" id="bottomNavClose">
        //                      <img src="images/close.gif">
        //                  </a>
        //              </div>
        //          </div>
        //      </div>
        //  </div>


        var objBody = $$('body')[0];

        objBody.appendChild(Builder.node('div',{id:'overlay'}));

        objBody.appendChild(Builder.node('div',{id:'lightbox'}, [
            Builder.node('div',{id:'outerImageContainer'}, 
                Builder.node('div',{id:'imageContainer', style: 'padding: ' + this.options.borderSize + 'px'}, [
//                    Builder.node('img',{id:'lightboxImage'}), 
                    Builder.node('div',{id:'word_help'}), 
                    Builder.node('div',{id:'hoverNav'}, [
                        Builder.node('a',{id:'prevLink', href: '#' }),
                        Builder.node('a',{id:'nextLink', href: '#' })
                    ]),
                    Builder.node('div',{id:'loading'}, 
                        Builder.node('a',{id:'loadingLink', href: '#' }, 
                            Builder.node('img', {src: this.options.fileLoadingImage})
                        )
                    )
                ])
            ),
            Builder.node('div', {id:'imageDataContainer'},
                Builder.node('div',{id:'imageData'}, [
                    Builder.node('div',{id:'imageDetails'}, [
                        Builder.node('span',{id:'caption'}),
                        Builder.node('span',{id:'numberDisplay'})
                    ]),
                    Builder.node('div',{id:'bottomNav'},
                        Builder.node('a',{id:'bottomNavClose', href: '#' },
                            Builder.node('img', { src: this.options.fileBottomNavCloseImage })
                        )
                    )
                ])
            )
        ]));

        $('overlay').hide().observe('click', (function() { this.end(); }).bind(this));
        $('lightbox').hide().observe('click', (function(event) { if (event.element().id == 'lightbox') this.end(); }).bind(this));
        $('outerImageContainer').setStyle({ width: size, height: size });
        $('prevLink').observe('click', (function(event) { event.stop(); this.changeImage(this.activeImage - 1); }).bindAsEventListener(this));
        $('nextLink').observe('click', (function(event) { event.stop(); this.changeImage(this.activeImage + 1); }).bindAsEventListener(this));
        $('loadingLink').observe('click', (function(event) { event.stop(); this.end(); }).bind(this));
        $('bottomNavClose').observe('click', (function(event) { event.stop(); this.end(); }).bind(this));
        
        var th = this;

        var ids = 
            'overlay lightbox outerImageContainer imageContainer word_help hoverNav prevLink nextLink loading loadingLink ' + 
            'imageDataContainer imageData imageDetails caption numberDisplay bottomNav bottomNavClose';   
/*            
        var ids = 
            'overlay lightbox outerImageContainer imageContainer lightboximage hoverNav prevLink nextLink loading loadingLink ' + 
            'imageDataContainer imageData imageDetails caption numberDisplay bottomNav bottomNavClose';   
*/

        $w(ids).each(function(id){ 
            th[id] = $(id); 
        });
        
        this.updateImageList();
    },

    //
    // updateImageList()
    // Loops through anchor tags looking for 'lightbox' references and applies onclick
    // events to appropriate links. You can rerun after dynamically adding images w/ajax.
    //
    updateImageList: function() { 
//        this.updateImageList = Prototype.emptyFunction;
        document.observe('click', (function(event){
            var target = event.findElement('a[rel^=lightbox]') || event.findElement('area[rel^=lightbox]');
            if (target) {
                event.stop();
                this.start(target);
            }
        }).bind(this));
    },
    
    //
    //  start()
    //  Display overlay and lightbox. If image is part of a set, add siblings to imageArray.
    //
    start: function(imageLink) {
        if (this.animating) {
            return false;
        }
        $$('select', 'object', 'embed').each(function(node){ node.style.visibility = 'hidden' });

        // stretch overlay to fill page and fade in
        var arrayPageSize = this.getPageSize();

        this.overlay.setStyle({ width: arrayPageSize[0] + 'px', height: arrayPageSize[1] + 'px' });
        
        new Effect.Appear(this.overlay, { duration: this.overlayDuration, from: 0.0, to: this.options.overlayOpacity });
        
        this.imageArray = [];
        var imageNum = 0;

        if ((imageLink.rel == 'lightbox')){
            // if image is NOT part of a set, add single image to imageArray
            this.imageArray.push([imageLink.href, imageLink.title]);
        } /* else {
            // if image is part of a set..
            this.imageArray = 
                $$(imageLink.tagName + '[href][rel="' + imageLink.rel + '"]').
                collect(function(anchor){ return [anchor.href, anchor.title]; }).
                uniq();		
            while (this.imageArray[imageNum][0] != imageLink.href) { imageNum++; }
        }*/

        // calculate top and left offset for the lightbox 
        var lightboxTop = this.pageScroll[1] + (this.pageHeight / 10);
        var lightboxLeft = this.pageScroll[0];
        this.lightbox.setStyle({ top: lightboxTop + 'px', left: lightboxLeft + 'px' }).show();
        
        this.changeImage(imageNum);
    },

    //
    //  changeImage()
    //  Hide most elements and preload image in preparation for resizing image container.
    //
    changeImage: function(imageNum) {   
        this.activeImage = imageNum; // update global var
        
        // hide elements during transition
        if (this.options.animate) this.loading.show();
//        this.lightboxImage.hide();
        this.word_help.hide();
        this.hoverNav.hide();
        this.prevLink.hide();
        this.nextLink.hide();
        // HACK: Opera9 does not currently support scriptaculous opacity and appear fx
        this.imageDataContainer.setStyle({opacity: .0001});
        this.numberDisplay.hide();

        // retrieve content for word_help
        var word_help_content = document.getElementById("word_help_content").innerHTML;
        document.getElementById("word_help").innerHTML = word_help_content;
        this.resizeImageContainer(265, 265);

/*
        var imgPreloader = new Image();

        // once image is preloaded, resize image container
        imgPreloader.onload = (function(){
            this.lightboxImage.src = this.imageArray[this.activeImage][0];
            this.resizeImageContainer(imgPreloader.width, imgPreloader.height);
        }).bind(this);

        imgPreloader.src = this.imageArray[this.activeImage][0];
*/
    },

    //
    //  resizeImageContainer()
    //
    resizeImageContainer: function(imgWidth, imgHeight) {
        // get current width and height
        var widthCurrent  = this.outerImageContainer.getWidth() || (this.options.animate ? 250 : 1);
        var heightCurrent = this.outerImageContainer.getHeight() || (this.options.animate ? 250 : 1);

        // get new width and height
        var widthNew  = (imgWidth  + this.options.borderSize * 2);
        var heightNew = (imgHeight + this.options.borderSize * 2);
        
        // scalars based on change from old to new
        var xScale = (widthNew  / widthCurrent)  * 100;
        var yScale = (heightNew / heightCurrent) * 100;
        
        // calculate size difference between new and old image, and resize if necessary
        var wDiff = widthCurrent - widthNew;
        var hDiff = heightCurrent - heightNew;
        
        if (hDiff != 0) {
            this.animating = true;
            new Effect.Scale(this.outerImageContainer, yScale, {
                scaleX: false, 
                duration: this.resizeDuration, 
                queue: 'front',
                afterFinish: function() {
                    this.outerImageContainer.setStyle({ height: heightNew + 'px' }); //invalid height fix;
                    this.animating = false;
                }.bind(this)
            }); 
        }
        if (wDiff != 0) {
            this.animating = true;
            new Effect.Scale(this.outerImageContainer, xScale, {
                scaleY : false,
                duration : this.resizeDuration,
                delay : this.resizeDuration,
                afterFinish: function() {
                    this.outerImageContainer.setStyle({ width: widthNew + 'px' }); //invalid width fix;
                    this.animating = false;
                }.bind(this)
            });
        }
        
        // if new and old image are same size and no scaling transition is necessary, 
        // do a quick pause to prevent image flicker.
        var timeout = 0;
        if ((hDiff == 0) && (wDiff == 0)){
            timeout = 100;
            if (Prototype.Browser.IE) timeout = 250;   
        }
        (function(){
            this.prevLink.setStyle({ height: imgHeight + 'px' });
            this.nextLink.setStyle({ height: imgHeight + 'px' });
            this.imageDataContainer.setStyle({ width: widthNew + 'px' });
            this.showImage();
        }).bind(this).delay(timeout / 1000);
    },
    
    //
    //  showImage()
    //  Display image and begin preloading neighbors.
    //
    showImage: function(){
        this.loading.hide();
//        new Effect.Appear(this.lightboxImage, { 
        new Effect.Appear(this.word_help, {     
            duration: this.resizeDuration, 
            queue: 'end', 
            afterFinish: (function(){ this.updateDetails(); }).bind(this) 
        });
        this.preloadNeighborImages();
    },

    //
    //  updateDetails()
    //  Display caption, image number, and bottom nav.
    //
    updateDetails: function() {
    
        // if caption is not null
        if (this.imageArray[this.activeImage][1] != ""){
            this.caption.update(this.imageArray[this.activeImage][1]).show();
        }
        
        // if image is part of set display 'Image x of x' 
        if (this.imageArray.length > 1){
            this.numberDisplay.update( this.options.labelImage + ' ' + (this.activeImage + 1) + ' ' + this.options.labelOf + '  ' + this.imageArray.length).show();
        }

        new Effect.Parallel(
            [ 
                new Effect.SlideDown(this.imageDataContainer, { sync: true, duration: this.resizeDuration, from: 0.0, to: 1.0 }), 
                new Effect.Appear(this.imageDataContainer, { sync: true, duration: this.resizeDuration }) 
            ], 
            { 
                duration: this.resizeDuration, 
                afterFinish: (function() {
                    // update overlay size and update nav
                    var arrayPageSize = this.getPageSize();
                    this.overlay.setStyle({ height: arrayPageSize[1] + 'px' });
                    this.updateNav();
                }).bind(this)
            } 
        );
    },

    //
    //  updateNav()
    //  Display appropriate previous and next hover navigation.
    //
    updateNav: function() {

        this.hoverNav.show();               

        // if not first image in set, display prev image button
        if (this.activeImage > 0) this.prevLink.show();

        // if not last image in set, display next image button
        if (this.activeImage < (this.imageArray.length - 1)) this.nextLink.show();
        
        this.enableKeyboardNav();
    },

    //
    //  enableKeyboardNav()
    //
    enableKeyboardNav: function() {
        document.observe('keydown', this.keyboardAction); 
    },

    //
    //  disableKeyboardNav()
    //
    disableKeyboardNav: function() {
        document.stopObserving('keydown', this.keyboardAction); 
    },

    //
    //  keyboardAction()
    //
    keyboardAction: function(event) {
        var keycode = event.keyCode;

        var escapeKey;
        if (event.DOM_VK_ESCAPE) {  // mozilla
            escapeKey = event.DOM_VK_ESCAPE;
        } else { // ie
            escapeKey = 27;
        }

        var key = String.fromCharCode(keycode).toLowerCase();
        
        if (key.match(/x|o|c/) || (keycode == escapeKey)){ // close lightbox
            this.end();
        } else if ((key == 'p') || (keycode == 37)){ // display previous image
            if (this.activeImage != 0){
                this.disableKeyboardNav();
                this.changeImage(this.activeImage - 1);
            }
        } else if ((key == 'n') || (keycode == 39)){ // display next image
            if (this.activeImage != (this.imageArray.length - 1)){
                this.disableKeyboardNav();
                this.changeImage(this.activeImage + 1);
            }
        }
    },

    //
    //  preloadNeighborImages()
    //  Preload previous and next images.
    //
    preloadNeighborImages: function(){
        var preloadNextImage, preloadPrevImage;
        if (this.imageArray.length > this.activeImage + 1){
            preloadNextImage = new Image();
            preloadNextImage.src = this.imageArray[this.activeImage + 1][0];
        }
        if (this.activeImage > 0){
            preloadPrevImage = new Image();
            preloadPrevImage.src = this.imageArray[this.activeImage - 1][0];
        }
    
    },

    //
    //  end()
    //
    end: function() {
        this.disableKeyboardNav();
        this.lightbox.hide();
        new Effect.Fade(this.overlay, { duration: this.overlayDuration });
        $$('select', 'object', 'embed').each(function(node){ node.style.visibility = 'visible' });
    },

    //
    //  getPageSize()
    //
    getPageSize: function() {
            
         var xScroll, yScroll;
        
        if (window.innerHeight && window.scrollMaxY) {    
            xScroll = window.innerWidth + window.scrollMaxX;
            yScroll = window.innerHeight + window.scrollMaxY;
        } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
            xScroll = document.body.scrollWidth;
            yScroll = document.body.scrollHeight;
        } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
            xScroll = document.body.offsetWidth;
            yScroll = document.body.offsetHeight;
        }
        
        var windowWidth, windowHeight;
        
        if (self.innerHeight) {    // all except Explorer
            if(document.documentElement.clientWidth){
                windowWidth = document.documentElement.clientWidth; 
            } else {
                windowWidth = self.innerWidth;
            }
            windowHeight = self.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowWidth = document.documentElement.clientWidth;
            windowHeight = document.documentElement.clientHeight;
        } else if (document.body) { // other Explorers
            windowWidth = document.body.clientWidth;
            windowHeight = document.body.clientHeight;
        }    
        
        // for small pages with total height less then height of the viewport
        if(yScroll < windowHeight){
            pageHeight = windowHeight;
        } else { 
            pageHeight = yScroll;
        }
    
        // for small pages with total width less then width of the viewport
        if(xScroll < windowWidth){    
            pageWidth = xScroll;        
        } else {
            pageWidth = windowWidth;
        }

        return [pageWidth,pageHeight];
    }
}