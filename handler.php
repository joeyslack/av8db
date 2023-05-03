<?php 
	ini_set('allow_url_fopen',1);
	//print_r($_SERVER['REQUEST_URI']['path']);exit();
	session_start();
	
	define('DIR_URL', $_SERVER["DOCUMENT_ROOT"] . '/');

	function secParam($data=array()){
		return $data[2];
	}

	function thirdParam($data=array()){
		return $data[3];
	}

	function fourthParam($data=array()){
		return $data[4];
	}

	$path=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//	echo "string";print_r($path);
	switch (@$path) {

		case '':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'phpinfo.php':
			require 'phpinfo.php';
			break;
		case '/phpinfo.php':
			require 'phpinfo.php';
			break;

		case 'validate_captcha':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		case '/validate_captcha':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case 'dashboard':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/dashboard':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case 'dashboard/':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/dashboard/':
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'checkIfEmailExists':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		case '/checkIfEmailExists':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case 'submit-feedback':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		case '/submit-feedback':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		// case 'submit-contactus':
		// 	require 'modules-nct/home-nct/ajax.home-nct.php';
		// 	break;
		// case '/submit-contactus':
		// 	require 'modules-nct/home-nct/ajax.home-nct.php';
		// 	break;

		case 'home':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/home':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'login':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/login':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'signup':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/signup':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'signup/user/'.thirdParam(explode("/", $path)).'/profile/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/index.php';
			break;

		case '/signup/user/'.thirdParam(explode("/", $path)).'/profile/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/index.php';
			break;

		case 'social-signup-facebook':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case '/social-signup-facebook':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case 'social-signup-gplus':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case '/social-signup-gplus':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		
		case 'signin/google':
			require 'modules-nct/social-login-nct/social-login-nct.php';
			break;
		case '/signin/google':
			require 'modules-nct/social-login-nct/social-login-nct.php';
			break;

		case 'process-google-login':
			require 'modules-nct/social-login-nct/process-google-login-nct.php';
			break;
		case '/process-google-login':
			require 'modules-nct/social-login-nct/process-google-login-nct.php';
			break;

		case 'signin/linkedin':
			require 'includes-nct/login-with-linkedin/login.php';
			break;
		case '/signin/linkedin':
			require 'includes-nct/login-with-linkedin/login.php';
			break;

		case 'signin/email/'.thirdParam(explode("/", $path)).'/activation_key/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/verify_email_address.php';
			break;
		case '/signin/email/'.thirdParam(explode("/", $path)).'/activation_key/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/verify_email_address.php';
			break;

		case 'resend_verification_email':
			require 'modules-nct/home-nct/resend-verification-email-nct.php';
			break;
		case '/resend_verification_email':
			require 'modules-nct/home-nct/resend-verification-email-nct.php';
			break;

		case 'forgot_password':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/forgot_password':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'resetpassword':
			require 'modules-nct/reset-password-nct/index.php';
			break;
		case '/resetpassword':
			require 'modules-nct/reset-password-nct/index.php';
			break;
		
		case 'resetpassword/'.end(explode("/", $path)):
			require 'modules-nct/reset-password-nct/index.php';
			break;
		case '/resetpassword/'.end(explode("/", $path)):
			require 'modules-nct/reset-password-nct/index.php';
			break;

		case 'logout':
			require 'modules-nct/home-nct/logout-nct.php';
			break;
		case '/logout':
			require 'modules-nct/home-nct/logout-nct.php';
			break;

		case 'content/'.end(explode("/", $path)):
			require 'modules-nct/content-nct/index.php';
			break;
		case '/content/'.end(explode("/", $path)):
			require 'modules-nct/content-nct/index.php';
			break;

		// case 'contact_us':
		// 	require 'modules-nct/contact_us-nct/index.php';
		// 	break;
		// case '/contact_us':
		// 	require 'modules-nct/contact_us-nct/index.php';
		// 	break;

		// case 'contact-us':
		// 	require 'modules-nct/contact-us-nct/index.php';
		// 	break;
		// case '/contact-us':
		// 	require 'modules-nct/contact-us-nct/index.php';
		// 	break;

		case 'profile':
			require 'modules-nct/profile-nct/index.php';
			break;
		case '/profile':
			require 'modules-nct/profile-nct/index.php';
			break;

		case 'crop.php':
			require 'modules-nct/profile-nct/crop.php';
			break;
		case '/crop.php':
			require 'modules-nct/profile-nct/crop.php';
			break;

		 case 'croppie.php':
	      require 'modules-nct/profile-nct/croppie.php';
	      break;
	    case '/croppie.php':
	      require 'modules-nct/profile-nct/croppie.php';
	      break;
	    case 'croppie.html':
	      require 'modules-nct/profile-nct/croppie.html';
	      break;
	    case '/croppie.html':
	      require 'modules-nct/profile-nct/croppie.html';
	      break;

	    case 'modules-nct/profile-nct/croppie.php':
	      require 'modules-nct/profile-nct/croppie.php';
	      break;
	    case '/modules-nct/profile-nct/croppie.php':
	      require 'modules-nct/profile-nct/croppie.php';
	      break;
	    case 'modules-nct/profile-nct/croppie.html':
	      require 'modules-nct/profile-nct/croppie.html';
	      break;
	    case '/modules-nct/profile-nct/croppie.html':
	      require 'modules-nct/profile-nct/croppie.html';
	      break;

		case 'modules-nct/profile-nct/crop.php':
			require 'modules-nct/profile-nct/crop.php';
			break;
		case '/modules-nct/profile-nct/crop.php':
			require 'modules-nct/profile-nct/crop.php';
			break;

		case 'modules-nct/edit-company-nct/crop.php':
			require 'modules-nct/edit-company-nct/crop.php';
			break;
		case '/modules-nct/edit-company-nct/crop.php':
			require 'modules-nct/edit-company-nct/crop.php';
			break;

		case 'modules-nct/companies-nct/crop.php':
			require 'modules-nct/companies-nct/crop.php';
			break;
		case '/modules-nct/companies-nct/crop.php':
			require 'modules-nct/companies-nct/crop.php';
			break;

		case 'modules-nct/create-group-nct/crop.php':
			require 'modules-nct/create-group-nct/crop.php';
			break;
		case '/modules-nct/create-group-nct/crop.php':
			require 'modules-nct/create-group-nct/crop.php';
			break;

		case 'referral':
			require 'modules-nct/referrals-nct/index.php';
			break;
		case '/referral':
			require 'modules-nct/referrals-nct/index.php';
			break;
		case '/referral/':
			require 'modules-nct/referrals-nct/index.php';
			break;

		case 'update-profile-picture':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/update-profile-picture':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'uploadProfile':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/uploadProfile':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeConnection':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeConnection':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeFollowing':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeFollowing':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'follow_user':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/follow_user':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeLanguage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeLanguage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeLanguage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeLanguage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeSkill':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeSkill':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'deleteExperience':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/deleteExperience':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'deleteEducation':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/deleteEducation':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'removeProfileImage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/removeProfileImage':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'profile/'.end(explode("/", $path)):
			require 'modules-nct/profile-nct/index.php';
			break;
		case '/profile/'.end(explode("/", $path)):
			require 'modules-nct/profile-nct/index.php';
			break;

		case 'membership-plans':
			require 'modules-nct/membership-plans-nct/index.php';
			break;
		case '/membership-plans':
			require 'modules-nct/membership-plans-nct/index.php';
			break;

		case 'subscribe-plan/'.end(explode("/", $path)):
			require 'modules-nct/membership-plans-nct/subscribe-plan-nct.php';
			break;
		case '/subscribe-plan/'.end(explode("/", $path)):
			require 'modules-nct/membership-plans-nct/subscribe-plan-nct.php';
			break;

		case '/modules-nct/membership-plans-nct/subscribe-plan-nct.php':
			require 'modules-nct/membership-plans-nct/subscribe-plan-nct.php';
			break;
		case '/modules-nct/membership-plans-nct/subscribe-plan-nct.php/':
			require 'modules-nct/membership-plans-nct/subscribe-plan-nct.php';
			break;
		case 'modules-nct/membership-plans-nct/subscribe-plan-nct.php/':
			require 'modules-nct/membership-plans-nct/subscribe-plan-nct.php';
			break;

		case 'pay-for-fj/plan/'.thirdParam(explode("/", $path)).'/job/'.end(explode("/", $path)):
			require 'modules-nct/membership-plans-nct/pay-for-fj-nct.php';
			break;
		case '/pay-for-fj/plan/'.thirdParam(explode("/", $path)).'/job/'.end(explode("/", $path)):
			require 'modules-nct/membership-plans-nct/pay-for-fj-nct.php';
			break;

		case 'purchase-adhoc-inmails':
			require 'modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php';
			break;
		case '/purchase-adhoc-inmails':
			require 'modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php';
			break;

		case 'modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php':
			require 'modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php';
			break;
		case '/modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php':
			require 'modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php';
			break;

		case 'payment-summary/'.end(explode("/", $path)):
			require 'modules-nct/payment-summary-nct/index.php';
			break;
		case '/payment-summary/'.end(explode("/", $path)):
			require 'modules-nct/payment-summary-nct/index.php';
			break;

		/*case 'checkout/txn_id/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;
		case '/checkout/txn_id/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;*/

		case 'checkout/txn_id/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;
		case '/checkout/txn_id/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;

		case 'payment_successful':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;
		case '/payment_successful':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;

		case 'transaction_cancelled':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;
		case '/transaction_cancelled':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;

		case 'notify':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;
		case '/notify':
			require 'modules-nct/checkout-nct/ipn-notification-listener-nct.php';
			break;

		case 'getCompany':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		case '/getCompany':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case 'getJobLocations':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;
		case '/getJobLocations':
			require 'modules-nct/home-nct/ajax.home-nct.php';
			break;

		case 'getExperienceForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getExperienceForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-experience':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-experience':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'edit_user_detail':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/edit_user_detail':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getCompanySuggestions':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getCompanySuggestions':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getCompanyLocations':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getCompanyLocations':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getEducationForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getEducationForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getUserDetailForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getUserDetailForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-education':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-education':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-skill':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-skill':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-language':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-language':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getSkillForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getSkillForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getLanguageForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getLanguageForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getSkills':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getSkills':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getLanguages':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getLanguages':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getEducation':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getEducation':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getLicensesEndorsementForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getLicensesEndorsementForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-licenses':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-licenses':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getLicenseSuggestions':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getLicenseSuggestions':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'deleteLicense':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/deleteLicense':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getAirportForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getAirportForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'add-edit-airport':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/add-edit-airport':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getAirports':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getAirports':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getAirports1':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getAirports1':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'requestForAirportAddition':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/requestForAirportAddition':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getAirportSuggestions':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/getAirportSuggestions':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'getUserAddedReviews':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getUserAddedReviews':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'getFerryPilotReviews':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getFerryPilotReviews':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'searchForReferrals':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/searchForReferrals':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'getHomeAirportForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getHomeAirportForm':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'deleteAirport':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/deleteAirport':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'payFerryPilotAmount':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/payFerryPilotAmount':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'searchInviteUser':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/searchInviteUser':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'sendReferralsRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/sendReferralsRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'getReferralReviewModal':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/getReferralReviewModal':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'rejectReferralRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/rejectReferralRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'approvepublishreferral':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/approvepublishreferral':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'resendReferralsRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;
		case '/resendReferralsRequest':
			require 'modules-nct/referrals-nct/ajax.referrals-nct.php';
			break;

		case 'verifyLicense':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/verifyLicense':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'inviteUserOnPlatform':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/inviteUserOnPlatform':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getInstituteSuggestion':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getInstituteSuggestion':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getLicenseList':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/getLicenseList':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'reportCompanyReviews':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/reportCompanyReviews':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'reportFerryPilotReviews':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/reportFerryPilotReviews':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'getSelectedLicenseName':
			require 'modules-nct/edit-job/ajax.edit-job.php';
			break;
		case '/getSelectedLicenseName':
			require 'modules-nct/edit-job/ajax.edit-job.php';
			break;

		case 'addLicense':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/addLicense':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'deleteSelectedLicense':
			require 'modules-nct/edit-job/ajax.edit-job.php';
			break;
		case '/deleteSelectedLicense':
			require 'modules-nct/edit-job/ajax.edit-job.php';
			break;

		case 'searchConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/searchConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'searchFollowing':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/searchFollowing':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'searchFollower':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/searchFollower':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'searchPeopleyoumayknow':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/searchPeopleyoumayknow':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'recent-updates':
			require 'modules-nct/my-updates-nct/index.php';
			break;
		case '/recent-updates':
			require 'modules-nct/my-updates-nct/index.php';
			break;

		case 'ajax/recent-updates':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/recent-updates':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'ajax/recent-updates/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/recent-updates/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'published-posts':
			require 'modules-nct/my-updates-nct/index.php';
			break;
		case '/published-posts':
			require 'modules-nct/my-updates-nct/index.php';
			break;

		case 'ajax/published-posts':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/published-posts':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'ajax/published-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/published-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'saved-posts':
			require 'modules-nct/my-updates-nct/index.php';
			break;
		case '/saved-posts':
			require 'modules-nct/my-updates-nct/index.php';
			break;

		case 'ajax/saved-posts':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/saved-posts':
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'ajax/saved-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;
		case '/ajax/saved-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/my-updates-nct/ajax.my-updates-nct.php';
			break;

		case 'like-unlike':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/like-unlike':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'post-comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/post-comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getLikers/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getLikers/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getSharedBy/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getSharedBy/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getComments/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getComments/feed_id/'.thirdParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getVisitors/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getVisitors/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'post-an-update':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/post-an-update':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'share-an-update':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/share-an-update':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'edit-feed/'.end(explode("/", $path)):
			require 'modules-nct/edit-feed-nct/index.php';
			break;
		case '/edit-feed/'.end(explode("/", $path)):
			require 'modules-nct/edit-feed-nct/index.php';
			break;

		case 'editfeed':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;
		case '/editfeed':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;

		case 'remove_edit_image':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;
		case '/remove_edit_image':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;

		case 'remove_edit_video':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;
		case '/remove_edit_video':
			require 'modules-nct/edit-feed-nct/ajax.edit-feed-nct.php';
			break;

		case 'followCompany':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/followCompany':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'addConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/addConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'approveConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/approveConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'rejectConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/rejectConnection':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'common-connection/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/common-connection/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'connection/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/connection/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'following/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/following/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'follower/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/follower/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'people-you-may-know':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/people-you-may-know':
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'view-all-notification':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/view-all-notification':
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'view-all-notification/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/view-all-notification/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'feed/'.end(explode("/", $path)):
			require 'modules-nct/feed-nct/index.php';
			break;
		case '/feed/'.end(explode("/", $path)):
			require 'modules-nct/feed-nct/index.php';
			break;

		case 'invitation':
			require 'modules-nct/dashboard-nct/index.php';
			break;
		case '/invitation':
			require 'modules-nct/dashboard-nct/index.php';
			break;

		case 'getCommonConnectionAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getCommonConnectionAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getConnectionAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getConnectionAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getFollowingAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getFollowingAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getFollowerAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getFollowerAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getNotificationAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getNotificationAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getPeopleYouKnowAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getPeopleYouKnowAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getPendingInvitationsAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getPendingInvitationsAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getSentInvitationsAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getSentInvitationsAjax':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'company/my-companies':
			require 'modules-nct/companies-nct/index.php';
			break;
		case '/company/my-companies':
			require 'modules-nct/companies-nct/index.php';
			break;
		case '/company/my-companies/':
			require 'modules-nct/companies-nct/index.php';
			break;

		case 'my-companies':
			require 'modules-nct/companies-nct/index.php';
			break;
		case '/my-companies/':
			require 'modules-nct/companies-nct/index.php';
			break;

		case 'company/verifyCompany/'.end(explode("/", $path)):
			require 'modules-nct/companies-nct/index.php';
			break;
		case '/company/verifyCompany/'.end(explode("/", $path)):
			require 'modules-nct/companies-nct/index.php';
			break;

		case 'company/following-companies':
			require 'modules-nct/companies-nct/index.php';
			break;
		case '/company/following-companies':
			require 'modules-nct/companies-nct/index.php';
			break;

		case 'getCompanies':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/getCompanies':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'unfollowCompany':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/unfollowCompany':
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'create-company':
			require 'modules-nct/create-company-nct/index.php';
			break;
		case '/create-company':
			require 'modules-nct/create-company-nct/index.php';
			break;

		case 'edit-company/'.end(explode("/", $path)):
			require 'modules-nct/edit-company-nct/index.php';
			break;
		case '/edit-company/'.end(explode("/", $path)):
			require 'modules-nct/edit-company-nct/index.php';
			break;

		case 'update-company-details/'.end(explode("/", $path)):
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;
		case '/update-company-details/'.end(explode("/", $path)):
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;

		case 'addCompanyLocation':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;
		case '/addCompanyLocation':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;

		case 'getConnections':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;
		case '/getConnections':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;

		case 'getConnectionBox':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;
		case '/getConnectionBox':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;

		case 'deleteCompany':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;
		case '/deleteCompany':
			require 'modules-nct/edit-company-nct/ajax.edit-company-nct.php';
			break;

		case 'company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/index.php';
			break;
		case '/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/index.php';
			break;

		case 'rate_review':
			require 'modules-nct/company-detail-nct/index.php';
			break;

		case '/rate_review':
			require 'modules-nct/company-detail-nct/index.php';
			break;

		case 'remove_company_follower':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/remove_company_follower':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'jobs/my-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;
		case '/jobs/my-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;

		case 'jobs/applied-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;
		case '/jobs/applied-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;

		case 'jobs/saved-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;
		case '/jobs/saved-jobs':
			require 'modules-nct/jobs-nct/index.php';
			break;

		case 'getJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/getJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'removeJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/removeJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'deleteJob':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/deleteJob':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'withdrawAppliedJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/withdrawAppliedJobs':
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'job/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;
		case '/job/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;

		case 'create-job-form':
			require 'modules-nct/create-job-nct/index.php';
			break;
		case '/create-job-form':
			require 'modules-nct/create-job-nct/index.php';
			break;

		case 'create-new-job':
			require 'modules-nct/create-job-nct/ajax.create-job-nct.php';
			break;
		case '/create-new-job':
			require 'modules-nct/create-job-nct/ajax.create-job-nct.php';
			break;

		case 'edit-job-form/'.end(explode("/", $path)):
			require 'modules-nct/edit-job-nct/index.php';
			break;
		case '/edit-job-form/'.end(explode("/", $path)):
			require 'modules-nct/edit-job-nct/index.php';
			break;

		case 'edit-job':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/edit-job':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'addJobLocation':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/addJobLocation':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'saveJobData':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/saveJobData':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'similar-jobs/job/'.thirdParam(explode("/", $path)).'/industry/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;
		case '/similar-jobs/job/'.thirdParam(explode("/", $path)).'/industry/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;

		case 'saveJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/saveJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'saveDirectJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/saveDirectJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'removeJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/removeJobApplication':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'saveJob':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/saveJob':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'removeSavedJob':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/removeSavedJob':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'getSimilarJobs':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/getSimilarJobs':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'getJobApplicants':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/getJobApplicants':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'job-applicants/job/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;
		case '/job-applicants/job/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/index.php';
			break;

		case 'getSkillsForEditJob':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/getSkillsForEditJob':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'getLicensesEndorsements':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/getLicensesEndorsements':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'getDegreesForSuggestion':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;
		case '/getDegreesForSuggestion':
			require 'modules-nct/edit-job-nct/ajax.edit-job-nct.php';
			break;

		case 'shareNewsFeed':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/shareNewsFeed':
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'shareCompanyNewsFeed':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/shareCompanyNewsFeed':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'groups/my-groups/'.thirdParam(explode("/", $path)):
			require 'modules-nct/groups-nct/index.php';
			break;
		case '/groups/my-groups/'.thirdParam(explode("/", $path)):
			require 'modules-nct/groups-nct/index.php';
			break;

		case 'groups/my-groups':
			require 'modules-nct/groups-nct/index.php';
			break;
		case '/groups/my-groups':
			require 'modules-nct/groups-nct/index.php';
			break;

		case 'groups/joined-groups':
			require 'modules-nct/groups-nct/index.php';
			break;
		case '/groups/joined-groups':
			require 'modules-nct/groups-nct/index.php';
			break;

		case 'getGroups':
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;
		case '/getGroups':
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;

		case 'removeJoinedGroup':
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;
		case '/removeJoinedGroup':
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;

		case 'create-group-form/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;
		case '/create-group-form/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;

		case 'create-group-form/'.secParam(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;
		case '/create-group-form/'.secParam(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;

		case 'getConnectionsForGropus':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/getConnectionsForGropus':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'deleteMember':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/deleteMember':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'getConnectionBoxForGropus':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/getConnectionBoxForGropus':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'create-new-group':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/create-new-group':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'edit-group-form/'.secParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;
		case '/edit-group-form/'.secParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;

		case 'edit-group-form/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;
		case '/edit-group-form/'.end(explode("/", $path)):
			require 'modules-nct/create-group-nct/index.php';
			break;

		case 'group/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/index.php';
			break;
		case '/group/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/index.php';
			break;

		case 'ask_to_join':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/ask_to_join':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'join_group':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/join_group':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'accept_group_invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/accept_group_invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'reject_group_invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/reject_group_invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'remove_group_member':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/remove_group_member':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'leave_group':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/leave_group':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'load-more-connection/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/load-more-connection/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'load-more-member/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/load-more-member/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'getGroupMember':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/getGroupMember':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'getNewsFeed':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/getNewsFeed':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'deleteGroup':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/deleteGroup':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'messaging':
			require 'modules-nct/messages-nct/index.php';
			break;
		case '/messaging':
			require 'modules-nct/messages-nct/index.php';
			break;
		case '/messaging/':
			require 'modules-nct/messages-nct/index.php';
			break;

		case 'compose-message':
			require 'modules-nct/messages-nct/index.php';
			break;
		case '/compose-message':
			require 'modules-nct/messages-nct/index.php';
			break;

		case 'getComposeMessageForm':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/getComposeMessageForm':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'compose-message/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/index.php';
			break;
		case '/compose-message/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/index.php';
			break;

		case 'getConversation':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/getConversation':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'send-message':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/send-message':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'send-message/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/send-message/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'getPreviousMessages':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/getPreviousMessages':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'deleteMessage':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/deleteMessage':
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'messaging/thread/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/index.php';
			break;
		case '/messaging/thread/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/index.php';
			break;

		case 'ajax/messaging/thread/'.fourthParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/ajax/messaging/thread/'.fourthParam(explode("/", $path)).'/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'ajax/getConversations/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;
		case '/ajax/getConversations/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/messages-nct/ajax.messages-nct.php';
			break;

		case 'email/'.secParam(explode("/", $path)).'/hash/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/newsletter-subscribe-nct.php';
			break;
		case '/email/'.secParam(explode("/", $path)).'/hash/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/newsletter-subscribe-nct.php';
			break;

		case 'unsubscribe_email/'.secParam(explode("/", $path)).'/hash/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/newsletter-subscribe-nct.php';
			break;
		case '/unsubscribe_email/'.secParam(explode("/", $path)).'/hash/'.end(explode("/", $path)):
			require 'modules-nct/home-nct/newsletter-subscribe-nct.php';
			break;

		case 'publish-post':
			require 'modules-nct/publish-post-nct/index.php';
			break;
		case '/publish-post':
			require 'modules-nct/publish-post-nct/index.php';
			break;

		case 'publish-post/'.end(explode("/", $path)):
			require 'modules-nct/publish-post-nct/index.php';
			break;
		case '/publish-post/'.end(explode("/", $path)):
			require 'modules-nct/publish-post-nct/index.php';
			break;

		case 'publish-editpost/'.end(explode("/", $path)):
			require 'modules-nct/publish-post-nct/index.php';
			break;
		case '/publish-editpost/'.end(explode("/", $path)):
			require 'modules-nct/publish-post-nct/index.php';
			break;

		case 'publish-post-save':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/publish-post-save':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'remove_saved_post':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/remove_saved_post':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'delete_post':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/delete_post':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getPendingInvitations':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getPendingInvitations':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'getSentInvitations':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/getSentInvitations':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'approve_invitation':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/approve_invitation':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'deny_invitation':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/deny_invitation':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'cancel_request':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/cancel_request':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'add_new_post':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;
		case '/add_new_post':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;

		case 'edit_post':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;
		case '/edit_post':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;

		case 'getPreviousPosts':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;
		case '/getPreviousPosts':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;

		case 'removeFeedImage':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;
		case '/removeFeedImage':
			require 'modules-nct/publish-post-nct/ajax.publish-post-nct.php';
			break;

		case 'search/users':
			require 'modules-nct/search-nct/index.php';
			break;
		case '/search/users':
			require 'modules-nct/search-nct/index.php';
			break;

		case 'ajax/users':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/users':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'search/jobs':
			require 'modules-nct/search-nct/index.php';
			break;
		case '/search/jobs':
			require 'modules-nct/search-nct/index.php';
			break;

		case 'ajax/jobs':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/jobs':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'search/companies':
			require 'modules-nct/search-nct/index.php';
			break;
		case '/search/companies':
			require 'modules-nct/search-nct/index.php';
			break;

		case 'ajax/companies':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/companies':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'search/groups':
			require 'modules-nct/search-nct/index.php';
			break;
		case '/search/groups':
			require 'modules-nct/search-nct/index.php';
			break;

		case 'ajax/groups':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/groups':
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'getGroupInvitations/group/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/getGroupInvitations/group/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'load-more-group-invitation/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/load-more-group-invitation/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'load-more-companies/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/load-more-companies/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'load-more-industries/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/load-more-industries/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'load-more-feeds/page/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/load-more-feeds/page/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'load-more-company-feeds/page/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/load-more-company-feeds/page/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'load-more-group-feeds/page/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/load-more-group-feeds/page/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'load-more-groups/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/load-more-groups/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'load-more-job-categories/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/load-more-job-categories/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'load-more-company-sizes/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/load-more-company-sizes/page/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'account-settings':
			require 'modules-nct/account-settings-nct/index.php';
			break;
		case '/account-settings':
			require 'modules-nct/account-settings-nct/index.php';
			break;

		case 'change-password':
			require 'modules-nct/account-settings-nct/ajax.account-settings-nct.php';
			break;
		case '/change-password':
			require 'modules-nct/account-settings-nct/ajax.account-settings-nct.php';
			break;

		case 'update-account-settings':
			require 'modules-nct/account-settings-nct/ajax.account-settings-nct.php';
			break;
		case '/update-account-settings':
			require 'modules-nct/account-settings-nct/ajax.account-settings-nct.php';
			break;

		case 'getCompanyFollowers':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getCompanyFollowers':
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'load-more-follower/company/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/load-more-follower/company/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'load-more-jobs/company/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/load-more-jobs/company/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'getCompanyActivities/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getCompanyActivities/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'getFollowerContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getFollowerContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'getJobContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getJobContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'getNotificationContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;
		case '/getNotificationContent/company/'.end(explode("/", $path)):
			require 'modules-nct/company-detail-nct/ajax.company-detail-nct.php';
			break;

		case 'load-more-messages/page/'.end(explode("/", $path)):
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;
		case '/load-more-messages/page/'.end(explode("/", $path)):
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;

		case 'load-more-notification/type/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;
		case '/load-more-notification/type/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)):
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;

		case 'mark_notifications_as_read':
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;
		case '/mark_notifications_as_read':
			require 'modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;

		case 'add-invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/add-invitation':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'saveUserName':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;
		case '/saveUserName':
			require 'modules-nct/profile-nct/ajax.profile-nct.php';
			break;

		case 'payment-history':
			require 'modules-nct/payment-history-nct/index.php';
			break;
		case '/payment-history':
			require 'modules-nct/payment-history-nct/index.php';
			break;

		case 'getTransaction':
			require 'modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;
		case '/getTransaction':
			require 'modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;

		case 'language/'.end(explode("/", $path)):
			require 'modules-nct/language-nct/index.php';
			break;

		case 'post_recent-updates':
			require 'modules-nct/post-activity-nct/index.php';
			break;
		case '/post_recent-updates':
			require 'modules-nct/post-activity-nct/index.php';
			break;

		case 'ajax/post_recent-updates':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_recent-updates':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'ajax/post_recent-updates/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_recent-updates/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'post_published-posts':
			require 'modules-nct/post-activity-nct/index.php';
			break;
		case '/post_published-posts':
			require 'modules-nct/post-activity-nct/index.php';
			break;

		case 'ajax/post_published-posts':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_published-posts':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'ajax/post_published-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_published-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'post_saved-posts':
			require 'modules-nct/post-activity-nct/index.php';
			break;
		case '/post_saved-posts':
			require 'modules-nct/post-activity-nct/index.php';
			break;

		case 'ajax/post_saved-posts':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_saved-posts':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'ajax/post_saved-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_saved-posts/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'post_all_activity':
			require 'modules-nct/post-activity-nct/index.php';
			break;
		case '/post_all_activity':
			require 'modules-nct/post-activity-nct/index.php';
			break;

		case 'ajax/post_all_activity':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_all_activity':
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'ajax/post_all_activity/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;
		case '/ajax/post_all_activity/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/post-activity-nct/ajax.post-activity-nct.php';
			break;

		case 'getInvitationForGroups':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;
		case '/getInvitationForGroups':
			require 'modules-nct/create-group-nct/ajax.create-group-nct.php';
			break;

		case 'ajax/getUsersBeforeLogin/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getUsersBeforeLogin/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getCompaniesBeforeLogin/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getCompaniesBeforeLogin/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getUsers/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getUsers/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getJobs/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getJobs/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getCompanies/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getCompanies/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getGroups/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;
		case '/ajax/getGroups/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/search-nct/ajax.search-nct.php';
			break;

		case 'ajax/getPeopleYouKnow_load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getPeopleYouKnow_load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getConnection_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getConnection_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getInvitation_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getInvitation_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getFollowing_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getFollowing_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getFollower_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getFollower_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getCommonConnection_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getCommonConnection_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getNotification_Load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/ajax/getNotification_Load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'ajax/getCompanies_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/ajax/getCompanies_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'ajax/getJobs_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/ajax/getJobs_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'ajax/getGroups_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;
		case '/ajax/getGroups_load/currentPage/'.fourthParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/groups-nct/ajax.groups-nct.php';
			break;

		case 'getGroupmember_load/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)).'':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/getGroupmember_load/group/'.thirdParam(explode("/", $path)).'/page/'.end(explode("/", $path)).'':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		case 'ajax/payment_load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;
		case '/ajax/payment_load/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;

		case 'signin':
			require 'modules-nct/home-nct/index.php';
			break;
		case '/signin':
			require 'modules-nct/home-nct/index.php';
			break;

		case 'ajax/getJobs_applicant/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;
		case '/ajax/getJobs_applicant/currentPage/'.end(explode("/", $path)):
			require 'modules-nct/job-detail-nct/ajax.job-detail-nct.php';
			break;

		case 'unsubscribe/'.end(explode("/", $path)):
			require 'modules-nct/unsubscribe-nct/index.php';
			break;

		case 'checkout/txn_id_app/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;

		case '/checkout/txn_id_app/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'modules-nct/checkout-nct/index.php';
			break;

		case 'del_comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/del_comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'edit_comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/edit_comment':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;

		case 'generate-captcha':
			require 'includes-nct/captcha-nct/generate_captcha.php';
			break;
		case '/generate-captcha':
			require 'includes-nct/captcha-nct/generate_captcha.php';
			break;

		case 'reportFeedPost':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/reportFeedPost':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case '/reportFeedPost/':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;
		case 'reportFeedPost/':
			require 'modules-nct/dashboard-nct/ajax.dashboard-nct.php';
			break;


		case 'reportGroupPost':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;
		case '/reportGroupPost':
			require 'modules-nct/group-detail-nct/ajax.group-detail-nct.php';
			break;

		//admin rule start
			
		case 'admin-nct':
			require 'admin-nct/index.html';
			break;
		case '/admin-nct':
			require 'admin-nct/index.html';
			break;

		case 'admin-nct/':
			require 'admin-nct/index.html';
			break;
		case '/admin-nct/':
			require 'admin-nct/index.html';
			break;

		case 'modules-nct/login-nct/':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;
		case '/modules-nct/login-nct/':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;
		case 'modules-nct/login-nct':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;

		case '/admin-nct/modules-nct/login-nct/':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;
		case 'admin-nct/modules-nct/login-nct':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;
		case '/admin-nct/modules-nct/login-nct':
			require 'admin-nct/modules-nct/login-nct/index.php';
			break;

		case 'admin-nct/modules-nct/home-nct':
			require 'admin-nct/modules-nct/home-nct/index.php';
			break;
		case '/admin-nct/modules-nct/home-nct':
			require 'admin-nct/modules-nct/home-nct/index.php';
			break;
		case '/admin-nct/modules-nct/home-nct/':
			require 'admin-nct/modules-nct/home-nct/index.php';
			break;
		
		//module 

		case 'admin-nct/user-dashboard/'.thirdParam(explode("/", $path)).'/action/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/ajax.user-dashboard-nct.php';
			break;
		case '/admin-nct/user-dashboard/'.thirdParam(explode("/", $path)).'/action/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/ajax.user-dashboard-nct.php';
			break;

		case 'admin-nct/user-dashboard/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/index.php';
			break;
		case '/admin-nct/user-dashboard/'.thirdParam(explode("/", $path)).'/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/index.php';
			break;
		
		case 'admin-nct/user-dashboard/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/index.php';
			break;
		case '/admin-nct/user-dashboard/'.end(explode("/", $path)):
			require 'admin-nct/modules-nct/user-dashboard-nct/index.php';
			break;

		case 'admin-nct/modules-nct/sitesetting-nct':
			require 'admin-nct/modules-nct/sitesetting-nct/index.php';
			break;
		case '/admin-nct/modules-nct/sitesetting-nct':
			require 'admin-nct/modules-nct/sitesetting-nct/index.php';
			break;

		case 'admin-nct/modules-nct/cPass-nct':
			require 'admin-nct/modules-nct/cPass-nct/index.php';
			break;
		case '/admin-nct/modules-nct/cPass-nct':
			require 'admin-nct/modules-nct/cPass-nct/index.php';
			break;

		case 'admin-nct/modules-nct/content-nct':
			require 'admin-nct/modules-nct/content-nct/index.php';
			break;
		case '/admin-nct/modules-nct/content-nct':
			require 'admin-nct/modules-nct/content-nct/index.php';
			break;

		case 'admin-nct/modules-nct/content-nct/ajax.content-nct.php':
			require 'admin-nct/modules-nct/content-nct/ajax.content-nct.php';
			break;
		case '/admin-nct/modules-nct/content-nct/ajax.content-nct.php':
			require 'admin-nct/modules-nct/content-nct/ajax.content-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.content-nct.php':
			require 'admin-nct/modules-nct/content-nct/ajax.content-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.content-nct.php':
			require 'admin-nct/modules-nct/content-nct/ajax.content-nct.php';
			break;

		case 'admin-nct/modules-nct/templates-nct':
			require 'admin-nct/modules-nct/templates-nct/index.php';
			break;
		case '/admin-nct/modules-nct/templates-nct':
			require 'admin-nct/modules-nct/templates-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.templates-nct.php':
			require 'admin-nct/modules-nct/templates-nct/ajax.templates-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.templates-nct.php':
			require 'admin-nct/modules-nct/templates-nct/ajax.templates-nct.php';
			break;

		case 'admin-nct/modules-nct/templates-nct/ajax.templates-nct.php':
			require 'admin-nct/modules-nct/templates-nct/ajax.templates-nct.php';
			break;
		case '/admin-nct/modules-nct/templates-nct/ajax.templates-nct.php':
			require 'admin-nct/modules-nct/templates-nct/ajax.templates-nct.php';
			break;

		case 'admin-nct/modules-nct/homepage_statics-nct':
			require 'admin-nct/modules-nct/homepage_statics-nct/index.php';
			break;
		case '/admin-nct/modules-nct/homepage_statics-nct':
			require 'admin-nct/modules-nct/homepage_statics-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.homepage_statics-nct.php':
			require 'admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.homepage_statics-nct.php':
			require 'admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php';
			break;

		case 'admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php':
			require 'admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php';
			break;
		case '/admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php':
			require 'admin-nct/modules-nct/homepage_statics-nct/ajax.homepage_statics-nct.php';
			break;

		case 'admin-nct/modules-nct/users-nct':
			require 'admin-nct/modules-nct/users-nct/index.php';
			break;
		case '/admin-nct/modules-nct/users-nct':
			require 'admin-nct/modules-nct/users-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.users-nct.php':
			require 'admin-nct/modules-nct/users-nct/ajax.users-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.users-nct.php':
			require 'admin-nct/modules-nct/users-nct/ajax.users-nct.php';
			break;

		case 'admin-nct/modules-nct/users-nct/ajax.users-nct.php':
			require 'admin-nct/modules-nct/users-nct/ajax.users-nct.php';
			break;
		case '/admin-nct/modules-nct/users-nct/ajax.users-nct.php':
			require 'admin-nct/modules-nct/users-nct/ajax.users-nct.php';
			break;

		case 'admin-nct/modules-nct/jobs-nct':
			require 'admin-nct/modules-nct/jobs-nct/index.php';
			break;
		case '/admin-nct/modules-nct/jobs-nct':
			require 'admin-nct/modules-nct/jobs-nct/index.php';
			break;

		case 'admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php':
			require 'admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php':
			require 'admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.jobs-nct.php':
			require 'admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.jobs-nct.php':
			require 'admin-nct/modules-nct/jobs-nct/ajax.jobs-nct.php';
			break;

		case 'admin-nct/modules-nct/job-category-nct':
			require 'admin-nct/modules-nct/job-category-nct/index.php';
			break;
		case '/admin-nct/modules-nct/job-category-nct':
			require 'admin-nct/modules-nct/job-category-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.job-category-nct.php':
			require 'admin-nct/modules-nct/job-category-nct/ajax.job-category-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.job-category-nct.php':
			require 'admin-nct/modules-nct/job-category-nct/ajax.job-category-nct.php';
			break;

		case 'admin-nct/modules-nct/degrees-nct':
			require 'admin-nct/modules-nct/degrees-nct/index.php';
			break;
		case '/admin-nct/modules-nct/degrees-nct':
			require 'admin-nct/modules-nct/degrees-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.degrees-nct.php':
			require 'admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.degrees-nct.php':
			require 'admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php';
			break;

		case 'admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php':
			require 'admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php';
			break;
		case '/admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php':
			require 'admin-nct/modules-nct/degrees-nct/ajax.degrees-nct.php';
			break;

		case 'admin-nct/modules-nct/languages-nct':
			require 'admin-nct/modules-nct/languages-nct/index.php';
			break;
		case '/admin-nct/modules-nct/languages-nct':
			require 'admin-nct/modules-nct/languages-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.languages-nct.php':
			require 'admin-nct/modules-nct/languages-nct/ajax.languages-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.languages-nct.php':
			require 'admin-nct/modules-nct/languages-nct/ajax.languages-nct.php';
			break;

		case 'admin-nct/modules-nct/languages-nct/ajax.languages-nct.php':
			require 'admin-nct/modules-nct/languages-nct/ajax.languages-nct.php';
			break;
		case '/admin-nct/modules-nct/languages-nct/ajax.languages-nct.php':
			require 'admin-nct/modules-nct/languages-nct/ajax.languages-nct.php';
			break;

		case 'admin-nct/modules-nct/companies-nct':
			require 'admin-nct/modules-nct/companies-nct/index.php';
			break;
		case '/admin-nct/modules-nct/companies-nct':
			require 'admin-nct/modules-nct/companies-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.companies-nct.php':
			require 'admin-nct/modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.companies-nct.php':
			require 'admin-nct/modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'admin-nct/modules-nct/companies-nct/ajax.companies-nct.php':
			require 'admin-nct/modules-nct/companies-nct/ajax.companies-nct.php';
			break;
		case '/admin-nct/modules-nct/companies-nct/ajax.companies-nct.php':
			require 'admin-nct/modules-nct/companies-nct/ajax.companies-nct.php';
			break;

		case 'admin-nct/modules-nct/industries-nct':
			require 'admin-nct/modules-nct/industries-nct/index.php';
			break;
		case '/admin-nct/modules-nct/industries-nct':
			require 'admin-nct/modules-nct/industries-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.industries-nct.php':
			require 'admin-nct/modules-nct/industries-nct/ajax.industries-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.industries-nct.php':
			require 'admin-nct/modules-nct/industries-nct/ajax.industries-nct.php';
			break;

		case 'admin-nct/modules-nct/groups-nct':
			require 'admin-nct/modules-nct/groups-nct/index.php';
			break;
		case '/admin-nct/modules-nct/groups-nct':
			require 'admin-nct/modules-nct/groups-nct/index.php';
			break;

		case 'admin-nct/modules-nct/groups-nct/ajax.groups-nct.php':
			require 'admin-nct/modules-nct/groups-nct/ajax.groups-nct.php';
			break;
		case '/admin-nct/modules-nct/groups-nct/ajax.groups-nct.php':
			require 'admin-nct/modules-nct/groups-nct/ajax.groups-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.groups-nct.php':
			require 'admin-nct/modules-nct/groups-nct/ajax.groups-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.groups-nct.php':
			require 'admin-nct/modules-nct/groups-nct/ajax.groups-nct.php';
			break;

		case 'admin-nct/modules-nct/group-type-nct':
			require 'admin-nct/modules-nct/group-type-nct/index.php';
			break;
		case '/admin-nct/modules-nct/group-type-nct':
			require 'admin-nct/modules-nct/group-type-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.group-type-nct.php':
			require 'admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.group-type-nct.php':
			require 'admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php';
			break;

		case 'admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php':
			require 'admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php';
			break;
		case '/admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php':
			require 'admin-nct/modules-nct/group-type-nct/ajax.group-type-nct.php';
			break;

		case 'admin-nct/modules-nct/membership-plans-nct':
			require 'admin-nct/modules-nct/membership-plans-nct/index.php';
			break;
		case '/admin-nct/modules-nct/membership-plans-nct':
			require 'admin-nct/modules-nct/membership-plans-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.membership-plans-nct.php':
			require 'admin-nct/modules-nct/membership-plans-nct/ajax.membership-plans-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.membership-plans-nct.php':
			require 'admin-nct/modules-nct/membership-plans-nct/ajax.membership-plans-nct.php';
			break;

		case 'admin-nct/modules-nct/adhoc-inmail-pricings-nct':
			require 'admin-nct/modules-nct/adhoc-inmail-pricings-nct/index.php';
			break;
		case '/admin-nct/modules-nct/adhoc-inmail-pricings-nct':
			require 'admin-nct/modules-nct/adhoc-inmail-pricings-nct/index.php';
			break;

		case 'admin-nct/modules-nct/payment-history-nct':
			require 'admin-nct/modules-nct/payment-history-nct/index.php';
			break;
		case '/admin-nct/modules-nct/payment-history-nct':
			require 'admin-nct/modules-nct/payment-history-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.payment-history-nct.php':
			require 'admin-nct/modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.payment-history-nct.php':
			require 'admin-nct/modules-nct/payment-history-nct/ajax.payment-history-nct.php';
			break;

		case 'admin-nct/modules-nct/featured-job-pricings-nct':
			require 'admin-nct/modules-nct/featured-job-pricings-nct/index.php';
			break;
		case '/admin-nct/modules-nct/featured-job-pricings-nct':
			require 'admin-nct/modules-nct/featured-job-pricings-nct/index.php';
			break;

		case 'admin-nct/modules-nct/subscribers-nct':
			require 'admin-nct/modules-nct/subscribers-nct/index.php';
			break;
		case '/admin-nct/modules-nct/subscribers-nct':
			require 'admin-nct/modules-nct/subscribers-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.subscribers-nct.php':
			require 'admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.subscribers-nct.php':
			require 'admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php';
			break;

		case 'admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php':
			require 'admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php';
			break;
		case '/admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php':
			require 'admin-nct/modules-nct/subscribers-nct/ajax.subscribers-nct.php';
			break;

		case 'admin-nct/modules-nct/city-nct':
			require 'admin-nct/modules-nct/city-nct/index.php';
			break;
		case '/admin-nct/modules-nct/city-nct':
			require 'admin-nct/modules-nct/city-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.city-nct.php':
			require 'admin-nct/modules-nct/city-nct/ajax.city-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.city-nct.php':
			require 'admin-nct/modules-nct/city-nct/ajax.city-nct.php';
			break;

		case 'admin-nct/modules-nct/state-nct':
			require 'admin-nct/modules-nct/state-nct/index.php';
			break;
		case '/admin-nct/modules-nct/state-nct':
			require 'admin-nct/modules-nct/state-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.state-nct.php':
			require 'admin-nct/modules-nct/state-nct/ajax.state-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.state-nct.php':
			require 'admin-nct/modules-nct/state-nct/ajax.state-nct.php';
			break;

		case 'admin-nct/modules-nct/country-nct':
			require 'admin-nct/modules-nct/country-nct/index.php';
			break;
		case '/admin-nct/modules-nct/country-nct':
			require 'admin-nct/modules-nct/country-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.country-nct.php':
			require 'admin-nct/modules-nct/country-nct/ajax.country-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.country-nct.php':
			require 'admin-nct/modules-nct/country-nct/ajax.country-nct.php';
			break;

		case 'admin-nct/modules-nct/contact-us-nct':
			require 'admin-nct/modules-nct/contact-us-nct/index.php';
			break;
		case '/admin-nct/modules-nct/contact-us-nct':
			require 'admin-nct/modules-nct/contact-us-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.contact-us-nct.php':
			require 'admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.contact-us-nct.php':
			require 'admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php';
			break;

		case 'admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php':
			require 'admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php';
			break;
		case '/admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php':
			require 'admin-nct/modules-nct/contact-us-nct/ajax.contact-us-nct.php';
			break;

		case 'admin-nct/modules-nct/language-nct':
			require 'admin-nct/modules-nct/language-nct/index.php';
			break;
		case '/admin-nct/modules-nct/language-nct':
			require 'admin-nct/modules-nct/language-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.language-nct.php':
			require 'admin-nct/modules-nct/language-nct/ajax.language-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.language-nct.php':
			require 'admin-nct/modules-nct/language-nct/ajax.language-nct.php';
			break;

		case 'admin-nct/modules-nct/constant-nct':
			require 'admin-nct/modules-nct/constant-nct/index.php';
			break;
		case '/admin-nct/modules-nct/constant-nct':
			require 'admin-nct/modules-nct/constant-nct/index.php';
			break;

		case 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php':
			require 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php';
			break;
		case '/admin-nct/modules-nct/constant-nct/ajax.constant-nct.php':
			require 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php';
			break;

		case 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php':
			require 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php';
			break;
		case '/admin-nct/modules-nct/constant-nct/ajax.constant-nct.php':
			require 'admin-nct/modules-nct/constant-nct/ajax.constant-nct.php';
			break;

		case 'admin-nct/modules-nct/licenses_endorsements-nct':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/index.php';
			break;
		case '/admin-nct/modules-nct/licenses_endorsements-nct':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/index.php';
			break;

		case 'admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php';
			break;
		case '/admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/licenses_endorsements-nct/ajax.licenses_endorsements-nct.php';
			break;

		case 'admin-nct/modules-nct/airports-nct':
			require 'admin-nct/modules-nct/airports-nct/index.php';
			break;
		case '/admin-nct/modules-nct/airports-nct':
			require 'admin-nct/modules-nct/airports-nct/index.php';
			break;

		case 'admin-nct/modules-nct/airports-nct/ajax.airports-nct.php':
			require 'admin-nct/modules-nct/airports-nct/ajax.airports-nct.php';
			break;
		case '/admin-nct/modules-nct/airports-nct/ajax.airports-nct.php':
			require 'admin-nct/modules-nct/airports-nct/ajax.airports-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.airports-nct.php':
			require 'admin-nct/modules-nct/airports-nct/ajax.airports-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.airports-nct.php':
			require 'admin-nct/modules-nct/airports-nct/ajax.airports-nct.php';
			break;

		case 'admin-nct/modules-nct/company_approvals-nct':
			require 'admin-nct/modules-nct/company_approvals-nct/index.php';
			break;
		case '/admin-nct/modules-nct/company_approvals-nct':
			require 'admin-nct/modules-nct/company_approvals-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ajax.company_approvals-nct.php':
			require 'admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.company_approvals-nct.php':
			require 'admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php';
			break;

		case 'admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php':
			require 'admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php';
			break;
		case '/admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php':
			require 'admin-nct/modules-nct/company_approvals-nct/ajax.company_approvals-nct.php';
			break;

		case 'admin-nct/modules-nct/requested_licenses_endorsements-nct':
			require 'admin-nct/modules-nct/requested_licenses_endorsements-nct/index.php';
			break;
		case '/admin-nct/modules-nct/requested_licenses_endorsements-nct':
			require 'admin-nct/modules-nct/requested_licenses_endorsements-nct/index.php';
			break;

		case 'admin-nct/modules-nct/requested_licenses_endorsements-nct/ajax.requested_licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/requested_licenses_endorsements-nct/ajax.requested_licenses_endorsements-nct.php';
			break;
		case '/admin-nct/modules-nct/requested_licenses_endorsements-nct/ajax.requested_licenses_endorsements-nct.php':
			require 'admin-nct/modules-nct/requested_licenses_endorsements-nct/ajax.requested_licenses_endorsements-nct.php';
			break;

		case 'admin-nct/modules-nct/requested_airports-nct':
			require 'admin-nct/modules-nct/requested_airports-nct/index.php';
			break;
		case '/admin-nct/modules-nct/requested_airports-nct':
			require 'admin-nct/modules-nct/requested_airports-nct/index.php';
			break;

		case 'admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php':
			require 'admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php';
			break;
		case '/admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php':
			require 'admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.requested_airports-nct.php':
			require 'admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.requested_airports-nct.php':
			require 'admin-nct/modules-nct/requested_airports-nct/ajax.requested_airports-nct.php';
			break;

		case 'admin-nct/modules-nct/requested_locations-nct':
			require 'admin-nct/modules-nct/requested_locations-nct/index.php';
			break;
		case '/admin-nct/modules-nct/requested_locations-nct':
			require 'admin-nct/modules-nct/requested_locations-nct/index.php';
			break;

		case 'admin-nct/modules-nct/requested_locations-nct/ajax.requested_locations-nct.php':
			require 'admin-nct/modules-nct/requested_locations-nct/ajax.requested_locations-nct.php';
			break;
		case '/admin-nct/modules-nct/requested_locations-nct/ajax.requested_locations-nct.php':
			require 'admin-nct/modules-nct/requested_locations-nct/ajax.requested_locations-nct.php';
			break;

		case 'admin-nct/modules-nct/company_reviews-nct':
			require 'admin-nct/modules-nct/company_reviews-nct/index.php';
			break;
		case '/admin-nct/modules-nct/company_reviews-nct':
			require 'admin-nct/modules-nct/company_reviews-nct/index.php';
			break;

		case 'admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php':
			require 'admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php';
			break;
		case '/admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php':
			require 'admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.company_reviews-nct.php':
			require 'admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.company_reviews-nct.php':
			require 'admin-nct/modules-nct/company_reviews-nct/ajax.company_reviews-nct.php';
			break;

		case 'admin-nct/modules-nct/ferry_pilot_reviews-nct':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/index.php';
			break;
		case '/admin-nct/modules-nct/ferry_pilot_reviews-nct':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/index.php';
			break;

		case 'admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php';
			break;
		case '/admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.ferry_pilot_reviews-nct.php':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.ferry_pilot_reviews-nct.php':
			require 'admin-nct/modules-nct/ferry_pilot_reviews-nct/ajax.ferry_pilot_reviews-nct.php';
			break;

		case 'admin-nct/modules-nct/reported_feeds-nct':
			require 'admin-nct/modules-nct/reported_feeds-nct/index.php';
			break;
		case '/admin-nct/modules-nct/reported_feeds-nct':
			require 'admin-nct/modules-nct/reported_feeds-nct/index.php';
			break;

		case 'admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php':
			require 'admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php';
			break;
		case '/admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php':
			require 'admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.reported_feeds-nct.php':
			require 'admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.reported_feeds-nct.php':
			require 'admin-nct/modules-nct/reported_feeds-nct/ajax.reported_feeds-nct.php';
			break;

		case 'admin-nct/modules-nct/reported_groups-nct':
			require 'admin-nct/modules-nct/reported_groups-nct/index.php';
			break;
		case '/admin-nct/modules-nct/reported_groups-nct':
			require 'admin-nct/modules-nct/reported_groups-nct/index.php';
			break;

		case 'admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php':
			require 'admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php';
			break;
		case '/admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php':
			require 'admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php';
			break;

		case 'admin-nct/modules-nct/ajax.reported_groups-nct.php':
			require 'admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php';
			break;
		case '/admin-nct/modules-nct/ajax.reported_groups-nct.php':
			require 'admin-nct/modules-nct/reported_groups-nct/ajax.reported_groups-nct.php';
			break;

		case 'admin-nct/includes-nct/logout-nct.php':
			require 'admin-nct/includes-nct/logout-nct.php';
			break;
		case '/admin-nct/includes-nct/logout-nct.php':
			require 'admin-nct/includes-nct/logout-nct.php';
			break;

		case 'admin-nct/modules-nct/notifications-nct':
			require 'admin-nct/modules-nct/notifications-nct/index.php';
			break;
		case '/admin-nct/modules-nct/notifications-nct':
			require 'admin-nct/modules-nct/notifications-nct/index.php';
			break;
		case '/admin-nct/modules-nct/notifications-nct/':
			require 'admin-nct/modules-nct/notifications-nct/index.php';
			break;
		case 'admin-nct/modules-nct/notifications-nct/':
			require 'admin-nct/modules-nct/notifications-nct/index.php';
			break;

		case 'admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php':
			require 'admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;
		case '/admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php':
			require 'admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;
		case '/admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php/':
			require 'admin-nct/modules-nct/notifications-nct/ajax.notifications-nct.php';
			break;

		//admin panel rules end

		default:
			http_response_code(404);
			require '404.php';
			break;
			// echo @parse_url($_SERVER['REQUEST_URI'])['path'];
			// exit('Not Found');
	}
?>