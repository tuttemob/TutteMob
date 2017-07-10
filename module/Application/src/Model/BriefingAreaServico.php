<?php
namespace Application\Model;

use Zend\Db\Sql\Predicate\Expression;

class BriefingAreaServico extends AbstractModel
{
	protected $table = 'briefingareaservico';
	
	public function cria($data){
		$data['datacriacao'] = new Expression("NOW()");
		
		return parent::cria($data);
	}
	
	public function lista($where=null,$order=null){
		$result = parent::lista($where,$order);
		
		$retorno = array();
		foreach ($result as $item) {
			$item->tp = 'areaservico';
			$item->tpLabel = 'Área de serviço';
			$retorno[] = $item;
		}
		
		return $retorno;
	}
}