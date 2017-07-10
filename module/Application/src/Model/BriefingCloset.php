<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingCloset extends AbstractModel
{
	protected $table = 'briefingcloset';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'closet';
			$item->tpLabel = 'Closet';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}