<?php
namespace Application\Api\Imagem;

/**
 * Redimensionamento de Imagem
 */
class BrImageRed {

	protected $imagem; // imagem atual
	protected $newImagem; // nova imagem a ser criada
	protected $alvoNome; // Nome do Alvo
	protected $alvoTamanho; // Tamanho em pixels do Alvo
	protected $x_Atual; // largura atual da imagem
	protected $y_Atual; // altura atual da imagem
	protected $extension; // extenção da imagem

	public function __construct($imagem_file = null)
	{
		if($imagem_file != null)
		{
			$this->carregaImagem($imagem_file);
		}
	}

	/**
	 * @param $image_file -> Imagem a ser carregada
	 */
	public function carregaImagem($imagem_file){

		$this->imagem = $imagem_file;

		// pega largura e altura da imagem atual
		list($this->x_Atual, $this->y_Atual) = getimagesize($this->imagem);

		// pega extenção da imagem
		$explode = explode('.', $this->imagem);
		$sizeof = sizeof($explode) - 1;

		$this->extension = $explode[$sizeof];

	}

	/**
	 * Retorna em pixel a largura atual
	 * @return integer
	 */
	public function getX(){
		return $this->x_Atual;
	}

	/**
	 * Retorna em pixel a altura atual
	 * @return integer
	 */
	public function getY(){
		return $this->y_Atual;
	}

	/**
	 * @param $alturaAlvo = Largura na qual a imagem será redimensionada
	 */
	public function setAlvo($alvo_nome, $alvo_tamanho){

		$this->alvoNome = $alvo_nome;
		$this->alvoTamanho = $alvo_tamanho;

	}

	/**
	 * Cria uma imagem apartir de sua extensão
	 */
	protected function createFrom(){

		switch($this->extension){

			case 'jpg':
				return imagecreatefromjpeg($this->imagem);
				break;
					
			case 'gif':
				return imagecreatefromgif($this->imagem);
				break;
					
			case 'png':
				return imagecreatefrompng($this->imagem);
				break;
					
			case 'bmp':
				return imagecreatefromwbmp($this->imagem);
				break;
					
		}

	}

	protected function executeRed(){

		$create = $this->createFrom();

		switch ($this->alvoNome){

			case 'altura':

				/**
				 * Nova Altura da Imagem
				 *
				 * 1º - Dividir Altura Atual pela Altura Alvo para saber o nomero do Divisor
				 * 2º - Dividir Largura pelo numero Divisor
				 */
				$divisor = $this->y_Atual / $this->alvoTamanho;
				$larguraNova = $this->x_Atual / $divisor;

				// cria uma imagem com as novas dimensões
				$nova = imagecreatetruecolor($larguraNova, $this->alvoTamanho);
				imagecopyresampled($nova, $create, 0,0,0,0, $larguraNova,
						$this->alvoTamanho, $this->x_Atual, $this->y_Atual);

				return $nova;

				break;
					
			case 'largura':

				/**
				 * Nova Largura da Imagem
				 *
				 * 1º - Dividir Largura Atual pela Largura Alvo para saber o nomero do Divisor
				 * 2º - Dividir Altura pelo numero Divisor
				 */
				$divisor = $this->x_Atual / $this->alvoTamanho;
				$alturaNova = $this->y_Atual / $divisor;

				// cria uma imagem com as novas dimensões
				$nova = imagecreatetruecolor($this->alvoTamanho, $alturaNova);
				imagecopyresampled($nova, $create, 0,0,0,0, $this->alvoTamanho,
						$alturaNova, $this->x_Atual, $this->y_Atual);
					
				return $nova;

				break;
					
		}

	}

	public function newImagem(){

		// cria imagem redimensionada
		$nova = $this->executeRed();

		// troca nova imagem pela atual
		imagejpeg($nova,$this->imagem);

	}

}