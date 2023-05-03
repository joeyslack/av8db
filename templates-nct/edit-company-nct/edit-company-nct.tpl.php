
<script type="text/javascript"  src="//maps.googleapis.com/maps/api/js?v=3.28&sensor=false&libraries=places&language=en&key={GOOGLE_MAPS_API_KEY}"></script>
<div class="inner-main">
    <div class="edit-company-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <form action="%EDIT_COMPANY_FORM_ACTION_URL%" method="post" name="edit_company_form" id="edit_company_form">
                        <div class="edt-comp-info">
                            <div class="gen-wht-bx cf">
                                <h1>{LBL_FORM_EDIT_COM_EDIT_COMPANY_PAGE}</h1>
                                <div class="back-btn-bx text-right">
                                    <a href="javascript:void(0);" id="deleteCompany" class="trash-ico" title="{BTN_EDIT_COMP_DELETE_COMPANY_PAGE}">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                    <a href="<?php echo SITE_URL; ?>company/my-companies" class="back-ico" title="{BTN_EDIT_COMP_BACK_TO_LISTING}"> <i class="icon-back-arrow"></i></a>
                                </div>

                                <div class="company-edit-view">
                                    <div class="company-edt-pic">
                                        <div id="select_logo_container" class="blank-logo-bx %LOGO_SELECT_CONTAINER_HIDDEN_CLASS%">
                                            <div id="logo_browse_btn_container" class="browse-btn">
                                                <span class="upload-btn btn-file">
                                                    <i class="fa fa-plus plus-upload"></i>
                                                    <input type="file" id="company_logo" class="places_image" name="company_logo" />
                                                    <input type="hidden" name="is_logo_removed" id="is_logo_removed" value="false" />
                                                </span>
                                            </div>
                                            <span class="img-note">{LBL_FORM_EDIT_COMP_LOGO_PIXELS}</span>
                                        </div>
                                        <div id="logo_preview_container" class="logo-preview-contianer %LOGO_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                                            <!-- <picture>
                                                <source srcset="%COMPANY_LOGO_URL_WEBP%" type="image/webp">
                                                <source srcset="%COMPANY_LOGO_URL%" type="image/jpg">
                                                <img src="%COMPANY_LOGO_URL%" class="" alt="%COMPANY_NAME%" id="company_logo_img" /> 
                                            </picture> -->

                                            <img id="company_logo_img" src="%COMPANY_LOGO_URL%" alt="%COMPANY_NAME%" />
                                            <div class="company_logo_actions">
                                                <a href="javascript:void(0);" title="{CHANGE}" id="change_company_logo">
                                                    <i class="icon-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" title="{LBL_REMOVE}" id="remove_company_logo">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="edt-dtl-company">
                                        <!-- <h3><a href="%COMPANY_DETAIL_PAGE_URL%" class="blue-color" title="%COMPANY_NAME%" target="_blank">%COMPANY_NAME%</a></h3>
                                        <h5 class="gray-text">%INDUSTRY_NAME%</h5>
                                        <div class="addr-bx"><i class="icon-email"></i>%OWNER_EMAIL_ADDRESS%</div>
                                        <div class="emp-bx">{LBL_FORM_EDIT_COMP_EMPLOYEES} <small>%RANGE_OF_NO_OF_EMPLOYEES% </small></div> -->
                                        <div class="form-group">
                                            <label>{LBL_COMPANY_NAME}</label>
                                            <input type="text" class="form-control border-field mrt5" placeholder="{LBL_COMPANY_NAME}" name="company_name" id="company_name" value="%COMPANY_NAME%" />
                                        </div>
                                        <div class="form-group">
                                            <label>{LBL_INDUSTRY}</label>
                                            <select name="company_industry_id" id="company_industry_id" class="form-control selectpicker show-tick">
                                                <option value="">{LBL_FORM_COMPANY_COMPANY_INDUSTRY}*</option>
                                                %COMPANY_INDUSTRY_OPTIONS%
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{LBL_LOGIN_EMAIL_ADDRESS}</label>
                                            <input type="email" class="form-control border-field mrt5" placeholder="{LBL_LOGIN_EMAIL_ADDRESS}" name="owner_email_address" id="owner_email" value="%OWNER_EMAIL_ADDRESS%" />
                                        </div>
                                       <!--  <div class="form-group">
                                            <label>{LBL_COMPANY_SIZE}</label>
                                            <select name="company_size_id" id="company_size_id" class="form-control selectpicker show-tick">
                                                <option value="">{LBL_FORM_COMPANY_COMPANY_SIZE}*</option>
                                                %COMPANY_SIZE_OPTIONS%
                                            </select>
                                        </div> -->
                                    </div>
                               </div>
                            </div>
                        </div>
                        <div class="gen-wht-bx cf">
                            <div class="company-banner-outer">
                                <h6>{LBL_FORM_EDIT_COMP_BANNER_IMAGE}</h6>
                                <div id="select_banner_image_container" class="banner-preview-outer %BANNER_IMAGE_SELECT_CONTAINER_HIDDEN_CLASS%">
                                    <div class="banner-inner-tbl">
                                        <div class="upload-img-file btn-file">
                                            <i class="fa fa-plus plus-upload"></i>
                                            <input type="file" class="slider_places_image" id="banner_image" name="banner_image" />
                                            <input type="hidden" name="is_banner_removed" id="is_banner_removed" value="false" />
                                            <span class="img-note">{LBL_FORM_EDIT_COMP_BANER_PIXELS}</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="banner_image_preview_container" class="banner-preview-contianer %BANNER_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                                    <img id="banner_image_img" src="%COMPANY_BANNER_IMAGE_URL%" alt="%COMPANY_NAME%" />
                                    <div class="banner_actions">
                                        <a href="javascript:void(0);" title="{CHANGE}" id="change_banner_image">
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" title="{LBL_REMOVE}" id="remove_banner_image">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gen-wht-bx cf">
                            <div class="company-banner-outer">
                                <div class="col-sm-12 col-md-12 form-group">
                                    <label>Location*</label>
                                    <input type="text" name="location" id="location" value="%LOCATION%" />
                                </div>
                                <div class="col-sm-12 col-md-12 form-group">
                                    <div id="select_banner_image_container" class="banner-preview-outer">
                                        <div id="map_canvas" style="width: 100%; height: 300px;"></div>
                                        <input type="hidden" name="lat" id="lat" value="%LAT%">
                                        <input type="hidden" name="lng" id="lng" value="%LNG%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gen-wht-bx cf">
                            <div class="other-form-list">
                                <div class="list-form cf">
                                    <div class="col-sm-12 col-md-6 form-group">
                                        <label>{LBL_FORM_EDIT_COMP_COMPANY_WEBSITE_URL}*</label>
                                        <input type="text" name="website_of_company" id="website_of_company" value="%WEBSITE_OF_COMPANY%" />
                                    </div>
                                    <div class="col-sm-12 col-md-6 form-group">
                                        <label>{LBL_FORM_EDIT_COMP_YEAR_FOUND}</label>
                                        <input type="text" class="date-picker" name="foundation_year" id="foundation_year" value="%FOUNDATION_YEAR%" />
                                    </div>
                                </div>
                                <div class="list-form cf">
                                    <div class="col-sm-12 col-md-6 form-group">
                                        <div class="company-admin">
                                        <label>{LBL_FORM_EDIT_COMP_COMPANY_LOCATIONS} <sup>*</sup> </label>
                                            <input type="text" placeholder="{LBL_FORM_EDIT_COMP_ENTER_LOCATION}" id="company_locations" name="company_locations" class="autocomplete" />
                                            <small>{LBL_FORM_EDIT_COMP_ADD_UP_TO_DIFFERENT_LOCATIONS}</small>
                                            <input type="hidden" name="formatted_address" id="formatted_address" value="%FORMATTED_ADDRESS%" /><div class="list-form cf">
                                            <ul id="company_locations_container" class="diffent-loc-bx">%COMPANY_LOCATIONS%</ul>
                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 form-group">
                                        <label>{LBL_FORM_EDIT_COMP_COMPANY_PAGES_ADMINS}</label>
                                            <input type="text" placeholder="{LBL_FORM_EDIT_COMP_START_TYPING_NAME}" name="company_admin" id="company_admin" /><small>{LBL_FORM_EDIT_COMP_YOU_MUST_BE_CONNECTED_TO_A_MEMBER_TO_INCLUDE_AN_ADMIN}</small>
                                            <ul id="company_admins_container" class="admin-row %COMPANY_ADMINS_UL_HIDDEN%">%COMPANY_ADMINS%</ul>
                                    </div>
                                </div>
                                <div class="list-form cf">
                                    <div class="col-sm-12 col-md-12 form-group">
                                        <label>{LBL_FORM_EDIT_COMP_COMPANY_DESCRIPTION}*</label>
                                        <small>(<span id="company_description_character_count"></span> {LBL_FORM_EDIT_COMP_OUT_OF_CHARACHTER})</small>
                                        <textarea placeholder="{LBL_FORM_EDIT_COMP_COMPANY_DESCRIPTION_PLACEHOLDER}" rows="4" maxlength="2000" name="company_description" id="company_description">%COMPANY_DESCRIPTION%</textarea>

                                    </div>
                                </div>
                                <div class="list-form cf">
                                    <div class="col-sm-12 col-md-12 form-group">
                                    <button type="submit" class="blue-btn" name="update_company_details" id="update_company_details">{LBL_SUBMIT}</button>
                                    <a href="<?php echo SITE_URL; ?>company/my-companies">
                                    <button type="button" class="outer-red-btn" name="send_message" id="send_message">{LBL_CANCEL}</button></a>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </form>

                </div>
                <div class="col-sm-12 col-md-1"></div>
            </div>
        </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-8 clearfix"></div>
                    <div class="col-sm-4 clearfix">

                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="right-part-main">
                    <!-- <span class="img-note text-right mrt25">*{LBL_FORM_EDIT_COMP_INDICATED_REQUIRED_FIELD}</span> -->
                </div>
                 <div class="clearfix"></div>
                 <div class="text-right button-btm col-sm-12 fade fadeIn">

                </div>
            </div>
        </form>
    </div>
