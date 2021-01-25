<?php
/**
 * General action, hooks loader
*/
class WPHPC_Loader {

	protected $wphpc_actions;
	protected $wphpc_filters;

	/**
	 * Class Constructor
	*/
	function __construct(){
		$this->wphpc_actions = array();
		$this->wphpc_filters = array();
	}

	function add_action( $hook, $component, $callback ){
		$this->wphpc_actions = $this->add( $this->wphpc_actions, $hook, $component, $callback );
	}

	function add_filter( $hook, $component, $callback ){
		$this->wphpc_filters = $this->add( $this->wphpc_filters, $hook, $component, $callback );
	}

	private function add( $hooks, $hook, $component, $callback ){
		$hooks[] = array( 'hook' => $hook, 'component' => $component, 'callback' => $callback );
		return $hooks;
	}

	public function wphpc_run(){
		foreach( $this->wphpc_filters as $hook ){
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}
		foreach( $this->wphpc_actions as $hook ){
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}
	}
}
?>