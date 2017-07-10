<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingDormitorioInfantil extends AbstractModel
{
	protected $table = 'briefingdormitorioinfantil';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'dormitorioinfantil';
			$item->tpLabel = 'Dormitório Infantil';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}