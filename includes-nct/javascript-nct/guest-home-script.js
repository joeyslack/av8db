$('.previous-button-1,.previous-button-2,.submit-button,.next-button-2').hide();
    $('.experience-info-1,.experience-info-2').hide();
    $('.next-button-1 .blue-btn').click(function () {
        if ($("#signup_form").valid()) {
            $('.general-info').hide("slide", {direction: "left"}, 500, function () {
                $('.experience-info-1').show();
                $('.next-button-1').hide();
                $('.next-button-2').show();
                $('.previous-button-1').show();
                $("#company_name").focus();
            });
        }
    });
    $('.next-button-2 .blue-btn').click(function () {
        if ($("#signup_form").valid()) {
            $('.experience-info-1').hide("slide", {direction: "left"}, 500, function () {
                $('.experience-info-2').show();
                $('.submit-button').show();
                $('.previous-button-2').show();
                $('.previous-button-1').hide();
                $('.next-button-2').hide();
                $("#job_title").focus();
            });
        }
    });
    $("#signup_form").keypress(function (e) {
        if (e.which == 13) {
            if ($(".next-button-1").is(":visible")) {
                $(".next-button-1 .blue-btn").click();
                return false;
            } else if ($(".next-button-2").is(":visible")) {
                $(".next-button-2 .blue-btn").click();
                return false;
            } else if ($(".submit-button").is(":visible")) {
                if ($("#signup_form").valid()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    });
    $('.previous-button-1').click(function () {
        $('.experience-info-1').hide("slide", {direction: "right"}, 500, function () {
            $('.general-info').show();
            $('.next-button-2').hide();
            $('.next-button-1').show();
            $('.previous-button-1').hide();
        });
    });
    $('.previous-button-2').click(function () {
        $('.experience-info-2').hide("slide", {direction: "right"}, 500, function () {
            $('.previous-button-2').hide();
            $('.submit-button').hide();
            $('.experience-info-1').show();
            $('.next-button-2').show();
            $('.previous-button-1').show();
        });
    });
    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('job_location')),{types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        } else {
            address1 = address2 = city1 = city2 = state = country = postal_code = '';
            formatted_address = place.formatted_address;
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();
            var arrAddress = place.address_components;
            $.each(arrAddress, function (i, address_component) {
                if(address_component.types[0]=="route") {address1 = address_component.long_name;}
                if(address_component.types[0]=="sublocality") {address2 = address_component.long_name;}
                if(address_component.types[0]=="locality") {city1 = address_component.long_name;}
                if(address_component.types[0]=="administrative_area_level_2") {city2 = address_component.long_name;}
                if(address_component.types[0]=="administrative_area_level_1") {state = address_component.long_name;}
                if(address_component.types[0]=="country") {country = address_component.long_name;}
                if(address_component.types[0]=="postal_code") {postal_code = address_component.long_name;}
            });
            $("#formatted_address").val(formatted_address);
            $("#address1").val(address1);
            $("#address2").val(address2);
            $("#country").val(country);
            $("#state").val(state);
            $("#city1").val(city1);
            $("#city2").val(city2);
            $("#postal_code").val(postal_code);
            $("#latitude").val(latitude);
            $("#longitude").val(longitude);
        }
    }
    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            $("#company_id").val("");
            $("#industry_dd_container").removeClass("hidden");
            $("#company_size_dd_container").removeClass("hidden");
            $("#job_location_dd_container").addClass("hidden");
            $("#job_location_container").removeClass("hidden");
            $.ajax({
                url: SITE_URL+"getCompany",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getCompanies',
                    company_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {return {label: item.company_name, value: item.company_name, id: item.company_id};}));
                }
            });
        },
        select: function (event, c) {
            company_id = c.item.id;
            $("#company_id").val(company_id);
            $("#industry_dd_container").addClass("hidden");
            $("#company_size_dd_container").addClass("hidden");
            $("#job_location_container").addClass("hidden");
            $("#job_location_dd_container").removeClass("hidden");
            $.ajax({
                url: SITE_URL+"getJobLocations",
                type: "POST",
                dataType: "json",
                data: {
                    action: 'getCompanyLocations',
                    company_id: $("#company_id").val()
                },
                success: function (data) {
                    $("#job_location_id").html(data);
                    $("#job_location_id").prepend('<option value="">job locations</option>');
                    $('.bootstrap-dropdowns').selectpicker('refresh')
                }
            });
        },
        autoFocus: true
    };
    $(document).ready(function () {
        $("#company_name").autocomplete(autocomp_opt);
        $(".bootstrap-dropdowns").selectpicker('refresh');
        $("#is_headline_container").fadeOut(1000);
        $("#password_tooltip").hide();
    });
    $(document).on('change', "#is_current", function () {
        var ischecked = $(this).is(':checked');
        if (!ischecked) {
            $("#to_date_container").fadeIn(1000);
            $("#is_headline_container").fadeOut(1000);
        } else {
            $("#to_date_container").fadeOut(1000);
            $("#is_headline_container").fadeIn(1000);
        }
    });
    $("#signup_form").validate({
        ignore: ":not(:visible)",
        rules: {
            first_name: {required: true,minlength: 3,maxlength: 25},
            last_name: {required: true,minlength: 3,maxlength: 25},
            signup_email_address: {required: true,checkEmail: true,remote: {url: SITE_URL+"checkIfEmailExists",async: false,type: "POST"}},
            signup_password: {required: true,minlength: 6,maxlength: 40},
            confirm_password: {required: true,equalTo: "#signup_password"},
            company_name: {},
            industry_id:{required:function(){if($("#company_id").val() == '') {return false;} else {return true;}}},
            company_size_id:{required:function(){if($("#company_id").val()==''){return false;}else{return true;}}},
            job_title: {},
            job_location_id:{required:function(){if($("#company_id").val()==''){return false;}else{return true;}}},
            job_location: {/*required: function () {if ($("#company_id").val() == '') {return true;} else {return false;}}*/},
            formatted_address: {required: function () {if ($("#company_id").val() == '') {return true;} else {return false;}}},
            country:{required:function () {if ($("#company_id").val() == '') {return true;} else {return false;}}},
            state: {required: function () {if ($("#company_id").val() == '') {return true;} else {return false;}}},
            latitude:{required:function() {if ($("#company_id").val() == '') {return true;} else {return false;}}},
            longitude:{required:function(){if ($("#company_id").val() == '') {return true;} else {return false;}}},
            from_month: {required: true},
            //from_year: {required: true,number: true},
            description: {/*required: true*/},
            hiddenRecaptcha: {required: function () {if (grecaptcha.getResponse() == '') {return true;} else {return false;}}}
        },
        groups: {clinic_location: "formatted_address address1 country state latitude longitude job_location"},
        messages: {
            first_name: {required: "Please enter your first name",minlength: "First name must be of at least 3 characters",maxlength: "First name must not be longer than 25 characters"},
            last_name: {required: "Please enter your last name",minlength: "Last name must be of at least 3 characters",maxlength: "Last name must not be longer than 25 characters"},
            signup_email_address: {required: "Please enter email address",checkEmail: "Please enter a valid email address",remote: "Entered email address already exists"},
            signup_password: {required: "Please enter a Password",minlength: "Password must be of at least 6 characters",maxlength: "Password must not be longer than 40 characters",/*validpassword: "Password must contain a lower case letter, an upper case letter and a digit"*/},
            confirm_password: {required: "Please confirm the Password",equalTo: "Password doesn't match"},
            company_name: {required: "&nbsp; Please enter company name."},
            industry_id: {required: "&nbsp; Please select industry."},
            company_size_id: {required: "&nbsp; Please select company size."},
            job_title: {/*required: "&nbsp; Please enter job title."*/},
            job_location: {required: "&nbsp; Please enter job location."},
            formatted_address: {required: "&nbsp; Please select job location."},
            country: {required: "&nbsp; Please select job location."},
            state: {required: "&nbsp; Please select job location."},
            latitude: {required: "&nbsp; Please select job location."},
            longitude: {required: "&nbsp; Please select job location."},
            from_month: {required: "&nbsp; Please select from month."},
            //from_year: {required: "&nbsp; Please select from year.",number: "&nbsp; Please enter a valid year."},
            description: {/*required: "&nbsp; Please enter description."*/},
            hiddenRecaptcha:{required:'Please prove you are not a robot.'}
        },
        submitHandler: function (form) {if ($(form).valid()) {/*$("#signup").prop("disabled", false);*/return true;} else {/*$("#signup").prop("disabled", true);*/return false;}}
    });
    $("#signup_form").ajaxForm({
        beforeSend: function () {
            // $(".loader").show();
        },
        uploadProgress: function (event, position, total, percentComplete) {},
        success: function (html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                $("#signup_form")[0].reset();
                $("#changeCaptcha").click();
                $('.general-info').show();
                $('.next-button-1').show();
                $('.experience-info-2').hide();
                $('.previous-button-2').hide();
                $('.submit-button').hide();
                $("#signup").prop("disabled", false);
                toastr["success"](obj.success);
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function (xhr) {
            $(".loader").fadeOut();
            return false;
        }
    });
    $(document).on('click', ".loginWithSocialMedia", function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var width = 626;
        var height = 436;
        var l = window.screenX + (window.outerWidth - width) / 2;
        var t = window.screenY + (window.outerHeight - height) / 2;
        var winProps = ['width=' + width, 'height=' + height, 'left=' + l, 'top=' + t, 'status=no', 'resizable=yes', 'toolbar=no', 'menubar=no', 'scrollbars=yes'].join(',');
        $.oauthpopup({
            path: url,
            windowOptions: winProps,
            callback: function () {window.location.reload();}
        });
        e.preventDefault();
    });
    $.oauthpopup = function (options) {
        options.windowName = options.windowName || 'ConnectWithOAuth';
        options.windowOptions = options.windowOptions || 'location=0,status=0,width=' + options.width + ',height=' + options.height + ',scrollbars=1';
        options.callback = options.callback || function () {window.location.reload();};
        var that = this;
        that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
        that._oauthInterval = window.setInterval(function () {
            if (that._oauthWindow.closed) {window.clearInterval(that._oauthInterval);options.callback();}
        }, 1000);
    };
    function login() {
        FB.login(function (response) {
            if (response.authResponse) { getUserData(); }
        }, {scope: 'email,public_profile', return_scopes: true});
    }
    function getUserData() {
        FB.api('/me', {locale: 'en_US', fields: 'name, email, gender'},function (response) {
            var data = [];
            data['name'] = response.name;
            data['email'] = response.email;
            data['gender'] = response.gender;
            data['provider'] = 'Facebook';
            $.ajax({
                method: 'post',
                url: SITE_URL+'modules-nct/social-login-nct/fb_process.php',
                data: response,
                dataType: 'json',
                success: function (data) {window.location.href = data.url;}
            });
        });
    }
    window.fbAsyncInit = function () {
        FB.init({
            appId: FB_APP_ID,
            xfbml: true,
            version: 'v2.2'
        });
    };
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    $(document).on("click", ".search-entity-selection-li", function() {
        var selected_entity_class = $(this).find(".search-entity-selection i").attr("class");
        $("#search_selected_entity").attr("class", selected_entity_class);
        //$("#selected_entity_container").data("search-entity", $(this).find(".search-entity-selection").data('entity'));
        $("#selected_entity_container").attr("data-entity",$(this).find(".search-entity-selection").data('entity'));
    });
    $(document).on("submit", "#header_search_form", function(e) {
        e.preventDefault();
        var urlParam = {};
        //var search_entity = $("#selected_entity_container").data("entity");
        var search_entity = $("#selected_entity_container").attr("data-entity");
        if($("#keyword").val().trim() != "") {
            urlParam['keyword'] = $("#keyword").val();
        } else {
            delete urlParam['keyword'];
        }
        var newurlParam = jQuery.extend({}, urlParam);
        delete newurlParam.search_type;
        var newParam = decodeURIComponent($.param(newurlParam));
        if(newParam != '') {
            var url = SITE_URL + 'search/'+ search_entity +'?' + newParam;
        } else {
            var url = SITE_URL + 'search/'+ search_entity;
        }
        window.location = url;
    });
