<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingDormitorioSolteiro extends AbstractModel
{
	protected $table = 'briefingdormitoriosolteiro';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'dormitoriosolteiro';
			$item->tpLabel = 'Dormitório Solteiro';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}