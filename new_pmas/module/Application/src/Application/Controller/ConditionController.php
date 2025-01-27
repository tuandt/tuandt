<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/24/13
 */
namespace Application\Controller;

use Application\Form\ConditionForm;
use Core\Controller\AbstractController;
use Core\Model\RecyclerProductCondition;
use Core\Model\TdmProductCondition;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\NotEmpty;
use Zend\View\Model\ViewModel;

class ConditionController extends AbstractController
{
    public function auth()
    {
        $sm = $this->getServiceLocator();
        $authService = $sm->get('auth_service');
        if (! $authService->hasIdentity()) {
            return $this->redirect()->toUrl('/login');
        }
    }
    public function getMessages()
    {
        $sm = $this->getServiceLocator();
        return $sm->get('messages');
    }

    public function indexAction()
    {
        $this->auth();
        $view = new ViewModel();
        return $view;
    }
    public function tdmAction()
    {
        $this->auth();
        $messages = $this->getMessages();
        $request = $this->getRequest();
        $tdmConditionTable = $this->getServiceLocator()->get('TdmProductConditionTable');
        $conditions = $tdmConditionTable->getAvaiableRows();
        $view = new ViewModel();
        $form = new ConditionForm($this->getServiceLocator());
        $view->setVariable('conditions',$conditions);
        $view->setVariable('form',$form);
        $id = $this->params('id',0);
        if($id != 0){
            $conditionEntry = $tdmConditionTable->getEntry($id);
            if(empty($conditionEntry)){
                $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['NO_DATA']));
                return $this->redirect()->toUrl('/condition/tdm');
            }
            $view->setVariable('name',$conditionEntry->name);
            $entryParse = (array) $conditionEntry;
            $form->setData($entryParse);
        }
        $view->setVariable('id',$id);
        $do = $this->params('do',null);
        $view->setVariable('do',$do);
        if($do != 'add' && $do != null){
            $this->redirect()->toUrl('/condition/tdm');
        }
        if($request->isPost()){
            $post = $request->getPost()->toArray();
            $form->setData($post);
            /**
             * Check empty
             */
            $empty = new NotEmpty();
            if(!$empty->isValid($post['name'])){
                $view->setVariable('msg',array('danger' => $messages['CONDITION_NAME_NOT_EMPTY']));
                $view->setVariable('form',$form);
                return $view;
            }
            /**
             * check condition exist
             */
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            if($id != 0){
                $exist_valid = new NoRecordExists(array('table' => 'tdm_product_condition','field' => 'name','adapter' => $dbAdapter,'exclude' => array('field' => 'name','value' => $conditionEntry->name)));
            }else{
                $exist_valid = new NoRecordExists(array('table' => 'tdm_product_condition','field' => 'name','adapter' => $dbAdapter));
            }
            if(!$exist_valid->isValid($post['name'])){
                $view->setVariable('msg',array('danger' => $messages['CONDITION_NAME_EXIST']));
                $view->setVariable('form',$form);
                return $view;
            }
            if($form->isValid()){
                $data = $form->getData();
                if(empty($data)){
                    $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['NO_DATA']));
                    return $this->redirect()->toUrl('/condition/tdm');
                }
                if($this->saveTdmCondition($data)){
                    if($id != 0){
                        /**
                         * Log user
                         */
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\tdm',$messages['LOG_UPDATE_CONDITION_SUCCESS'].$id);
                        $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['UPDATE_SUCCESS']));
                    }else{
                        /**
                         * Log user
                         */
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\tdm',$messages['LOG_INSERT_CONDITION_SUCCESS'].$tdmConditionTable->getLastInsertValue());
                        $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['INSERT_SUCCESS']));
                    }
                }else{
                    if($id != 0){
                        /**
                         * Log user
                         */
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\tdm',$messages['LOG_UPDATE_CONDITION_FAIL'].$id);
                        $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['UPDATE_FAIL']));
                    }else{
                        /**
                         * Log user
                         */
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\tdm',$messages['LOG_INSERT_CONDITION_FAIL']);
                        $view->setVariable('msg',array('danger' => $messages['INSERT_FAIL']));
                        $view->setVariable('form',$form);
                        return $view;
                    }
                }
                if($id != 0){
                    return $this->redirect()->toUrl('/condition/tdm/id/'.$id);
                }else{

                    return $this->redirect()->toUrl('/condition/tdm/id/'.$tdmConditionTable->getLastInsertValue());
                }
            }else{
                foreach($form->getMessages() as $msg){
                    $view->setVariable('msg',array('danger' => $msg));
                }
                $view->setVariable('form',$form);
                return $view;
            }
        }
        return $view;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function saveTdmCondition($data)
    {
        $sm = $this->getServiceLocator();
        $conditionTable = $sm->get('TdmProductConditionTable');
        $dataFinal = $data;
        $condition = new TdmProductCondition();
        $condition->exchangeArray($dataFinal);
        return $conditionTable->save($condition);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function saveRecyclerCondition($data)
    {
        $sm = $this->getServiceLocator();
        $conditionTable = $sm->get('RecyclerProductConditionTable');
        $dataFinal = $data;
        $condition = new RecyclerProductCondition();
        $condition->exchangeArray($dataFinal);
        return $conditionTable->save($condition);
    }
    public function deleteTdmAction()
    {
        $this->auth();
        $messages = $this->getMessages();
        $request = $this->getRequest();
        $ids = $request->getPost('ids');
        $id = $this->params('id',0);
        $conditionTable = $this->getServiceLocator()->get('TdmProductConditionTable');
        if($id != 0){
            if($this->deleteTdm($id,$conditionTable)){
                $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['DELETE_SUCCESS']));
            }else{
                $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['DELETE_FAIL']));
            }
        }
        if(!empty($ids) && is_array($ids)){
            $i = 0;
            foreach($ids as $id){
                $i++;
                $this->deleteTdm($id,$conditionTable);
            }
            $this->flashMessenger()->setNamespace('success')->addMessage($i. $this->__($messages['QTY_CONDITIONS_DELETE_SUCCESS']));
        }
        return $this->redirect()->toUrl('/condition/tdm');
    }
    public function deleteRecyclerAction()
    {
        $this->auth();
        $messages = $this->getMessages();
        $request = $this->getRequest();
        $ids = $request->getPost('ids');
        $id = $this->params('id',0);
        $conditionTable = $this->getServiceLocator()->get('RecyclerProductConditionTable');
        if($id != 0){
            if($this->deleteRecycler($id,$conditionTable)){
                $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['DELETE_SUCCESS']));
            }else{
                $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['DELETE_FAIL']));
            }
        }
        if(!empty($ids) && is_array($ids)){
            $i = 0;
            foreach($ids as $id){
                if($this->deleteRecycler($id,$conditionTable)){
                    $i++;
                }
            }
            $this->flashMessenger()->setNamespace('success')->addMessage($i. $this->__($messages['QTY_CONDITIONS_DELETE_SUCCESS']));
        }
        return $this->redirect()->toUrl('/condition/recycler');
    }
    /**
     * Clear condition id
     * @param $id
     * @param $table
     * @return bool
     */
    protected function deleteTdm($id,$table)
    {
        $messages = $this->getMessages();
        $result = $table->clearTdmCondition($id);
        if($result){
            $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\delete-tdm',$messages['LOG_DELETE_CONDITION_SUCCESS'].$id);
        }else{
            $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\delete-tdm',$messages['LOG_DELETE_CONDITION_FAIL'].$id);
        }
        return $result;
    }
    protected function deleteRecycler($id,$table)
    {
        $messages = $this->getMessages();
        $result = $table->clearRecyclerCondition($id);
        if($result){
            $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\delete-recycler',$messages['LOG_DELETE_RECYCLER_CONDITION_SUCCESS'].$id);
        }else{
            $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\delete-recycler',$messages['LOG_DELETE_RECYCLER_CONDITION_FAIL'].$id);
        }
        return $result;
    }
    public function recyclerAction()
    {
        $this->auth();
        $messages = $this->getMessages();
        $request = $this->getRequest();
        $recyclerConditionTable = $this->getServiceLocator()->get('RecyclerProductConditionTable');
        $conditions = $recyclerConditionTable->getAvaiableRows();
        $view = new ViewModel();
        $form = new ConditionForm($this->getServiceLocator());
        $view->setVariable('conditions',$conditions);
        $view->setVariable('form',$form);
        $id = $this->params('id',0);
        if($id != 0){
            $conditionEntry = $recyclerConditionTable->getEntry($id);
            if(empty($conditionEntry)){
                $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['NO_DATA']));
                return $this->redirect()->toUrl('/condition/recycler');
            }
            $view->setVariable('name',$conditionEntry->name);
            $entryParse = (array) $conditionEntry;
            $form->setData($entryParse);
        }
        $view->setVariable('id',$id);
        $do = $this->params('do',null);
        $view->setVariable('do',$do);
        if($do != 'add' && $do != null){
            $this->redirect()->toUrl('/condition/recycler');
        }
        if($request->isPost()){
            $post = $request->getPost()->toArray();
            $form->setData($post);
            /**
             * Check empty
             */
            $empty = new NotEmpty();
            if(!$empty->isValid($post['name'])){
                $view->setVariable('msg',array('danger' => $messages['CONDITION_NAME_NOT_EMPTY']));
                $view->setVariable('form',$form);
                return $view;
            }
            /**
             * check condition exist
             */
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            if($id != 0){
                $exist_valid = new NoRecordExists(array('table' => 'recycler_product_condition','field' => 'name','adapter' => $dbAdapter,'exclude' => array('field' => 'name','value' => $conditionEntry->name)));
            }else{
                $exist_valid = new NoRecordExists(array('table' => 'recycler_product_condition','field' => 'name','adapter' => $dbAdapter));
            }
            if(!$exist_valid->isValid($post['name'])){
                $view->setVariable('msg',array('danger' => $messages['CONDITION_NAME_EXIST']));
                $view->setVariable('form',$form);
                return $view;
            }
            if($form->isValid()){
                $data = $form->getData();
                if(empty($data)){
                    $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['NO_DATA']));
                    return $this->redirect()->toUrl('/condition/recycler');
                }
                if($this->saveRecyclerCondition($data)){
                    if($id != 0){
                        /**
                         * Log user
                         */
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\recycler',$messages['LOG_UPDATE_RECYCLER_CONDITION_SUCCESS'].$id);
                        $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['UPDATE_SUCCESS']));
                    }else{
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\recycler',$messages['LOG_INSERT_RECYCLER_CONDITION_SUCCESS'].$recyclerConditionTable->getLastInsertValue());
                        $this->flashMessenger()->setNamespace('success')->addMessage($this->__($messages['INSERT_SUCCESS']));
                    }
                }else{
                    if($id != 0){
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\recycler',$messages['LOG_UPDATE_RECYCLER_CONDITION_FAIL'].$id);
                        $this->flashMessenger()->setNamespace('error')->addMessage($this->__($messages['UPDATE_FAIL']));
                    }else{
                        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\condition\\recycler',$messages['LOG_INSERT_RECYCLER_CONDITION_FAIL']);
                        $view->setVariable('msg',array('danger' => $messages['INSERT_FAIL']));
                        $view->setVariable('form',$form);
                        return $view;
                    }
                }
                if($id != 0){
                    return $this->redirect()->toUrl('/condition/recycler/id/'.$id);
                }else{

                    return $this->redirect()->toUrl('/condition/recycler/id/'.$recyclerConditionTable->getLastInsertValue());
                }
            }else{
                foreach($form->getMessages() as $msg){
                    $view->setVariable('msg',array('danger' => $msg));
                }
                $view->setVariable('form',$form);
                return $view;
            }
        }
        return $view;
    }
}