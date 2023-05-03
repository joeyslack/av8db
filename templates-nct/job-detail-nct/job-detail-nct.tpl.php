<div class="inner-main">
	<div class="job-dtl-sec cf">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-4 col-lg-3">
					<?php echo $this->job_posted_by; ?>
					<?php echo $this->subscribed_membership_plan_details; ?>
					<div class="looking-jobs orange-code text-center cf">
						<h4 class="post-label">{LBL_LOOKING_FOR_TALENT}</h4>
						<a data-target="#createjobs" data-toggle="modal" title="{LBL_POST_JOB}" class="outer-blue-btn">{LBL_POST_JOB}</a>
					</div>
				</div>
				<div class="col-sm-12 col-md-8 col-lg-9">
					<div class="gen-wht-bx fade fadeIn cf">
						<div class="company-dtl-view">
                            %FEATURED%
							<a class="in-img-85" title="%COMPANY_NAME%" href="%COMPANY_URL%">%COMPANY_LOGO_URL%</a>
							<div class="job-dtl-rgt">
								<h3>%JOB_TITLE%</h3>
								
								<h4><a href="%COMPANY_URL%" title="%COMPANY_NAME%" class="blue-color">%COMPANY_NAME%</a></h4>
								<h5>%INDUSTRY_NAME%</h5>
								<div class="addr-bx"><i class="icon-map"></i>%LOCATION%</div>
								<div class="addr-bx"><i class="icon-calendar"></i>{LBL_LAST_DATE_APPLICATION}: %LAST_DATE_OF_APPLICATION%</div>
							</div>
							<div class="rgt-yr-emp">
								<p class="gray-text text-12">{LBL_POSTED} %POSTED_DATE% </p>
							</div>
						</div>
						<div class="manage-job-btm cf">
							<div class="share-number">
								<span>{LBL_APPLICANTS}</span>
								<em><a href="%JOB_APPLICANTS_URL%" title="{JOB_APPLICANTS}" class="blue-color no_of_applicants">%NO_OF_APPLICANTS%</a></em>
							</div>
							<div class="text-center mdl-btns">%SAVE_JOB_URL% %APPLY_JOB_URL% %DIRECT_APPLY_JOB_LINK%</div>
							<div class="share-part">
									<?php echo $this->share_on_social_media; ?>
							</div>
						</div>
					</div>

					<div class="gen-wht-bx in-heading cf">
						<h3>{LBL_JOB_DESCRIPTION}</h3>
						<div class="job_description-dtl">
							<h4>{LBL_REPONSIBILITY}</h4>
								<ul class="gray-text"> 
									%RESPONSIBILITY%
								</ul>
							
							<h4>{LBL_JOB_DETAILS_LICENSES_ENDORSEMENT}</h4>
								<ul class="gray-text"> 
									%LICENSES_ENDORSEMENTS_NAME%
								</ul>
						</div>
						<div class="other-desc-info">
							<div class="job-desc-part">
								<h5>
									{LBL_EMPLOYMENT_TYPE}
								</h5>
								<p class="gray-text">%EMPLOYMENT_TYPE%</p>
							</div>
							<div class="job-desc-part">
								<h5>
									{LBL_EXPERIENCE}
								</h5>
								<p class="gray-text">%EXPERIENCE%</p>
							</div> 
							<div class="job-desc-part">
								<h5>
								 {LBL_CATEGORY}
								</h5>
								<p class="gray-text">%JOB_CATEGORY%</p>
							</div> 
							
						</div>
					</div>
				</div>
			</div>
			<div class="similar-pro-dtls cf">
			<h3>
				{LBL_SIMILAR_JOBS}
				<a class="edit-link blue-color %ALL_SIMILAR_JOBS_LINK_VISIBLE%" title="{LBL_VIEW_ALL}" href="%ALL_SIMILAR_JOBS_LINK%">{LBL_VIEW_ALL} <i class="fa fa-mail-forward"></i></a>
			</h3>
				<div class="similar-carousel owl-carousel owl-theme fade fadeIn">
					<?php echo $this->similar_jobs; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>



