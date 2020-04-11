<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Role;

    class UserForm extends Form
    {
        public function buildForm()
        {
            $this->add('username', 'text', [
                'attr' => ['disabled' => 'disabled'],
            ]);

             $this->add('withdraw_disabled', 'checkbox', [
                'rules' => ''
            ]);
            $this->add('is_banned', 'checkbox', [
                'rules' => ''
            ]);
            $this->add('reason_ban', 'text', [
                'rules' => ''
            ]);
    
    
            $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
        }
    }
