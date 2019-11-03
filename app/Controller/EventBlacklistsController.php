<?php
App::uses('AppController', 'Controller');

class EventBlacklistsController extends AppController
{
    public $components = array('Session', 'RequestHandler', 'BlackList');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if (!$this->_isSiteAdmin()) {
            $this->redirect('/');
        }
        if (false === Configure::read('MISP.enableEventBlacklisting')) {
            $this->Flash->info(__('Event Blacklisting is not currently enabled on this instance.'));
            $this->redirect('/');
        }
    }

    public $paginate = array(
            'limit' => 60,
            'maxLimit' => 9999, // LATER we will bump here on a problem once we have more than 9999 events <- no we won't, this is the max a user van view/page.
            'order' => array(
                    'EventBlacklist.created' => 'DESC'
            ),
    );

    public function index()
    {
        $passedArgsArray = array();
        $passedArgs = $this->passedArgs;
        $params = array();
        $validParams = array('event_uuid', 'comment', 'event_info', 'event_orgc');
        foreach ($validParams as $validParam) {
            if (!empty($this->params['named'][$validParam])) {
                $params[$validParam] = $this->params['named'][$validParam];
            }
        }
        if (!empty($this->params['named']['searchall'])) {
            $params['AND']['OR'] = array(
                'event_uuid' => $this->params['named']['searchall'],
                'comment' => $this->params['named']['searchall'],
                'event_info' => $this->params['named']['searchall'],
                'event_orgc' => $this->params['named']['searchall']
            );
        }
        $this->set('passedArgs', json_encode($passedArgs));
        $this->set('passedArgsArray', $passedArgsArray);
        $this->BlackList->index($this->_isRest(), $params);
    }

    public function add()
    {
        $this->BlackList->add($this->_isRest());
    }

    public function edit($id)
    {
        $this->BlackList->edit($this->_isRest(), $id);
    }

    public function delete($id)
    {
        $this->BlackList->delete($this->_isRest(), $id);
    }

    public function massDelete()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!isset($this->request->data['EventBlacklist'])) {
                $this->request->data = array('EventBlacklist' => $this->request->data);
            }
            $ids = $this->request->data['EventBlacklist']['ids'];
            $event_ids = json_decode($ids, true);
            if (empty($event_ids)) {
                throw new NotFoundException(__('Invalid event IDs.'));
            }
            $result = $this->EventBlacklist->deleteAll(array('EventBlacklist.id' => $event_ids));
            if ($result) {
                if ($this->_isRest()) {
                    return $this->RestResponse->saveSuccessResponse('EventBlacklist', 'Deleted', $ids, $this->response->type());
                } else {
                    $this->Flash->success('Blacklist entry removed');
                    $this->redirect(array('controller' => 'eventBlacklists', 'action' => 'index'));
                }
            } else {
                $error = __('Failed to delete Event from EventBlacklist. Error: ') . PHP_EOL . h($result);
                if ($this->_isRest()) {
                    return $this->RestResponse->saveFailResponse('EventBlacklist', 'Deleted', false, $error, $this->response->type());
                } else {
                    $this->Flash->error($error);
                    $this->redirect(array('controller' => 'eventBlacklists', 'action' => 'index'));
                }
            }
        } else {
            $ids = json_decode($this->request->query('ids'), true);
            if (empty($ids)) {
                throw new NotFoundException(__('Invalid event IDs.'));

            }
            $this->set('event_ids', $ids);
        }
    }
}
