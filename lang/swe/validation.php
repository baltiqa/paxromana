<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'blocked'             => 'This user is already blocked',
    'blocked_1'             => 'The user is now unblocked.',
    'blocked_2'             => 'The user is now blocked.',
    'blocked_now'             => 'This user has now been blocked.',
    'report_received'             => 'The report is received, the moderator will see this situation within 24 hours. We can sent you message about the report, if not answered within the 3 days. Case will be closed.',
    'image_size'             => 'The size of image 1 must to less than or equal to 512Kb',
    'listing_error'             => '<strong>Error!</strong> The listing was not updated.',
    'listing_updated'             => '<strong>Success!</strong> The listing was updated. See now your latest update via',
    'image_error'             => '<strong>Error!</strong> There is something wrong with the images.',
    'listing_created'             => '<strong>Success!</strong> The listing was created.',
    'listing_disabled'             => 'The listing has been disabled.',
    'listing_enabled'             => 'The listing has been enabled.',
    'copy_listing'             => 'Unfortunately, it is not allowed to copy sub-listings',
    'copy_listing_yes'             => '<strong>Success!</strong> The listing was cloned.',
    'cant_be_parent'             => 'Unfortunately, this parent can\'t be a child listing.',
    'postage_removed'             => 'The postage option has been removed.',
    'ltc_not_enough'             => 'You have currently not enough Litecoin to buy this product..',
    'btc_not_enough'             => 'You have currently not enough Bitcoin to buy this product.',
    'xmr_not_enough'             => 'You have currently not enough Monero to buy this product.',
    'invalid_pgp_or_address'             => 'You have not set up an BTC refund address or a multisig public key yet. You must update this before you can use Multisig. Go update now <a href="/account/multisig">now</a>.',
    'seller_invalid_pgp'                 => 'The seller has not setup any BTC address or multisig public key.',
    'error'                 => 'There is something wrong, please try again. If this persist, contact support.',
    'multisig_not_received'                 => 'Payment hasn\'t been received yet. If you have just sent it please wait a few seconds before submitting.',
    'order_successfully_placed'                 => 'Order has been succesfull placed, the vendor has 3 days to accept the order.',
    'only_parent_copy'             => 'Unfortunately, you can only copy the head parent listing.',
    'no_multisig'             => 'Please for accepting Multisig to your listing add first a Bitcoin Refund address or Bitcoin Multisig Key. You can do it via <a href="/account/multisig">here</a>',
    'follow_now'             => 'You have unfollowed',
    'unfollowed'             => 'You follow now',
    'missed_some_fields'             => 'You missed some fields',
    'sent_message_to_your_self'             => 'You can\'t sent message to yourself..',
    'favorite_now'         => 'You have succesfull added this listing as your favourite.',
    'bought_ads'         => 'You have succesfull bought the ads. The ads is now active and will only be active for 5 days.',
    'not_allowed'         => 'This child listing is not allowed to have this ads.',
    'already_active'         => 'This is already active.',
    'already_soldout'         => 'This is already sold out. Please try again later',
    'invalid_address'         => 'Invalid Address',
    'invalid_withdraw_pin'         => 'Wrong PIN code.',
    'not_enough_balance'         => 'Not enough balance.',
    'cant_withdraw_to_that_address'         => 'Can\'t withdraw to an address of the same website.',
    'withdraw_problem'         => 'Error sending the transaction please contact support.',
    'payment_sent'         => 'Payment has been succesfully sent.',
    'no_history'         => 'You have not made any transactions.',
    'not_possible_empty'         => 'Can\'t empty transaction when it is still in pending.',
    'history_emptied'         => 'The transaction history has been emptied.',
    'listing_restored'         => '<strong>Success!</strong> The listing has been restored.',
    'listing_removedreal'         => 'The listing(s) has been permanent deleted.',
    'listing_removedtemporary'         => 'The listing(s) has been temporary deleted.',
    'closed_ticket'         => 'You have now closed the ticket.',
    'already_closed'         => 'This ticket has already been closed.',
    'ticket_placed'         => 'The ticket has been created.',
    'added_ticket_comment'         => 'You have added a new comment.',
    'already_placed_rating'         => 'You have already placed a rating.',
    'placed_rating'         => 'You have succesfull placed the rating.',
    'finalized_order'         => 'You have finalized the order. The funds will be now transfered to the vendor. It is optional to leave a feedback.',
    'succesfull_saved_withdraw'         => 'You have succesfully changed the withdraw PIN.',
    'succesfull_saved'         => 'Successfully saved!',
    'old_pin_wrong'         => 'Old Pin is incorrect!',
    'old_password_Wrong'         => 'Old password is incorrect!',
    'mnemonic_wrong'         => 'Mnemonic is not correct',
    'order_shipped'         => 'The order(s) has been shipped.',
    'order_accepted'         => 'The order(s) has been accepted.',
    'order_cancelled'         => 'The order(s) has been cancelled.',
    'order_not_enough_confim'         => 'Not enough confirmations for the transactions, please wait.',
    'order_is_updated'         => 'Order has been succesfull updated',
    'order_funds_is_sent'         => 'Funds have been sent. Transaction:',
    'active_vendor_now'         => 'You are succesfull vendor.',
    'add_pgp_key'         => 'Please add a PGP key to your profile via <a href="/account/change_pgp">here</a>.',
    'invalid_pub_key'         => 'Invalid Public Key.',
    'invalid_btc_address'         => 'Invalid BTC address.',
    'invalid_code_pgp'         => 'Invalid code.',
    'invalid_pgp_key'         => 'Invalid PGP Key',
    'pgp_key_wrong'         => 'Private Key is wrong, make sure that the private key belongs to the public key of the bitcoin address',
    'error_code_contact'         => 'Please contact support. Mention the error code!',

    'accepted' => ':attribute måste accepteras.',
    'active_url' => ':attribute är inte en giltig webbadress.',
    'after' => ':attribute måste vara ett datum efter den :date.',
    'after_or_equal' => ':attribute måste vara ett datum senare eller samma dag som :date.',
    'alpha' => ':attribute får endast innehålla bokstäver.',
    'alpha_dash' => ':attribute får endast innehålla bokstäver, siffror och bindestreck.',
    'alpha_num' => ':attribute får endast innehålla bokstäver och siffror.',
    'array' => ':attribute måste vara en array.',
    'before' => ':attribute måste vara ett datum innan den :date.',
    'before_or_equal' => ':attribute måste vara ett datum före eller samma dag som :date.',
    'between' => [
        'numeric' => ':attribute måste vara en siffra mellan :min och :max.',
        'file' => ':attribute måste vara mellan :min till :max kilobyte stor.',
        'string' => ':attribute måste innehålla :min till :max tecken.',
        'array' => ':attribute måste innehålla mellan :min - :max objekt.',
    ],
    'boolean' => ':attribute måste vara sant eller falskt.',
    'confirmed' => ':attribute bekräftelsen matchar inte.',
    'date' => ':attribute är inte ett giltigt datum.',
    'date_format' => ':attribute matchar inte formatet :format.',
    'different' => ':attribute och :other får inte vara lika.',
    'digits' => ':attribute måste vara :digits tecken.',
    'digits_between' => ':attribute måste vara mellan :min och :max tecken.',
    'dimensions' => ':attribute har felaktiga bilddimensioner.',
    'distinct' => ':attribute innehåller fler än en repetition av samma element.',
    'email' => ':attribute måste innehålla en korrekt e-postadress.',
    'exists' => ':attribute är ogiltigt.',
    'file' => ':attribute måste vara en fil.',
    'filled' => ':attribute är obligatoriskt.',
    'image' => ':attribute måste vara en bild.',
    'in' => ':attribute är ogiltigt.',
    'in_array' => ':attribute finns inte i :other.',
    'integer' => ':attribute måste vara en siffra.',
    'ip' => ':attribute måste vara en giltig IP-adress.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => ':attribute måste vara en giltig JSON-sträng.',
    'max' => [
        'numeric' => ':attribute får inte vara större än :max.',
        'file' => ':attribute får max vara :max kilobyte stor.',
        'string' => ':attribute får max innehålla :max tecken.',
        'array' => ':attribute får inte innehålla mer än :max objekt.',
    ],
    'mimes' => ':attribute måste vara en fil av typen: :values.',
    'mimetypes' => ':attribute måste vara en fil av typen: :values.',
    'min' => [
        'numeric' => ':attribute måste vara större än :min.',
        'file' => ':attribute måste vara minst :min kilobyte stor.',
        'string' => ':attribute måste innehålla minst :min tecken.',
        'array' => ':attribute måste innehålla minst :min objekt.',
    ],
    'not_in' => ':attribute är ogiltigt.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute måste vara en siffra.',
    'present' => ':attribute måste finnas med.',
    'regex' => ':attribute har ogiltigt format.',
    'required' => ':attribute är obligatoriskt.',
    'required_if' => ':attribute är obligatoriskt när :other är :value.',
    'required_unless' => ':attribute är obligatoriskt när inte :other finns bland :values.',
    'required_with' => ':attribute är obligatoriskt när :values är ifyllt.',
    'required_with_all' => ':attribute är obligatoriskt när :values är ifyllt.',
    'required_without' => ':attribute är obligatoriskt när :values ej är ifyllt.',
    'required_without_all' => ':attribute är obligatoriskt när ingen av :values är ifyllt.',
    'same' => ':attribute och :other måste vara lika.',
    'size' => [
        'numeric' => ':attribute måste vara :size.',
        'file' => ':attribute får endast vara :size kilobyte stor.',
        'string' => ':attribute måste innehålla :size tecken.',
        'array' => ':attribute måste innehålla :size objekt.',
    ],
    'string' => ':attribute måste vara en sträng.',
    'timezone' => ':attribute måste vara en giltig tidszon.',
    'unique' => ':attribute används redan.',
    'uploaded' => ':attribute kunde inte laddas upp.',
    'url' => ':attribute har ett ogiltigt format.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [],
];