</div>

<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>


<script type="text/javascript">
var geocoder;

google.maps.event.addDomListener(window, 'load', function ()
{
  var places = new google.maps.places.Autocomplete(document.getElementById('location'));
  
  google.maps.event.addListener(places, 'place_changed', function () 
  {
    console.log(places.getPlace());
    var getaddress    = places.getPlace();        
    var whole_address = getaddress.address_components;   
    
    console.log(whole_address);
    var lat = getaddress.geometry.location.lat(),lng = getaddress.geometry.location.lng();
    $('#lat').val(lat);
    $('#lng').val(lng);

    mapPinPoint(lat,lng,8);
  });
});

function mapPinPoint(lat="",lng="",zoom_no=4){
    geocoder = new google.maps.Geocoder();
    var myLatlng = { lat: lat, lng: lng };
    var map = new google.maps.Map(document.getElementById("map_canvas"), {
      zoom: zoom_no,
      center: myLatlng,
    });

    var marker = new google.maps.Marker({
        draggable: true,
        position: myLatlng,
        title: 'Selected Location',
        map: map
   });

    var mapZoom=map.getZoom();
    $("#zoom_level").val(mapZoom);

    google.maps.event.addListener(map, 'zoom_changed', function(ee) {
      mapZoom=map.getZoom();
      $("#zoom_level").val(mapZoom);
    });

    google.maps.event.addListener(marker, 'dragend', function (event) {
        document.getElementById("lat").value = this.getPosition().lat();
        document.getElementById("lng").value = this.getPosition().lng();
        geocodePosition(marker.getPosition());
    });
}
function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      document.getElementById("location").value=(responses[0].formatted_address);
    } else {
      alert('Cannot determine address at this location.');
    }
  });
}

