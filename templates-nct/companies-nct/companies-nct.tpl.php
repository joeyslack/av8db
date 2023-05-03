<div class="inner-main">
    <div class="nav-menu in-menu">
    <div class="container">
        <ul id="submenu" class="sub-menu">
            <li><a href="javascript:void(0);" class="switch_my_following_company %MY_COMPANIES_ACTIVE_CLASS%" title="{LBL_MYC_MY_PAGES}" data-type="my_companies" data-endpoint="my-companies">{LBL_MYC_MY_PAGES}</a></li>
            <li><a href="javascript:void(0);" class="switch_my_following_company %FOLLOWING_COMPANIES_ACTIVE_CLASS%" title="{LBL_MYC_FOLLOWING}" data-type="following_companies" data-endpoint="following-companies">{LBL_MYC_FOLLOWING}</a></li>
        </ul>
    </div>
</div>
    <div class="my-compny-sec cf">
        <div class="container">
            <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                    <div class="gen-wht-bx text-center cf fix-sidebar left-first-fix" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
                        <div class="in-compny-heading fade fadeIn">
                            <h1>{LBL_MYC_COMPANIES}</h1>
                            <p>{LBL_MYC_WELCOME_TEXT}</p>
                        </div>
                        <div class="in-create-com fade fadeIn">
                                <h3>{LBL_MYC_CREATE_COMPANY_PAGE}</h3>
                                <p>{LBL_MYC_CREATE_COMPANY_WELCOME_NOTE}</p>
                                <div><a class="blue-btn" data-toggle="modal" data-target="#createCompany" title="{BTN_MYC_CREATE_COMPANY_TITLE}">{BTN_MYC_CREATE_COMPANY}</a></div>
                        </div>
                    </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 in-fl-rgt hidden-sm hidden-xs">
                <?php echo $this->subscribed_membership_plan_details; ?>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div id="companies_container"><?php echo $this->content; ?></div>
                <!-- <div id="pagination_container"><?php //echo $this->pagination; ?></div> -->
            </div>
            </div>

        </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<!-- Modal -->
<div class="modal fade" id="createCompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
        <h4 class="modal-title" id="myModalLabel">{LBL_FORM_COMPANY_WELCOME_TEXT}</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo SITE_URL; ?>create-company" class="create-form" name="create_company_form" id="create_company_form" method="post" enctype="multipart/form-data">
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_FORM_COMPANY_COMPANY_NAME} <sup>*</sup></label>
                    <input type="text" id="company_name" name="company_name" placeholder="{LBL_FORM_COMPANY_COMPANY_NAME}*" autocomplete="off" />
                    <input type="hidden" name="company_id" id="company_id" value="%COMPANY_ID%" />
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{ENTER_BUSINESS_URL} <sup>*</sup></label>
                    <input type="text" id="company_url" name="company_url" placeholder="{ENTER_BUSINESS_URL}*" />
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_FORM_COMPANY_YOUR_EMAIL_ADDRESS_AT_COMPANY} <sup>*</sup></label>
                    <input type="text" id="owner_email_address" name="owner_email_address" placeholder="{LBL_FORM_COMPANY_YOUR_EMAIL_ADDRESS_AT_COMPANY}*" />
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{ENTER_NO_OF_EMPLOYEES} <sup>*</sup></label>
                    <input type="text" id="company_employees" name="company_employees" placeholder="{ENTER_NO_OF_EMPLOYEES}*" />
                </div>
                <!-- <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_FORM_COMPANY_COMPANY_INDUSTRY} <sup>*</sup></label>
                    <select name="company_industry_id" id="company_industry_id" class="selectpicker show-tick">
                        <option value="">{LBL_FORM_COMPANY_COMPANY_INDUSTRY}*</option>
                        %COMPANY_INDUSTRY_OPTIONS%
                    </select>
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_FORM_COMPANY_COMPANY_SIZE} <sup>*</sup></label>
                    <select name="company_size_id" id="company_size_id" class="selectpicker show-tick">
                            <option value="">{LBL_FORM_COMPANY_COMPANY_SIZE}*</option>
                            %COMPANY_SIZE_OPTIONS%
                        </select>
                </div> -->
            </div>
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{SELECT_CLOSEST_AIRPORT} <sup>*</sup></label>
                    <input type="text" name="closest_airport" id="closest_airport" placeholder="{SELECT_CLOSEST_AIRPORT}*" value="" autocomplete="off" />
                    <input type="hidden" name="airport_id" id="airport_id" value=""/>
                    <!-- <select name="closest_airport" id="closest_airport" class="selectpicker show-tick">
                        <option value="">{SELECT_CLOSEST_AIRPORT}*</option>
                        %COMPANY_CLOSEST_AIRPORT%
                    </select> -->
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{SELECT_COMPANY_TYPE} <sup>*</sup></label>
                    <select name="company_type" id="company_type" class="selectpicker show-tick">
                        <option value="">{SELECT_COMPANY_TYPE}*</option>
                        %COMPANY_INDUSTRY_OPTIONS%
                    </select>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                <div class="flat-checkbox">
                        <label>{COMPANY_LOGO} <sup>*</sup></label>
                        <div id="select_banner_image_container" class="banner-preview-outer">
                            <div class="banner-inner-tbl">
                                <div class="upload-img-file btn-file">
                                    <i class="fa fa-plus plus-upload"></i>
                                    <input type="hidden" id="company_logo" name="company_logo" />
                                    <input type="hidden" name="is_banner_removed" id="is_banner_removed" value="false" />
                                </div>
                            </div>
                        </div>
                        <div id="banner_image_preview_container" class="banner-preview-contianer %BANNER_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                            <img id="banner_image_img" src="" alt="" />
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
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                    <label>{SELECT_LOCATION} <sup>*</sup></label>
                    <input type="text" id="location" name="location" placeholder="{SELECT_LOCATION}*" />
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                    <div id="map_canvas" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                <div class="flat-checkbox">
                        <input type="checkbox" id="accept_terms" name="accept_terms" required />
                        <label for="accept_terms">
                            {LBL_FORM_COMPANY_VERIFY_RIGHTS_CHECKBOX}
                        </label>
                    </div>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 ">
                    <input type="hidden" name="lat" id="lat" value="37.090240">
                    <input type="hidden" name="lng" id="lng" value="-95.712891">
                    <button type="submit" class="blue-btn" name="create_company" id="create_company">{BTN_CREATE_COMPANY_CREATE}</button>
                    <button type="button" class="outer-red-btn" data-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="form-group text-center"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"  src="//maps.googleapis.com/maps/api/js?v=3.28&sensor=false&libraries=places&language=en&key={GOOGLE_MAPS_API_KEY}"></script>

