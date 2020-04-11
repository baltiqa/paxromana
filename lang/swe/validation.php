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

    'accepted' => ':attribute m�ste accepteras.',
    'active_url' => ':attribute �r inte en giltig webbadress.',
    'after' => ':attribute m�ste vara ett datum efter den :date.',
    'after_or_equal' => ':attribute m�ste vara ett datum senare eller samma dag som :date.',
    'alpha' => ':attribute f�r endast inneh�lla bokst�ver.',
    'alpha_dash' => ':attribute f�r endast inneh�lla bokst�ver, siffror och bindestreck.',
    'alpha_num' => ':attribute f�r endast inneh�lla bokst�ver och siffror.',
    'array' => ':attribute m�ste vara en array.',
    'before' => ':attribute m�ste vara ett datum innan den :date.',
    'before_or_equal' => ':attribute m�ste vara ett datum f�re eller samma dag som :date.',
    'between' => [
        'numeric' => ':attribute m�ste vara en siffra mellan :min och :max.',
        'file' => ':attribute m�ste vara mellan :min till :max kilobyte stor.',
        'string' => ':attribute m�ste inneh�lla :min till :max tecken.',
        'array' => ':attribute m�ste inneh�lla mellan :min - :max objekt.',
    ],
    'boolean' => ':attribute m�ste vara sant eller falskt.',
    'confirmed' => ':attribute bekr�ftelsen matchar inte.',
    'date' => ':attribute �r inte ett giltigt datum.',
    'date_format' => ':attribute matchar inte formatet :format.',
    'different' => ':attribute och :other f�r inte vara lika.',
    'digits' => ':attribute m�ste vara :digits tecken.',
    'digits_between' => ':attribute m�ste vara mellan :min och :max tecken.',
    'dimensions' => ':attribute har felaktiga bilddimensioner.',
    'distinct' => ':attribute inneh�ller fler �n en repetition av samma element.',
    'email' => ':attribute m�ste inneh�lla en korrekt e-postadress.',
    'exists' => ':attribute �r ogiltigt.',
    'file' => ':attribute m�ste vara en fil.',
    'filled' => ':attribute �r obligatoriskt.',
    'image' => ':attribute m�ste vara en bild.',
    'in' => ':attribute �r ogiltigt.',
    'in_array' => ':attribute finns inte i :other.',
    'integer' => ':attribute m�ste vara en siffra.',
    'ip' => ':attribute m�ste vara en giltig IP-adress.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => ':attribute m�ste vara en giltig JSON-str�ng.',
    'max' => [
        'numeric' => ':attribute f�r inte vara st�rre �n :max.',
        'file' => ':attribute f�r max vara :max kilobyte stor.',
        'string' => ':attribute f�r max inneh�lla :max tecken.',
        'array' => ':attribute f�r inte inneh�lla mer �n :max objekt.',
    ],
    'mimes' => ':attribute m�ste vara en fil av typen: :values.',
    'mimetypes' => ':attribute m�ste vara en fil av typen: :values.',
    'min' => [
        'numeric' => ':attribute m�ste vara st�rre �n :min.',
        'file' => ':attribute m�ste vara minst :min kilobyte stor.',
        'string' => ':attribute m�ste inneh�lla minst :min tecken.',
        'array' => ':attribute m�ste inneh�lla minst :min objekt.',
    ],
    'not_in' => ':attribute �r ogiltigt.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute m�ste vara en siffra.',
    'present' => ':attribute m�ste finnas med.',
    'regex' => ':attribute har ogiltigt format.',
    'required' => ':attribute �r obligatoriskt.',
    'required_if' => ':attribute �r obligatoriskt n�r :other �r :value.',
    'required_unless' => ':attribute �r obligatoriskt n�r inte :other finns bland :values.',
    'required_with' => ':attribute �r obligatoriskt n�r :values �r ifyllt.',
    'required_with_all' => ':attribute �r obligatoriskt n�r :values �r ifyllt.',
    'required_without' => ':attribute �r obligatoriskt n�r :values ej �r ifyllt.',
    'required_without_all' => ':attribute �r obligatoriskt n�r ingen av :values �r ifyllt.',
    'same' => ':attribute och :other m�ste vara lika.',
    'size' => [
        'numeric' => ':attribute m�ste vara :size.',
        'file' => ':attribute f�r endast vara :size kilobyte stor.',
        'string' => ':attribute m�ste inneh�lla :size tecken.',
        'array' => ':attribute m�ste inneh�lla :size objekt.',
    ],
    'string' => ':attribute m�ste vara en str�ng.',
    'timezone' => ':attribute m�ste vara en giltig tidszon.',
    'unique' => ':attribute anv�nds redan.',
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