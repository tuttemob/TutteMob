<?php
namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

abstract class AbstractModel extends TableGateway {
	
	/**
	 * Construtor
	 * @param Adapter $adapter
	 */
	public function __construct(Adapter $adapter){
		parent::__construct($this->table, $adapter);
	}
	
	/**
	 * Retorna a lista com base na pesquisa
	 * @param mixed $where
	 * @param string $order
	 */
	public function lista($where = null,$order = null){
		$sql = $this->getSql()->select();
	
		if($where){
			$sql->where($where);
		}
	
		if($order) {
			$sql->order($order);
		}
	
		$stmt = $this->getSql()->prepareStatementForSqlObject($sql);
		$result = $stmt->execute();
	
		$retorno = array();
		foreach ($result as $item) {
			$retorno[] = (object) $item;
		}
	
		return $retorno;
	}
	
	public function cria($data){
		$result = $this->insert($data);
		
		$lastId = $this->lastInsertValue;
		
		return $lastId;
	}
}