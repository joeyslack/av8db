(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as anonymous module.
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node / CommonJS
    factory(require('jquery'));
  } else {
    // Browser globals.
    factory(jQuery);
  }
})(function ($) {

  'use strict';

  var console = window.console || { log: function () {} };

  function CropAvatar($element) {
    this.$container2 = $element;

    this.$avatarView = this.$container2.find('.avatar-view');
    this.$avatar = this.$avatarView.find('img');
   // this.$avatarModal = this.$container2.find('#avatar-modal');
    this.$loading = this.$container2.find('.loading');

    this.$avatarForm = this.$container2.find('.avatar-form');
    this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
    this.$avatarSrc = this.$avatarForm.find('.avatar-src');
    this.$avatarData = this.$avatarForm.find('.avatar-data');
    this.$avatarInput = this.$avatarForm.find('.avatar-input');
    this.$avatarSave = this.$avatarForm.find('.avatar-save');
    this.$avatarBtns = this.$avatarForm.find('.avatar-btns');



    /** new dev **/
    this.$zoominBTN = this.$avatarForm.find('#zoomin');
    this.$zoomoutBTN = this.$avatarForm.find('#zoomout');

    this.$rotateleftBTN = this.$avatarForm.find('#rotateleft');
    this.$rotaterightBTN = this.$avatarForm.find('#rotateright');

    this.$flipxBTN = this.$avatarForm.find('#flipx');
    this.$flipyBTN = this.$avatarForm.find('#flipy');
    /** new dev **/

    

    this.$avatarWrapper = this.$container2.find('.avatar-wrapper');
    this.$avatarPreview = this.$container2.find('.avatar-preview');

    this.init();
  }

  CropAvatar.prototype = {
    constructor: CropAvatar,

    support: {
      fileList: !!$('<input type="file">').prop('files'),
      blobURLs: !!window.URL && URL.createObjectURL,
      formData: !!window.FormData
    },

    init: function () {
      this.support.datauri = this.support.fileList && this.support.blobURLs;

      if (!this.support.formData) {
        this.initIframe();
      }

      this.initTooltip();
     // this.initModal();
      this.addListener();
    },

    addListener: function () {
      this.$avatarView.on('click', $.proxy(this.click, this));
      this.$avatarInput.on('change', $.proxy(this.change, this));
      this.$avatarForm.on('submit', $.proxy(this.submit, this));
      this.$avatarBtns.on('click', $.proxy(this.rotate, this));

      /** new dev **/
      this.$zoominBTN.on('click', $.proxy(this.onZoomIn, this));
      this.$zoomoutBTN.on('click', $.proxy(this.onZoomOut, this));

      //this.$rotateleftBTN.on('click', $.proxy(this.rotateLeft, this));
      //this.$rotaterightBTN.on('click', $.proxy(this.rotateRight, this));

      this.$flipxBTN.on('click', $.proxy(this.flipxs, this));
      this.$flipyBTN.on('click', $.proxy(this.flipys, this));
       /** new dev **/      
    },

    initTooltip: function () {
      this.$avatarView.tooltip({
        placement: 'bottom'
      });
    },

   /* initModal: function () {
      this.$avatarModal.modal({
        show: false
      });
    },*/

    initPreview: function () {
      var url = this.$avatar.attr('src');

      this.$avatarPreview.html('<img src="' + url + '">');
    },

    initIframe: function () {
      var target = 'upload-iframe-' + (new Date()).getTime();
      var $iframe = $('<iframe>').attr({
            name: target,
            src: ''
          });
      var _this = this;

      // Ready ifrmae
      $iframe.one('load', function () {

        // respond response
        $iframe.on('load', function () {
          var data;

          try {
            data = $(this).contents().find('body').text();
          } catch (e) {
            console.log(e.message);
          }

          if (data) {
            try {
              data = $.parseJSON(data);
            } catch (e) {
              console.log(e.message);
            }

            _this.submitDone(data);
          } else {
            _this.submitFail('Image upload failed!');
          }

          _this.submitEnd();

        });
      });

      this.$iframe = $iframe;
      this.$avatarForm.attr('target', target).after($iframe.hide());
    },

    click: function () {
      this.$avatarModal.modal('show');
      this.initPreview();
    },

    change: function () {
      var files;
      var file;

      if (this.support.datauri) {
        files = this.$avatarInput.prop('files');

        if (files.length > 0) {
          file = files[0];

          if (this.isImageFile(file)) {
            if (this.url) {
              URL.revokeObjectURL(this.url); // Revoke the old one
            }

            this.url = URL.createObjectURL(file);
            this.startCropper();
          }
        }
      } else {
        file = this.$avatarInput.val();

        if (this.isImageFile(file)) {
          this.syncUpload();
        }
      }
    },

    submit: function () {
      if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
        return false;
      }

      if (this.support.formData) {
        this.ajaxUpload();
        return false;
      }
    },

    rotate: function (e) {
      var data;

      if (this.active) {
        data = $(e.target).data();

        if (data.method) {
          this.$img.cropper(data.method, data.option);
        }
      }
    },


    onZoomIn: function(e){
        this.$img.cropper('zoom', 0.1);
    },
    onZoomOut: function(e){
        this.$img.cropper('zoom', '-0.1');
    },
    rotateLeft: function(e){
        this.$img.cropper("rotate", -45);
    },
    rotateRight: function(e){
        this.$img.cropper("rotate", 45);
    },
    flipxs: function(e){
        this.$img.cropper("scale", -1, 1);
    },
    flipys: function(e){
        this.$img.cropper("scale", 1, -1);
    },


    isImageFile: function (file) {
      if (file.type) {
        return /^image\/\w+$/.test(file.type);
      } else {
        return /\.(jpg|jpeg|png|gif)$/.test(file);
      }
    },

    startCropper: function () {
      var _this = this;
      
      var aspect=0;
      var which_type=$("#hidden_image_id").html();

      if(which_type=='images' || which_type=='site_logo' || which_type=='favicon' || which_type=='change_profile'){
        aspect=1;

      }else if(which_type=='header_slider'){

        aspect=3.14;

      }else if(which_type=='advertise_slider'){

        aspect=13.65;

      } else if(which_type=='slider'){
        aspect=4.65;
      } else if(which_type=='company'){
        aspect=2.41;
      } else if(which_type=='activity_image'){
        aspect=1.52;
      } else if(which_type=='slider_home'){
        aspect=2.18;
      }

      aspect = 1;
        //this.$img.cropper('replace', this.url);

        this.$img = $('<img src="' + this.url + '">');
        this.$avatarWrapper.empty().html(this.$img);
        this.$img.cropper({
          aspectRatio:aspect,
          preview: this.$avatarPreview.selector,
          strict: false,
          movable:false,
          cropBoxResizable:false,
          zoomable:true,
          minCropBoxWidth: 260,
          minCropBoxHeight: 168,
          autoCropArea: 0.5,
          dragCrop: false,
          scalable: true,
          rotatable: true,
          crop: function (e) {
                var json = [
                  '{"x":' + Math.round(e.x),
                  '"y":' + Math.round(e.y),
                  '"height":' + Math.round(e.height),
                  '"width":' + Math.round(e.width),
                  '"scaleX":' + Math.round(e.scaleX),
                  '"scaleY":' + Math.round(e.scaleY),
                  '"rotate":' + e.rotate + '}'
                ].join();

            _this.$avatarData.val(json);
          }
        });

        //this.active = true;


     /* this.$avatarModal.one('hidden.bs.modal', function () {
        _this.$avatarPreview.empty();
        _this.stopCropper();
      });*/
    },

    stopCropper: function () {
      if (this.active) {
        this.$img.cropper('destroy');
        this.$img.remove();
        this.active = false;
      }
    },

    ajaxUpload: function () {
      var url = this.$avatarForm.attr('action');
      var data = new FormData(this.$avatarForm[0]);
      var _this = this;
      
      $.ajax(url, {
        type: 'post',
        data: data,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function () {
          _this.submitStart();
        },

        success: function (data) {
          _this.submitDone(data);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
          _this.submitFail(textStatus || errorThrown);
        },

        complete: function () {
          _this.submitEnd();
        }
      });
    },

    syncUpload: function () {
      this.$avatarSave.click();
    },

    submitStart: function () {
      this.$loading.fadeIn();
    },

    submitDone: function (data) {
      console.log(data);
      return false;
      if ($.isPlainObject(data) && data.state === 200) {
        if (data.result) {
          this.url = data.result;

          if (this.support.datauri || this.uploaded) {
            this.uploaded = false;
            this.cropDone();
          } else {
            this.uploaded = true;
            this.$avatarSrc.val(this.url);
            this.startCropper();
          }

          this.$avatarInput.val('');
        } else if (data.message) {
          this.alert(data.message);
        }
      } else {
        this.alert('Failed to response');
      }
    },

    submitFail: function (msg) {
      this.alert(msg);
    },

    submitEnd: function () {
      this.$loading.fadeOut();
    },

    cropDone: function () {
      this.$avatarForm.get(0).reset();
      this.$avatar.attr('src', this.url);
      this.stopCropper();
      this.$avatarModal.modal('hide');
    },

    alert: function (msg) {
      var $alert = [
            '<div class="alert alert-danger avatar-alert alert-dismissable">',
              '<button type="button" class="close" data-dismiss="alert">&times;</button>',
              msg,
            '</div>'
          ].join('');

      this.$avatarUpload.after($alert);
    }
  };

  $(function () {
    // console.log("88888888");
    // $("#rotateleft").on('click',function(){
    //     var newCropperIMG = new CropAvatar($('#crop-avatar')); 
    //     console.log("33333333");       
    //     newCropperIMG.onZoomIn();
    //     console.log("44444444");
    // });

    return new CropAvatar($('#crop-avatar'));
  });



});