var lat=$("#lat").val();
var lng=$("#lng").val();
if(lat!="" && lng!=""){
  mapPinPoint(parseFloat(lat),parseFloat(lng));
}


   /* $(document).on('change',"#company_locations",function(){
        alert(1);
    });*/
    var autocomplete;    
    var IsplaceChange = true;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('company_locations')),{types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            var company_admin_ids = $("input[name='company_admin_ids[]']").map(function(){return $(this).val();}).get();
            $.ajax({
                url: "<?php echo SITE_URL; ?>getConnections",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getConnections',
                    user_name: request.term,
                    company_admin_ids: company_admin_ids
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.user_name, value: item.user_name, id: item.user_id};
                    }));
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
            });
        },
        select: function (event, c) {
            //console.log(c.item.id);
            var company_admin_ids = $("input[name='company_admin_ids[]']").map(function(){return $(this).val();}).get();
            user_id = c.item.id;
            $.ajax({
                url: "<?php echo SITE_URL; ?>getConnectionBox",
                type: "POST",
                dataType: "json",
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                data: 'action=getConnectionBox&user_id=' + user_id+'&company_admin_ids='+company_admin_ids,
                success: function (data) {
                    if(data.status) {
                        $("#company_admin").val("");
                        $("#company_admins_container").removeClass("hidden");
                        $("#company_admins_container").append(data.content);
                    } else {
                        toastr["error"](data.error);
                    }
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
            });
        },
        autoFocus: true
    };
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            //toastr['error']("{ERROR_EDIT_COMP_AUTOCOMPLETE_RETURNED_PLACE_NO_GEOMETRY}");
            //window.alert("{ERROR_EDIT_COMP_AUTOCOMPLETE_RETURNED_PLACE_NO_GEOMETRY}");
           //return;
        } else {
            address1 = address2 = city1 = city2 = state = country = postal_code = '';
            formatted_address = place.formatted_address;
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();
            var arrAddress = place.address_components;
            proceed_to_add_location = true;
            $(".map-box").each(function() {
                var added_latitude = parseFloat($(this).find(".latitude").val()).toFixed(2);
                var added_longitude = parseFloat($(this).find(".longitude").val()).toFixed(2);
                if(added_latitude == parseFloat(latitude).toFixed(2) && added_longitude == parseFloat(longitude).toFixed(2) ) {
                    proceed_to_add_location = false;
                    toastr['error']("{ERROR_EDIT_COMP_YOU_ALREADY_ADDED_LOCATION}");
                    return false;
                }
            });
            if(!proceed_to_add_location) {
                $("#company_locations").val();
                return true;
            }
            $.each(arrAddress, function(i, address_component) {
                if (address_component.types[0] == "route") {address1 = address_component.long_name;}
                if (address_component.types[0] == "sublocality") {address2 = address_component.long_name;}
                if (address_component.types[0] == "locality") {city1 = address_component.long_name;}
                if (address_component.types[0] == "administrative_area_level_2") {city2 = address_component.long_name;}
                if (address_component.types[0] == "administrative_area_level_1") {state = address_component.long_name;}
                if (address_component.types[0] == "country") {country = address_component.long_name;}
                if (address_component.types[0] == "postal_code") {postal_code = address_component.long_name;}
            });
            $("#formatted_address").val(formatted_address);
            IsplaceChange = true;

            no_of_locations = $(".map-box").length;
            if(no_of_locations > 0) {
                is_hq = "n";
            } else {
                is_hq = "y";
            }

            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>addCompanyLocation",
                data: {
                    action: 'addCompanyLocation',
                    is_hq: is_hq,
                    company_id: '%ENCRYPTED_COMPANY_ID%',
                    formatted_address: formatted_address,
                    address1: address1,
                    address2: address2,
                    country: country,
                    state: state,
                    city1: city1,
                    city2: city2,
                    postal_code: postal_code,
                    latitude: latitude,
                    longitude: longitude,
                    place: JSON.stringify( place )
                },
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $("#company_locations").val('').focus();
                        $("#company_locations_container").append(data.content);
                        initializeTootltip();
                        no_of_locations = $(".map-box").length;
                        if(no_of_locations == 5 || no_of_locations > 5) {
                            //$("#company_locations").parents(".form-group").fadeOut(1500);
                            $("#company_locations").hide();
                        }
                    } else {
                        toastr['error'](data.error);
                    }
                }
            });
        }
    }
    $(document).on("click", ".make-hq", function() {
        $(".map-box").each(function() {
            $(this).find(".hq_anchor").removeClass("is-hq").addClass("make-hq");
            $(this).find(".is_hq_hidden").val('n');
        });
        $(this).removeClass("make-hq").addClass("is-hq");
        $(this).parents(".map-box").find(".is_hq_hidden").val('y');
    });
    $(document).on("click", ".remove-company-location", function() {
        var map_box = $(this).parents(".map-box");
        no_of_locations = $(".map-box").length;
        if(no_of_locations == 1){
            bootbox.alert({
                title: '{LBL_ALERT}',
                message: '{ERROR_MINIMUM_LOCATION}',
                reorder: true,
                buttons:{ok:{label:'{LBL_OK}',className:'outer-blue-btn '}},
            });
            return false;
        }
        var bootBoxCallback = function(result) {
            if(result) {
                is_hq = map_box.find(".is_hq_hidden").val();
                map_box.fadeOut(800, function() {
                    map_box.remove();
                });


                if('y' == is_hq && no_of_locations > 1) {
                    first_map_box = $(".map-box").first();
                    first_map_box.find(".hq_anchor").removeClass("make-hq").addClass("is-hq");
                    first_map_box.find(".is_hq_hidden").val('y');
                }
                if(no_of_locations <= 5) {
                    $("#company_locations").fadeIn(1500);
                }
            }
        }
        initBootBox("{ALERT_DELETE_COMPANY_LOCATION}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_LOCATION}", bootBoxCallback);
    });
    /*************************************************************************************/
    $(document).on("click", "#change_company_logo", function() {
        $("#company_logo").click();
    });
    $(document).on("click", "#remove_company_logo", function() {
        var bootBoxCallback = function(result) {
            if(result) {
                $("#is_logo_removed").val(true);
                $("#select_logo_container").removeClass("hidden");
                $("#company_logo_img").attr("src", "");
                $("#logo_preview_container").addClass("hidden");
            }
        }
        initBootBox("{LBL_REMOVE_COMPANY_LOGO}", "{ALERT_ARE_YOU_SURE_WANT_TO_REMOVE_THIS_COMPANY_LOGO}", bootBoxCallback);
    });
    $(document).on("change", "#company_logo", function(e) {
        var file = this.files[0];
        showCompanyLogo(file);
    });
    function showCompanyLogo(file) {
        readFile(file, function(e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function() {
                // access image size here
                width = this.width;
                height = this.height;
                aspectRatio = width / height;
                if (aspectRatio == 1 && width >= 300 && height >= 300) {
                    $("#select_logo_container").addClass("hidden");
                    $("#company_logo_img").attr("src", this.src);
                    $("#logo_preview_container").removeClass("hidden");
                } else {
                    $("#company_logo").val("");
                    toastr["error"]("{ALERT_PLEASE_UPLOAD_COMPANY_BANER_OF_RATIO}");
                }
            };
        });
    }
    /*************************************************************************************/
    $(document).on("click", "#change_banner_image", function() {
        $("#banner_image").click();
    });
    $(document).on("click", "#remove_banner_image", function() {
        var bootBoxCallback = function(result) {
            if(result) {
                $("#is_banner_removed").val(true);
                $("#select_banner_image_container").removeClass("hidden");
                $("#banner_image_img").attr("src", "");
                $("#banner_image_preview_container").addClass("hidden");
            }
        }
        initBootBox("{ALERT_REMOVE_COMPANY_BANER_IMAGE}", "{ALERT_ARE_YOU_SURE_WANT_TO_REMOVE_BANER_IMAGE_COMPANY}", bootBoxCallback);
    });
    $(document).on("change", "#banner_image", function(e) {
        var file = this.files[0];
        showBannerImage(file);
    });
    $(document).on("change","#avatarInput",function(e){
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
            var url = (typeof FileReader == "undefined") ? webkitURL.createObjectURL(e.target.files[0]) : URL.createObjectURL(e.target.files[0]);
            /*$('.avatar-wrapper').empty().html('<img src="' + url + '">');
            $('#avatar-modal').modal('show');*/
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $(".loading").hide();
            $("#banner_image").val("");


        }
    });
    /*************************************************************************************/
    function showBannerImage(file) {
        readFile(file, function(e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function() {
                // access image size here
                width = this.width;
                height = this.height;
                aspectRatio = width / height;
                if (aspectRatio == 3) {
                    $("#select_banner_image_container").addClass("hidden");
                    $("#banner_image_img").attr("src", this.src);
                    $("#banner_image_preview_container").removeClass("hidden");
                } else {
                    $("#banner_image").val("");
                    toastr["error"]("{ALERT_UPLOAD_BANER_ASPECT_RATIO_EDIT_COMPANY}");
                }
            };

        });
    }
    function countCharacters() {
        var maxLength = 2000;
        var length = $("#company_description").val().length;
        //var length = maxLength-length;
        $("#company_description_character_count").text(length);
    }
    $(document).ready(function() {
      $("#company_locations").keydown(function () {
            IsplaceChange = false;
        });

      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    });
    $(document).on("keyup", "#company_description", function() {
        countCharacters();
    });
    $.validator.addMethod("companyNm", function(value, element) {
       // return this.optional(element) || /[a-z]+[0-9]*$/i.test(value);
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);

    }, "{PLEASE_ENTER_ALPHANUMERIC_VALUE_COMPANY}");
    $(document).on('change',"#company_industry_id",function(){
       $("#company_industry_id").valid();
    });
    $(document).on('change',"#company_size_id",function(){
       $("#company_size_id").valid();
    });
    $("#edit_company_form").validate({
        ignore: [],
        rules: {
           // company_name: {required: true,companyNm:true},
            company_name: {required: true},

            owner_email_address: {required: true},
            company_industry_id: {required: true},
            company_size_id: {required: true},
            company_description: {required: true,minlength:250},
            website_of_company: {required: true,url: true,},
            company_locations: {required:function(){
                    if($("#company_locations_container").html() == '' ){
                        return true
                    }else{
                        return false
                    }
                }
            }
        },
        messages: {
            company_name: {required: "{ERROR_EDIT_COMP_ENTER_COMPANY_NAME}"},
            owner_email_address: {required: "{ERROR_EDIT_COMP_ENTER_EMAIL_ADDRESS}"},
            company_industry_id: {required: "{ERROR_EDIT_COMP_SELECT_INDUSTRY_COMPANY}"},
            company_size_id: {required: "{ERROR_EDIT_COMP_SELECT_SIZE_OF_COMPANY}"},
            company_description: {required: "{ERROR_EDIT_COMP_ENTER_COMPANY_DESCRIPTION}"},
            website_of_company: {required: "{ERROR_EDIT_COMP_ENTER_WEBSITE_OF_COMPANY}",url: "&nbsp; {ERROR_EDIT_COMP_ENTER_VALID_URL}"},
            company_locations: {required:"{ENT_LOCTION_COM}"}
        },
        highlight: function(element) {
            //$(element).addClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass("valid-input").addClass("has-error");
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
                if (IsplaceChange == false) {
                    $("#company_locations").val('');
                    toastr["error"]("{LOCATION_ERROR_MSG_VAILD}");
                    IsplaceChange=true;
                }
                else {
                        return true;

                }
        }
    });

    $("#edit_company_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $(document).ready(function() {
        countCharacters();
        $("#company_admin").autocomplete(autocomp_opt);
        var no_of_locations = $(".map-box").length;
        if(no_of_locations == 5 || no_of_locations > 5) {
          //  $("#company_locations").parents(".form-group").fadeOut(1500);
            $("#company_locations").hide();

        }

        /*$(".date-picker").datepicker({
            autoclose: true,
            dateFormat: "yy",
            changeYear: true,
            viewMode: "years",
            minViewMode: "years",
        });
*/
        $('#foundation_year').datepicker({
            minViewMode: 2,
            autoclose: true,
            format: 'yyyy',
            endDate: "+0d",

          });
    });
    $(document).on('click', "#deleteCompany", function() {
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>deleteCompany",
                    data: {
                        company_id: '%ENCRYPTED_COMPANY_ID%',
                        action: "deleteCompany"
                    },
                    beforeSend: function() {addOverlay();},
                    complete: function() {removeOverlay();},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                           // toastr['success'](data.success);
                            window.location = "<?php echo SITE_URL; ?>company/my-companies";
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_EDIT_COMP_DELETE_COMPANY}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_COMPANY}", bootBoxCallback);
    });
    $(document).on("click", ".remove-company-admin", function() {
        var element = $(this);
        var bootBoxCallback = function(result, e) {
            if(result) {
                element.closest(".admin-cell").fadeOut(800, function() {
                    element.closest(".admin-cell").remove();
                });
            }
        }
        initBootBox("{ALERT_REMOVE_COMPANY_ADMIN}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_THIS_USER_FROM_COMPANY_ADMIN}", bootBoxCallback);
    });
