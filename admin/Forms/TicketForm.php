<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Permission;
use App\Models\Category;

class TicketForm extends Form
{
    public function buildForm()
    {
		
		$this->add('title', 'text', [
			'label' => "Subject",
            'attr' => ['disabled' => 'disabled'],
		]);
		
		
		$this->add('category', 'text', [
			'label' => "Category",
            'attr' => ['disabled' => 'disabled'],
        ]);
		
		$this->add('text', 'textarea', [
            'label' => "Reason",
            'attr' => ['disabled' => 'disabled'],
        ]);
        
        $this->add('message', 'textarea', [
            'label' => "Text",
            'attr' => ['style' => 'height: 100px'],
        ]);
		
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
