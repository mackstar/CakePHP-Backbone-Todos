<?php
App::uses('AppController', 'Controller');
App::uses('CakeSession', 'Model/Datasource');
/**
 * Todos Controller
 *
 * @property Todo $Todo
 */
class TodosController extends AppController {

	var $helpers = array('AssetCompress.AssetCompress');

	public $components = array (
		'RequestHandler',
		'Backbone.Backbone'
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Todo->recursive = 0;
		$this->set('todos', $this->Todo->find('all'));
		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Todo->id = $id;
		if (!$this->Todo->exists()) {
			throw new NotFoundException(__('Invalid todo'));
		}
		$this->set('todo', $this->Todo->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Todo->create();
			if ($result = $this->Todo->save($this->request->data)) {
				if (!$this->RequestHandler->isAjax()) {
					$this->Session->setFlash(__('The todo has been saved'));
					$this->redirect(array('action' => 'index'));
				}
				$this->set('todo', $result);
			} else {
				$this->Session->setFlash(__('The todo could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Todo->id = $id;
		if (!$this->Todo->exists()) {
			throw new NotFoundException(__('Invalid todo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($data = $this->Todo->save($this->request->data)) {
				if (!$this->RequestHandler->isAjax()) {
					$this->Session->setFlash(__('The todo has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->set('todo', $data);
				}
			} else {
				$this->Session->setFlash(__('The todo could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Todo->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			throw new MethodNotAllowedException();
		}
		$this->Todo->id = $id;
		if (!$this->Todo->exists()) {
			throw new NotFoundException(__('Invalid todo'));
		}
		if ($this->Todo->delete()) {
			if (!$this->RequestHandler->isAjax()) {
				$this->Session->setFlash(__('Todo deleted'));
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->Session->setFlash(__('Todo was not deleted'));
		if (!$this->RequestHandler->isAjax()) {
			$this->redirect(array('action' => 'index'));
		}
	}
}
