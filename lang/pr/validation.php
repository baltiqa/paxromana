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
    

    'accepted' => 'O campo :attribute deverá ser aceite.',
    'active_url' => 'O campo :attribute não contém um URL válido.',
    'after' => 'O campo :attribute deverá conter uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deverá conter uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute deverá conter apenas letras.',
    'alpha_dash' => 'O campo :attribute deverá conter apenas letras, números e traços.',
    'alpha_num' => 'O campo :attribute deverá conter apenas letras e números .',
    'array' => 'O campo :attribute deverá conter uma coleção de elementos.',
    'before' => 'O campo :attribute deverá conter uma data anterior a :date.',
    'before_or_equal' => 'O Campo :attribute deverá conter uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => 'O campo :attribute deverá ter um valor entre :min - :max.',
        'file' => 'O campo :attribute deverá ter um tamanho entre :min - :max kilobytes.',
        'string' => 'O campo :attribute deverá conter entre :min - :max caracteres.',
        'array' => 'O campo :attribute deverá conter entre :min - :max elementos.',
    ],
    'boolean' => 'O campo :attribute deverá conter o valor verdadeiro ou falso.',
    'confirmed' => 'A confirmação para o campo :attribute não coincide.',
    'date' => 'O campo :attribute não contém uma data válida.',
    'date_format' => 'A data indicada para o campo :attribute não respeita o formato :format.',
    'different' => 'Os campos :attribute e :other deverão conter valores diferentes.',
    'digits' => 'O campo :attribute deverá conter :digits caracteres.',
    'digits_between' => 'O campo :attribute deverá conter entre :min a :max caracteres.',
    'dimensions' => 'O campo :attribute deverá conter uma dimensão de imagem válida.',
    'distinct' => 'O campo :attribute contém um valor duplicado.',
    'email' => 'O campo :attribute não contém um endereço de correio eletrónico válido.',
    'exists' => 'O valor selecionado para o campo :attribute é inválido.',
    'file' => 'O campo :attribute deverá conter um ficheiro.',
    'filled' => 'É obrigatória a indicação de um valor para o campo :attribute.',
    'image' => 'O campo :attribute deverá conter uma imagem.',
    'in' => 'O campo :attribute não contém um valor válido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute deverá conter um número inteiro.',
    'ip' => 'O campo :attribute deverá conter um IP válido.',
    'ipv4' => 'O campo :attribute deverá conter um IPv4 válido.',
    'ipv6' => 'O campo :attribute deverá conter um IPv6 válido.',
    'json' => 'O campo :attribute deverá conter um texto JSON válido.',
    'max' => [
        'numeric' => 'O campo :attribute não deverá conter um valor superior a :max.',
        'file' => 'O campo :attribute não deverá ter um tamanho superior a :max kilobytes.',
        'string' => 'O campo :attribute não deverá conter mais de :max caracteres.',
        'array' => 'O campo :attribute não deverá conter mais de :max elementos.',
    ],
    'mimes' => 'O campo :attribute deverá conter um ficheiro do tipo: :values.',
    'mimetypes' => 'O campo :attribute deverá conter um ficheiro do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deverá ter um valor superior ou igual a :min.',
        'file' => 'O campo :attribute deverá ter no mínimo :min kilobytes.',
        'string' => 'O campo :attribute deverá conter no mínimo :min caracteres.',
        'array' => 'O campo :attribute deverá conter no mínimo :min elementos.',
    ],
    'not_in' => 'O campo :attribute contém um valor inválido.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'O campo :attribute deverá conter um valor numérico.',
    'present' => 'O campo :attribute deverá estar presente.',
    'regex' => 'O formato do valor para o campo :attribute é inválido.',
    'required' => 'É obrigatória a indicação de um valor para o campo :attribute.',
    'required_if' => 'É obrigatória a indicação de um valor para o campo :attribute quando o valor do campo :other é igual a :value.',
    'required_unless' => 'É obrigatória a indicação de um valor para o campo :attribute a menos que :other esteja presente em :values.',
    'required_with' => 'É obrigatória a indicação de um valor para o campo :attribute quando :values está presente.',
    'required_with_all' => 'É obrigatória a indicação de um valor para o campo :attribute quando um dos :values está presente.',
    'required_without' => 'É obrigatória a indicação de um valor para o campo :attribute quando :values não está presente.',
    'required_without_all' => 'É obrigatória a indicação de um valor para o campo :attribute quando nenhum dos :values está presente.',
    'same' => 'Os campos :attribute e :other deverão conter valores iguais.',
    'size' => [
        'numeric' => 'O campo :attribute deverá conter o valor :size.',
        'file' => 'O campo :attribute deverá ter o tamanho de :size kilobytes.',
        'string' => 'O campo :attribute deverá conter :size caracteres.',
        'array' => 'O campo :attribute deverá conter :size elementos.',
    ],
    'string' => 'O campo :attribute deverá conter texto.',
    'timezone' => 'O campo :attribute deverá ter um fuso horário válido.',
    'unique' => 'O valor indicado para o campo :attribute já se encontra registado.',
    'uploaded' => 'O upload do ficheiro :attribute falhou.',
    'url' => 'O formato do URL indicado para o campo :attribute é inválido.',

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

    'attributes' => [
    ],
];