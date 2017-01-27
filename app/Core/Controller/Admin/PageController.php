<?php

namespace Core\Controller\Admin;

use Core\Model\Admin\Page\Page,
    Core\Model\Admin\Page\PageContent,
    ForceCMS\Model\ORM as ORM,
    ForceCMS\Model\Language,
    ForceX\Controller\Controller;

class PageController extends Controller
{
    private $_widhtXL = 1060;
    private $_heightXL = 1060;
    
    private $_widhtL = 748;
    private $_heightL = 748;
    
    private $_widhtS = 100;
    private $_heightS = 100;
    
    protected $_redirector;
    protected $_flashMessenger;
    protected $_systemMessages;

    public function init()  {
        $this->_redirector = $this->getHelper('Redirector');
        $this->_flashMessenger = $this->getHelper('FlashMessenger');
        $this->_systemMessages = [
            'success' => $this->_flashMessenger->getMessages('success'),
            'errors' => $this->_flashMessenger->getMessages('errors')
        ];


        $this->view->systemMessages = $this->_systemMessages;
    }
    
    public function indexAction() {
        // All pages fetching
        $modelPage = new Page();
        $allPages = ORM::Mapper_Search($modelPage, [
            'filters' => [
                'deleted' => Page::IS_ACTIVE
            ]
        ]);
        
        // All page content array creating and fetching
        $modelPageContent = new PageContent();
        $pageContent = [];
        foreach ($allPages as $page) {
            $pageContent[$page['id']] = ORM::Mapper_Search($modelPageContent, [
                'filters' => [
                    'page_id' => $page['id']
                ]
            ]);
        }

        // All languages fetching
        $modelLanguage = new Language();
        $allLanguages = ORM::Mapper_Search($modelLanguage, []);

        // Passing variable resources to view
        $this->view->languages = $allLanguages;
        $this->view->pages = $allPages;
        $this->view->pagecontent = $pageContent;
    }
    
