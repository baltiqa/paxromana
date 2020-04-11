<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;
use App\Models\Category;

class ListingForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
			'attr' => [
                'disabled' => 'disabled',
                'class' => 'form-control',
            ],
            'rules' => 'required|min:3'
        ]);    
        
        $this->add('deleted_at', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
