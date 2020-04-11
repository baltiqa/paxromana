<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class PageForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required|min:3'
        ]);

       
        $this->add('body', 'textarea', [
            'rules' => '',
            'attr' => ['style' => 'height: 800px'],
        ]);

        $this->add('featured', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
