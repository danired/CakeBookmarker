<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Bookmarks']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|NULL
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AppController::initialize()
     */
    public function initialize()
    {
        parent::initialize();
        // Lista de metodos permitidos sin estar logueado
        $this->Auth->allow(['logout', 'add']);
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|NULL
     */
    public function logout()
    {
        $this->Flash->success('You are now logged out.');

        return $this->redirect($this->Auth->logout());
    }

    /**
     * Comprueba la autorización del usuario según la acción.
     *
     * {@inheritDoc}
     * @see \App\Controller\AppController::isAuthorized()
     */
    public function isAuthorized($user)
    {
        return true;
    }
}
