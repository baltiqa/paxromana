<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class DisputeForm extends Form
{
    public function buildForm()
    {
        $this->add('moderator_message', 'textarea', [
            'label' => "Moderator message",
            'attr' => ['style' => 'height: 100px'],
        ]);
        $this->add('Submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

    }
}
