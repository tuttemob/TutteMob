<?php
namespace Application\Controller\Rest;

use Zend\Db\Sql\Predicate\Expression as PredicateExpression;
use Zend\Db\Sql\Expression as SqlExpression;

class Clientes extends AbstractRest
{
	public function get($id)
	{
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
	
		$data = $modelPessoa->lista(
			array('idpapel' => 1, 'idpessoa' => $id)
		);
	
		$this->jsonDispatch(reset($data));
	}
	
	public function getList()
	{
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$q = null;
		
		if(isset($_GET['q'])){
			$q = trim($_GET['q']);
		}
		
		if($q != null){
			$q = urldecode($q);
			$data = $modelPessoa->lista(
				array('idpapel' => 1, new PredicateExpression("nome LIKE ?", "%{$q}%")),
				'nome ASC'
			);
		}else{
			$data = $modelPessoa->lista(
				array('idpapel' => 1),
				'nome ASC'
			);
		}
		
		$this->jsonDispatch($data);
	}
	
	public function create($data){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$data['datacriacao'] = new SqlExpression("NOW()");
		$data['idpapel']	 = 1;
		
		try{
			$idcliente = $modelPessoa->cria($data);
			
			$this->jsonDispatch(array('idpessoa' => $idcliente));
		}catch (\Exception $e){
			$this->jsonDispatch(array('idpessoa' => false));
		}
	}
	
	public function update($id, $data){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		try{
			unset($data['idpessoa']);
			
			$modelPessoa->update($data, array('idpessoa' => $id));
				
			$this->jsonDispatch(array('idpessoa' => $id));
		}catch (\Exception $e){
			$this->jsonDispatch(array('idpessoa' => false));
		}
	}
	
	public function delete($id){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
	
		try{
			$modelPessoa->delete(array('idpessoa' => $id));
	
			$this->jsonDispatch(array('idpessoa' => $id));
		}catch (\Exception $e){
			$this->jsonDispatch(array('idpessoa' => false));
		}
	}
}