var gaia_initPasswordStrengthMeter = function(ratingMessages) {
  var inputHolders = [];
  inputHolders.passwd = $("#signup_password");
  $(document).on('keyup', "#signup_password", inputHandler);
  $(document).on('focus', "#signup_password", inputHandler);
  $(document).on('focusout', "#signup_password", function() {$("#password_tooltip").hide();});
  function inputHandler() {
    $("#password_tooltip").show();    
    if ($("#signup_password").val().length == 0) {
      var message = document.getElementById('passwdRating');
      message.innerHTML = '';
      updatePasswordBar(5); 
    } else if ($("#signup_password").val().length < 6) {
      updatePasswordBar(0);
      /*inputHolders.passwd.previousValue = $("#signup_password").val();*/
    } else  {
        CheckPasswordStrength($("#signup_password").val());
    }
  }
  function CheckPasswordStrength(password) {
        if (password.length == 0) {updatePasswordBar(0);} 
        var regex = new Array();
        regex.push("[A-Z]");
        regex.push("[a-z]");
        regex.push("[0-9]");
        regex.push("[$@$!%*#?&]"); 
        var passed = 0;
        for (var i = 0; i < regex.length; i++) { if (new RegExp(regex[i]).test(password)) { passed++; } }
        if (passed > 2 && password.length > 8) { passed++; }
        var color = "";
        var strength = "";
        switch (passed) {
            case 0:
            case 1:updatePasswordBar(1);break;
            case 2:updatePasswordBar(2);break;
            case 3:
            case 4:updatePasswordBar(3);break;
            case 5:updatePasswordBar(4);break;
        }
    }
  function updatePasswordBar(rating) {
    var ratingClasses = new Array(6);
    ratingClasses[0] = 'short';
    ratingClasses[1] = 'weak';
    ratingClasses[2] = 'fair';
    ratingClasses[3] = 'good';
    ratingClasses[4] = 'strong';
    ratingClasses[5] = 'notRated';
    var bar = document.getElementById('strength-bar');
    if (bar) { 
      var message = document.getElementById('passwdRating');
      var barLength = document.getElementById('passwdBar').clientWidth;
      bar.className = ratingClasses[rating];
      if (rating >= 0 && rating <= 4) {  
        bar.style.width  = (barLength * (parseInt(rating) + 1.0) / 5.0) + 'px';
        message.innerHTML = ratingMessages[rating];
      } else {
        bar.style.width = 0;
        rating = 5; 
      }
    }
  }
};
gaia_initPasswordStrengthMeter(["Too short","Weak","Fair","Good","Strong","Not rated"]);