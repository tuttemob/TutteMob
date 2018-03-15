<?php
namespace Application\Controller;

use Zend\Db\Sql\Expression as SqlExpression;
use Zend\View\Model\ViewModel;
use Application\Api\Form\Cadastro;
use Application\Api\Form\Contato;
use Zend\Form\FormInterface;
use Application\Api\Notify\Template;
use Application\Api\Notify\Email;

class SiteController extends ManagerController {
	
	public function indexAction()
	{
		return new ViewModel(array());
	}
	
	public function ambientesAction()
	{
		return new ViewModel(array());
	}
	
	/**
	 * Views lincadas ao Ambientes
	 * @return \Zend\View\Model\ViewModel
	 */
	public function ambientesHomeTheaterAction()
	{
		return new ViewModel(array());
	}
	
	public function ambientesCozinhaAction()
	{
		return new ViewModel(array());
	}
	
	public function ambientesQuartoAction()
	{
		return new ViewModel(array());
	}
	
	public function ambientesHomeOfficeAction()
	{
		return new ViewModel(array());
	}
	
	public function ambientesBanheiroAction()
	{
		return new ViewModel(array());
	}
	
	public function sobreNosAction()
	{
		return new ViewModel(array());
	}
	
	public function franquiasFisicasAction()
	{
		return new ViewModel(array());
	}
	
	public function experienciaClientesAction()
	{
		return new ViewModel(array());
	}
	
	public function descubraLinkUmAction()
	{
		return new ViewModel(array());
	}
	
	public function descubraLinkDoisAction()
	{
		return new ViewModel(array());
	}
	
	public function descubraLinkTresAction()
	{
		return new ViewModel(array());
	}
	
	public function depoimentosClientesAction()
	{
		return new ViewModel(array());
	}
	
	/**
	 * Cadastrar usuarios
	 * @return \Zend\View\Model\ViewModel
	 */
	public function cadastrarAction()
	{
		$error = null;
		$success = null;
		
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');

		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();

			if(isset($data['acao']) && $data['acao'] == 'cadastrar'){
				try{
					$_SESSION['nome']=$data['nome'];
					$_SESSION['email']=$data['email'];
					$_SESSION['senha']=$data['senha'];
					$_SESSION['senharepeat']=$data['senharepeat'];
					
					unset($data['acao']);
						
					$form = new Cadastro();
					$form->setData($data);
						
					if($form->isValid()){
						$data = $form->getData(FormInterface::VALUES_AS_ARRAY);
						
						if($data['senha'] != $data['senharepeat']) {
							$error = 'As senhas não conferem.';
						}
						else {
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

								// TODO: Descomentar a linha abaixo quando for colocado em produção e apagar a mais de baixo
                                //$url = $this->getRequest()->getServer()['HTTP_ORIGIN'].$this->getRequest()->getBaseUrl();
                                $url = 'http://149.56.2.12/tuttemob/public';
                                $imgPath = $url.'/img/layout/';

								// envia email de notificacao
								$macros = array(
								    'nome' => $data['nome'],
									'ctoken' => $data['ctoken'],
                                    'url' => $url,
                                    'imgPath' => $imgPath
								);
									
								$template = new Template('template.CadastroConfirmacao.phtml', $macros);
								$email    = new Email($template);
								$email->setSubject('Confirmação de cadastro');
								$email->addTo($data['email'], $data['nome']);
								$email->send();
									
								$success = 'Seu cadastro foi concluído. Um e-mail de confirmação foi enviado para você.';
							}
						}
					}else{
						$error = 'Preencha corretamente os campos do formulário.';
					}
				}catch (\Exception $e){
					$error = 'Ocorreu um erro, tente novamente mais tarde.' . $e->getMessage();
				}
		
				$type = null;
				$msg = null;
				
				if($error){
					$type = 'error';
					$msg  = base64_encode($error);
				}
					
				if($success){
					$type = 'success';
					$msg  = base64_encode($success);
				}
					
				return $this->redirect()->toRoute('home', array(
					'action' => 'cadastrar',
					'params' => $type.'='.$msg
				));
			}
		}
		
		// apresenta mensagem ao usuario
		$params = $this->params('params');
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
	
	public function contatosTuttemobAction()
	{
		$error = null;
		$success = null;
		
		//$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			//session_start();
			/* 
			$_SESSION[nome]=$data[nome];
			$_SESSION[telefone]=$data[telefone];
			$_SESSION[email]=$data[email];
			$_SESSION[mensagem]=$data[mensagem];
			*/
			
			if(isset($data['acao']) && $data['acao'] == 'contatos'){
				try{
					unset($data['acao']);
					
					$form = new Contato();
					$form->setData($data);
					
					if($form->isValid()){
						$data = $form->getData(FormInterface::VALUES_AS_ARRAY);
						
						// envia email com a mensagem
						$macros = array(
								'nome' => $data['nome'],
								'telefone' => $data['telefone'],
								'email' => $data['email'],
								'mensagem' => $data['mensagem']
						);
						
						$template = new Template('template.EmailMensagemContato.phtml', $macros);
						$email    = new Email($template);
						$email->setSubject('Contato');
						$email->addTo('tuttemob@gmail.com', 'TutteMob');
						$email->send();
						
						$success = 'Sua mensagem foi enviada, aguarde nosso contato.';
					}else{
						$error = 'Preencha corretamente os campos do formulário.';
					}
				}catch (\Exception $e){
					$error = 'Ocorreu um erro, tente novamente mais tarde.' . $e->getMessage();
				}
				
				$type = null;
				$msg = null;
				
				if($error){
					$type = 'error';
					$msg  = base64_encode($error);
				}
				
				if($success){
					$type = 'success';
					$msg  = base64_encode($success);
				}
				
				return $this->redirect()->toRoute('home', array(
						'action' => 'contatos-tuttemob',
						'params' => $type.'='.$msg
				));
			}
		}
		
		// apresenta mensagem ao usuario
		$params = $this->params('params');
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
	
}