    public function createAction() {
        // Request object inicialization
        $request = $this->getRequest();
        
        // All languages fetching
        $modelLanguage = new ForceCMS_Model_Language();
        $allLanguages = ORM::Mapper_Search($modelLanguage, []);
        $currentLanguage = ORM::Mapper_SearchByOne($modelLanguage, [
            'orders' => ['priority' => ORM::COLUMN_ASC]
        ]);
        
        // Page and page content model inicialization
        $modelPage = new Model_Admin_Page_Page();
        $modelPageContent = new Model_Admin_Page_PageContent();
        
        // Form proccessing
        $form = new Form_Admin_Page(NULL);
        
        if ($this->_request->isPost() && $this->_request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($this->_request->getPost())) {
                    var_dump($form->getMessages()); exit();
                }
                
                $formData = $form->getValues();
                
                // Page table data
                $pageData = [];
                $pageData['page_layout'] = $this->_request->getPost('page_layout');
                $page = $modelPage->addPage($pageData);
                
                $pageContentData = $formData;
                $pageContentData['page_id'] = $page['id'];
                $pageContentData['language_id'] = $currentLanguage->id;
                
                if ($form->page_photo->isUploaded()) {
                    $form->page_photo->receive();
                    $fileExtension =  pathinfo($form->page_photo->getFileName(), PATHINFO_EXTENSION);
                    $image = '/uploads/pages/' .basename($form->page_photo->getFileName());

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtXL, $this->_heightXL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-xl.".$fileExtension);

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtL, $this->_heightL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-l.".$fileExtension);

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtS, $this->_heightS, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-s.".$fileExtension);

                    $pageContentData['page_photo'] = $image;
                }
                        
                $modelPageContent->addPageContent($pageContentData);
                
                $this->_flashMessenger->addMessage('Page is successfully created', 'success');
                $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_page',
                            'action' => 'index',
                        ], 'default', true);
                
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->form = $form;
        $this->view->currentlang = $currentLanguage;
        $this->view->languages = $allLanguages;
    }
    
    public function contentAction() {
        // Request object inicialization
        $request = $this->getRequest();
        
        // Request params fetching from http
        $pageId = $this->_request->getParam('page');
        $langId = $this->_request->getParam('language');
        
        // All and current language fetching
        $modelLanguage = new ForceCMS_Model_Language();
        $allLanguages = ORM::Mapper_Search($modelLanguage, []);
        $currentLanguage = ORM::Mapper_SearchByOne($modelLanguage, [
            'filters' => ['id' => $langId]
        ]);
        
        // Page model inicialization
        $modelPage = new Model_Admin_Page_Page();
        $modelPageContent = new Model_Admin_Page_PageContent();
        $page = ORM::Mapper_SearchByOne($modelPage, [
           'filters' => ['id' => $pageId]
        ]);
        
        // Form proccessing
        $form = new Form_Admin_Page($page);
        
        if ($this->_request->isPost() && $this->_request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($this->_request->getPost())) {
                    var_dump($form->getMessages()); exit();
                }
                
                $formData = $form->getValues();
                
                // Page table data
                $pageData = [];
                $pageData['page_layout'] = $this->_request->getPost('page_layout');
                $modelPage->update($pageData, 'id = ' . $pageId);
                
                $pageContentData = $formData;
                $pageContentData['page_id'] = $page->id;
                $pageContentData['language_id'] = $currentLanguage->id;
                
                if ($form->page_photo->isUploaded()) {
                    $form->page_photo->receive();
                    $fileExtension =  pathinfo($form->page_photo->getFileName(), PATHINFO_EXTENSION);
                    $image = '/uploads/pages/' .basename($form->page_photo->getFileName());

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtXL, $this->_heightXL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-xl.".$fileExtension);

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtL, $this->_heightL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-l.".$fileExtension);

                    $resizeImage = new ForceCMS_Image_Resizeimage(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtS, $this->_heightS, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-s.".$fileExtension);

                    $pageContentData['page_photo'] = $image;
                }
        
                $modelPageContent->addPageContent($pageContentData);
                
                $this->_flashMessenger->addMessage('Page is successfully created', 'success');
                $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_page',
                            'action' => 'index',
                        ], 'default', true);
                
            } catch (Application_Model_Exception_InvalidInput $ex) {
                var_dump($ex->getMessage()); exit();
            }
        }
        
        $this->view->form = $form;
        $this->view->languages = $allLanguages;
        $this->view->currentpage = $page;
        $this->view->currentlang = $currentLanguage;
    }
    
    public function editAction() {
        // Request object inicialization
        $request = $this->getRequest();
        
        // Request params fetching from http
        $pageId = $this->_request->getParam('page');
        $langId = $this->_request->getParam('language');
        
        // All and current language fetching
        $modelLanguage = new ForceCMS_Model_Language();
        $allLanguages = ORM::Mapper_Search($modelLanguage, []);
        $currentLanguage = ORM::Mapper_SearchByOne($modelLanguage, [
            'filters' => ['id' => $langId]
        ]);
        
        // Page model inicialization
        $modelPage = new Model_Admin_Page_Page();
        $modelPageContent = new Model_Admin_Page_PageContent();
        $page = ORM::Mapper_SearchByOne($modelPage, [
           'filters' => ['id' => $pageId]
        ]);
        
        $pageContent = ORM::Mapper_SearchByOne($modelPageContent, [
            'filters' => [
                'page_id' => $page->id,
                'language_id' => $currentLanguage->id
            ]
        ], ORM::TO_ARRAY);
        
        if (count($pageContent) <= 0 || $pageContent == NULL) {
            $this->_redirector->setExit(true)
                    ->gotoRoute([
                        'controller' => 'admin_page',
                        'action' => 'content',
                        'page' => $page['id'],
                        'language' => $currentLanguage->id
                    ], 'default', true);
        }

        $pagePhoto = $pageContent['page_photo'] !== NULL &&
        $pageContent['page_photo'] !== '' ? $pageContent['page_photo'] : NULL;

        // Form proccessing
        $form = new Form_Admin_Page($page, 'update', $pagePhoto);
        
        if ($this->_request->isPost() && $this->_request->getPost('task') === 'update') {

            try {
                if (!$form->isValid($this->_request->getPost())) {
                    var_dump($form->getMessages()); exit();
                }
                
                $formData = $form->getValues();
                
                // Page table data
                $pageData = [];
                $pageData['page_layout'] = $this->_request->getPost('page_layout');
                $modelPage->update($pageData, 'id = ' . $pageId);
                
                $pageContentData = $formData;
                $pageContentData['page_id'] = $page->id;
                $pageContentData['language_id'] = $currentLanguage->id;

                unset($pageContentData['page_photo']);

                if ($form->page_photo->isUploaded()) {
                    $form->page_photo->receive();
                    $fileExtension =  pathinfo($form->page_photo->getFileName(), PATHINFO_EXTENSION);
                    $image = '/uploads/pages/' .basename($form->page_photo->getFileName());

                    $resizeImage = new ForceCMS_Collections_Image_ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtXL, $this->_heightXL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-xl.".$fileExtension);

                    $resizeImage->resizeTo($this->_widhtL, $this->_heightL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-l.".$fileExtension);

                    $resizeImage->resizeTo($this->_widhtS, $this->_heightS, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-s.".$fileExtension);

                    $pageContentData['page_photo'] = $image;
                }
        
                $modelPageContent->editPageContent($pageId, $langId, $pageContentData);
                
                $this->_flashMessenger->addMessage('Page is successfully updated', 'success');
                $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_page',
                            'action' => 'index',
                        ], 'default', true);
                
            } catch (Application_Model_Exception_InvalidInput $ex) {
                var_dump($ex->getMessage()); exit();
            }
        } else {
            $form->populate($pageContent);
        }
        
        $this->view->form = $form;
        $this->view->languages = $allLanguages;
        $this->view->currentcontent = $pageContent;
        $this->view->currentpage = $page;
        $this->view->currentlang = $currentLanguage;
    }
    
    public function statusAction() {

        if ($this->_request->getPost('task') == 'hide' || $this->_request->getPost('task') == 'show') {
            try {

                $id = (int) $this->_request->getPost('id');
                if ($id <= 0) {
                    throw new Exception('Page with id: ' . $id . ' not valid.' . ' exist');
                }

                $modelPages = new Model_Admin_Page_Page();
                $page = $modelPages->getPageById($id);

                if($page->status == 0){
                    $page->status = 1;
                    $this->_flashMessenger->addMessage('Page status is visible.', 'success');
                } else {
                    $page->status = 0;
                    $this->_flashMessenger->addMessage('Page status is hidden.', 'success');
                }

                $page->save();

                $this->_redirector->setExit(true)
                    ->gotoRoute([
                        'controller' => 'admin_page',
                        'action' => 'index',
                    ], 'default', true);

            } catch (Exception $ex) {

                $this->_flashMessenger->addMessage($ex->getMessage(), 'errors');

                $this->_redirector->setExit(true)
                    ->gotoRoute([
                        'controller' => 'admin_page',
                        'action' => 'index'
                    ], 'default', true);

            }
        } elseif ($this->_request->getPost('task') == 'delete') {

            try {
                $id = (int) $this->_request->getPost('id');
                if ($id <= 0) {
                    throw new Exception('Page with id: ' . $id . ' not valid.' . ' exist');
                }

                $modelPages = new Model_Admin_Page_Page();
                $page = $modelPages->getPageById($id);
                $page->deleted = Model_Admin_Page_Page::IS_DELETED;
                $page->save();

                $this->_flashMessenger->addMessage('Page was deleted successfully.', 'success');

                $this->_redirector->setExit(true)
                    ->gotoRoute([
                        'controller' => 'admin_page',
                        'action' => 'index',
                    ], 'default', true);

            } catch (Exception $ex) {
                $this->_redirector->setExit(true)
                    ->gotoRoute([
                        'controller' => 'admin_page',
                        'action' => 'index'
                    ], 'default', true);
            }
        }
    }
}