<!-- Modal -->
<div class="modal fade" id="createjobs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
        <h4 class="modal-title" id="myModalLabel">{LBL_REACH_QUALITY_CANDIDATE}</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo SITE_URL; ?>create-new-job" class="create-form" name="create_job_form" id="create_job_form" method="post">
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_JOB_TITLE} <sup>*</sup></label>
                    <input type="text" class="" id="job_title" name="job_title" placeholder="{LBL_JOB_TITLE}" />
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_LAST_DATE_APPLICATION} <sup>*</sup></label>
                    <input type="text" class="date-picker" id="last_date_of_application" name="last_date_of_application" placeholder="{LBL_LAST_DATE_APPLICATION}" readonly/>

                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_COMPANY_NAME} <sup>*</sup></label>
                    <select name="company_name_id" id="company_name_id" class="selectpicker show-tick">
                        <option value="">{LBL_COMPANY_NAME}</option>
                        %COMPANY_NAME_OPTIONS%
                    </select>
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_CATEGORY} <sup>*</sup></label>
                    <select name="category_id" id="category_id" class="selectpicker show-tick">
                        <option value="">{LBL_CATEGORY}</option>
                        %CATEGORY_OPTIONS%
                    </select>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                    <label>{LBL_LOCATIONS} <sup>*</sup></label>
                    <select id="job_location" name="job_location" class="selectpicker show-tick bootstrap-dropdowns border-field" data-error-placement="inline">                    
                        <option value="">{LBL_LOCATIONS}</option>                    
                    </select>
                </div>
            </div>
             <input type="hidden"  id="formatted_address" name="formatted_address" val="" />
                 <input type="hidden"  id="address1" name="address1" val="" />
                 <input type="hidden"  id="address2" name="address2" val="" />
                 <input type="hidden"  id="country" name="country" val="" />
                 <input type="hidden"  id="state" name="state" val="" />
                 <input type="hidden"  id="city1" name="city1" val="" />
                 <input type="hidden"  id="city2" name="city2" val="" />
                 <input type="hidden"  id="postal_code" name="postal_code" val="" />
                 <input type="hidden"  id="latitude" name="latitude" val="" />
                 <input type="hidden"  id="longitude" name="longitude" val="" />
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 ">
                    <button type="submit" class="blue-btn" name="create_job" id="create_job">
                        {LBL_START_JOB_POST}
                    </button>
                    <button type="button" class="outer-red-btn" data-dismiss="modal">{LBL_COM_DET_CLOSE}</button>
                </div>
            </div>

                <div class="form-group text-center"></div>
            </form>
      </div>
      
    </div>
  </div>
</div>

