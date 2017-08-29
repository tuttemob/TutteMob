<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingMedidasArquivos extends AbstractModel
{
	protected $table = 'briefingmedidasarquivos';

	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
}