<!-- Image crop model start-->
<div class="modal fade in" id="company1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog  is-width-set" role="document">
    <div class="modal-content">
      <div class="modal-header_1"> 
        <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title_1 text-center blue-text" id="myModalLabel">Crop Image</h4>
      </div>
      <div class="modal-body_1">
        <div class="edit-profile-block">
          <div class="container2"  id="crop-avatar"> 
            <!-- Cropping modal -->
            <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post" name="avtar_form" id="avtar_form">
              <input type="hidden" name="subcat_id" id="subcat_id" value="">
              <div class="modal-body_1">
                <div class="avatar-body"> 
                  <!-- Upload image and data -->
                  <div class="avatar-upload">
                    <input type="hidden" class="avatar-src" name="avatar_src">
                    <input type="hidden" class="avatar-data" name="avatar_data">
                    <input type="hidden"  name="which_types" id="which_types">
                    <label for="avatarInput">Upload</label>
                    <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  </div>
                  <!-- Crop and preview -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="avatar-wrapper"></div>
                    </div>
                  </div>
                  <div class="row avatar-btns">
                    <div class="col-md-12">
                      <div id="hidden_image_id" style="display:none;"></div>
                      <button id="rotateleft" class="btn btn-primary" style="float:left; margin-left:5px;margin-right:5px;"><span class="fa fa-rotate-left"></span></button>
                      <button id="rotateright" class="btn btn-primary" style="float:left; margin-left:5px;margin-right:5px;"><span class="fa fa-rotate-right"></span></button>
                      &nbsp;&nbsp;
                      <button type="button" style="float:left; margin-left:5px;margin-right:5px;width:70px;" class="btn btn-primary btn-block avatar-save" onclick="return showdata();">Done</button>
                      &nbsp;&nbsp;
                      <button type="button" style="float:left" class="btn btn-default" data-dismiss="modal" id="close_popup">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- /.modal --> 
          </div>
        </div>
      </div>
    </div>
  </div>
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
function mapPinPoint(lat="",lng="",zoom_no=12){
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

    // var mapZoom=map.getZoom();
    // $("#zoom_level").val(mapZoom);

    // google.maps.event.addListener(map, 'zoom_changed', function(ee) {
    //   mapZoom=map.getZoom();
    //   $("#zoom_level").val(mapZoom);
    // });

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

    $(document).on("click", ".load_more", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");
        
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#companies_container").find(".load-more-data").remove();
                    $("#companies_container").append(data.content);
                   // $("#search_results_container").find(".no-results").remove();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
    function loadMoreRecordfordata(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#companies_container").find(".view-more-btn a").remove();
                    $("#companies_container").append(data.content);

                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    var ajax_call = true;
   
    window.addEventListener("scroll",onScrollnew);
    function onScrollnew(){
        var height=$(window).height();

        if( /Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            height=window.visualViewport.height;
        }
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0) 
        {
            height=$(window).innerheight();
        }
         if (($(window).scrollTop() + height) >= $(document).height() && ajax_call==true) {
            var url = $(".view-more-btn a").attr('href');
            if(url) {

                loadMoreRecordfordata(url);
            }
        }
    }

    $(document).ready(function() {
        $("#closest_airport").autocomplete(autocomp_opt1);
        $("#company_name").autocomplete(autocomp_opt);
    });
    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            $("#company_id").val("");
            $("#industry_dd_container").removeClass("hidden");
            $("#company_size_dd_container").removeClass("hidden");

            $.ajax({
                url: "<?php echo SITE_URL; ?>getCompanySuggestions",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getCompaniesExp',
                    company_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.company_name, value: item.company_name, id: item.company_id,size_id:item.company_size_id,industry_id:item.company_industry_id};
                    }));
                },
                error: function (jq, status, message) {
                }
            });
        },
        select: function (event, c) {
            company_id = c.item.id;
            size_id=c.item.size_id;
            industry_id=c.item.industry_id;
            $("#company_id").val(company_id);
            $('select[name=company_industry_id]').val(industry_id);
            $('.selectpicker').selectpicker('refresh')
            $('select[name=company_size_id]').val(size_id);
            $('.selectpicker').selectpicker('refresh')
        },
        autoFocus: true
    };

    var autocomp_opt1 = {
        source: function (request, response) {
            var input = this.element;
            $.ajax({
                url: "<?php echo SITE_URL; ?>getAirportSuggestions",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getClosestAirport',
                    airport_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.airport_name, value: item.airport_name, id: item.airport_id};
                    }));
                },
                error: function (jq, status, message) {
                }
            });
        },
        select: function (event, c) {
            airport_id = c.item.id;
            $("#airport_id").val(airport_id);
            $('.selectpicker').selectpicker('refresh')
        },
        autoFocus: true
    };

    function updatePageContent(data) {
        $("#companies_container").html(data.content);
        $("#pagination_container").html(data.pagination);
        height = $("#submenu").offset().top;
        scrolWithAnimation(height);
        $(window).scroll();
    }
    function getCompanies(page, type, tab_changed = false) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getCompanies",
            data: {
                page: page,
                type: type,
                action: 'getCompanies',
                sess_user_id: "<?php echo $_SESSION['user_id'];?>",
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (tab_changed) {
                        $("#submenu li").each(function() {
                            current_element = $(this).find("a.switch_my_following_company");
                            current_element.toggleClass("active");
                            if (current_element.hasClass("active")) {
                                var endpoints = current_element.data("endpoint");
                                window.history.pushState("", "Title", endpoints);
                            }
                        });
                    }
                    updatePageContent(data);
                    if (type == 'my_companies') {
                        search_type = "my-companies";
                    } else if(type == 'following_companies') {
                        search_type = "following-companies";
                    }
                    if(page > 1) {
                        window.history.pushState("", "Title", search_type + "?page=" + page);
                    } else {
                        window.history.pushState("", "Title", search_type);
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    $(document).on('change',"#company_industry_id",function(){
       $("#company_industry_id").valid();
    });
    $(document).on('change',"#company_size_id",function(){
       $("#company_size_id").valid();
    });
    $('#createCompany').on('hidden.bs.modal', function () {
        // $("#create_company_form")[0].reset();
        $('.selectpicker').selectpicker('refresh');
        // $('#create_company_form').validate().resetForm();
        $('#create_company_form').find('.error').removeClass('has-error');
        $('#create_company_form').find('.valid-input').removeClass('valid-input');
    });
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        var type = $("#submenu").find("li a.active").data("type");
        getCompanies(page, type);
    });
    $(document).on("click", ".unfollow-company", function() {
        company_li = $(this).parents(".following-cell");
        var page = $(".pagination  li.active a.buttonPageActive").html();
        console.log(page);
        var company_id = $(this).data("company-id");

        var bootBoxCallback = function(result) {
        if (result) {

            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>unfollowCompany",
                data: {
                    action: "unfollowCompany",
                    page: page,
                    company_id: company_id
                },
                beforeSend: function() {$(".loader").show();},
                complete: function() {$(".loader").fadeOut(2000);},
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        //toastr['success'](data.success);
                        updatePageContent(data);
                    } else {
                        toastr['error'](data.error);
                    }
                }
            });
        }
        }
        initBootBox_company("{LBL_COM_DET_UNFOLLOW_COMPANY}", "{LBL_COM_DET_ARE_YOU_SURE_WANT_UNFOLLOW_COMPANY}", bootBoxCallback);



    });
    $(document).on("click", ".switch_my_following_company", function() {
        if (!$(this).hasClass("active")) {
            type = $(this).data("type");
            getCompanies(1, type, true);
        } else {
            toastr['error']("{ERROR_MYC_YOU_ARE_ON_SAME_WINDOW_TRYING_TO_VIEW}");
        }
    });

    $(document).on('click', ".deleteCompany", function() {
        var company_id = $(this).data("id");
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>deleteCompany",
                    data: {
                        company_id: company_id,
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
</script>
<script type="text/javascript">
    $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    }, "{PLEASE_ENTER_ALPHANUMERIC_VALUE_COMPANY}");
    $.validator.addMethod("emailCheck", function(value, element) {
        var company_url   = $('#company_url').val();
        var company_email = $('#owner_email_address').val();
        let get_domain_url = domain = '';
        if (company_url.indexOf("http://") == 0 || company_url.indexOf("https://") == 0){
            get_domain_url= new URL(company_url);
            domain = get_domain_url.hostname.replace('www.','');
        }else{
            domain = company_url.replace('www.','');
        }
        var regex = new RegExp("^([a-zA-Z0-9.])+\@" + domain + "+$", "");
        if(regex.test(company_email)){
            return true;
        }else{
            return false;
        }
    }, "{ERROR_FORM_CREATE_COMPANY_VALID_EMAIL_MATCH_WITH_URL}");
    
    $.validator.addMethod('validUrl', function(value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url('http://' + value, element);
    });

    $("#create_company_form").validate({
        ignore: [],
        rules: {
            company_name:        {required: true,companyNm: true},
            owner_email_address: {required: true,checkEmail: true,emailCheck: true},
            company_url:         {required: true,validUrl: true},
            company_employees:   {required: true},
            closest_airport:     {required: true},
            company_type:        {required: true},
            company_logo:        {required: {depends: function (element) {
              return ((($("div.banner-preview-contianer").attr('src')!="")) ? false : true);
          }}},
            accept_terms:        {required: true},
            location:            {required: true},
        },
        messages: {
            company_name: {required: "{ERROR_FROM_COMPANY_ENTER_COMPANY_NAME}"},
            owner_email_address: {required: "{ERROR_FORM_CREATE_COMPANY_ENTER_EMAIL_ADDRESS}"},
            company_url: {required: "{ERROR_FORM_CREATE_COMPANY_ENTER_URL}",validUrl:"{ERROR_FORM_CREATE_COMPANY_ENTER_VALID_URL}"},
            company_employees: {required: "{ERROR_FORM_CREATE_COMPANY_ENTER_VALID_URLEMPLOYEES}"},
            closest_airport: {required: "{ERROR_FORM_CREATE_COMPANY_SELECT_CLOSEST_AIRPORT}"},
            company_type: {required: "{ERROR_FORM_CREATE_COMPANY_SELECT_COMPANY_TYPE}"},
            company_logo: {required: "{ERROR_FORM_CREATE_COMPANY_SELECT_COMPANY_LOGO}"},
            accept_terms: "{ERROR_FORM_CREATE_COMPANY_ACCEPT_TERMS_AND_CONDITIONS}",
            location: "Please select location.",
        },
        highlight: function(element) {
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
            return true;
        }
    });
    $("#create_company_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
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
</script>
<script>
$(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    var header = document.getElementById("membership_plan_id");
    if(header === null){
        header = document.getElementById("membership_add_plan_id");

    }
    var sticky = header.offsetTop;
    window.onscroll = function() {
        if(header != ''){
            myFunction();

        }
    };
    function myFunction() {
      if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    }
    $(document).on("click", "#change_banner_image", function() {
        $("#company_logo").click();
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
    $(document).on('click','#company_logo',function(){
        $('#createCompany').modal('hide');
        $("#Edit_Profile1").show();
    });
    // $(document).on("change", "#company_logo", function(e) {
    //     var file = this.files[0];
    //     showBannerImage(file);
    // });
    /*$(document).on("change", "#company_logo", function(e) {
        var file1 = this.files[0];
         var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        const fi = document.getElementById('company_logo');
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
             if (fi.files.length > 0) {
                for (const i = 0; i <= fi.files.length - 1; i++) {
                    const fsize = fi.files.item(i).size;
                    const file = Math.round((fsize / 1024));
                    if (file >= 4096) {
                        toastr["error"]('File size is too large, please select a file less than 4 MB');
                        $('#company_logo').val('');
                    }else{
                        showBannerImage(file1);
                    }
                }
            }
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $("#company_logo").val("");
        }
    });*/
    $('#avatar-modal').on('hidden.bs.modal', function () {
        $('.avatar-wrapper img').cropper('destroy');
        $('.avatar-wrapper').empty();
    });
    $('#avatar-modal').on('shown.bs.modal', function () {
        $('.avatar-wrapper img').cropper({
            aspectRatio: 1,
            strict: true,
            minCropBoxWidth: 130,
            minCropBoxHeight: 130,
            viewMode: 1,
            crop: function (e) {
                var json = [
                    '{"x":' + e.x,
                    '"y":' + e.y,
                    '"height":' + e.height,
                    '"width":' + e.width,
                    '"rotate":' + e.rotate + '}'
                ].join();
                $('.avatar-data').val(json);
            }
        });
    });
    $(document).on('change', '#avatarInput', function (e) {
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
            var url = (typeof FileReader == "undefined") ? webkitURL.createObjectURL(e.target.files[0]) : URL.createObjectURL(e.target.files[0]);
            console.log(typeof FileReader);
            console.log(URL.createObjectURL(e.target.files[0]));
            // console.log(url);
            // console.log(extension);
            //$('.avatar-wrapper').empty().html('<img src="' + url + '">');
            //$('#avatar-modal').modal('show');
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $(".loading").hide();
            $("#profile_picture").val("");

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
                //if (aspectRatio == 3) {
                    $("#select_banner_image_container").addClass("hidden");
                    $("#banner_image_img").attr("src", this.src);
                    $("#banner_image_preview_container").removeClass("hidden");
                // } else {
                //     $("#company_logo").val("");
                //     toastr["error"]("{ALERT_UPLOAD_BANER_ASPECT_RATIO_EDIT_COMPANY}");
                // }
            };

        });
    }
</script>
<script type="text/javascript">
$(document).on("click","#close_popup",function(e){
   $("#Edit_Profile1").hide();
   // $('#createCompany').modal('toggle');
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
    console.log(which_types);
    if(which_types=='images' || which_types=='header_slider' || which_types == 'activity_image' || which_types=='slider_home' || which_types == 'cover_photo_user'){ var url_send='crop.php'; }
    
    var url_send = "<?php echo SITE_URL; ?>modules-nct/companies-nct/crop.php";
    $(window).scrollTop(0);
    /*$(".avatar-wrapper").append("<img class='loading' src='<?php echo SITE_THEME_IMG;?>/ajax-loader-transparent.gif' style='margin-left:300px; margin-top:100px;'/>");*/
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
        console.log(data);
        $("#Edit_Profile1").hide();
        $('#createCompany').modal('show');

        //window.location.href=data.url;
        //$('#thumb_video').attr('src', data)
            removeOverlay();

            var site_url = "<?php echo SITE_URL; ?>";
            var dir_url = "<?php echo DIR_URL; ?>";

            var str = data.result;
            var final_img_url = str.replace(dir_url, site_url);
            $("#select_banner_image_container").addClass("hidden");
            $("#banner_image_img").attr("src", final_img_url);
            $("#banner_image_preview_container").removeClass("hidden");
            $('#company_logo').val(data.filename);
            // console.log(data.updated_profile_pic_src);
            // if(which_types == 'images'){

            //   $('#tmp_img').val(data.filename);
            //   $("#company_logo_img").attr("src",final_img_url);
            //   $(".user-img").html('<img src="' +final_img_url+ '"  />')
            //   $('#profile_picture_container').html(data.updated_profile_pic_src+'<div class="profile-overlay"><a href="javascript:void(0);" title="Edit" id="change_profile_picture"><div class="btn-file active"><i class="fa fa-pencil"></i><input type="file" class="places_image" accept="image/x-png,image/jpeg" name="profile_picture" id="profile_picture" tabindex="-1"></div></a><a href="javascript:void(0);" id="removeUserImage" title="Remove">  <i class="fa fa-close active"></i></a></div>');
            // }else{
            //     //$('.profile-view-outer').css('background-image','url('+data.updated_profile_pic_src+')');

            //     $(".banner_img_change").attr("src",data.updated_profile_pic_src);
            //     toastr['success']("{UPDATE_COVER_PHOTO_MSG}");
            // }
            // $(".close").click();
            $(".loading").hide();
        }

    });
}
</script>