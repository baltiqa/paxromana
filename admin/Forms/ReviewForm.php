<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class ReviewForm extends Form
{
    protected $formOptions = [
        'id' => 'filter_form'
    ];

    public function buildForm()
    {

        $this->add('comment', 'text', [
            'rule' => ''
        ]);

        $this->add('rate', 'text', [
            'attr' => ['disabled' => 'disabled'],
        ]);


		
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

    }
}
