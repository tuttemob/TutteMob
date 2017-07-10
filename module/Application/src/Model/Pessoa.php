<?php
namespace Application\Model;

use Zend\Authentication\Adapter\DbTable;

class Pessoa extends AbstractModel
{
	protected $table = 'pessoa';
	
	const NAMESPACE_USER_SESSION = 'TutteMobSession';
	
	/**
	 * Autentica o usuário no sistema
	 *
	 * @param string $email
	 * @param string $password
	 *
	 * @return bool
	 */
	public function authentication($login, $password)
	{
		$authAdapter = new DbTable\CredentialTreatmentAdapter($this->adapter, $this->table, 'email', 'senha', 'MD5(?)');
	
		$authAdapter->setIdentity($login);
		$authAdapter->setCredential($password);
			
		$authenticate = $authAdapter->authenticate();
	
		if($authenticate->isValid())
		{
			$columnsToOmit = array('senha');
			$result = $authAdapter->getResultRowObject(null, $columnsToOmit);
			
			$_SESSION[self::NAMESPACE_USER_SESSION] = $result;
				
			return true;
		}
	
		return false;
	}
	
	/**
	 * Obtem os dados de um usuário logado
	 * @return \ArrayObject|bool
	 */
	public function getUsuarioLogado()
	{
		if(!isset($_SESSION[self::NAMESPACE_USER_SESSION]) || empty($_SESSION[self::NAMESPACE_USER_SESSION]))
		{
			return false;
		}
	
		$storage = $_SESSION[self::NAMESPACE_USER_SESSION];
		
		$select = $this->select(array(
			'idpessoa' => $storage->idpessoa,
		));
	
		return $select->current();
	}
	
	/**
	 * Desloga o usuário do sistema
	 */
	public function usuarioLogout()
	{
		unset($_SESSION[self::NAMESPACE_USER_SESSION]);
		return true;
	}
}