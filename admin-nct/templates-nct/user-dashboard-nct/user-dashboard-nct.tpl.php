<div class="tabbable tabs-left" id="rootwizard">
    <ul class="left-tabs">
<li class="profile-pic">
            <p class="cover-pic"><img src="%USER_COVER_PHOTO%" alt="cover photo"></p>   
            <span>
                %USERs_PROFILE_PICTURE_URL%
            </span>

        </li>
        <li class="%BASIC_INFORMATION_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#basic_information" class="view_form" title="Basic information">
                Basic information
            </a>
        </li>
        <li class="%COMPANY_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#company" class="view_form" title="Company">
                Business
            </a>
        </li>
        <li class="%JOB_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#job" class="view_form" title="Job">
                Job
            </a>
        </li>
        <li class="%GROUPS_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#groups" class="view_form" title="Groups">
                Groups
            </a>
        </li>
        <li class="%CONNECTIONS_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#connections" class="view_form" title="Connections" data-url="%CONNECTIONS_URL%">
                Connections
            </a>
        </li>
        <li class="%MEMBERSHIP_PLANS_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#membership_plans" class="view_form" title="Membership Plans" data-url="%MEMBERSHIP_PLANS_URL%">
                Membership Plans
            </a>
        </li>
        <li class="%ADHOC_INMAILS_ACTIVE_CLASS%">
            <a data-toggle="tab" href="#adhoc_inmails" class="view_form" title="Adhoc Inmails" data-url="%ADHOC_INMAILS_URL%">
                Adhoc Inmails
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="bottom-border">
            <div class="row">
                <div class="col-sm-12">
                    <span class="label-name">
                        <h2>%USER_NAME%</h2>
                    </span>
                </div>
                <div class="col-sm-12">
                    <span class="label-name">
                        <h4><i class="fa fa-envelope"></i> %EMAIL_ADDRESS%</h4>
                    </span>
                </div>
                <!-- <div class="col-sm-12">
                    <span class="label-name">
                        <h4><i class="fa fa-user fa-user-admin"></i> %USER_HEADLINE%</h4>
                    </span>
                </div> -->
                <div class="col-sm-12">
                    <span class="label-name">
                         <h4><i class="fa fa-map-marker fa-location-admin"></i> %USER_LOCATION%</h4>
                    </span>
                </div>
                <div class="col-sm-12">
                    <span class="label-name">
                         %USER_LANGUAGES%
                    </span>
                </div>
                <div class="col-sm-12">
                    <span class="label-name">
                         %USER_SKILLS%
                    </span>
                </div>
                
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="basic_information" class="tab-pane %BASIC_INFORMATION_ACTIVE_CLASS%">
            <!--Tabs Start-->
            <ul role="tablist" class="nav nav-tabs inner-tabs-nav">
                <!-- <li class="%EXPERIENCE_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="experience" id="view_form_personal_first" href="#experience" class="view_form" title="Experience" data-url="%EXPERIENCE_URL%">
                        Experience
                    </a>
                </li> -->
                <li class="%EDUACATION_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="education" href="#education" class="view_form" title="Education" data-url="%EDUCATION_URL%">
                        Education
                    </a>
                </li>
                <!--<li class="%LANGUAGES_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="languages" href="#languages" class="view_form" title="Languages" data-url="%LANGUAGES_URL%">
                        Languages
                    </a>
                </li>
                <li class="%SKILLS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="skills" href="#skills" class="view_form" title="Skills" data-url="%SKILLS_URL%">
                        Skills
                    </a>
                </li>-->

            </ul>
            <!-- Tab panes -->
            <div class="tab-content inner-tab">

                <div id="experience" class="tab-pane %EXPERIENCE_ACTIVE_CLASS%" role="tabpanel">
                    %EXPERIENCE_CONTENT%
                </div>

                <div id="education" class="tab-pane %EDUACATION_ACTIVE_CLASS%" role="tabpanel">
                    %EDUACATION_CONTENT%
                </div>

                <!--<div id="languages" class="tab-pane %LANGUAGES_ACTIVE_CLASS%" role="tabpanel">
                    %LANGUAGES_CONTENT%
                </div>

                <div id="skills" class="tab-pane %SKILLS_ACTIVE_CLASS%" role="tabpanel">
                    %SKILLS_CONTENT%
                </div>-->


            </div>
        </div>


        <div id="company" class="tab-pane %COMPANY_ACTIVE_CLASS%">
            <ul role="tablist" class="nav nav-tabs inner-tabs-nav">
                <li class="%MY_PAGES_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="my_pages" class="view_form" href="#my_pages" title="My Pages" data-url="%MY_PAGES_URL%">
                        My Pages
                    </a>
                </li>

                <li class="%FOLLOWING_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="following" class="view_form" href="#following" title="Following" data-url="%FOLLOWING_URL%">
                        Following
                    </a>
                </li>

            </ul>

            <div class="tab-content inner-tab">

                <div id="my_pages" class="tab-pane %MY_PAGES_ACTIVE_CLASS%" role="tabpanel">
                    %MY_PAGES_CONTENT%
                </div>

                <div id="following" class="tab-pane %FOLLOWING_ACTIVE_CLASS%" role="tabpanel">
                    %FOLLOWING_CONTENT%
                </div>

            </div>

        </div>

        <div id="job" class="tab-pane %JOB_ACTIVE_CLASS%">
            <ul role="tablist" class="nav nav-tabs inner-tabs-nav">
                <li class="%MY_JOBS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="my_jobs" class="view_form" href="#my_jobs" title="My Jobs" data-url="%MY_JOBS_URL%">
                        My Jobs
                    </a>
                </li>

                <li class="%SAVED_JOBS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="applied_jobs" class="view_form" href="#saved_jobs" title="Saved Jobs" data-url="%SAVED_JOBS_URL%">
                        Saved Jobs
                    </a>
                </li>

                <li class="%APPLIED_JOBS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="applied_jobs" class="view_form" href="#applied_jobs" title="Applied Jobs" data-url="%APPLIED_JOBS_URL%">
                        Applied Jobs
                    </a>
                </li>

            </ul>

            <div class="tab-content inner-tab">

                <div id="my_jobs" class="tab-pane %MY_JOBS_ACTIVE_CLASS%" role="tabpanel">
                    %MY_JOBS_CONTENT%
                </div>

                <div id="saved_jobs" class="tab-pane %SAVED_JOBS_ACTIVE_CLASS%" role="tabpanel">
                    %SAVED_JOBS_CONTENT%
                </div>

                <div id="applied_jobs" class="tab-pane %APPLIED_JOBS_ACTIVE_CLASS%" role="tabpanel">
                    %APPLIED_JOBS_CONTENT%
                </div>

            </div>
        </div>

        <div id="groups" class="tab-pane %GROUPS_ACTIVE_CLASS%">
            <ul role="tablist" class="nav nav-tabs inner-tabs-nav">
                <li class="%MY_GROUPS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="my_groups" class="view_form" href="#my_groups" title="My Groups" data-url="%MY_GROUPS_URL%">
                        My Groups
                    </a>
                </li>

                <li class="%JOINED_GROUPS_ACTIVE_CLASS%" role="presentation">
                    <a data-toggle="tab" role="tab" aria-controls="joined_groups" class="view_form" href="#joined_groups" title="Joined Groups" data-url="%JOINED_GROUPS_URL%">
                        Joined Groups
                    </a>
                </li>

            </ul>

            <div class="tab-content inner-tab">

                <div id="my_groups" class="tab-pane %MY_GROUPS_ACTIVE_CLASS%" role="tabpanel">
                    %MY_GROUPS_CONTENT%
                </div>

                <div id="joined_groups" class="tab-pane %JOINED_GROUPS_ACTIVE_CLASS%" role="tabpanel">
                    %JOINED_GROUPS_CONTENT%
                </div>
            </div>

        </div>

        <div id="connections" class="tab-pane %CONNECTIONS_ACTIVE_CLASS%">
            %CONNECTIONS_CONTENT%
        </div>

        <div id="membership_plans" class="tab-pane %MEMBERSHIP_PLANS_ACTIVE_CLASS%">
            %MEMBERSHIP_PLANS_CONTENT%
        </div>

        <div id="adhoc_inmails" class="tab-pane %ADHOC_INMAILS_ACTIVE_CLASS%">
            %ADHOC_INMAILS_CONTENT%
        </div>

    </div>
