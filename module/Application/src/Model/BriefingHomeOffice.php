<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingHomeOffice extends AbstractModel
{
	protected $table = 'briefinghomeoffice';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'homeoffice';
			$item->tpLabel = 'Home Office';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}