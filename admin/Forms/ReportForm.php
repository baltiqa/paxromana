<?php

namespace Modules\ModerateListings\Forms;

use Kris\LaravelFormBuilder\Form;

class ReportForm extends Form
{
    public function buildForm()
    {

        $this->add('reason', 'text', [
            'label' => "Reason",
            'attr' => ['disabled' => 'disabled'],
            'default_value' => $this->getData('reason')
        ]);
        $this->add('notes', 'textarea', [
            'label' => "User notes",
            'attr' => ['disabled' => 'disabled', 'style' => 'height: 100px'],
            'default_value' => $this->getData('notes')
        ]);
        $this->add('active', 'select', [
            'choices' => [1 => 'Open', 0 => 'Close'],
            'label' => "Status",
            'default_value' => $this->getData('active')
        ]);
        $this->add('action', 'select', [
            'choices' => [ null => ' -- SELECT -- ', 'do_nothing' => 'Do nothing', 'ban_user' => 'Ban user',],
            'label' => "Action to perform",
            'default_value' => null
        ]);
        $this->add('moderator_message', 'textarea', [
            'label' => "Moderator message",
            'attr' => ['style' => 'height: 100px'],
            'default_value' => $this->getData('moderator_message')
        ]);
        $this->add('Submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

    }
}
