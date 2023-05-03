<div class="toggle-srch">
 <a href="#" class="cart-buttom" title="{LBL_SEARCH}"><i class="icon-srch"></i></a>
	<div class="navbar-form navbar-left custom-dropdown header-srch-bx">
	<form role="search" action="" method="get" name="header_search_form" id="header_search_form">
	      <div class="input-group-addon">
	        <div class="dropdown search-menu"> <span id="selected_entity_container" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="amount-menu" data-entity="%SELECTED_ENTITY_NAME%"><i id="search_selected_entity" class="fa %SELECTED_ENTITY_CLASS%"></i><i class="fa fa-caret-down"></i></span>
	          <ul class="dropdown-menu" aria-labelledby="dLabel">
	            <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_PEOPLE}" class="search-entity-selection" data-entity="users"><i class="fa fa-user"></i>{LBL_HEADER_PEOPLE}</a></li>
	            <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_COMPANY}" class="search-entity-selection" data-entity="companies"><i class="fa fa-building"></i>{LBL_HEADER_COMPANY}</a></li>
	          </ul>
	        </div>
	      </div>
	      <input type="text" id="keyword" name="keyword" placeholder="{LBL_HEADER_SEARCH_PEOPLE_JOBS_COMPANIES_AND_MORE}" autocomplete="off" value="%KEYWORD%"/>
	    <button type="submit" id="header_search_submit" name="header_search_submit" class="search-btn" title="{LBL_SEARCH}"><i class="icon-srch"></i></button>
	</form>
	</div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	  <ul class="nav navbar-nav navbar-right rgt-menu">
	    <li id="CCCCC" class="dropdown in-login-drodown">
	    	<a href="{SITE_URL}signin" title="{LBL_LOGIN_BUTTON_SIGNIN}" >{LBL_LOGIN_BUTTON_SIGNIN}</a>	    
	    </li>
    	<li><a href="%SITE_URL%" title="{LBL_SIGNUP}" >{LBL_SIGNUP}</a></li>
	  </ul>
	</div>
</div>