<!-- Direct apply modal-->
<div class="modal fade" id="directApplyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
        <h4 class="modal-title" id="myModalLabel">{LBL_DIRECT_APPLY_FORM}</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo SITE_URL; ?>saveDirectJobApplication" class="create-form" name="direct_apply_form" id="direct_apply_form" method="post" enctype="multipart/formdata">
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_SELECT_RESUME} <sup>*</sup></label>
                    <input type="file" class="" id="user_resume" name="user_resume" accept="application/pdf,application/doc"/>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 ">
                    <input type="hidden" name="job_id" id="job_id" value="%ENCRYPTED_JOB_ID%">
                	<input type="hidden" name="action" id="action" value="saveDirectJobApplication">
                    <button type="submit" class="blue-btn" name="direct_apply" id="direct_apply" data-value="%ENCRYPTED_JOB_ID%">
                        {LBL_DIRECT_APPLY}
                    </button>
                    <button type="button" class="outer-red-btn" data-dismiss="modal">{LBL_COM_DET_CLOSE}</button>
                </div>
            </div>
            <div class="form-group text-center"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$(document).on('click', '.job_apply', function() {
		var job_btn = $(this);
		job_id = $(this).data('value');

		$.ajax({
			type: 'POST',
			url: "<?php echo SITE_URL; ?>saveJobApplication",
			data: {
				job_id: job_id,
				action: 'saveJobApplication',
				sess_user_id: '<?php echo $_SESSION['user_id']; ?>',
			},
			beforeSend: function() {
				addOverlay();
			},
			complete: function() {
				removeOverlay();
			},
			dataType: 'json',
			success: function(data) {
				if (data.status == 'true') {
					//alert($(this).data('value'));
					if(data.recommanded == 'y'){    
					   	toastr['success'](data.msg);
						job_btn.addClass('remove_from_job_apply');
						job_btn.html("{LBL_WITHDRAW}");
						job_btn.removeClass('job_apply');
						$('#direct_job_apply').addClass('hide');
						$(".no_of_applicants").html(data.no_of_applicants);
					}else{
						window.open(data.url, "_blank");
					}
					
				} else {
					toastr['error'](data.msg);
				}
			}
		});
	});

	$(document).on('click', '.remove_from_job_apply', function() {
		var job_btn = $(this);
		job_id = $(this).data('value');

		var bootBoxCallback = function(result) {
			if(result){
				$.ajax({
					type: 'POST',
					url: "<?php echo SITE_URL; ?>removeJobApplication",
					data: {
						job_id: job_id,
						action: 'removeJobApplication'
					},
					beforeSend: function() {
						addOverlay();
					},
					complete: function() {
						removeOverlay();
					},
					dataType: 'json',
					success: function(data) {
						console.log(data);
						if (data.status) {
							toastr['success'](data.msg);

							job_btn.addClass('job_apply');
							job_btn.html("{LBL_APPLY}");

							// job_btn.addClass('job_apply');
							// job_btn.html("{LBL_APPLY}");
							//$('#direct_job_apply').addClass('direct_job_apply ');
							//$('#direct_job_apply').html('direct_job_apply ');
							job_btn.removeClass('remove_from_job_apply');
							$(".no_of_applicants").html(data.no_of_applicants);
							//$('#direct_job_apply').removeClass('hide');
						} else {
							toastr['error'](data.msg);
						}
					}
				});
	}
	}            
	initBootBox("{ALERT_DELETE_APPLIED_JOBS}", lang.ARE_YOU_SURE_T0_REMOVE_APPLIED_JOB, bootBoxCallback);
	});

	$(document).on('click', '.job_save', function() {
		var job_btn = $(this);
		job_id = $(this).data('value');
		$.ajax({
			type: 'POST',
			url: "<?php echo SITE_URL; ?>saveJob",
			data: {
				job_id: job_id,
				action: 'saveJob'
			},
			beforeSend: function() {
				addOverlay();
			},
			complete: function() {
				removeOverlay();
			},
			dataType: 'json',
			success: function(data) {
				if (data.status) {
					toastr['success'](data.msg);

					job_btn.addClass('remove_from_job_save');
					job_btn.html("{LBL_SAVED}");
					job_btn.removeClass('job_save');


				} else {
					toastr['error'](data.msg);
				}
			}
		});   
	});

	$(document).on('click', '.remove_from_job_save', function() {
		var job_btn = $(this);
		job_id = $(this).data('value');

		var bootBoxCallback = function(result) {
			if(result) {
				$.ajax({
					type: 'POST',
					url: "<?php echo SITE_URL; ?>removeSavedJob",
					data: {
						job_id: job_id,
						action: 'removeSavedJob'
					},
					beforeSend: function() {
						addOverlay();
					},
					complete: function() {
						removeOverlay();
					},
					dataType: 'json',
					success: function(data) {
						if (data.status) {
							toastr['success'](data.msg);
							job_btn.addClass('job_save');

							job_btn.html("{LBL_SAVE}");
							job_btn.removeClass('remove_from_job_save');


						} else {
							toastr['error'](data.msg);
						}
					}
				});
			}
		} 

		initBootBox("{ALERT_DELETE_SAVED_JOB}", "{ALERT_ARE_YOU_SURE}", bootBoxCallback);
	});
	});

	$(document).on('click', '#share_news_feed', function() {
		job_id = $(this).data('value');
		var bootBoxCallback = function(result) {
			if(result){ 
				$.ajax({
					type: 'POST',
					url: "<?php echo SITE_URL; ?>shareNewsFeed",
					data: {
						job_id: job_id,
						action: 'shareNewsFeed'
					},
					beforeSend: function() {
						addOverlay();
					},
					complete: function() {
						removeOverlay();
					},
					dataType: 'json',
					success: function(data) {
						if (data.status) {
							//toastr['success'](data.msg);

						} else {
							toastr['error'](data.msg);
						}
					}
				});
			}
		}

		initBootBoxForSharing("{LBL_SHARE_JOB}", "{ALERT_SHARE_JOB_NEWS_FEED}", bootBoxCallback);
	});

	function initBootBoxForSharing(title, message, callbackFn) {
		bootbox.confirm({
			title: title,
			message: message,
			reorder: true,
			buttons: {
				cancel: {
					label: 'Cancel',
					className: 'outer-blue-btn '
				},
				confirm: {
					label: 'Share',
					className: 'blue-btn'
				}               
			},
			callback: callbackFn
		});
	}

	$('.similar-carousel').owlCarousel({
		loop:false,
		margin:10,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			800:{
				items:3
			},
			1000:{
				items:4
			}
		},
		onInitialized: data_hide,

	});
	 function data_hide(event) {
        var totalItems = $('.similar-carousel').find('.owl-item').length;
        if(totalItems<=1){
            $('.similar-carousel').find(".owl-controls").attr("class","hidden");
        }   
    }
    $("#direct_apply_form").validate({
        ignore: [],
        rules: {
            user_resume: {
                required: true,
               // extension: "pdf|docx|doc"
            }
        },
        messages: {
            user_resume: {
                required: lang.LBL_SELECT_USER_RESUME,
            //    extension:"Please upload valid file type"
            }
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
    
    $("#direct_apply_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            console.log(obj);
            if (obj.status == 'true') {
                if(obj.recommanded == 'y'){
                    toastr['success'](obj.msg);
					$('#direct_job_apply').addClass('remove_from_job_apply');
					$('#direct_job_apply').html("{LBL_WITHDRAW}");
					$('#direct_job_apply').removeClass('job_apply');
					$(".no_of_applicants").html(obj.no_of_applicants);
				// 	$('#job_apply').remove();
				// 	$('#directApplyModal').hide();
					window.location.href = '' + obj.url + '';
                }else{
                    window.location.href = '' + obj.url + '';
                }
               // window.location.href = '' + obj.url + '';
                //toastr["success"](obj.msg);
            }else{
                toastr["error"](obj.msg);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
 //    $(document).on('click', '#direct_apply', function(e){
 //    	var data1 = new FormData();
	// 	jQuery.each(jQuery('#user_resume')[0].files, function(i, file) {
	// 	    data1.append('file-'+i, file);
	// 	});

 //    	e.preventDefault();
	// 	var job_btn = $(this);
	// 	job_id = $(this).data('value');
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: "<?php echo SITE_URL; ?>saveDirectJobApplication",
	// 		data: $('#direct_apply_form').serialize(),
	// 		beforeSend: function() {
	// 			addOverlay();
	// 		},
	// 		complete: function() {
	// 			removeOverlay();
	// 		},
	// 		dataType: 'json',
	// 		success: function(data) {
	// 			console.log(data);
	// 			if (data.status == 'true') {

	// 				//alert($(this).data('value'));
	// 				// if(data.recommanded == 'y'){    
	// 				//    	toastr['success'](data.msg);
	// 				// 	job_btn.addClass('remove_from_job_apply');
	// 				// 	job_btn.html("{LBL_WITHDRAW}");
	// 				// 	job_btn.removeClass('job_apply');
	// 				// 	$(".no_of_applicants").html(data.no_of_applicants);
	// 				// }else{
	// 				// 	window.open(data.url, "_blank");
	// 				// }
					
	// 			} else {
	// 				toastr['error'](data.msg);
	// 			}
	// 		}
	// 	});
	// });
</script>
<script type="text/javascript">
    $(".date-picker").datepicker({
        minDate: 0,
        autoclose: true,
        dateFormat: "M d, yy",
        language: "fr"
    });

    $(document).on('change','#company_name_id',function(){
        var select_company_id = $(this).val();
        if(select_company_id != ""){
            $.ajax({
                url: "<?php echo SITE_URL; ?>getCompanyLocations",
                type: "POST",
                dataType: "json",
                data: {
                    action: 'getCompanyLocations',
                    company_id: select_company_id
                },
                success: function (data) {
                    if(data == ''){
                        bootbox.alert({
                            title: 'Alert',
                            message: 'Company details are incomplete',
                            reorder: true,
                            buttons:{ok:{label:'OK',className:'btn blue-btn cancel-btn '}},
                        });
                        return false;
                    }
                    $("#job_location").html(data);
                   // $("#job_location").prepend('<option value="">Locations*</option>');
                    $('.bootstrap-dropdowns').selectpicker('refresh')
                    
                }
            });
        }        
    });
    
    $("#create_job_form").validate({
        ignore: [],
        rules: {
            company_name_id: {
                required: true
            },
            category_id: {
                required: true
            },
            job_title: {
                required: true,
                onlyChar: true
            },
            job_location: {
                required: true
            },
            last_date_of_application: {
              required: true  
            }
        },
        messages: {
            company_name_id: {
                required: lang.LBL_SELECT_COMPANY_NAME
            },
            category_id: {
                required: lang.LBL_SELECT_CATEGORY
            },
            job_title: {
                required: lang.ERROR_ENTER_JOB_TITLE
            },
            job_location: {
                required: lang.ERROR_ENTER_JOB_LOCATION
            },
            last_date_of_application: {
                required: lang.ERROR_LAST_DATE_APPLICATION
            }
        },
        highlight: function(element) {
            //$(element).addClass('has-error');
            
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
    
    $("#create_job_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
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
    
    $(document).on('click','#direct_job_apply',function(){
        $('#directApplyModal').modal('show');
    });
    $('#directApplyModal').on('hidden.bs.modal', function() {
        var form_var = $('#direct_apply_form');
        form_var.validate().resetForm();
        form_var.find('.error').removeClass('error');
    });
    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('job_location')),
                {types: ['geocode']}
        );
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress(){
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert(lang.ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY);
            return;
        } else {
            address1 = '';
            address2 = '';
            city1 = '';
            city2 = '';
            state = '';
            country = '';
            postal_code = '';

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
                    toastr['error'](lang.ERROR_ALREADY_ADDED_LOCATION);
                    return false;
                }
            });
            
            if(!proceed_to_add_location) {
                //$("#job_location").val();
                return true;
            }
            
            $.each(arrAddress, function(i, address_component) {
                if (address_component.types[0] == "route") {
                    address1 = address_component.long_name;
                }
                if (address_component.types[0] == "sublocality") {
                    address2 = address_component.long_name;
                }

                if (address_component.types[0] == "locality") {
                    //alert("city1:"+address_component.long_name);
                    city1 = address_component.long_name;
                }
                if (address_component.types[0] == "administrative_area_level_2") {
                    city2 = address_component.long_name;
                }

                if (address_component.types[0] == "administrative_area_level_1") {
                    state = address_component.long_name;
                }
                if (address_component.types[0] == "country") {
                    country = address_component.long_name;
                }
                if (address_component.types[0] == "postal_code") {
                    postal_code = address_component.long_name;
                }
            });
            
            no_of_locations = $(".map-box").length;
            if(no_of_locations > 0) {
                is_hq = "n";
            } else {
                is_hq = "y";
            }

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
</script>