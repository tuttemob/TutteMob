<?php
namespace Application\Controller;

use Application\Api\Imagem\BrImageRed;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class ClientesController extends ManagerController {
	
	/**
	 * @see ManagerController
	 */
	protected $requerAcessoAutenticado = true;
	
	public function indexAction()
	{
		$error = null;
		$success = null;
		$complementar = null;
		$complementarAcao = null;
		$complementarId = null;
		
		$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
		$modelBriefingDormitorioCasal = $this->serviceManager->get('Model\BriefingDormitorioCasal');
		$modelBriefingDormitorioSolteiro = $this->serviceManager->get('Model\BriefingDormitorioSolteiro');
		$modelBriefingDormitorioInfantil = $this->serviceManager->get('Model\BriefingDormitorioInfantil');
		$modelBriefingBanheiro = $this->serviceManager->get('Model\BriefingBanheiro');
		$modelBriefingAreaServico = $this->serviceManager->get('Model\BriefingAreaServico');
		$modelBriefingCloset = $this->serviceManager->get('Model\BriefingCloset');
		$modelBriefingHomeTheater = $this->serviceManager->get('Model\BriefingHomeTheater');
		$modelBriefingHomeOffice = $this->serviceManager->get('Model\BriefingHomeOffice');
		$modelAgendaMedicao = $this->serviceManager->get('Model\BriefingAgendaMedicao');
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			//var_dump($modelBriefingCozinha);
			
			list($error, $success) = $this->_postAcaoAmbientes($data, $modelBriefingCozinha, $modelBriefingDormitorioCasal,
					$modelBriefingDormitorioSolteiro, $modelBriefingDormitorioInfantil, $modelBriefingBanheiro,
					$modelBriefingAreaServico, $modelBriefingCloset, $modelBriefingHomeTheater, $modelBriefingHomeOffice,
					$modelAgendaMedicao);
			
			if($error){
				$type = 'error';
				$msg  = base64_encode($error);
			}
			
			if($success){
				$type = 'success';
				$msg  = base64_encode($success);
			}
			
			/*
			if($codBriefing){
				$type = $_POST['acao'];
				$msg  = base64_encode($codBriefing);
			}
			*/
			
			return $this->redirect()->toRoute('clientes', array(
				'params' => $type.'='.$msg
			));
		}

		// obtem os registros de briefings
		$listaCozinha = $modelBriefingCozinha->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaDormitorioCasal = $modelBriefingDormitorioCasal->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaDormitorioSolteiro = $modelBriefingDormitorioSolteiro->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaBanheiro = $modelBriefingBanheiro->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaAreaServico = $modelBriefingAreaServico->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaCloset = $modelBriefingCloset->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaHomeTheater = $modelBriefingHomeTheater->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaHomeOffice = $modelBriefingHomeOffice->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		
		// apresenta mensagem ao usuario
		$params = $this->params('params');
		if($params != '' && !preg_match('/^[0-9]*$/', $params)){
			preg_match('/^([A-z\-0-9]*)\=(.*)$/', $params, $matches);
			list($match, $type, $msg) = $matches;
			
			if($type == 'error'){
				$error = base64_decode($msg); 
			}
			else if($type == 'success'){
				$success = base64_decode($msg);
			}
			else {
				$complementar = true;
				$complementarAcao = $type;
				$complementarId = base64_decode($msg);
			}
		}
		
		// se possuir trás a agenda da medição
		$agendaMedicao = (array) reset($modelAgendaMedicao->lista(array('idpessoa' => $this->usuario->idpessoa)));
		if(isset($agendaMedicao['datahora'])){
			list($medicaoDia, $medicaoHoras) = explode(' ', $agendaMedicao['datahora']);
			$agendaMedicao['dia'] = preg_replace('/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/','$3/$2/$1',$medicaoDia);
			$agendaMedicao['horas'] = substr($medicaoHoras,0,5);
		}

		return new ViewModel(array(
			'success' 			=> $success,
			'error'  			=> $error,
			'complementar' 		=> $complementar,	
			'complementarAcao' 	=> $complementarAcao,	
			'complementarId' 	=> $complementarId,	
			// grid itens
			'listaCozinha'				=> $listaCozinha,
			'listaDormitorioCasal' 		=> $listaDormitorioCasal,
			'listaDormitorioSolteiro'  	=> $listaDormitorioSolteiro,
			'listaBanheiro' 			=> $listaBanheiro,
			'listaAreaServico' 			=> $listaAreaServico,
			'listaCloset' 				=> $listaCloset,
			'listaHomeTheater' 			=> $listaHomeTheater,
			'listaHomeOffice' 			=> $listaHomeOffice,
			'agendaMedicao'				=> $agendaMedicao
		));
	}

	public function confirmarAction(){
		$error = null;
		$success = null;
		$complementar = null;
		$complementarAcao = null;
		$complementarId = null;
		
		$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
		$modelBriefingDormitorioCasal = $this->serviceManager->get('Model\BriefingDormitorioCasal');
		$modelBriefingDormitorioSolteiro = $this->serviceManager->get('Model\BriefingDormitorioSolteiro');
		$modelBriefingDormitorioInfantil = $this->serviceManager->get('Model\BriefingDormitorioInfantil');
		$modelBriefingBanheiro = $this->serviceManager->get('Model\BriefingBanheiro');
		$modelBriefingAreaServico = $this->serviceManager->get('Model\BriefingAreaServico');
		$modelBriefingCloset = $this->serviceManager->get('Model\BriefingCloset');
		$modelBriefingHomeTheater = $this->serviceManager->get('Model\BriefingHomeTheater');
		$modelBriefingHomeOffice = $this->serviceManager->get('Model\BriefingHomeOffice');
		$modelAgendaMedicao = $this->serviceManager->get('Model\BriefingAgendaMedicao');
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			list($error, $success) = $this->_postAcaoAmbientes($data);
			
			if($error){
				$type = 'error';
				$msg  = base64_encode($error);
			}
			
			if($success){
				$type = 'success';
				$msg  = base64_encode($success);
			}
			
			/*
			if($codBriefing){
				$type = $_POST['acao'];
				$msg  = base64_encode($codBriefing);
			}
			*/
			
			return $this->redirect()->toRoute('clientes', array(
				'params' => $type.'='.$msg
			));
		}

		// obtem os registros de briefings
		$listaCozinha = $modelBriefingCozinha->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaDormitorioCasal = $modelBriefingDormitorioCasal->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaDormitorioSolteiro = $modelBriefingDormitorioSolteiro->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaBanheiro = $modelBriefingBanheiro->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaAreaServico = $modelBriefingAreaServico->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaCloset = $modelBriefingCloset->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaHomeTheater = $modelBriefingHomeTheater->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		$listaHomeOffice = $modelBriefingHomeOffice->lista(array('idpessoa' => $this->usuario->idpessoa),'datacriacao DESC');
		
		// apresenta mensagem ao usuario
		$params = $this->params('params');
		if($params != '' && !preg_match('/^[0-9]*$/', $params)){
			preg_match('/^([A-z\-0-9]*)\=(.*)$/', $params, $matches);
			list($match, $type, $msg) = $matches;
			
			if($type == 'error'){
				$error = base64_decode($msg); 
			}
			else if($type == 'success'){
				$success = base64_decode($msg);
			}
			else {
				$complementar = true;
				$complementarAcao = $type;
				$complementarId = base64_decode($msg);
			}
		}
		
		// se possuir trás a agenda da medição
		$agendaMedicao = (array) reset($modelAgendaMedicao->lista(array('idpessoa' => $this->usuario->idpessoa)));
		if(isset($agendaMedicao['datahora'])){
			list($medicaoDia, $medicaoHoras) = explode(' ', $agendaMedicao['datahora']);
			$agendaMedicao['dia'] = preg_replace('/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/','$3/$2/$1',$medicaoDia);
			$agendaMedicao['horas'] = substr($medicaoHoras,0,5);
		}

		return new ViewModel(array(
			'success' 			=> $success,
			'error'  			=> $error,
			'complementar' 		=> $complementar,	
			'complementarAcao' 	=> $complementarAcao,	
			'complementarId' 	=> $complementarId,	
			// grid itens
			'listaCozinha'				=> $listaCozinha,
			'listaDormitorioCasal' 		=> $listaDormitorioCasal,
			'listaDormitorioSolteiro'  	=> $listaDormitorioSolteiro,
			'listaBanheiro' 			=> $listaBanheiro,
			'listaAreaServico' 			=> $listaAreaServico,
			'listaCloset' 				=> $listaCloset,
			'listaHomeTheater' 			=> $listaHomeTheater,
			'listaHomeOffice' 			=> $listaHomeOffice,
			'agendaMedicao'				=> $agendaMedicao
		));
	}

	public function _postAcaoAmbientes($data, $modelBriefingCozinha, $modelBriefingDormitorioCasal,
			$modelBriefingDormitorioSolteiro, $modelBriefingDormitorioInfantil, $modelBriefingBanheiro,
			$modelBriefingAreaServico, $modelBriefingCloset, $modelBriefingHomeTheater, $modelBriefingHomeOffice,
			$modelAgendaMedicao){
		$error = null;
		$success = null;

		if(isset($data['acao']) && $data['acao'] == 'briefing-cozinha'){
				
			unset($data['acao']);
						
			try{
				// trata quantidade de pessoas
				$data['qtdpessoas'] = (int) $data['qtdpessoas'];
				
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['preferenciacores'])){
					$insertArrayValues['preferenciacores'] = implode(',', $data['preferenciacores']);
				}else{
					$insertArrayValues['preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['imagem']) && $_FILES['imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
						
					$codBriefing = $modelBriefingCozinha->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
						'briefingcozinha.idpessoa' => $this->usuario->idpessoa,
						'briefingcozinha.idbriefing' => $insertArrayValues['id'] 
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingCozinha->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
				
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		}  elseif(isset($data['acao']) && $data['acao'] == 'briefing-AreaServico'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bas_preferenciacores'])){
					$insertArrayValues['bas_preferenciacores'] = implode(',', $data['bas_preferenciacores']);
				}else{
					$insertArrayValues['bas_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bas_imagem']) && $_FILES['bas_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bas_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bas_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bas_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingAreaServico->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingareaservico.idpessoa' => $this->usuario->idpessoa,
							'briefingareaservico.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingAreaServico->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-dormitoriocasal'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bdc_preferenciacores'])){
					$insertArrayValues['bdc_preferenciacores'] = implode(',', $data['bdc_preferenciacores']);
				}else{
					$insertArrayValues['bdc_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bdc_imagem']) && $_FILES['bdc_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bdc_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bdc_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bdc_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingDormitorioCasal->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingdormitoriocasal.idpessoa' => $this->usuario->idpessoa,
							'briefingdormitoriocasal.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingDormitorioCasal->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-dormitoriosolteiro'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bds_preferenciacores'])){
					$insertArrayValues['bds_preferenciacores'] = implode(',', $data['bds_preferenciacores']);
				}else{
					$insertArrayValues['bds_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bds_imagem']) && $_FILES['bds_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bds_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bds_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bds_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingDormitorioSolteiro->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingdormitoriosolteiro.idpessoa' => $this->usuario->idpessoa,
							'briefingdormitoriosolteiro.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingDormitorioSolteiro->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-dormitorioinfantil'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bdi_preferenciacores'])){
					$insertArrayValues['bdi_preferenciacores'] = implode(',', $data['bdi_preferenciacores']);
				}else{
					$insertArrayValues['bdi_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bdi_imagem']) && $_FILES['bdi_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bdi_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bdi_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bdi_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingDormitorioInfantil->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingdormitorioinfantil.idpessoa' => $this->usuario->idpessoa,
							'briefingdormitorioinfantil.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingDormitorioInfantil->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		}  elseif(isset($data['acao']) && $data['acao'] == 'briefing-closet'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bcl_preferenciacores'])){
					$insertArrayValues['bcl_preferenciacores'] = implode(',', $data['bcl_preferenciacores']);
				}else{
					$insertArrayValues['bcl_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bcl_imagem']) && $_FILES['bcl_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bcl_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bcl_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bcl_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingCloset->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingcloset.idpessoa' => $this->usuario->idpessoa,
							'briefingcloset.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingCloset->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-banheiro'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['ban_preferenciacores'])){
					$insertArrayValues['ban_preferenciacores'] = implode(',', $data['ban_preferenciacores']);
				}else{
					$insertArrayValues['ban_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['ban_imagem']) && $_FILES['ban_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['ban_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['ban_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['ban_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingBanheiro->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefingbanheiro.idpessoa' => $this->usuario->idpessoa,
							'briefingbanheiro.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingBanheiro->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			} 
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-hometheater'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bht_preferenciacores'])){
					$insertArrayValues['bht_preferenciacores'] = implode(',', $data['bht_preferenciacores']);
				}else{
					$insertArrayValues['bht_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bht_imagem']) && $_FILES['bht_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bht_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bht_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bht_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingHomeTheater->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefinghometheater.idpessoa' => $this->usuario->idpessoa,
							'briefinghometheater.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingHomeTheater->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		} elseif(isset($data['acao']) && $data['acao'] == 'briefing-homeoffice'){
			
			unset($data['acao']);
			
			try{
				$insertArrayValues = array();
				foreach($data as $k => $v){
					if(!is_array($v) && trim($v) != ''){
						$insertArrayValues[$k] = strip_tags(trim($v));
					}
				}
				
				if(isset($data['bho_preferenciacores'])){
					$insertArrayValues['bho_preferenciacores'] = implode(',', $data['bho_preferenciacores']);
				}else{
					$insertArrayValues['bho_preferenciacores'] = '';
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['bho_imagem']) && $_FILES['bho_imagem']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['bho_imagem']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['bho_imagem']['tmp_name'], './public/upload/briefing/'.$nomeArquivo);
						
						$insertArrayValues['bho_imagem'] = $nomeArquivo;
					}
				}
				
				if($data['id'] == ''){
					// obter dados da pessoa logada
					$insertArrayValues['idpessoa'] = $this->usuario->idpessoa;
					
					$codBriefing = $modelBriefingHomeOffice->cria($insertArrayValues);
					if($codBriefing){
						$success = 'Seu briefing foi cadastrado com sucesso.';
					}else{
						$error = 'Ocorreu um erro, tente novamente mais tarde.';
					}
				}else{
					// atualiza dados
					$filtro = array(
							'briefinghomeoffice.idpessoa' => $this->usuario->idpessoa,
							'briefinghomeoffice.idbriefing' => $insertArrayValues['id']
					);
					
					// remove o id dos values
					unset($insertArrayValues['id']);
					
					$modelBriefingHomeOffice->update($insertArrayValues, $filtro);
					$success = 'Seu briefing foi alterado com sucesso.';
				}
			}
			catch (\Exception $e){
				$error = 'Ocorreu um erro, tente novamente mais tarde.';
			}
		}
		
		return array($error, $success);
	}

	public function carregaBriefingAction(){
		//$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
		$briefing= array();
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			if($data['tipo'] == 'cozinha'){
				$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
				$briefing= reset($modelBriefingCozinha->lista(array('idbriefing' => $data['id'])));
				$briefing->preferenciacores = explode(',', $briefing->preferenciacores); 
			} elseif($data['tipo'] == 'dormitoriocasal'){
				$modelBriefingDormitorioCasal = $this->serviceManager->get('Model\BriefingDormitorioCasal');
				$briefing= reset($modelBriefingDormitorioCasal->lista(array('idbriefing' => $data['id'])));
				$briefing->bdc_preferenciacores = explode(',', $briefing->bdc_preferenciacores);
			} elseif($data['tipo'] == 'dormitoriosolteiro'){
				$modelBriefingDormitorioSolteiro = $this->serviceManager->get('Model\BriefingDormitorioSolteiro');
				$briefing= reset($modelBriefingDormitorioSolteiro->lista(array('idbriefing' => $data['id'])));
				$briefing->bds_preferenciacores = explode(',', $briefing->bds_preferenciacores);
			} elseif($data['tipo'] == 'dormitorioinfantil'){
				$modelBriefingDormitorioInfantil = $this->serviceManager->get('Model\BriefingDormitorioInfantil');
				$briefing= reset($modelBriefingDormitorioInfantil->lista(array('idbriefing' => $data['id'])));
				$briefing->bdi_preferenciacores = explode(',', $briefing->bdi_preferenciacores);
			} elseif($data['tipo'] == 'banheiro'){
				$modelBriefingBanheiro = $this->serviceManager->get('Model\BriefingBanheiro');
				$briefing= reset($modelBriefingBanheiro->lista(array('idbriefing' => $data['id'])));
				$briefing->ban_preferenciacores = explode(',', $briefing->ban_preferenciacores);
			} elseif($data['tipo'] == 'areaservico'){
				$modelBriefingAreaServico = $this->serviceManager->get('Model\BriefingAreaServico');
				$briefing= reset($modelBriefingAreaServico->lista(array('idbriefing' => $data['id'])));
				$briefing->bas_preferenciacores = explode(',', $briefing->bas_preferenciacores);
			} elseif($data['tipo'] == 'closet'){
				$modelBriefingCloset = $this->serviceManager->get('Model\BriefingCloset');
				$briefing= reset($modelBriefingCloset->lista(array('idbriefing' => $data['id'])));
				$briefing->bcl_preferenciacores = explode(',', $briefing->bcl_preferenciacores);
			}  elseif($data['tipo'] == 'hometheater'){
				$modelBriefingHomeTheater = $this->serviceManager->get('Model\BriefingHomeTheater');
				$briefing= reset($modelBriefingHomeTheater->lista(array('idbriefing' => $data['id'])));
				$briefing->bht_preferenciacores = explode(',', $briefing->bht_preferenciacores);
			}  elseif($data['tipo'] == 'homeoffice'){
				$modelBriefingHomeOffice = $this->serviceManager->get('Model\BriefingHomeOffice');
				$briefing= reset($modelBriefingHomeOffice->lista(array('idbriefing' => $data['id'])));
				$briefing->bho_preferenciacores = explode(',', $briefing->bho_preferenciacores);
			}
		}
		
		$this->jsonDispatch(array('data' => $briefing));
	}
	
	public function removeBriefingAction(){
		//$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
		$briefing= array();
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			try{
				if($data['tipo'] == 'cozinha'){
					$modelBriefingCozinha = $this->serviceManager->get('Model\BriefingCozinha');
					$modelBriefingCozinha->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'dormitoriocasal'){
					$modelBriefingDormitorioCasal = $this->serviceManager->get('Model\BriefingDormitorioCasal');
					$modelBriefingDormitorioCasal->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'dormitoriosolteiro'){
					$modelBriefingDormitorioSolteiro = $this->serviceManager->get('Model\BriefingDormitorioSolteiro');
					$modelBriefingDormitorioSolteiro->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'dormitorioinfantil'){
					$modelBriefingDormitorioInfantil = $this->serviceManager->get('Model\BriefingDormitorioInfantil');
					$modelBriefingDormitorioInfantil->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'banheiro'){
					$modelBriefingBanheiro = $this->serviceManager->get('Model\BriefingBanheiro');
					$modelBriefingBanheiro->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'areaservico'){
					$modelBriefingAreaServico = $this->serviceManager->get('Model\BriefingAreaServico');
					$modelBriefingAreaServico->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				} elseif($data['tipo'] == 'closet'){
					$modelBriefingCloset = $this->serviceManager->get('Model\BriefingCloset');
					$modelBriefingCloset->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				}  elseif($data['tipo'] == 'hometheater'){
					$modelBriefingHomeTheater = $this->serviceManager->get('Model\BriefingHomeTheater');
					$modelBriefingHomeTheater->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				}  elseif($data['tipo'] == 'homeoffice'){
					$modelBriefingHomeOffice = $this->serviceManager->get('Model\BriefingHomeOffice');
					$modelBriefingHomeOffice->delete(array('idbriefing' => $data['id'], 'idpessoa' => $this->usuario->idpessoa));
				}

				$redirect = 'success=' . base64_encode("Seu briefing foi removido com sucesso.");
			}
			catch(\Exception $e){
				trigger_error($e->getMessage());
				$redirect = 'error=' . base64_encode("Ocorreu um erro, tente novamente mais tarde.");
			}
		}
		
		$this->jsonDispatch(array('data' => $redirect));
	}

	public function completaCadastroAction(){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		
		$retorno = false;
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
		
			if($data['acao'] == 'checa-cadastro'){
				if($this->usuario->datacomplemento != ''){
					$retorno = true;
				} else {
					$retorno = array(
						'nome' => $this->usuario->nome,
						'email' => $this->usuario->email,							
						'fone_cel' => $this->usuario->fone_cel							
					);
				}
			}
			
			if($data['acao'] == 'completa-cadastro'){
				$camposAtualizar = array(
					'cpf','rg','datanascimento',
					'sexo','endereco','numero','bairro',
					'cidade','cep','fone_com','obs'
				);				
				
				$arrayValues = array();
				foreach($camposAtualizar as $coluna){
					$value = trim($data[$coluna]);
					if($value){
						$arrayValues[$coluna] = $value;
					}
					// apresenta mensagem de campos em branco, menos para fone_com e obs
					else if($coluna != 'fone_com' && $coluna != 'obs'){
						$retorno = 'Há campos em branco, preencha todos os campos.';
					}
				}
				
				// upload de arquivo enviado
				if(isset($_FILES['avatar']) && $_FILES['avatar']['name'] != ''){
					$extArquivo = preg_replace('/(.*)\./', '', $_FILES['avatar']['name']);
					
					if(preg_match('/(jpg|bmp|png|gif|jpeg)$/', $extArquivo)){
						$nomeArquivo = ((int) time()) . '.'. $extArquivo;
						
						// move o arquivo para a pasta
						move_uploaded_file($_FILES['avatar']['tmp_name'], './public/upload/avatar/'.$nomeArquivo);
						
						// redimensiona a imagem
						$brImageRed = new BrImageRed('./public/upload/avatar/'.$nomeArquivo);
						$brImageRed->setAlvo('altura', 150);
						$brImageRed->newImagem();

						$arrayValues['avatar'] = $nomeArquivo;
					}else{
						$retorno = 'O arquivo enviado nao é uma imagem. Selecione outro arquivo.';
					}
				}

				if(!$retorno){
					try{
						// ajusta datanascimento
						$arrayValues['datanascimento'] = implode('-', array_reverse(explode('/', $arrayValues['datanascimento'])));
						
						// adiciona a data do complemento do cadastro
						$arrayValues['datacomplemento'] = date('Y-m-d H:i:s');
						
						$modelPessoa->update($arrayValues, array('idpessoa' => $this->usuario->idpessoa));
						$retorno = true;
					}
					catch (\Exception $e){
						trigger_error($e->getMessage());
						$retorno = 'Ocorreu um erro ao atualizar, tente novamente mais tarde.';
					}
				}
			}
		}
		
		$this->jsonDispatch(array('data' => $retorno));
	}

	public function agendaMedicaoAction(){
		$modelPessoa = $this->serviceManager->get('Model\Pessoa');
		$modelAgendaMedicao = $this->serviceManager->get('Model\BriefingAgendaMedicao');
		
		$success = false;
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost()->toArray();
			
			try{
				// apaga o agendamento atual
				$modelAgendaMedicao->delete(array('idpessoa' => $this->usuario->idpessoa));

				// cria um novo agendamento
				$data['dia'] = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/','$3-$2-$1',$data['dia']);
				$dataHora = $data['dia'].' '.$data['horas'];
				$modelAgendaMedicao->insert(array('datahora' => $dataHora, 'idpessoa' => $this->usuario->idpessoa));

				$success = true;
			}
			catch (\Exception $e){
				trigger_error($e->getMessage());
				$success = 'Ocorreu um erro ao atualizar, tente novamente mais tarde.';
			}
		}

		$this->jsonDispatch(array('data' => $success));
	}

	public function uploadArquivosBriefingsAction(){
		$modelMedidasArquivos = $this->serviceManager->get('Model\BriefingMedidasArquivos');

		$success = '';
		if($this->getRequest()->isPost()){
			$files = $this->getRequest()->getFiles()->toArray();
			
			// upload arquivos
			if(isset($files['briefing'])){
				$totalArquivos = 0;
				foreach($files['briefing'] as $tp => $briefings){
					foreach($briefings as $idbriefing => $arquivos){
						foreach($arquivos['arquivos'] as $arquivo){
							// upload de arquivo enviado
							$extArquivo = preg_replace('/(.*)\./', '', $arquivo['name']);
							
							if(preg_match('/(jpg|png|jpeg|pdf)$/', $extArquivo)){
								$nomeArquivo = $idbriefing . '_' . ((int) time()) . '.'. $extArquivo;
								
								// move o arquivo para a pasta
								move_uploaded_file($arquivo['tmp_name'], './public/upload/briefing-medidas/'.$nomeArquivo);
								
								$modelMedidasArquivos->cria(array(
									'tp' 		 => $tp,
									'idbriefing' => $idbriefing,
									'arquivo' 	 => $nomeArquivo
								));

								$totalArquivos++;
							}
						}
					}
				}

				if($totalArquivos){
					$success = 'Os arquivos foram enviados com sucesso.';
				}else{
					$success = 'Nenhum arquivo foi enviado.';
				}
			}
		}

		$this->jsonDispatch(array('data' => $success, 'files' => $totalArquivos));
	}
}