</script>

<!-- Image crop model end-->
<script type="text/javascript">
  $(document).on("click","#close_popup",function(e){
           $("#Edit_Profile1").hide();
          //$(".close").click();
        });
var img_incr=-1;
function showdata()
{
    var formnew=document.getElementById("avtar_form");

    //var a=form.imageupload.value;
    //var formdata = $("#projectCreatefield").serialize();
    var formData = new FormData(formnew);
    var pathname = window.location.pathname.split('/');
    var mod=pathname['4'];
    var which_types=$("#hidden_image_id").html();
    $("#which_types").val(which_types);
    if(which_types=='images' || which_types=='header_slider' || which_types == 'activity_image' || which_types=='slider_home'){ var url_send='crop.php'; }

    var url_send = "<?php echo SITE_URL; ?>modules-nct/edit-company-nct/crop.php";
    $(window).scrollTop(0);
    /*$(".avatar-wrapper").append("<img class='loading' src='<?php// echo SITE_THEME_IMG;?>/ajax-loader-transparent.gif' style='margin-left:300px; margin-top:100px;'/>");*/
    var id = $("#id").val();

    formData.append('id',id);
    jQuery.ajax({
      url: url_send,
      type: 'post',
      dataType:'json',
      data:  formData,
      processData: false,  // tell jQuery not to process the data
      contentType: false ,  // tell jQuery not to set contentType
      enctype: 'multipart/form-data',
      mimeType: 'multipart/form-data',
      cache: false,
      beforeSend: function() {
                addOverlay();
        },
      success: function(data) {
        //window.location.href=data.url;
        //$('#thumb_video').attr('src', data)
        removeOverlay();

        $("#Edit_Profile1").hide();

        var site_url = "<?php echo SITE_URL; ?>";
        var dir_url = "<?php echo DIR_URL; ?>";

        var str = data.result;
        var final_img_url = str.replace(dir_url, site_url);
        console.log(final_img_url);
            $('#tmp_img').val(data.filename);
            if(which_types == 'images'){
                $("#select_logo_container").addClass("hidden");
                $("#company_logo_img").attr("src", final_img_url);
                $("#logo_preview_container").removeClass("hidden");
            }else{
                $("#select_banner_image_container").addClass("hidden");
                $("#banner_image_img").attr("src", final_img_url);
                $("#banner_image_preview_container").removeClass("hidden");
            }


          $(".close").click();
            $(".loading").hide();
        }

    });
}
</script>