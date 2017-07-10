<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingHomeTheater extends AbstractModel
{
	protected $table = 'briefinghometheater';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'hometheater';
			$item->tpLabel = 'Home Theater';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}