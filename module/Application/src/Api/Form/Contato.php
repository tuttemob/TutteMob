<?php
use Zend\Form\Form;
class Contato extends Form
{
	public function __construct(){
		parent::__construct('contato');
		// cria os filtros de validação
		$filter = new InputFilter();
		$filter->add(array(
			'name' => 'nome',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim')
			)
		));
		
		$this->setInputFilter($filter);
	}
}