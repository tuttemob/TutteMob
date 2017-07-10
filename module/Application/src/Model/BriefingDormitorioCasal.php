<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingDormitorioCasal extends AbstractModel
{
	protected $table = 'briefingdormitoriocasal';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'dormitoriocasal';
			$item->tpLabel = 'Dormitório Casal';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}