</div>

<script type="text/javascript">
    var urlParam = {};
    urlParam['user_id'] = %USER_ID%;
    
    $(document).on('click', '.view_form', function () {
        if($(this).data("url")) {
            current_tab = $(this);
            current_tab_name = $(this).attr("href").replace('#', '');
            var ajax_url = current_tab.data("url");
        } else {
            //console.log( $("#"+$(this).attr("href").replace('#', '')).children("ul.inner-tabs-nav").find("li.active a") );
            current_tab = $($(this).attr("href")).children("ul.inner-tabs-nav").find("li.active a");
            current_tab_name = current_tab.attr("href").replace('#', '');
            var ajax_url = current_tab.data("url");
        }
        current_tab_data_container = $(current_tab.attr("href"));
        //console.log(current_tab_data_container);
        //return false;
        
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: ajax_url,
            data: {
                
            },
            beforeSend: function () {
                current_tab_data_container.html("");
                addOverlay();
            },
            complete: function () {
                removeOverlay();
            },
            success: function (data) {
                if(data.status) {
                    current_tab_data_container.html(data.html);
                    
                    window.history.pushState("", "Title", current_tab_name);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
        return false;
    });

    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");

        var div_id = $(this).parents(".tab-pane.active").attr("id");
        
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>get_"+div_id+"_ajax",
            data: {
                page: page,
                user_id: <?php echo $this->user_id; ?>,
                action: "get_"+div_id
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) { 
                $("#"+div_id).html(data);

                if(page > 1) {
                    //console.log(1);
                    //window.history.pushState("", "Title",  "?page=" + page);    
                } else {
                    //console.log(2);
                    //window.history.pushState("", "Title",  "?page=" + page);    
                }
            }
        });
        
    });

    $(document).on("click", ".company_edit", function() {

            company_id = $(this).data("value");
            url_to_redirect = SITE_ADM_URL_COMPANIES;
            if(company_id != '')  {
                url_to_redirect += "?company_id=" + company_id;
            }
            window.open(url_to_redirect, '_blank');
    });

    $(document).on("click", ".group_edit", function() {

            group_id = $(this).data("value");
            url_to_redirect = SITE_ADM_URL_GROUPS;
            if(group_id != '')  {
                url_to_redirect += "?group_id=" + group_id;
            }
            window.open(url_to_redirect, '_blank');
    });

    $(document).on("click", ".job_edit", function() {

            job_id = $(this).data("value");
            url_to_redirect = SITE_ADM_URL_JOBS;
            if(job_id != '')  {
                url_to_redirect += "?job_id=" + job_id;
            }
            window.open(url_to_redirect, '_blank');
    });
</script>
