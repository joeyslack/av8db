(function(factory){if(typeof define==='function'&&define.amd){define(['jquery'],factory);}else if(typeof exports==='object'){factory(require('jquery'));}else{factory(jQuery);}})(function($){'use strict';var $window=$(window);var $document=$(document);var location=window.location;var NAMESPACE='cropper';var PREVIEW='preview.'+NAMESPACE;var CLASS_MODAL='cropper-modal';var CLASS_HIDE='cropper-hide';var CLASS_HIDDEN='cropper-hidden';var CLASS_INVISIBLE='cropper-invisible';var CLASS_MOVE='cropper-move';var CLASS_CROP='cropper-crop';var CLASS_DISABLED='cropper-disabled';var CLASS_BG='cropper-bg';var EVENT_MOUSE_DOWN='mousedown touchstart pointerdown MSPointerDown';var EVENT_MOUSE_MOVE='mousemove touchmove pointermove MSPointerMove';var EVENT_MOUSE_UP='mouseup touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel';var EVENT_WHEEL='wheel mousewheel DOMMouseScroll';var EVENT_DBLCLICK='dblclick';var EVENT_RESIZE='resize.'+NAMESPACE;var EVENT_BUILD='build.'+NAMESPACE;var EVENT_BUILT='built.'+NAMESPACE;var EVENT_CROP_START='cropstart.'+NAMESPACE;var EVENT_CROP_MOVE='cropmove.'+NAMESPACE;var EVENT_CROP_END='cropend.'+NAMESPACE;var EVENT_CROP='crop.'+NAMESPACE;var EVENT_ZOOM='zoom.'+NAMESPACE;var REGEXP_ACTIONS=/^(e|w|s|n|se|sw|ne|nw|all|crop|move|zoom)$/;var ACTION_EAST='e';var ACTION_WEST='w';var ACTION_SOUTH='s';var ACTION_NORTH='n';var ACTION_SOUTH_EAST='se';var ACTION_SOUTH_WEST='sw';var ACTION_NORTH_EAST='ne';var ACTION_NORTH_WEST='nw';var ACTION_ALL='all';var ACTION_CROP='crop';var ACTION_MOVE='move';var ACTION_ZOOM='zoom';var ACTION_NONE='none';var SUPPORT_CANVAS=$.isFunction($('<canvas>')[0].getContext);var sqrt=Math.sqrt;var min=Math.min;var max=Math.max;var abs=Math.abs;var sin=Math.sin;var cos=Math.cos;var num=parseFloat;var prototype={};function isNumber(n){return typeof n==='number'&&!isNaN(n);}function isUndefined(n){return typeof n==='undefined';}function toArray(obj,offset){var args=[];if(isNumber(offset)){args.push(offset);}return args.slice.apply(obj,args);}function proxy(fn,context){var args=toArray(arguments,2);return function(){return fn.apply(context,args.concat(toArray(arguments)));};}function isCrossOriginURL(url){var parts=url.match(/^(https?:)\/\/([^\:\/\?#]+):?(\d*)/i);return parts&&(parts[1]!==location.protocol||parts[2]!==location.hostname||parts[3]!==location.port);}function addTimestamp(url){var timestamp='timestamp='+(new Date()).getTime();return(url+(url.indexOf('?')===-1?'?':'&')+timestamp);}function getTransform(options){var transforms=[];var rotate=options.rotate;var scaleX=options.scaleX;var scaleY=options.scaleY;if(isNumber(rotate)){transforms.push('rotate('+rotate+'deg)');}if(isNumber(scaleX)&&isNumber(scaleY)){transforms.push('scale('+scaleX+','+scaleY+')');}return transforms.length?transforms.join(' '):'none';}function getRotatedSizes(data,reverse){var deg=abs(data.degree)%180;var arc=(deg>90?(180-deg):deg)*Math.PI/180;var sinArc=sin(arc);var cosArc=cos(arc);var width=data.width;var height=data.height;var aspectRatio=data.aspectRatio;var newWidth;var newHeight;if(!reverse){newWidth=width*cosArc+height*sinArc;newHeight=width*sinArc+height*cosArc;}else{newWidth=width/(cosArc+sinArc/aspectRatio);newHeight=newWidth/aspectRatio;}return{width:newWidth,height:newHeight};}function getSourceCanvas(image,data){var canvas=$('<canvas>')[0];var context=canvas.getContext('2d');var x=0;var y=0;var width=data.naturalWidth;var height=data.naturalHeight;var rotate=data.rotate;var scaleX=data.scaleX;var scaleY=data.scaleY;var scalable=isNumber(scaleX)&&isNumber(scaleY)&&(scaleX!==1||scaleY!==1);var rotatable=isNumber(rotate)&&rotate!==0;var advanced=rotatable||scalable;var canvasWidth=width;var canvasHeight=height;var translateX;var translateY;var rotated;if(scalable){translateX=width/2;translateY=height/2;}if(rotatable){rotated=getRotatedSizes({width:width,height:height,degree:rotate});canvasWidth=rotated.width;canvasHeight=rotated.height;translateX=rotated.width/2;translateY=rotated.height/2;}canvas.width=canvasWidth;canvas.height=canvasHeight;if(advanced){x=-width/2;y=-height/2;context.save();context.translate(translateX,translateY);}if(rotatable){context.rotate(rotate*Math.PI/180);}if(scalable){context.scale(scaleX,scaleY);}context.drawImage(image,x,y,width,height);if(advanced){context.restore();}return canvas;}function Cropper(element,options){this.$element=$(element);this.options=$.extend({},Cropper.DEFAULTS,$.isPlainObject(options)&&options);this.ready=false;this.built=false;this.rotated=false;this.cropped=false;this.disabled=false;this.replaced=false;this.isImg=false;this.originalUrl='';this.canvas=null;this.cropBox=null;this.init();}$.extend(prototype,{init:function(){var $this=this.$element;var url;if($this.is('img')){this.isImg=true;this.originalUrl=url=$this.attr('src');if(!url){return;}url=$this.prop('src');}else if($this.is('canvas')&&SUPPORT_CANVAS){url=$this[0].toDataURL();}this.load(url);},trigger:function(type,data){var e=$.Event(type,data);this.$element.trigger(e);return e.isDefaultPrevented();},load:function(url){var options=this.options;var $this=this.$element;var crossOrigin='';var bustCacheUrl;var $clone;if(!url){return;}$this.one(EVENT_BUILD,options.build);if(this.trigger(EVENT_BUILD)){return;}if(options.checkImageOrigin&&isCrossOriginURL(url)){crossOrigin=' crossOrigin="anonymous"';if(!$this.prop('crossOrigin')){bustCacheUrl=addTimestamp(url);}}this.$clone=$clone=$('<img'+crossOrigin+' src="'+(bustCacheUrl||url)+'">');$clone.one('load',$.proxy(function(){var image=$clone[0];var naturalWidth=image.naturalWidth||image.width;var naturalHeight=image.naturalHeight||image.height;this.image={naturalWidth:naturalWidth,naturalHeight:naturalHeight,aspectRatio:naturalWidth/naturalHeight};this.url=url;this.ready=true;this.build();},this)).one('error',function(){$clone.remove();});$clone.addClass(CLASS_HIDE).insertAfter($this);}});$.extend(prototype,{build:function(){var options=this.options;var $this=this.$element;var $clone=this.$clone;var $cropper;var $cropBox;var $face;if(!this.ready){return;}if(this.built){this.unbuild();}this.$cropper=$cropper=$(Cropper.TEMPLATE);$this.addClass(CLASS_HIDDEN);$clone.removeClass(CLASS_HIDE);this.$container=$this.parent().append($cropper);this.$canvas=$cropper.find('.cropper-canvas').append($clone);this.$dragBox=$cropper.find('.cropper-drag-box');this.$cropBox=$cropBox=$cropper.find('.cropper-crop-box');this.$viewBox=$cropper.find('.cropper-view-box');this.$face=$face=$cropBox.find('.cropper-face');this.initPreview();this.bind();options.aspectRatio=num(options.aspectRatio)||NaN;if(options.autoCrop){this.cropped=true;if(options.modal){this.$dragBox.addClass(CLASS_MODAL);}}else{$cropBox.addClass(CLASS_HIDDEN);}if(!options.guides){$cropBox.find('.cropper-dashed').addClass(CLASS_HIDDEN);}if(!options.center){$cropBox.find('.cropper-center').addClass(CLASS_HIDDEN);}if(options.cropBoxMovable){$face.addClass(CLASS_MOVE).data('action',ACTION_ALL);}if(!options.highlight){$face.addClass(CLASS_INVISIBLE);}if(options.background){$cropper.addClass(CLASS_BG);}if(!options.cropBoxResizable){$cropBox.find('.cropper-line, .cropper-point').addClass(CLASS_HIDDEN);}this.setDragMode(options.dragCrop?ACTION_CROP:(options.movable?ACTION_MOVE:ACTION_NONE));this.built=true;this.render();this.setData(options.data);$this.one(EVENT_BUILT,options.built);this.trigger(EVENT_BUILT);},unbuild:function(){if(!this.built){return;}this.built=false;this.initialImage=null;this.initialCanvas=null;this.initialCropBox=null;this.container=null;this.canvas=null;this.cropBox=null;this.unbind();this.resetPreview();this.$preview=null;this.$viewBox=null;this.$cropBox=null;this.$dragBox=null;this.$canvas=null;this.$container=null;this.$cropper.remove();this.$cropper=null;}});$.extend(prototype,{render:function(){this.initContainer();this.initCanvas();this.initCropBox();this.renderCanvas();if(this.cropped){this.renderCropBox();}},initContainer:function(){var options=this.options;var $this=this.$element;var $container=this.$container;var $cropper=this.$cropper;$cropper.addClass(CLASS_HIDDEN);$this.removeClass(CLASS_HIDDEN);$cropper.css((this.container={width:max($container.width(),num(options.minContainerWidth)||200),height:max($container.height(),num(options.minContainerHeight)||100)}));$this.addClass(CLASS_HIDDEN);$cropper.removeClass(CLASS_HIDDEN);},initCanvas:function(){var container=this.container;var containerWidth=container.width;var containerHeight=container.height;var image=this.image;var aspectRatio=image.aspectRatio;var canvas={aspectRatio:aspectRatio,width:containerWidth,height:containerHeight};if(containerHeight*aspectRatio>containerWidth){canvas.height=containerWidth/aspectRatio;}else{canvas.width=containerHeight*aspectRatio;}canvas.oldLeft=canvas.left=(containerWidth-canvas.width)/2;canvas.oldTop=canvas.top=(containerHeight-canvas.height)/2;this.canvas=canvas;this.limitCanvas(true,true);this.initialImage=$.extend({},image);this.initialCanvas=$.extend({},canvas);},limitCanvas:function(size,position){var options=this.options;var strict=options.strict;var container=this.container;var containerWidth=container.width;var containerHeight=container.height;var canvas=this.canvas;var aspectRatio=canvas.aspectRatio;var cropBox=this.cropBox;var cropped=this.cropped&&cropBox;var initialCanvas=this.initialCanvas||canvas;var initialCanvasWidth=initialCanvas.width;var initialCanvasHeight=initialCanvas.height;var minCanvasWidth;var minCanvasHeight;if(size){minCanvasWidth=num(options.minCanvasWidth)||0;minCanvasHeight=num(options.minCanvasHeight)||0;if(minCanvasWidth){if(strict){minCanvasWidth=max(cropped?cropBox.width:initialCanvasWidth,minCanvasWidth);}minCanvasHeight=minCanvasWidth/aspectRatio;}else if(minCanvasHeight){if(strict){minCanvasHeight=max(cropped?cropBox.height:initialCanvasHeight,minCanvasHeight);}minCanvasWidth=minCanvasHeight*aspectRatio;}else if(strict){if(cropped){minCanvasWidth=cropBox.width;minCanvasHeight=cropBox.height;if(minCanvasHeight*aspectRatio>minCanvasWidth){minCanvasWidth=minCanvasHeight*aspectRatio;}else{minCanvasHeight=minCanvasWidth/aspectRatio;}}else{minCanvasWidth=initialCanvasWidth;minCanvasHeight=initialCanvasHeight;}}$.extend(canvas,{minWidth:minCanvasWidth,minHeight:minCanvasHeight,maxWidth:Infinity,maxHeight:Infinity});}if(position){if(strict){if(cropped){canvas.minLeft=min(cropBox.left,(cropBox.left+cropBox.width)-canvas.width);canvas.minTop=min(cropBox.top,(cropBox.top+cropBox.height)-canvas.height);canvas.maxLeft=cropBox.left;canvas.maxTop=cropBox.top;}else{canvas.minLeft=min(0,containerWidth-canvas.width);canvas.minTop=min(0,containerHeight-canvas.height);canvas.maxLeft=max(0,containerWidth-canvas.width);canvas.maxTop=max(0,containerHeight-canvas.height);}}else{canvas.minLeft=-canvas.width;canvas.minTop=-canvas.height;canvas.maxLeft=containerWidth;canvas.maxTop=containerHeight;}}},renderCanvas:function(changed){var options=this.options;var canvas=this.canvas;var image=this.image;var aspectRatio;var rotated;if(this.rotated){this.rotated=false;rotated=getRotatedSizes({width:image.width,height:image.height,degree:image.rotate});aspectRatio=rotated.width/rotated.height;if(aspectRatio!==canvas.aspectRatio){canvas.left-=(rotated.width-canvas.width)/2;canvas.top-=(rotated.height-canvas.height)/2;canvas.width=rotated.width;canvas.height=rotated.height;canvas.aspectRatio=aspectRatio;this.limitCanvas(true,false);}}if(canvas.width>canvas.maxWidth||canvas.width<canvas.minWidth){canvas.left=canvas.oldLeft;}if(canvas.height>canvas.maxHeight||canvas.height<canvas.minHeight){canvas.top=canvas.oldTop;}canvas.width=min(max(canvas.width,canvas.minWidth),canvas.maxWidth);canvas.height=min(max(canvas.height,canvas.minHeight),canvas.maxHeight);this.limitCanvas(false,true);canvas.oldLeft=canvas.left=min(max(canvas.left,canvas.minLeft),canvas.maxLeft);canvas.oldTop=canvas.top=min(max(canvas.top,canvas.minTop),canvas.maxTop);this.$canvas.css({width:canvas.width,height:canvas.height,left:canvas.left,top:canvas.top});this.renderImage();if(this.cropped&&options.strict){this.limitCropBox(true,true);}if(changed){this.output();}},renderImage:function(changed){var canvas=this.canvas;var image=this.image;var reversed;if(image.rotate){reversed=getRotatedSizes({width:canvas.width,height:canvas.height,degree:image.rotate,aspectRatio:image.aspectRatio},true);}$.extend(image,reversed?{width:reversed.width,height:reversed.height,left:(canvas.width-reversed.width)/2,top:(canvas.height-reversed.height)/2}:{width:canvas.width,height:canvas.height,left:0,top:0});this.$clone.css({width:image.width,height:image.height,marginLeft:image.left,marginTop:image.top,transform:getTransform(image)});if(changed){this.output();}},initCropBox:function(){var options=this.options;var canvas=this.canvas;var aspectRatio=options.aspectRatio;var autoCropArea=num(options.autoCropArea)||0.8;var cropBox={width:canvas.width,height:canvas.height};if(aspectRatio){if(canvas.height*aspectRatio>canvas.width){cropBox.height=cropBox.width/aspectRatio;}else{cropBox.width=cropBox.height*aspectRatio;}}this.cropBox=cropBox;this.limitCropBox(true,true);cropBox.width=min(max(cropBox.width,cropBox.minWidth),cropBox.maxWidth);cropBox.height=min(max(cropBox.height,cropBox.minHeight),cropBox.maxHeight);cropBox.width=max(cropBox.minWidth,cropBox.width*autoCropArea);cropBox.height=max(cropBox.minHeight,cropBox.height*autoCropArea);cropBox.oldLeft=cropBox.left=canvas.left+(canvas.width-cropBox.width)/2;cropBox.oldTop=cropBox.top=canvas.top+(canvas.height-cropBox.height)/2;this.initialCropBox=$.extend({},cropBox);},limitCropBox:function(size,position){var options=this.options;var strict=options.strict;var container=this.container;var containerWidth=container.width;var containerHeight=container.height;var canvas=this.canvas;var cropBox=this.cropBox;var aspectRatio=options.aspectRatio;var minCropBoxWidth;var minCropBoxHeight;if(size){minCropBoxWidth=num(options.minCropBoxWidth)||0;minCropBoxHeight=num(options.minCropBoxHeight)||0;cropBox.minWidth=min(containerWidth,minCropBoxWidth);cropBox.minHeight=min(containerHeight,minCropBoxHeight);cropBox.maxWidth=min(containerWidth,strict?canvas.width:containerWidth);cropBox.maxHeight=min(containerHeight,strict?canvas.height:containerHeight);if(aspectRatio){if(cropBox.maxHeight*aspectRatio>cropBox.maxWidth){cropBox.minHeight=cropBox.minWidth/aspectRatio;cropBox.maxHeight=cropBox.maxWidth/aspectRatio;}else{cropBox.minWidth=cropBox.minHeight*aspectRatio;cropBox.maxWidth=cropBox.maxHeight*aspectRatio;}}cropBox.minWidth=min(cropBox.maxWidth,cropBox.minWidth);cropBox.minHeight=min(cropBox.maxHeight,cropBox.minHeight);}if(position){if(strict){cropBox.minLeft=max(0,canvas.left);cropBox.minTop=max(0,canvas.top);cropBox.maxLeft=min(containerWidth,canvas.left+canvas.width)-cropBox.width;cropBox.maxTop=min(containerHeight,canvas.top+canvas.height)-cropBox.height;}else{cropBox.minLeft=0;cropBox.minTop=0;cropBox.maxLeft=containerWidth-cropBox.width;cropBox.maxTop=containerHeight-cropBox.height;}}},renderCropBox:function(){var options=this.options;var container=this.container;var containerWidth=container.width;var containerHeight=container.height;var cropBox=this.cropBox;if(cropBox.width>cropBox.maxWidth||cropBox.width<cropBox.minWidth){cropBox.left=cropBox.oldLeft;}if(cropBox.height>cropBox.maxHeight||cropBox.height<cropBox.minHeight){cropBox.top=cropBox.oldTop;}cropBox.width=min(max(cropBox.width,cropBox.minWidth),cropBox.maxWidth);cropBox.height=min(max(cropBox.height,cropBox.minHeight),cropBox.maxHeight);this.limitCropBox(false,true);cropBox.oldLeft=cropBox.left=min(max(cropBox.left,cropBox.minLeft),cropBox.maxLeft);cropBox.oldTop=cropBox.top=min(max(cropBox.top,cropBox.minTop),cropBox.maxTop);if(options.movable&&options.cropBoxMovable){this.$face.data('action',(cropBox.width===containerWidth&&cropBox.height===containerHeight)?ACTION_MOVE:ACTION_ALL);}this.$cropBox.css({width:cropBox.width,height:cropBox.height,left:cropBox.left,top:cropBox.top});if(this.cropped&&options.strict){this.limitCanvas(true,true);}if(!this.disabled){this.output();}},output:function(){this.preview();this.trigger(EVENT_CROP,this.getData());}});$.extend(prototype,{initPreview:function(){var url=this.url;this.$preview=$(this.options.preview);this.$viewBox.html('<img src="'+url+'">');this.$preview.each(function(){var $this=$(this);$this.data(PREVIEW,{width:$this.width(),height:$this.height(),original:$this.html()});$this.html('<img src="'+url+'" style="display:block;width:100%;'+'min-width:0!important;min-height:0!important;'+'max-width:none!important;max-height:none!important;'+'image-orientation:0deg!important">');});},resetPreview:function(){this.$preview.each(function(){var $this=$(this);$this.html($this.data(PREVIEW).original).removeData(PREVIEW);});},preview:function(){var image=this.image;var canvas=this.canvas;var cropBox=this.cropBox;var width=image.width;var height=image.height;var left=cropBox.left-canvas.left-image.left;var top=cropBox.top-canvas.top-image.top;if(!this.cropped||this.disabled){return;}this.$viewBox.find('img').css({width:width,height:height,marginLeft:-left,marginTop:-top,transform:getTransform(image)});this.$preview.each(function(){var $this=$(this);var data=$this.data(PREVIEW);var ratio=data.width/cropBox.width;var newWidth=data.width;var newHeight=cropBox.height*ratio;if(newHeight>data.height){ratio=data.height/cropBox.height;newWidth=cropBox.width*ratio;newHeight=data.height;}$this.width(newWidth).height(newHeight).find('img').css({width:width*ratio,height:height*ratio,marginLeft:-left*ratio,marginTop:-top*ratio,transform:getTransform(image)});});}});$.extend(prototype,{bind:function(){var options=this.options;var $this=this.$element;var $cropper=this.$cropper;if($.isFunction(options.cropstart)){$this.on(EVENT_CROP_START,options.cropstart);}if($.isFunction(options.cropmove)){$this.on(EVENT_CROP_MOVE,options.cropmove);}if($.isFunction(options.cropend)){$this.on(EVENT_CROP_END,options.cropend);}if($.isFunction(options.crop)){$this.on(EVENT_CROP,options.crop);}if($.isFunction(options.zoom)){$this.on(EVENT_ZOOM,options.zoom);}$cropper.on(EVENT_MOUSE_DOWN,$.proxy(this.cropStart,this));if(options.zoomable&&options.mouseWheelZoom){$cropper.on(EVENT_WHEEL,$.proxy(this.wheel,this));}if(options.doubleClickToggle){$cropper.on(EVENT_DBLCLICK,$.proxy(this.dblclick,this));}$document.on(EVENT_MOUSE_MOVE,(this._cropMove=proxy(this.cropMove,this))).on(EVENT_MOUSE_UP,(this._cropEnd=proxy(this.cropEnd,this)));if(options.responsive){$window.on(EVENT_RESIZE,(this._resize=proxy(this.resize,this)));}},unbind:function(){var options=this.options;var $this=this.$element;var $cropper=this.$cropper;if($.isFunction(options.cropstart)){$this.off(EVENT_CROP_START,options.cropstart);}if($.isFunction(options.cropmove)){$this.off(EVENT_CROP_MOVE,options.cropmove);}if($.isFunction(options.cropend)){$this.off(EVENT_CROP_END,options.cropend);}if($.isFunction(options.crop)){$this.off(EVENT_CROP,options.crop);}if($.isFunction(options.zoom)){$this.off(EVENT_ZOOM,options.zoom);}$cropper.off(EVENT_MOUSE_DOWN,this.cropStart);if(options.zoomable&&options.mouseWheelZoom){$cropper.off(EVENT_WHEEL,this.wheel);}if(options.doubleClickToggle){$cropper.off(EVENT_DBLCLICK,this.dblclick);}$document.off(EVENT_MOUSE_MOVE,this._cropMove).off(EVENT_MOUSE_UP,this._cropEnd);if(options.responsive){$window.off(EVENT_RESIZE,this._resize);}}});$.extend(prototype,{resize:function(){var $container=this.$container;var container=this.container;var canvasData;var cropBoxData;var ratio;if(this.disabled||!container){return;}ratio=$container.width()/container.width;if(ratio!==1||$container.height()!==container.height){canvasData=this.getCanvasData();cropBoxData=this.getCropBoxData();this.render();this.setCanvasData($.each(canvasData,function(i,n){canvasData[i]=n*ratio;}));this.setCropBoxData($.each(cropBoxData,function(i,n){cropBoxData[i]=n*ratio;}));}},dblclick:function(){if(this.disabled){return;}if(this.$dragBox.hasClass(CLASS_CROP)){this.setDragMode(ACTION_MOVE);}else{this.setDragMode(ACTION_CROP);}},wheel:function(event){var originalEvent=event.originalEvent;var e=originalEvent;var ratio=num(this.options.wheelZoomRatio)||0.1;var delta=1;if(this.disabled){return;}event.preventDefault();if(e.deltaY){delta=e.deltaY>0?1:-1;}else if(e.wheelDelta){delta=-e.wheelDelta/120;}else if(e.detail){delta=e.detail>0?1:-1;}this.zoom(-delta*ratio,originalEvent);},cropStart:function(event){var options=this.options;var originalEvent=event.originalEvent;var touches=originalEvent&&originalEvent.touches;var e=originalEvent||event;var touchesLength;var action;if(this.disabled){return;}if(touches){touchesLength=touches.length;if(touchesLength>1){if(options.zoomable&&options.touchDragZoom&&touchesLength===2){e=touches[1];this.startX2=e.pageX;this.startY2=e.pageY;action=ACTION_ZOOM;}else{return;}}e=touches[0];}action=action||$(e.target).data('action');if(REGEXP_ACTIONS.test(action)){if(this.trigger(EVENT_CROP_START,{originalEvent:originalEvent,action:action})){return;}event.preventDefault();this.action=action;this.cropping=false;this.startX=e.pageX;this.startY=e.pageY;if(action===ACTION_CROP){this.cropping=true;this.$dragBox.addClass(CLASS_MODAL);}}},cropMove:function(event){var options=this.options;var originalEvent=event.originalEvent;var touches=originalEvent&&originalEvent.touches;var e=originalEvent||event;var action=this.action;var touchesLength;if(this.disabled){return;}if(touches){touchesLength=touches.length;if(touchesLength>1){if(options.zoomable&&options.touchDragZoom&&touchesLength===2){e=touches[1];this.endX2=e.pageX;this.endY2=e.pageY;}else{return;}}e=touches[0];}if(action){if(this.trigger(EVENT_CROP_MOVE,{originalEvent:originalEvent,action:action})){return;}event.preventDefault();this.endX=e.pageX;this.endY=e.pageY;this.change(e.shiftKey,action===ACTION_ZOOM?originalEvent:null);}},cropEnd:function(event){var originalEvent=event.originalEvent;var action=this.action;if(this.disabled){return;}if(action){event.preventDefault();if(this.cropping){this.cropping=false;this.$dragBox.toggleClass(CLASS_MODAL,this.cropped&&this.options.modal);}this.action='';this.trigger(EVENT_CROP_END,{originalEvent:originalEvent,action:action});}}});$.extend(prototype,{change:function(shiftKey,originalEvent){var options=this.options;var aspectRatio=options.aspectRatio;var action=this.action;var container=this.container;var canvas=this.canvas;var cropBox=this.cropBox;var width=cropBox.width;var height=cropBox.height;var left=cropBox.left;var top=cropBox.top;var right=left+width;var bottom=top+height;var minLeft=0;var minTop=0;var maxWidth=container.width;var maxHeight=container.height;var renderable=true;var offset;var range;if(!aspectRatio&&shiftKey){aspectRatio=width&&height?width/height:1;}if(options.strict){minLeft=cropBox.minLeft;minTop=cropBox.minTop;maxWidth=minLeft+min(container.width,canvas.width);maxHeight=minTop+min(container.height,canvas.height);}range={x:this.endX-this.startX,y:this.endY-this.startY};if(aspectRatio){range.X=range.y*aspectRatio;range.Y=range.x/aspectRatio;}switch(action){case ACTION_ALL:left+=range.x;top+=range.y;break;case ACTION_EAST:if(range.x>=0&&(right>=maxWidth||aspectRatio&&(top<=minTop||bottom>=maxHeight))){renderable=false;break;}width+=range.x;if(aspectRatio){height=width/aspectRatio;top-=range.Y/2;}if(width<0){action=ACTION_WEST;width=0;}break;case ACTION_NORTH:if(range.y<=0&&(top<=minTop||aspectRatio&&(left<=minLeft||right>=maxWidth))){renderable=false;break;}height-=range.y;top+=range.y;if(aspectRatio){width=height*aspectRatio;left+=range.X/2;}if(height<0){action=ACTION_SOUTH;height=0;}break;case ACTION_WEST:if(range.x<=0&&(left<=minLeft||aspectRatio&&(top<=minTop||bottom>=maxHeight))){renderable=false;break;}width-=range.x;left+=range.x;if(aspectRatio){height=width/aspectRatio;top+=range.Y/2;}if(width<0){action=ACTION_EAST;width=0;}break;case ACTION_SOUTH:if(range.y>=0&&(bottom>=maxHeight||aspectRatio&&(left<=minLeft||right>=maxWidth))){renderable=false;break;}height+=range.y;if(aspectRatio){width=height*aspectRatio;left-=range.X/2;}if(height<0){action=ACTION_NORTH;height=0;}break;case ACTION_NORTH_EAST:if(aspectRatio){if(range.y<=0&&(top<=minTop||right>=maxWidth)){renderable=false;break;}height-=range.y;top+=range.y;width=height*aspectRatio;}else{if(range.x>=0){if(right<maxWidth){width+=range.x;}else if(range.y<=0&&top<=minTop){renderable=false;}}else{width+=range.x;}if(range.y<=0){if(top>minTop){height-=range.y;top+=range.y;}}else{height-=range.y;top+=range.y;}}if(width<0&&height<0){action=ACTION_SOUTH_WEST;height=0;width=0;}else if(width<0){action=ACTION_NORTH_WEST;width=0;}else if(height<0){action=ACTION_SOUTH_EAST;height=0;}break;case ACTION_NORTH_WEST:if(aspectRatio){if(range.y<=0&&(top<=minTop||left<=minLeft)){renderable=false;break;}height-=range.y;top+=range.y;width=height*aspectRatio;left+=range.X;}else{if(range.x<=0){if(left>minLeft){width-=range.x;left+=range.x;}else if(range.y<=0&&top<=minTop){renderable=false;}}else{width-=range.x;left+=range.x;}if(range.y<=0){if(top>minTop){height-=range.y;top+=range.y;}}else{height-=range.y;top+=range.y;}}if(width<0&&height<0){action=ACTION_SOUTH_EAST;height=0;width=0;}else if(width<0){action=ACTION_NORTH_EAST;width=0;}else if(height<0){action=ACTION_SOUTH_WEST;height=0;}break;case ACTION_SOUTH_WEST:if(aspectRatio){if(range.x<=0&&(left<=minLeft||bottom>=maxHeight)){renderable=false;break;}width-=range.x;left+=range.x;height=width/aspectRatio;}else{if(range.x<=0){if(left>minLeft){width-=range.x;left+=range.x;}else if(range.y>=0&&bottom>=maxHeight){renderable=false;}}else{width-=range.x;left+=range.x;}if(range.y>=0){if(bottom<maxHeight){height+=range.y;}}else{height+=range.y;}}if(width<0&&height<0){action=ACTION_NORTH_EAST;height=0;width=0;}else if(width<0){action=ACTION_SOUTH_EAST;width=0;}else if(height<0){action=ACTION_NORTH_WEST;height=0;}break;case ACTION_SOUTH_EAST:if(aspectRatio){if(range.x>=0&&(right>=maxWidth||bottom>=maxHeight)){renderable=false;break;}width+=range.x;height=width/aspectRatio;}else{if(range.x>=0){if(right<maxWidth){width+=range.x;}else if(range.y>=0&&bottom>=maxHeight){renderable=false;}}else{width+=range.x;}if(range.y>=0){if(bottom<maxHeight){height+=range.y;}}else{height+=range.y;}}if(width<0&&height<0){action=ACTION_NORTH_WEST;height=0;width=0;}else if(width<0){action=ACTION_SOUTH_WEST;width=0;}else if(height<0){action=ACTION_NORTH_EAST;height=0;}break;case ACTION_MOVE:canvas.left+=range.x;canvas.top+=range.y;this.renderCanvas(true);renderable=false;break;case ACTION_ZOOM:this.zoom((function(x1,y1,x2,y2){var z1=sqrt(x1*x1+y1*y1);var z2=sqrt(x2*x2+y2*y2);return(z2-z1)/z1;})(abs(this.startX-this.startX2),abs(this.startY-this.startY2),abs(this.endX-this.endX2),abs(this.endY-this.endY2)),originalEvent);this.startX2=this.endX2;this.startY2=this.endY2;renderable=false;break;case ACTION_CROP:if(range.x&&range.y){offset=this.$cropper.offset();left=this.startX-offset.left;top=this.startY-offset.top;width=cropBox.minWidth;height=cropBox.minHeight;if(range.x>0){if(range.y>0){action=ACTION_SOUTH_EAST;}else{action=ACTION_NORTH_EAST;top-=height;}}else{if(range.y>0){action=ACTION_SOUTH_WEST;left-=width;}else{action=ACTION_NORTH_WEST;left-=width;top-=height;}}if(!this.cropped){this.cropped=true;this.$cropBox.removeClass(CLASS_HIDDEN);}}break;}if(renderable){cropBox.width=width;cropBox.height=height;cropBox.left=left;cropBox.top=top;this.action=action;this.renderCropBox();}this.startX=this.endX;this.startY=this.endY;}});$.extend(prototype,{crop:function(){if(!this.built||this.disabled){return;}if(!this.cropped){this.cropped=true;this.limitCropBox(true,true);if(this.options.modal){this.$dragBox.addClass(CLASS_MODAL);}this.$cropBox.removeClass(CLASS_HIDDEN);}this.setCropBoxData(this.initialCropBox);},reset:function(){if(!this.built||this.disabled){return;}this.image=$.extend({},this.initialImage);this.canvas=$.extend({},this.initialCanvas);this.cropBox=$.extend({},this.initialCropBox);this.renderCanvas();if(this.cropped){this.renderCropBox();}},clear:function(){if(!this.cropped||this.disabled){return;}$.extend(this.cropBox,{left:0,top:0,width:0,height:0});this.cropped=false;this.renderCropBox();this.limitCanvas();this.renderCanvas();this.$dragBox.removeClass(CLASS_MODAL);this.$cropBox.addClass(CLASS_HIDDEN);},replace:function(url){if(!this.disabled&&url){if(this.isImg){this.$element.attr('src',url);}this.options.data=null;this.load(url);}},enable:function(){if(this.built){this.disabled=false;this.$cropper.removeClass(CLASS_DISABLED);}},disable:function(){if(this.built){this.disabled=true;this.$cropper.addClass(CLASS_DISABLED);}},destroy:function(){var $this=this.$element;if(this.ready){if(this.isImg){$this.attr('src',this.originalUrl);}this.unbuild();$this.removeClass(CLASS_HIDDEN);}else if(this.$clone){this.$clone.remove();}$this.removeData(NAMESPACE);},move:function(offsetX,offsetY){var canvas=this.canvas;if(isUndefined(offsetY)){offsetY=offsetX;}offsetX=num(offsetX);offsetY=num(offsetY);if(this.built&&!this.disabled&&this.options.movable){canvas.left+=isNumber(offsetX)?offsetX:0;canvas.top+=isNumber(offsetY)?offsetY:0;this.renderCanvas(true);}},zoom:function(ratio,_originalEvent){var canvas=this.canvas;var width;var height;ratio=num(ratio);if(ratio&&this.built&&!this.disabled&&this.options.zoomable){if(this.trigger(EVENT_ZOOM,{originalEvent:_originalEvent,ratio:ratio})){return;}if(ratio<0){ratio=1/(1-ratio);}else{ratio=1+ratio;}width=canvas.width*ratio;height=canvas.height*ratio;canvas.left-=(width-canvas.width)/2;canvas.top-=(height-canvas.height)/2;canvas.width=width;canvas.height=height;this.renderCanvas(true);this.setDragMode(ACTION_MOVE);}},rotate:function(degree){var image=this.image;var rotate=image.rotate||0;degree=num(degree)||0;if(this.built&&!this.disabled&&this.options.rotatable){image.rotate=(rotate+degree)%360;this.rotated=true;this.renderCanvas(true);}},scale:function(scaleX,scaleY){var image=this.image;if(isUndefined(scaleY)){scaleY=scaleX;}scaleX=num(scaleX);scaleY=num(scaleY);if(this.built&&!this.disabled&&this.options.scalable){image.scaleX=isNumber(scaleX)?scaleX:1;image.scaleY=isNumber(scaleY)?scaleY:1;this.renderImage(true);}},getData:function(rounded){var options=this.options;var image=this.image;var canvas=this.canvas;var cropBox=this.cropBox;var ratio;var data;if(this.built&&this.cropped){data={x:cropBox.left-canvas.left,y:cropBox.top-canvas.top,width:cropBox.width,height:cropBox.height};ratio=image.width/image.naturalWidth;$.each(data,function(i,n){n=n/ratio;data[i]=rounded?Math.round(n):n;});}else{data={x:0,y:0,width:0,height:0};}if(options.rotatable){data.rotate=image.rotate||0;}if(options.scalable){data.scaleX=image.scaleX||1;data.scaleY=image.scaleY||1;}return data;},setData:function(data){var image=this.image;var canvas=this.canvas;var cropBoxData={};var ratio;if($.isFunction(data)){data=data.call(this.$element);}if(this.built&&!this.disabled&&$.isPlainObject(data)){if(isNumber(data.rotate)&&data.rotate!==image.rotate&&this.options.rotatable){image.rotate=data.rotate;this.rotated=true;this.renderCanvas(true);}ratio=image.width/image.naturalWidth;if(isNumber(data.x)){cropBoxData.left=data.x*ratio+canvas.left;}if(isNumber(data.y)){cropBoxData.top=data.y*ratio+canvas.top;}if(isNumber(data.width)){cropBoxData.width=data.width*ratio;}if(isNumber(data.height)){cropBoxData.height=data.height*ratio;}this.setCropBoxData(cropBoxData);}},getContainerData:function(){return this.built?this.container:{};},getImageData:function(){return this.ready?this.image:{};},getCanvasData:function(){var canvas=this.canvas;var data;if(this.built){data={left:canvas.left,top:canvas.top,width:canvas.width,height:canvas.height};}return data||{};},setCanvasData:function(data){var canvas=this.canvas;var aspectRatio=canvas.aspectRatio;if($.isFunction(data)){data=data.call(this.$element);}if(this.built&&!this.disabled&&$.isPlainObject(data)){if(isNumber(data.left)){canvas.left=data.left;}if(isNumber(data.top)){canvas.top=data.top;}if(isNumber(data.width)){canvas.width=data.width;canvas.height=data.width/aspectRatio;}else if(isNumber(data.height)){canvas.height=data.height;canvas.width=data.height*aspectRatio;}this.renderCanvas(true);}},getCropBoxData:function(){var cropBox=this.cropBox;var data;if(this.built&&this.cropped){data={left:cropBox.left,top:cropBox.top,width:cropBox.width,height:cropBox.height};}return data||{};},setCropBoxData:function(data){var cropBox=this.cropBox;var aspectRatio=this.options.aspectRatio;var widthChanged;var heightChanged;if($.isFunction(data)){data=data.call(this.$element);}if(this.built&&this.cropped&&!this.disabled&&$.isPlainObject(data)){if(isNumber(data.left)){cropBox.left=data.left;}if(isNumber(data.top)){cropBox.top=data.top;}if(isNumber(data.width)&&data.width!==cropBox.width){widthChanged=true;cropBox.width=data.width;}if(isNumber(data.height)&&data.height!==cropBox.height){heightChanged=true;cropBox.height=data.height;}if(aspectRatio){if(widthChanged){cropBox.height=cropBox.width/aspectRatio;}else if(heightChanged){cropBox.width=cropBox.height*aspectRatio;}}this.renderCropBox();}},getCroppedCanvas:function(options){var originalWidth;var originalHeight;var canvasWidth;var canvasHeight;var scaledWidth;var scaledHeight;var scaledRatio;var aspectRatio;var canvas;var context;var data;if(!this.built||!this.cropped||!SUPPORT_CANVAS){return;}if(!$.isPlainObject(options)){options={};}data=this.getData();originalWidth=data.width;originalHeight=data.height;aspectRatio=originalWidth/originalHeight;if($.isPlainObject(options)){scaledWidth=options.width;scaledHeight=options.height;if(scaledWidth){scaledHeight=scaledWidth/aspectRatio;scaledRatio=scaledWidth/originalWidth;}else if(scaledHeight){scaledWidth=scaledHeight*aspectRatio;scaledRatio=scaledHeight/originalHeight;}}canvasWidth=scaledWidth||originalWidth;canvasHeight=scaledHeight||originalHeight;canvas=$('<canvas>')[0];canvas.width=canvasWidth;canvas.height=canvasHeight;context=canvas.getContext('2d');if(options.fillColor){context.fillStyle=options.fillColor;context.fillRect(0,0,canvasWidth,canvasHeight);}context.drawImage.apply(context,(function(){var source=getSourceCanvas(this.$clone[0],this.image);var sourceWidth=source.width;var sourceHeight=source.height;var args=[source];var srcX=data.x;var srcY=data.y;var srcWidth;var srcHeight;var dstX;var dstY;var dstWidth;var dstHeight;if(srcX<=-originalWidth||srcX>sourceWidth){srcX=srcWidth=dstX=dstWidth=0;}else if(srcX<=0){dstX=-srcX;srcX=0;srcWidth=dstWidth=min(sourceWidth,originalWidth+srcX);}else if(srcX<=sourceWidth){dstX=0;srcWidth=dstWidth=min(originalWidth,sourceWidth-srcX);}if(srcWidth<=0||srcY<=-originalHeight||srcY>sourceHeight){srcY=srcHeight=dstY=dstHeight=0;}else if(srcY<=0){dstY=-srcY;srcY=0;srcHeight=dstHeight=min(sourceHeight,originalHeight+srcY);}else if(srcY<=sourceHeight){dstY=0;srcHeight=dstHeight=min(originalHeight,sourceHeight-srcY);}args.push(srcX,srcY,srcWidth,srcHeight);if(scaledRatio){dstX*=scaledRatio;dstY*=scaledRatio;dstWidth*=scaledRatio;dstHeight*=scaledRatio;}if(dstWidth>0&&dstHeight>0){args.push(dstX,dstY,dstWidth,dstHeight);}return args;}).call(this));return canvas;},setAspectRatio:function(aspectRatio){var options=this.options;if(!this.disabled&&!isUndefined(aspectRatio)){options.aspectRatio=num(aspectRatio)||NaN;if(this.built){this.initCropBox();if(this.cropped){this.renderCropBox();}}}},setDragMode:function(mode){var options=this.options;var croppable;var movable;if(this.ready&&!this.disabled){croppable=options.dragCrop&&mode===ACTION_CROP;movable=options.movable&&mode===ACTION_MOVE;mode=(croppable||movable)?mode:ACTION_NONE;this.$dragBox.data('action',mode).toggleClass(CLASS_CROP,croppable).toggleClass(CLASS_MOVE,movable);if(!options.cropBoxMovable){this.$face.data('action',mode).toggleClass(CLASS_CROP,croppable).toggleClass(CLASS_MOVE,movable);}}}});$.extend(Cropper.prototype,prototype);Cropper.DEFAULTS={aspectRatio:NaN,data:null,preview:'',strict:true,responsive:true,checkImageOrigin:true,modal:true,guides:true,center:true,highlight:true,background:true,autoCrop:true,autoCropArea:0.8,dragCrop:true,movable:true,rotatable:true,scalable:true,zoomable:true,mouseWheelZoom:false,wheelZoomRatio:0.1,touchDragZoom:true,cropBoxMovable:true,cropBoxResizable:true,doubleClickToggle:true,minCanvasWidth:0,minCanvasHeight:0,minCropBoxWidth:0,minCropBoxHeight:0,minContainerWidth:200,minContainerHeight:100,build:null,built:null,cropstart:null,cropmove:null,cropend:null,crop:null,zoom:null};Cropper.setDefaults=function(options){$.extend(Cropper.DEFAULTS,options);};Cropper.TEMPLATE=('<div class="cropper-container">'+'<div class="cropper-canvas"></div>'+'<div class="cropper-drag-box"></div>'+'<div class="cropper-crop-box">'+'<span class="cropper-view-box"></span>'+'<span class="cropper-dashed dashed-h"></span>'+'<span class="cropper-dashed dashed-v"></span>'+'<span class="cropper-center"></span>'+'<span class="cropper-face"></span>'+'<span class="cropper-line line-e" data-action="e"></span>'+'<span class="cropper-line line-n" data-action="n"></span>'+'<span class="cropper-line line-w" data-action="w"></span>'+'<span class="cropper-line line-s" data-action="s"></span>'+'<span class="cropper-point point-e" data-action="e"></span>'+'<span class="cropper-point point-n" data-action="n"></span>'+'<span class="cropper-point point-w" data-action="w"></span>'+'<span class="cropper-point point-s" data-action="s"></span>'+'<span class="cropper-point point-ne" data-action="ne"></span>'+'<span class="cropper-point point-nw" data-action="nw"></span>'+'<span class="cropper-point point-sw" data-action="sw"></span>'+'<span class="cropper-point point-se" data-action="se"></span>'+'</div>'+'</div>');Cropper.other=$.fn.cropper;$.fn.cropper=function(options){var args=toArray(arguments,1);var result;this.each(function(){var $this=$(this);var data=$this.data(NAMESPACE);var fn;if(!data){if(/destroy/.test(options)){return;}$this.data(NAMESPACE,(data=new Cropper(this,options)));}if(typeof options==='string'&&$.isFunction(fn=data[options])){result=fn.apply(data,args);}});return isUndefined(result)?this:result;};$.fn.cropper.Constructor=Cropper;$.fn.cropper.setDefaults=Cropper.setDefaults;$.fn.cropper.noConflict=function(){$.fn.cropper=Cropper.other;return this;};});