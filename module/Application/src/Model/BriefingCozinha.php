<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingCozinha extends AbstractModel
{
	protected $table = 'briefingcozinha';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'Cozinha';
			$item->tpLabel = 'Cozinha';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}