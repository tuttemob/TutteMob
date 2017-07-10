<?php
namespace Application\Controller;

//use Zend\Db\Sql\Predicate\Expression as PredicateExpression;
use Zend\Db\Sql\Expression as SqlExpression;
use Zend\View\Model\ViewModel;
use Application\Api\Form\Cadastro;
use Zend\Form\FormInterface;
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
			
			if(isset($data['acao']) && $data['acao'] == 'cadastrar'){
				try{
					unset($data['acao']);
					
					$form = new Cadastro();
					$form->setData($data);
					
					if($form->isValid()){
						$data = $form->getData(FormInterface::VALUES_AS_ARRAY);
						
						// verifica a existencia do email
						$pessoa = reset($modelPessoa->lista(array('email' => $data['email'])));
						
						if($pessoa){
							$error = 'O e-mail informado já possui cadastro.';
						}
						else {
							// adiciona os campos extras
							$data['datacriacao'] = new SqlExpression("NOW()");
							$data['idpapel']	 = 1;
							
							// criptografa a senha
							$data['senha'] = md5($data['senha']);
							
							$data['ctoken'] = md5(time());
							
							$modelPessoa->cria($data);
							
							// envia email de notificacao
							$macros = array(
								'ctoken' => $data['ctoken'] 	
							);
							
							$template = new Template('template.CadastroConfirmacao.phtml', $macros);
							$email    = new Email($template);
							$email->setSubject('Confirmação de cadastro');
							$email->addTo($data['email'], $data['nome']);
							$email->send();
							
							$success = 'Seu cadastro foi concluído. Um e-mail de confirmação foi enviado para você.';
						}
					}else{
						$error = 'Preencha corretamente os campos do formulário.';
					}
				}catch (\Exception $e){
					$error = 'Ocorreu um erro, tente novamente mais tarde.' + $e;
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
					'ctoken' => $type.'='.$msg
				));
			}
			
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
}