<?php

Route::group(['as' => 'panel.', 'prefix' => 'panel','middleware' => ['admin'], 'namespace' => '\Admin'], function()
{
    Route::get('/dashboard', 'PanelController@moderatorDashboard');
    Route::get('/admindashboard', 'PanelController@index');
    Route::get('/', 'PanelController@infopage');
    Route::post('/submit', 'PanelController@submitIdea')->name('submit.idea');

    Route::resource('listings', 'ListingsController');
    Route::resource('categories', 'CategoriesController');
    Route::get('/categories/{id}/remove', 'CategoriesController@destroy')->name('categories.destroy');
    Route::resource('users', 'UsersController');
    Route::resource('wiki', 'PagesController');
    Route::get('/wiki/{id}/remove', 'PagesController@destroy')->name('wiki.destroy');
    Route::resource('reviews', 'ReviewsController');
    Route::any('/settings/remove', 'SettingsController@remove')->name('settings.remove');
    Route::resource('settings', 'SettingsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('moderatelistings', 'ModerateListingsController');
    Route::resource('tickets', 'TicketsController');
    Route::resource('usermarkets', 'UserMarketController');

    Route::get('buyerlogs', 'BuyerLogController@index');
    Route::get('regularescrow', 'BuyerLogController@regularEscrow');
    Route::get('multisigescrow', 'BuyerLogController@multisigEscrow');

    Route::get('staffchat', 'StaffChatController@index');


    Route::get('withdraw/ltc/{amount}/{state}', 'BuyerLogController@withdrawLTC')->name('withdraw.ltc');
    Route::get('withdraw/btc/{amount}/{state}', 'BuyerLogController@withdrawBTC')->name('withdraw.btc');
    Route::get('withdraw/xmr/{amount}/{state}', 'BuyerLogController@withdrawXMR')->name('withdraw.xmr');



    Route::get('/usermarkets/{market}/remove', 'UserMarketController@destroy')->name('market.destroy');

    Route::get('/ticket/{id}/close', 'TicketsController@closeTicket')->name('ticket.close');
    Route::get('/disputes/{id}/edit', 'DisputeController@edit')->name('disputes.edit');
    Route::put('/disputes/{id}', 'DisputeController@update')->name('disputes.update');
    Route::get('/disputes', 'DisputeController@index')->name('disputes.index');


});

Route::namespace('Admin')->group(function () {
    Route::get('admin/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');
    Route::get('admin/logout', 'LoginController@logout')->name('admin.logout');
});