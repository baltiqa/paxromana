<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
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

    'accepted' => ':attribute deve essere accettato.',
    'active_url' => ':attribute non � un URL valido.',
    'after' => ':attribute deve essere una data successiva al :date.',
    'after_or_equal' => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha' => ':attribute pu� contenere solo lettere.',
    'alpha_dash' => ':attribute pu� contenere solo lettere, numeri e trattini.',
    'alpha_num' => ':attribute pu� contenere solo lettere e numeri.',
    'array' => ':attribute deve essere un array.',
    'before' => ':attribute deve essere una data precedente al :date.',
    'before_or_equal' => ':attribute deve essere una data precedente o uguale al :date.',
    'between' => [
        'numeric' => ':attribute deve trovarsi tra :min - :max.',
        'file' => ':attribute deve trovarsi tra :min - :max kilobytes.',
        'string' => ':attribute deve trovarsi tra :min - :max caratteri.',
        'array' => ':attribute deve avere tra :min - :max elementi.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'Il campo di conferma per :attribute non coincide.',
    'date' => ':attribute non � una data valida.',
    'date_format' => ':attribute non coincide con il formato :format.',
    'different' => ':attribute e :other devono essere differenti.',
    'digits' => ':attribute deve essere di :digits cifre.',
    'digits_between' => ':attribute deve essere tra :min e :max cifre.',
    'dimensions' => "Le dimensioni dell'immagine di :attribute non sono valide.",
    'distinct' => ':attribute contiene un valore duplicato.',
    'email' => ':attribute non � valido.',
    'exists' => ':attribute selezionato non � valido.',
    'file' => ':attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve contenere un valore.',
    'image' => ":attribute deve essere un'immagine.",
    'in' => ':attribute selezionato non � valido.',
    'in_array' => 'Il valore del campo :attribute non esiste in :other.',
    'integer' => ':attribute deve essere un numero intero.',
    'ip' => ':attribute deve essere un indirizzo IP valido.',
    'ipv4' => ':attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => ':attribute deve essere un indirizzo IPv6 valido.',
    'json' => ':attribute deve essere una stringa JSON valida.',
    'max' => [
        'numeric' => ':attribute non pu� essere superiore a :max.',
        'file' => ':attribute non pu� essere superiore a :max kilobytes.',
        'string' => ':attribute non pu� contenere pi� di :max caratteri.',
        'array' => ':attribute non pu� avere pi� di :max elementi.',
    ],
    'mimes' => ':attribute deve essere del tipo: :values.',
    'mimetypes' => ':attribute deve essere del tipo: :values.',
    'min' => [
        'numeric' => ':attribute deve essere almeno :min.',
        'file' => ':attribute deve essere almeno di :min kilobytes.',
        'string' => ':attribute deve contenere almeno :min caratteri.',
        'array' => ':attribute deve avere almeno :min elementi.',
    ],
    'not_in' => 'Il valore selezionato per :attribute non � valido.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute deve essere un numero.',
    'present' => 'Il campo :attribute deve essere presente.',
    'regex' => 'Il formato del campo :attribute non � valido.',
    'required' => 'Il campo :attribute � richiesto.',
    'required_if' => 'Il campo :attribute � richiesto quando :other � :value.',
    'required_unless' => 'Il campo :attribute � richiesto a meno che :other sia in :values.',
    'required_with' => 'Il campo :attribute � richiesto quando :values � presente.',
    'required_with_all' => 'Il campo :attribute � richiesto quando :values � presente.',
    'required_without' => 'Il campo :attribute � richiesto quando :values non � presente.',
    'required_without_all' => 'Il campo :attribute � richiesto quando nessuno di :values � presente.',
    'same' => ':attribute e :other devono coincidere.',
    'size' => [
        'numeric' => ':attribute deve essere :size.',
        'file' => ':attribute deve essere :size kilobytes.',
        'string' => ':attribute deve contenere :size caratteri.',
        'array' => ':attribute deve contenere :size elementi.',
    ],
    'string' => ':attribute deve essere una stringa.',
    'timezone' => ':attribute deve essere una zona valida.',
    'unique' => ':attribute � stato gi� utilizzato.',
    'uploaded' => ':attribute non � stato caricato.',
    'url' => 'Il formato del campo :attribute non � valido.',

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