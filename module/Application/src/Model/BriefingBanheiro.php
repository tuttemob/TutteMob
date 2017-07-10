<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingBanheiro extends AbstractModel
{
	protected $table = 'briefingbanheiro';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'banheiro';
			$item->tpLabel = 'Banheiro';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}