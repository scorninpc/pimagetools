<?php

/**
 * Classe de ações do formulario frmConvert
 *
 * @name frmConvert
 */
class frmConvert extends IfrmConvert {
	
	/**
	 * @name __construct()
	 */
	public function __construct($parent) {
		parent::__construct($parent);
	}
	
	/**
	 * Método do carregamento do formulario
	 * 
	 * @name frmConvert_onload()
	 */
	public function frmConvert_onload() {
		// Popula o combo de tipos
		$this->widgets['cmbTipo']->append(1, "Joint Photographic Experts Group (JPEG)");
		$this->widgets['cmbTipo']->append(2, "Graphics Interchange Format (GIF)");
		$this->widgets['cmbTipo']->append(3, "Portable Network Graphics (PNG)");
		
		// Seta o valor da qualidade
		$this->widgets['sclQualidade']->set_value(75);
		
		// Seta o valor da largura
		$this->widgets['txtLargura']->set_text(1200);
		
		// Seta o tipo padrão
		$this->widgets['cmbTipo']->set_selected_value(1);
		
		// Inicia a aplicação
		$this->widgets['frmConvert']->show_all();
		Gtk::main();
	}
	
	/**
	 * Método do descarregamento do formulario
	 * 
	 * @name frmConvert_unload()
	 */
	public function frmConvert_unload() {
		// Encerra a aplicação
		$this->widgets['frmConvert']->destroy();
	}
	
	/**
	 * Inicia a converção
	 * 
	 * @name btnConverter_onclick
	 */
	public function btnConverter_onclick() {
		// Desabilita os botões do toolbar
		$buttons = $this->parent->widgets['tlrMain']->get_toolitems();
		foreach($buttons as $button) {
			$button->set_sensitive(FALSE);
		}
		
		Fabula::DoEvents();
		
		// Recupera as informações do form
		$destiny_dir = $this->widgets['txtDestino']->get_text();
		$width = $this->widgets['txtLargura']->get_text();
		$quality = $this->widgets['sclQualidade']->get_value();
		$type = $this->widgets['cmbTipo']->get_selected_value();
		switch($type) {
			case 1:
				$file_type = "jpg";
				break;
			case 2:
				$file_type = "gif";
				break;
			case 3:
				$file_type = "png";
				break;
		}
		
		// Esconde a janela
		$this->widgets['frmConvert']->destroy();
		
		// Busca o model
		$model = $this->parent->widgets['trvMain']->get_model();
		
		// Percorre os arquivos à converter
		foreach($model as $row) {
			Fabula::DoEvents();
			
			// Busca as informações do arquivo
			$file = $model->get_value($row->iter, 0);
			$file_name = substr(basename($file), 0, strpos(basename($file), "."));
			$file_destiny = $destiny_dir . "/" . $file_name . "." . $file_type;
			
			Fabula::DoEvents();
			
			// Atualiza o status
			$this->parent->widgets['stsMain']->set_text("Convertendo o arquivo " . $file);
			
			// Converte 
			$this->makeThumb(
				$file, 
				$file_destiny, 
				$type, 
				$width,
				$quality
			);
		}
		
		// Destroy a janela
		$this->widgets['frmConvert']->destroy();
		
		// Abilita os botões do toolbar
		$buttons = $this->parent->widgets['tlrMain']->get_toolitems();
		foreach($buttons as $button) {
			$button->set_sensitive(TRUE);
		}
		
		// Atualiza o status
		$this->parent->widgets['stsMain']->set_text("Feito!");
	}
	
	/**
	 * Cancela a tela de conversão
	 * 
	 * @name btnCancelar
	 */
	 public function btnCancelar_onclick() {
		 $this->frmConvert_unload();
	 } 
	 
	 /**
	  * Redimensiona imagens
	  * 
	  * @name makeThumb
	  * @param string $imgName
	  * @param string $destiny
	  * @param string $dType
	  * @param int $dWidth
	  * @param int @quality
	  */
	 public function makeThumb($imgName, $destiny, $dType, $dWidth, $quality)
		{
			// ------------------------------------------------------------------------------------------------------------
			// Cria a imagem temporaria
			// @since 17/01/2010
			$extension = strtoupper(substr($imgName, strlen($imgName) - 3));
			switch($extension)
			{
				case "BMP":
					$imgResorce = imagecreatefrombmp($imgName);
					break;
				case "JPG":
				case "JPEG":
					$imgResorce = imagecreatefromjpeg($imgName);
					break;
				case "GIF":
					$imgResorce = imagecreatefromgif($imgName);
					break;
				case "PNG":
					$imgResorce = imagecreatefrompng($imgName);
					break;
			}
			
			// ------------------------------------------------------------------------------------------------------------
			// Obtém as dimensões da imagem original
			// @since 17/01/2010
			$oWidth = ImagesX($imgResorce);
			$oHeight = ImagesY($imgResorce);
			
			// ------------------------------------------------------------------------------------------------------------
			// Faz o calculo do height destino
			// @since 17/01/2010
			if($oWidth >= $oHeight)
			{
				if($oWidth > $dWidth)
				{
					$width = (int)($oWidth * ($dWidth / $oWidth));
					$height = (int)($oHeight * ($dWidth / $oWidth));
				}
				else
				{
					$width = $oWidth;
					$height = $oHeight;
				}
			}
			else
			{
				if($oHeight > $dWidth)
				{
					$width = (int)($oWidth * ($dWidth / $oHeight));
					$height = (int)($oHeight * ($dWidth / $oHeight));
				}
				else
				{
					$width = $oWidth;
					$height = $oHeight;
				}
			}
			$dWidth = $width;
			$dHeight = $height;
			
			// ------------------------------------------------------------------------------------------------------------
			// Cria uma imagem em branco e copia da imagem original
			// @since 17/01/2010
			$imgFinal = ImageCreateTrueColor($dWidth, $dHeight);
			ImageCopyResampled($imgFinal, $imgResorce, 0, 0, 0, 0, $dWidth + 1, $dHeight + 1, $oWidth, $oHeight);

			// ------------------------------------------------------------------------------------------------------------
			// Salva e destroi a imagem
			// @since 17/01/2010
			switch($dType)
			{
				case 1:
					imagejpeg($imgFinal, $destiny, $quality);
					break;
				case 2:
					imagegif($imgFinal, $destiny, $quality);
					break;
				case 3:
					imagepng($imgFinal, $destiny, $quality);
					break;
			}
			ImageDestroy($imgFinal);
			ImageDestroy($imgResorce);
		}
}
