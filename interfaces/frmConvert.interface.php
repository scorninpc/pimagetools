<?php

/**
 * Classe da interface formulario frmConvert
 *
 * @name IfrmConvert
 */
abstract class IfrmConvert {
	/**
	 * Armazena os widgets necessarios
	 * 
	 * @access private
	 * @name $widgets
	 * @var array
	 */
	public $widgets = array();
	
	/**
	 * Armazena o objeto anterior
	 * 
	 * @access protected
	 * @name parent
	 * @var object
	 */
	protected $parent = NULL;
	
	/**
	 * @name __construct()
	 */
	public function __construct($parent) {
		$this->parent = $parent;
		
		// Cria a janela
		$this->widgets['frmConvert'] = new GtkWindow();
		$this->widgets['frmConvert']->set_modal(TRUE);
		$this->widgets['frmConvert']->set_type_hint(Gdk::WINDOW_TYPE_HINT_UTILITY);
		$this->widgets['frmConvert']->set_transient_for($this->parent->widgets['frmMain']);
		$this->widgets['frmConvert']->set_position(Gtk::WIN_POS_CENTER_ON_PARENT);
		$this->widgets['frmConvert']->set_title("PHP Image Tools - Configure");
		$this->widgets['frmConvert']->set_default_size(350, FALSE);
		$box = new GtkVBox();
		
		// Cria o caminho destino
		$label = Fabula::GtkLabel("Destino");
		$hbox = new GtkHBox();
		$this->widgets['txtDestino'] = Fabula::GtkEntry();
		$this->widgets['btnDestino'] = new GtkButton("...");
		$this->widgets['btnDestino']->set_size_request(30, FALSE);
		$hbox->pack_start($this->widgets['txtDestino'], TRUE);
		$hbox->pack_start($this->widgets['btnDestino'], FALSE);
		$box->pack_start($label, FALSE);
		$box->pack_start($hbox, FALSE);
		
		// Cria o tipo do arquivo para criar
		$label = Fabula::GtkLabel("Tipo do arquivo");
		$this->widgets['cmbTipo'] = Fabula::GtkComboBox();
		$box->pack_start($label, FALSE);
		$box->pack_start($this->widgets['cmbTipo'], FALSE);
		
		// Cria o campo para largura da imagem
		$label = Fabula::GtkLabel("Largura");
		$this->widgets['txtLargura'] = Fabula::GtkEntry();
		$box->pack_start($label, FALSE);
		$box->pack_start($this->widgets['txtLargura'], FALSE);
		
		// Cria o campo para qualidade da imagem
		$label = Fabula::GtkLabel("Qualidade");
		$this->widgets['sclQualidade'] = GtkHScale::new_with_range(1, 100, 1);
		$box->pack_start($label, FALSE);
		$box->pack_start($this->widgets['sclQualidade'], FALSE);
		
		// Cria o separador
		$separator = new GtkHSeparator();
		$box->pack_start($separator, FALSE);
		
		// Cria os botões
		$label = new GtkLabel("");
		$label->set_size_request(FALSE, 10);
		$box->pack_start($label, FALSE);
		
		
		$hbox = new GtkHBox();
		$this->widgets['btnConverter'] = new GtkButton("Converter");
		$this->widgets['btnCancelar'] = new GtkButton("Cancelar");
		$hbox->pack_start(new GtkFixed(), TRUE);
		$hbox->pack_start($this->widgets['btnConverter'], FALSE);
		$hbox->pack_start($this->widgets['btnCancelar'], FALSE);
		$box->pack_start($hbox, FALSE);
		
		$label = new GtkLabel("");
		$label->set_size_request(FALSE, 10);
		$box->pack_start($label, FALSE);
		
		// Inicia a aplicação
		$this->widgets['frmConvert']->add($box);
		$this->_signals();
		$this->frmConvert_onload();
	}
	
	/**
	 * Conecta os sinais dos widgets
	 *
	 * @name _signals
	 */
	private function _signals() {
		$this->widgets['btnCancelar']->connect("clicked", array($this, "btnCancelar_onclick"));
		$this->widgets['btnConverter']->connect("clicked", array($this, "btnConverter_onclick"));
		$this->widgets['frmConvert']->connect("destroy", array($this, "frmConvert_unload"));
	}
}
