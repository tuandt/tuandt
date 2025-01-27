<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/17/13
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController
{
    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('auth_service');
        if (! $authService->hasIdentity()) {
            // if not log in, redirect to login page
            return $this->redirect()->toUrl('/login');
        }
        $authService->clearIdentity();
        $this->flashMessenger()->setNamespace('success')->addMessage('Logout success');
        $this->getServiceLocator()->get('viewhelpermanager')->get('user')->log('application\\logout\\index','Logout success');
        return $this->redirect()->toUrl('/login');
    }
}