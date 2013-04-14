<?php

/**
 * Classe de ações do formulario frmMain
 *
 * @name frmMain
 */
class frmMain extends IfrmMain {
	/**
	 * @name __construct()
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Método do carregamento do formulario
	 * 
	 * @name frmMain_onload()
	 */
	public function frmMain_onload() {
		// Inicia a aplicação
		$this->widgets['frmMain']->show_all();
		Gtk::main();
	}
	
	/**
	 * Método do descarregamento do formulario
	 * 
	 * @name frmMain_unload()
	 */
	public function frmMain_unload() {
		// Encerra a aplicação
		Gtk::main_quit();
		exit(0);
	}
	
	/**
	 * Método para limpar a lista
	 * 
	 * @name btnClear_onclick
	 */
	public function btnClear_onclick() {
		// Abre o dialogo da pergunta
		$res = Fabula::GtkMessageDialog($this->widgets['frmMain'], Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_YES_NO, "Deseja limpar a lista de imagens?", TRUE);
		if($res->get_return() == Gtk::RESPONSE_YES) {
			// Limpa a lista
			$this->widgets['trvMain']->clear();
		}
	}
	
	/**
	 * Método de abertuda da tela para selecionar os arquivos
	 * 
	 * @name btnOpen_onclick
	 */
	public function btnOpen_onclick() {
		// Constri o dialogo
		$fileDialog = Fabula::GtkFileChooserDialog("Abrir arquivos para conversão", $this->widgets['frmMain'], FALSE);
		
		// Seta como seleção multipla
		$fileDialog->set_select_multiple(TRUE);
		
		// Inicia o dialogo
		$files = $fileDialog->run();
		
		// Verifica se foi selecionado ao menos um arquivo
		if($files !== FALSE) {
			// Percorre os arquivos
			foreach($files as $file) {
				// Verifica se existe o arquivo
				if((trim($file) != "") && (file_exists($file))) {
					// Separa o arquivo do diretório base
					$fileName = basename($file);
					$filePath = str_replace($fileName, "", $file);
					
					// Adiciona a linha
					$this->widgets['trvMain']->add_row(array($filePath));
				}
			}
		}
	}
	
	/**
	 * Método que dropa os arquivos no treeview
	 * 
	 * @name trvMain_ondrop
	 * @param GtkWidget $widget
	 * @param GtkContext $context
	 * @param int $x
	 * @param int $y
	 * @param StdClass $data
	 * @param array $info
	 * @param int $time
	 */
	public function trvMain_ondrop($widget, $context, $x, $y, $data, $info, $time) {
		// Trata os arquivos e separa em um vetor
		$dropedData = str_replace("\r", "", $data->data);
		$dropedData = str_replace("file://", "", $dropedData);
		$files = explode("\n", $dropedData);
		
		// Percorre os arquivos
		foreach($files as $file) {
			$file = urldecode($file);
			// Verifica se existe o arquivo
			if((trim($file) != "") && (file_exists($file))) {
				// Adiciona a linha
				$this->widgets['trvMain']->add_row(array($file));
			}
		}
	}
	
	/**
	 * Método que inicia a conversão
	 * 
	 * @name btnConvert_onclick
	 */
	public function btnConvert_onclick() {
		static $form = NULL;
		
		$form = new frmConvert($this);
	}
}
