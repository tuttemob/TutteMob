<?phpnamespace Application\Api\Form;
use Zend\Form\Form;use Zend\InputFilter\InputFilter;
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
				$filter->add(array(			'name' => 'email',			'required' => true,			'filters' => array(				array('name' => 'StripTags'),				array('name' => 'StringTrim')			),			'validators' => array(				array('name' => 'EmailAddress'),			)		));		$filter->add(array(			'name' => 'telefone',			'required' => true,			'filters' => array(				array('name' => 'StripTags'),				array('name' => 'StringTrim')			)		));				$filter->add(array(			'name' => 'mensagem',			'required' => true,			'filters' => array(				array('name' => 'StripTags'),				array('name' => 'StringTrim')			)		));		
		$this->setInputFilter($filter);
	}
}