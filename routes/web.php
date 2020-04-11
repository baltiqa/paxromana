<?php


 include "admin.php";

// Route::get('/api/search', 'ApiController@search')->name('auth.apikey');
// Route::get('/api/listing/{id}', 'ApiController@listing')->name('auth.listing');
// Route::get('/api/vendor/{username}', 'ApiController@vendor')->name('auth.username');
// Route::get('/api/vendor/{username}/feedback', 'ApiController@feedback')->name('auth.feedback');


Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::group(['middleware' => ['jailBanned']], function()
{

    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');

    
    $this->get('register/{referrer?}/{id?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register/{referrer?}/{id?}', 'Auth\RegisterController@register');

 

       // Password Reset Routes...
       $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
       $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
       $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
       $this->post('password/reset', 'Auth\ResetPasswordController@reset');


    Route::get('/password/reset', 'Auth\ForgotPasswordController@indexPage')->name('page_resetpassword');
    Route::post('/password/reset', 'Auth\ForgotPasswordController@verifyPage')->name('verify_password');

    Route::get('/account/reset/token/{token}', 'Auth\ForgotPasswordController@resetPasswordPage')->name('reset_password');
    Route::post('/account/reset/token/save', 'Auth\ForgotPasswordController@savePasswordReset')->name('reset_password');

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/mnemonic', 'Auth\LoginController@mnemonicPage')->name('mnemonic');
    Route::post('/mnemonic', 'Auth\LoginController@mnemonicPost')->name('mnemonic_post');

    Route::get('/two_factor_auth', 'Auth\LoginController@pGP2FACheckP')->name('2fa_factor');
    Route::post('/two_factor_auth', 'Auth\LoginController@verifyTwoFactorAuth')->name('2fa_factorcheck');
  

    Route::get('/captcha.html', 'Auth\LoginController@captcha');
    Route::get('/antiddoss', 'Auth\LoginController@antiDdosPage');
    Route::post('/antiddoss', 'Auth\LoginController@submitAntiDdos')->name('submit.antidos');



    Route::get('/verify', 'Auth\LoginController@verifyUrl');


	//ACCOUNT
	Route::group(['middleware' => ['auth','auth.mnemonic'], 'prefix' => 'account', 'as' => 'account.', 'namespace' => 'Account'], function()
	{

		Route::get('/', function () {
			return redirect(route('account.edit_profile.index'));
		});
		Route::resource('change_settings', 'PasswordController');
        Route::get('edit_profile', 'ProfileController@index');
        Route::get('edit_profile/me', 'ProfileController@edit');
        Route::post('edit_profile/me', 'ProfileController@storeProfile')->name('update.profile');


        Route::get('reset_withdrawpin', 'PasswordController@resetWithdrawPin');
        Route::post('reset_withdrawpin', 'PasswordController@storeResetWithdrawPin')->name('reset.withdrawpin');


        Route::get('change_pgp', 'ProfileController@pgp_key');
        Route::post('change_pgp', 'ProfileController@pgpUpdate')->name('update.pgp');

        Route::get('vendor_settings', 'ProfileController@vendor_settings');
        Route::post('vendor_settings', 'ProfileController@updateStore')->name('update.store');

        Route::get('verify_pgp', 'ProfileController@pgpVerifyPage');
        Route::post('verify_pgp', 'ProfileController@verifyPGP')->name('verify.pgp');

        
        Route::get('multisig', 'ProfileController@multisig');
        Route::post('multisig', 'ProfileController@multisigUpdate');

        Route::get('referrals', 'ReferralsController@index')->name('referrals')->middleware("auth");


        Route::get('/buy/ads/{listing}', 'AdsController@index')->name('ads');

        Route::post('/buy/ads/{listing}', 'AdsController@buyingAds')->name('ads.buyingads');


        Route::get('feedbacks', 'ProfileController@feedbacks');

        Route::resource('purchase-history', 'PurchaseHistoryController');
        Route::get('purchase-history/{purchase_history}/feedback', 'PurchaseHistoryController@feedbackPage')->name('purchase-history.feedback');
        Route::post('purchase-history/{purchase_history}', 'PurchaseHistoryController@update')->name('give.feedback');
        Route::post('purchase-history/{purchase_history}/finalize', 'PurchaseHistoryController@finalizeOrder')->name('finalize.order');


        Route::get('tickets', 'TicketsController@index')->name('list.ticket');
        Route::get('create/ticket', 'TicketsController@create');

        Route::post('create/ticket', 'TicketsController@createTicket')->name('create.ticket');
        Route::get('reports', 'TicketsController@reports');
        Route::get('report/{id}', 'TicketsController@showReport');


        Route::get('ticket/{id}', 'TicketsController@showTicket')->name('ticket.show');
        Route::get('ticket/{id}/close', 'TicketsController@closeTicket')->name('ticket.close');
        Route::post('ticket/{id}', 'TicketsController@reply')->name('post.reply');;


        
        Route::resource('favorites', 'FavoritesController');
        Route::post('favorites/remove', 'FavoritesController@remove')->name('remove.favorites');
        

        Route::resource('listings', 'ListingsController');
        Route::get('listings-trash', 'ListingsController@trashedListings')->name('trash.listings');
        Route::get('listings-restore/{id}', 'ListingsController@restore')->name('restore.listings');
        Route::post('listings', 'ListingsController@removeAll')->name('remove.listings');
        Route::post('listings-trash', 'ListingsController@permanentDelete')->name('permanent.listings');

      
        Route::resource('orders', 'OrdersController');
        Route::post('/orders/{order}', 'OrdersController@update');
        Route::post('0/update/orders', 'OrdersController@updateAll')->name('update.orders');

        Route::get('/orders/state/1', 'OrdersController@accepted')->name('orders.shipped');
        Route::get('/orders/state/2', 'OrdersController@shipped');
        Route::get('/orders/state/3', 'OrdersController@finalized');
        Route::get('/orders/state/4', 'OrdersController@dispute');
        Route::get('/orders/state/5', 'OrdersController@cancelled');




        route::get('/orders/multisig/{hex}/{id}','OrdersController@Sign');
        route::post('/orders/multisig/{hex}/{id}','OrdersController@SignAction');


        Route::get('disputes', 'DisputeController@index');
        Route::get('dispute/0/{id}', 'DisputeController@dispute')->name('dispute.show');
        Route::post('dispute/0/{order}/create', 'DisputeController@createDispute')->name('dispute.create');
        Route::post('dispute/0/{id}/sendmessage', 'DisputeController@sentMessage')->name('dispute.message');
        Route::post('dispute/0/{id}/cancel', 'DisputeController@cancel')->name('dispute.cancel');


        Route::get('wallet', 'WalletController@index')->name('wallet');

        Route::get('apply_vendor', 'ProfileController@applyVendor')->name('page.vendor');
        Route::post('apply_vendor', 'ProfileController@payVendor')->name('pay.vendor');

        Route::get('wallet/btc', 'BitcoinController@index')->name('bitcoin-wallet');
        Route::get('wallet/xmr', 'MoneroController@index')->name('monero-wallet');
        Route::get('wallet/ltc', 'LiteCoinController@index')->name('litecoin-wallet');

        Route::get('wallet/ltc/empty', 'LiteCoinController@clearHistory')->name('emptyltc');
        Route::get('wallet/xmr/empty', 'MoneroController@clearHistory')->name('emptyxmr');
        Route::get('wallet/btc/empty', 'BitcoinController@clearHistory')->name('emptybtc');

        
        Route::get('wallet/xmr/sign_address', 'MoneroController@signMoneroAddress')->name('monero-sign-wallet');   
        Route::get('wallet/btc/sign_address', 'BitcoinController@signBitcoinAddress')->name('bitcoin-sign-wallet');        
        Route::get('wallet/ltc/sign_address', 'LiteCoinController@signLitecoinAddress')->name('litecoin-sign-wallet');        

        

        Route::post('withdraw/btc', 'BitcoinController@Withdraw')->name('bitcoin-withdraw');
        Route::post('withdraw/xmr', 'MoneroController@Withdraw')->name('monero-withdraw');
        Route::post('withdraw/ltc', 'LiteCoinController@Withdraw')->name('litecoin-withdraw');

	});

	//REQUIRES AUTHENTICATION
	Route::group(['middleware' => ['auth','auth.mnemonic']], function () {

        Route::get('/', 'BrowseController@listings')->name('browse');
        Route::get('/categories', 'BrowseController@categories')->name('categories');
        Route::get('/exchanges/{exchange}','BrowseController@exchange')->name('currency.update');

        Route::get('/antiphishing', 'Auth\LoginController@antiPhishing')->name('antiphishing');
        Route::get('/antiphishing.html', 'Auth\LoginController@imageAntiphishing');

		//INBOX
        Route::resource('inbox', 'InboxController')->middleware('talk'); //Inbox
        
        Route::post('/inbox/sendmessage', 'InboxController@sendMessage')->name('send.message');
        Route::post('/inbox/{id}/invite', 'InboxController@inviteUser')->name('invite.message');
        Route::post('/inbox/{id}/report', 'InboxController@report')->name('report.message');


        Route::get('notifications', 'NotificationController@index')->name('notifications');
        Route::get('notifications/delete/{id}', 'NotificationController@delete')->name('notifications');
        Route::get('notifications/go/{id}', 'NotificationController@go')->name('notifications');
        Route::get('notifications/markread', 'NotificationController@markAllRead')->name('notifications');
        Route::get('notifications/empty', 'NotificationController@deleteAllNotifications')->name('notifications');


        Route::get('/profile/{user}', 'ProfileController@index')->name('profile'); //PROFILE
        Route::get('/profile/{user}/follow', 'ProfileController@follow')->name('profile.follow'); //PROFILE
        Route::get('/profile/{user}/store', 'ProfileController@storeShop')->name('profile.storeshop'); //PROFILE
        Route::get('/profile/{user}/ratings', 'ProfileController@reviews')->name('profile.ratings'); //PROFILE
        Route::get('/profile/{user}/sendmessage', 'ProfileController@createMessageViaListing')->name('profile.messages'); //PROFILE
        Route::post('/profile/{userId}/sendmessage', 'ProfileController@storeMessageViaProfile')->name('profile.sendmessage'); //PROFILE
        Route::post('/profile/{user}/report', 'ProfileController@submit')->name('profile.report'); //PROFILE

        Route::get('/profile/{user}/block', 'ProfileController@blockUser')->name('profile.block'); //PROFILE
        Route::get('/profile/{user}/unblock', 'ProfileController@unblockUser')->name('profile.unblock'); //PROFILE

        Route::get('/wiki', 'HelpController@index')->name('page');
        Route::get('/wiki/{id}', 'HelpController@help')->name('page.single');
        Route::get('/wiki/{id}/voteup', 'HelpController@voteUp');
        Route::get('/wiki/{id}/votedown', 'HelpController@voteDown');
        
    
        Route::get('/category/{id}', 'CategoryController@index')->name('category.index');


	//LISTINGS
	Route::group(['prefix' => 'listing'], function()
	{
		Route::get('/{listing}/{slug}', 'ListingController@index')->name('listing');
		Route::get('/{listing}/{slug}/spotlight', 'ListingController@spotlight')->middleware('auth.ajax')->name('listing.spotlight');
        Route::get('/{listing}/{slug}/message', 'InboxController@createMessageViaListing')->middleware('auth.ajax')->name('listing.sendmessage');
		Route::post('/{listing}/{slug}/message', 'InboxController@store')->middleware('auth.ajax')->name('listing.storemessage');
        Route::get('/{listing}/{slug}/star', 'ListingController@star')->middleware('auth.ajax')->name('listing.star');
        Route::get('/{listing}/{slug}/follow', 'ListingController@follow')->middleware('auth.ajax')->name('listing.follow');
		Route::get('/{listing}/{slug}/edit', 'ListingController@edit')->name('listing.edit');
        Route::any('/{id}/update', 'ListingController@update')->name('listing.update');
    

	});



		//CREATE LISTING
        
        Route::get('/create/listing', 'CreateController@index')->name('listing.creat');
        Route::post('/create/listing', 'CreateController@store')->name('listing.create');
        Route::get('/clone/{listing}/listing', 'CreateController@clone')->name('listing.clone');
        Route::get('/enable/{listing}/listing', 'CreateController@enable')->name('listing.enable');
        Route::get('/disable/{listing}/listing', 'CreateController@disable')->name('listing.disable');

        Route::get('/edit/{listing}/listing', 'CreateController@edit')->name('listing.edit');
        Route::post('/edit/{listing}/listing', 'CreateController@update')->name('listing.update');
        Route::get('/remove/{listing}/{id}/listing', 'CreateController@deleteShippingOption')->name('shipping.remove');



		//CHECKOUT
        Route::get('/checkout/error', 'CheckoutController@error_page')->name('checkout.error');
        Route::get('/checkout', 'CheckoutController@index')->name('checkout');
        Route::get('/checkout/{listing}', 'CheckoutController@index')->name('checkout');
        Route::get('/checkout/multisig/{address}', 'CheckoutController@CheckMultisig')->name('multisig');
        Route::get('/checkout/complete/{temp_id}', 'CheckoutController@ContinueOrder')->name('finish.order');



        Route::post('/checkout/{listing}', 'CheckoutController@checkout')->name('checkout.store');
        Route::post('/checkout', 'CheckoutController@placingOrder')->name('placing.order');


    });
});

Route::get("/rates","TransactionsController@UpdateRates");
Route::post("/transactions/btc","TransactionsController@DepositBitcoin");
Route::post("/transactions/ltc","TransactionsController@DepositLitecoin");
Route::post("/transactions/xmr","TransactionsController@DepositMonero");
Route::get("/transactions/cron","TransactionsController@Cron");

#errors
Route::get('/suspended',function(){
    return 'Sorry something went wrong.';
})->name('error.suspended');
