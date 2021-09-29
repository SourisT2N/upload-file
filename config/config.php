<?php 
				/*Database*/
	define("DB_HOST"		, "localhost");
	define("DB_NAME"		, "webupload");
	define("DB_USER"		, "root");
	define("DB_PASS"		, "souris0112");

				/*PATH*/
	define("FOLDER_PATH"	, $_SERVER['DOCUMENT_ROOT'] . "/");
	define("APP_PATH"		, FOLDER_PATH . "app/");
	define("PUBLIC_PATH"	, FOLDER_PATH . "public/");
	define("TEMPLATE_PATH"	, FOLDER_PATH . "template/");

				/*URL*/
	define("DOMAIN"			, "https://webfileupload.so");
	define("ASSETS_URL"		, DOMAIN."/public/assets");
	define("UPLOAD_URL"		, "upload/");

				/*Email*/
	define("USER_EMAIL"		, "0d16c2bf506454bf902acc55ed5b0bdf");
	define("PASS_EMAIL"		, "46b249e889e5a09db765c807d0ba5d12");
	define("SMTP"			, "in-v3.mailjet.com");
	define("PORT"			, 587);

				/*PRIVATE KEY*/
	define("PRIVATE_KEY"	, "souris_979257600608f7a6b809c55.80449553");
	define("ALGO_TOKEN"		, "HS512");
	define("SECRET_CAPTCHA" , "6LdQ2dYaAAAAAOdF7ewrZkBo9mCmQBPS0AW0sjMw");
	define("IDAPP_FB"		, "1879028298915681");
	define("SECRET_FB"		, "854b85976eebf03f1db73768a3d2258c");
	define("IDAPP_GG"		, "77835408956-7vc3enfka3pqkhfp6c3019eern3dhi57.apps.googleusercontent.com");
	define("SECRET_GG"		, "UObAEcIzgOJxXitfMcmeMFEG");

				/*PAYMENT MOMO*/
	define("CODE_MOMO"		, "MOMO6PQ920210522");
	define("KEY_MOMO"		, "dWpclfTCC3QaMyOS");
	define("SECRET_MOMO"	, "aQDXfwkAkv6G9XQR9x2kYlm65EE3E5Zq");
	define("URL_MOMO"		, "https://test-payment.momo.vn/gw_payment/transactionProcessor");
	define("RETURN_MOMO"	, DOMAIN. "/user/donate");
	define("TYPE_MOMO"		, "SHA256");