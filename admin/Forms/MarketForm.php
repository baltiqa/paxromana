<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Role;

    class MarketForm extends Form
    {
        public function buildForm()
        {
            
         
    
            $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
        }
    }
