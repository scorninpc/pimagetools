<?php

/**
 * Classe da interface formulario frmMain
 *
 * @name IfrmMain
 */
abstract class IfrmMain {
	/**
	 * Armazena os widgets necessarios
	 * 
	 * @access private
	 * @name $widgets
	 * @var array
	 */
	public $widgets = array();
	
	/**
	 * @name __construct()
	 */
	public function __construct() {
		// Cria a janela
		$this->widgets['frmMain'] = new GtkWindow();
		$this->widgets['frmMain']->set_type_hint(Gdk::WINDOW_TYPE_HINT_UTILITY);
		$this->widgets['frmMain']->set_default_size(500, 450);
		$this->widgets['frmMain']->set_title("PHP Image Tools");
		$this->widgets['vbox'] = new GtkVBox();
		
		// Cria o toolbar
		$this->widgets['tlrMain'] = Fabula::GtkToolbar();
		$this->widgets['vbox']->pack_start($this->widgets['tlrMain'], FALSE, FALSE);
		$this->__createToolbar();
		
		// Cria o treeview
		$this->widgets['trvMain'] = Fabula::GtkTreeView();
		$this->widgets['trvMain']->set_headers_visible(FALSE);
		$this->widgets['vbox']->pack_start(Fabula::GtkViewPort($this->widgets['trvMain']), TRUE, TRUE);
		$this->__createTreeview();
		
		// Cria o statusbar
		$this->widgets['stsMain'] = Fabula::GtkProStatusBar();
		$this->widgets['vbox']->pack_start($this->widgets['stsMain'], FALSE, FALSE);
		
		// Inicia a aplicação
		$this->widgets['frmMain']->add($this->widgets['vbox']);
		$this->_signals();
		$this->frmMain_onload();
	}
	
	/**
	 * Conecta os sinais dos widgets
	 *
	 * @name _signals
	 */
	private function _signals() {
		$this->widgets['trvMain']->connect("drag-data-received", array($this, "trvMain_ondrop"));
		$this->widgets['frmMain']->connect("destroy", array($this, "frmMain_unload"));
	}
	
	// Cria os botoes do toolbar
	private function __createToolbar() {
		// Abrir
		$btnOpen = $this->widgets['tlrMain']->append_button_from_stock(Gtk::STOCK_OPEN, NULL, "Abrir");
		$btnOpen->connect("clicked", array($this, "btnOpen_onclick"));
		
		// Limpar
		$btnClear = $this->widgets['tlrMain']->append_button_from_stock(Gtk::STOCK_CLEAR, NULL, "Limpar");
		$btnClear->connect("clicked", array($this, "btnClear_onclick"));
		
		// Separador
		$this->widgets['tlrMain']->append_separator();
		
		// Converter
		$btnConvert = $this->widgets['tlrMain']->append_button_from_stock(Gtk::STOCK_CONVERT, NULL, "Converter");
		$btnConvert->connect("clicked", array($this, "btnConvert_onclick"));
	}
	
	// Cria as colunas do treeview
	private function __createTreeview() {
		// Adiciona o model
		$model = new GtkListStore(GObject::TYPE_STRING);
		$this->widgets['trvMain']->set_model($model);
		
		// Adiciona as colunas
		$column1 = $this->widgets['trvMain']->add_column(new GtkCellRendererText(), "Arquivo", "text");
		
		// Adiciona o highlight
		$this->widgets['trvMain']->set_highlight("#FFFFFF", "#EEEEEE");
		
		// Seta as opções de drop
		$this->widgets['trvMain']->drag_dest_set(Gtk::DEST_DEFAULT_ALL, array(array("text/uri-list", 0, 0)), Gdk::ACTION_COPY);
		
	}
}
