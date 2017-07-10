<?php
namespace Application\Api\Notify;

class Template {
	
	protected $arquivo;
	protected $macros;
	
	public function __construct($arquivo, array $macros) {
		if (! file_exists ( './emails/' . $arquivo )) {
			
			throw new \Exception ( "Arquivo {$arquivo}, não foi encontrado." );
		}
		
		$this->arquivo = $arquivo;
		
		$this->macros = $macros;
	}
	
	public function getContent() {
		$content = null;
		
		ob_start ();
		
		include './emails/' . $this->arquivo;
		
		$content = ob_get_clean ();
		
		ob_end_flush ();
		
		return $content;
	}
}