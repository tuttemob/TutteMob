<?php
namespace Application\Api\Notify;

use Zend\Mail\Transport;
use Zend\Mail\Message;
use Zend\Mime;

class Email extends Message {
	
	/**
	 *
	 * @var \Zend\Mail\Transport\Smtp
	 *
	 */
	private $smtp;
	
	/**
	 *
	 * Cria um objeto Notify\Email
	 *
	 * @param Notify\Template $template        	
	 *
	 */
	public function __construct(Template $template) {
		
		// Obtem o corpo do email
		$this->setBody ( $template->getContent () );
		
		// Obtem as configurações de email
		
		$configMail = include './config/application.config.email.php';
		
		// configura o email de remetente
		
		$this->setFrom ( $configMail ['connection_config'] ['username'], 'TutteMob' );
		
		// prepare uma instancia do transport via SMTP
		
		$this->smtp = new Transport\Smtp ();
		
		$this->smtp->setOptions ( new Transport\SmtpOptions ( $configMail ) );
	}
	
	/**
	 *
	 * Adiciona o texto do corpo do email
	 *
	 * @param string $body
	 *        	- texto html do corpo da mensagem
	 *        	
	 */
	public function setBody($body) {
		$mimePart = new Mime\Part ( $body );
		
		$mimePart->type = 'text/html';
		
		// $mimePart->charset = 'ISO-8859-1';
		
		$mimePart->charset = 'UTF-8';
		
		$mimeMessage = new Mime\Message ();
		
		$mimeMessage->setParts ( array (
				$mimePart 
		) );
		
		return parent::setBody ( $mimeMessage );
	}
	
	public function send() {
		$this->smtp->send ( $this );
	}
}