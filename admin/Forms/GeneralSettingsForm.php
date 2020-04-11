<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class GeneralSettingsForm extends Form
{
    public function buildForm()
    {
        $this->add('marketplace_transaction_fee', 'text', [
            'rules' => '',
            'default_value' => $this->getData('marketplace_transaction_fee')
        ]);
        $this->add('marketplace_withdraw_fee', 'text', [
            'rules' => '',
            'default_value' => $this->getData('marketplace_withdraw_fee')
        ]);
    

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }

}
