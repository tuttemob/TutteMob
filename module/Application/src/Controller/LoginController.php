<?php
namespace Application\Controller;

use Zend\Db\Sql\Expression as SqlExpression;
use Zend\View\Model\ViewModel;
use Application\Api\Notify\Template;
use Application\Api\Notify\Email;

class LoginController extends ManagerController {
	
	public function indexAction()
	{
		$error = null;
		$success = null;
		
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			if(isset($data['acao']) && $data['acao'] == 'login'){
				unset($data['acao']);
				
				if($modelPessoa->authentication($data['email'], $data['senha'])){
					$usuario = $modelPessoa->getUsuarioLogado();
					if($usuario->dataconfirmacao){
						$this->redirect()->toRoute('clientes');
					}else{
						$modelPessoa->usuarioLogout();
						$error = 'Cliente com e-mail não confirmado.';
					}
				}else{
					$error = 'E-mail ou senha não conferem.';
				}
			}
		}
		
		// apresenta mensagem ao usuario
		$params = $this->params('ctoken');
		if($params != ''){
			list($type, $msg) = explode('=', $params);
				
			if($type == 'error'){
				$error = base64_decode($msg);
			}
				
			if($type == 'success'){
				$success = base64_decode($msg);
			}
		}
		
		return new ViewModel(array(
			'success' => $success,
			'error'  => $error
		));
	}

	public function facebookAction()
	{
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$error = null;
		$success = null;

		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();

			if(isset($data['accessToken'])){
				// obtem os dados do usuario pelo facebook
				$facebookUser = \Application\Api\Facebook::getUser($data['accessToken']);

				if($facebookUser){
					// verifica a existencia do facebook id
					$pessoa = reset($modelPessoa->lista(array('facebook_userid' => $facebookUser['id'])));
					if(!$pessoa){
						// verifica a existencia do email
						$pessoa = reset($modelPessoa->lista(array('email' => $facebookUser['email'])));
						if($pessoa){
							$modelPessoa->update(
								array(
									'facebook_userid' => $facebookUser['id'], 
									'dataconfirmacao' => new SqlExpression("NOW()")
								),
								array('idpessoa' => $pessoa->idpessoa)
							);

							$success = true;
						}else{
							$idpessoa = $modelPessoa->cria([
								'facebook_userid' => $facebookUser['id'],
								'nome' => $facebookUser['name'],
								'email' => $facebookUser['email'],
								'senha' => md5(date('siHdmY')),
								'fone_cel' => '',
								'idpapel' => 1,
								'datacriacao' => new SqlExpression("NOW()"),
								'dataconfirmacao' => new SqlExpression("NOW()"),
							]);

							$pessoa = reset($modelPessoa->lista(array('email' => $facebookUser['email'])));

							$success = true;
						}
					}

					// loga o usuário com os dados da Pessoa
					$modelPessoa->authenticationPessoa($pessoa);
					$success = true;
				}else{
					$error = 'Usuário não encontrado ou sem permissões para o site.';
				}
			}else{
				$error = 'Requisição inválida.';
			}
		}else{
			$error = 'Requisição inválida.';
		}

		if($success){
			$this->jsonDispatch(array('data' => 'success'));
		}else{
			$this->jsonDispatch(array('data' => 'error', 'msg' => $error));
		}
	}
	
	public function logoutAction(){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		$modelPessoa->usuarioLogout();
		
		$this->redirect()->toRoute('home');
	}
	
	public function confirmacaoAction(){
		$ctoken = trim($this->params('ctoken'));

		$error = null;
		$success = null;
		
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		if($ctoken){
			// verifica a existencia do ctoken
			$pessoa = reset($modelPessoa->lista(array('ctoken' => $ctoken)));
			
			if($pessoa){
				$data = array( 'dataconfirmacao' => new SqlExpression("NOW()") );
				
				$modelPessoa->update($data, array('idpessoa' => $pessoa->idpessoa));
				
				$success = "E-mail confirmado com sucesso.";
			}
			else {
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		}else{
			$error = 'Ocorreu um erro, tente novamente mais tarde.';
		}
		
		return new ViewModel(array(
			'success' => $success,
			'error'  => $error
		));
	}

	public function esqueceuSenhaAction()
	{
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$error = null;
		$success = null;

		if($this->request->isPost()){
			$data = $this->request->getPost();

			// verifica a existencia da pessoa por email
			$pessoa = reset($modelPessoa->lista(array('email' => $data->email)));
			
			if($pessoa){
				// gera uma nova senha
				$senhaToken = md5($pessoa->idpessoa . date('siHdmY'));

				$dataUpdate = array( 'senhatoken' => $senhaToken );
				
				$modelPessoa->update($dataUpdate, array('idpessoa' => $pessoa->idpessoa));
				
				$macros = array(
					'email' => $pessoa->email, 
					'nome'  => $pessoa->nome,
					'senhaToken' => $senhaToken
				);
				$template = new Template('template.EsqueceuSenha.phtml', $macros);
				$email    = new Email($template);
				$email->setSubject('Cadastrar nova senha');
				$email->addTo($pessoa->email, $pessoa->nome);
				$email->send();

				$success = "Os passos para alterar sua senha foram enviados para o seu email.";
			}
			else {
				$error = 'O e-mail informado não pertence a nossa lista de usuários.';
			}

			if($error){
				$type = 'error';
				$msg  = base64_encode($error);
			}
			
			if($success){
				$type = 'success';
				$msg  = base64_encode($success);
			}
			
			return $this->redirect()->toRoute('login', array(
				'action' => 'esqueceu-senha',
				'ctoken' => $type.'='.$msg
			));
		}

		$params = $this->params('ctoken');
		if($params != '' && !preg_match('/^[0-9]*$/', $params)){
			preg_match('/^([A-z\-0-9]*)\=(.*)$/', $params, $matches);
			list($match, $type, $msg) = $matches;
			
			if($type == 'error'){
				$error = base64_decode($msg); 
			}
			else if($type == 'success'){
				$success = base64_decode($msg);
			}
		}

		return new ViewModel(array(
			'success' => $success,
			'error'  => $error
		));
	}

	public function cadastrarSenhaAction()
	{
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$error = null;
		$success = null;
		$senhaToken = null;

		if($this->request->isPost()){
			$data = $this->request->getPost();

			if($data->senhaToken != ''){
				// verifica a existencia da pessoa por email
				$pessoa = reset($modelPessoa->lista(array('senhaToken' => $data->senhaToken)));
				if($pessoa){
					$dataUpdate = array('senha' => md5($data->senha), 'senhaToken' => '');
					$modelPessoa->update($dataUpdate, array('idpessoa' => $pessoa->idpessoa));

					$success = 'Nova senha cadastrada com sucesso.';
				}else{
					$error = 'Cadastro não encontrado.';
				}
			}else{
				$error = 'Código de troca de senha inválido.';
			}

			if($error){
				$type = 'error';
				$msg  = base64_encode($error);
			}
			
			if($success){
				$type = 'success';
				$msg  = base64_encode($success);
			}
			
			return $this->redirect()->toRoute('login', array(
				'action' => 'cadastrar-senha',
				'ctoken' => $type.'='.$msg
			));
		}

		$params = $this->params('ctoken');
		if($params != '' && !preg_match('/^[0-9]*$/', $params)){
			preg_match('/^([A-z\-0-9]*)\=(.*)$/', $params, $matches);
			list($match, $type, $msg) = $matches;
			
			if($type == 'error'){
				$error = base64_decode($msg); 
			}
			else if($type == 'success'){
				$success = base64_decode($msg);
			}
			else if($type == 'senhaToken'){
				$senhaToken = $msg;
			}
		}

		return new ViewModel(array(
			'senhaToken' => $senhaToken,
			'success' => $success,
			'error'  => $error
		));
	}
}