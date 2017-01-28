<?php

namespace Core\Controller\Admin;

use ForceCMS\Controller\ControllerAbstract;
use ForceCMS\Model\ORM as ORM,
    ForceCMS\Collections\Image\ImageResize as ImageResize,
    Core\Model\Admin\Blog\BlogCategory as BlogCategory,
    Core\Model\Admin\Blog\BlogPostToCategory as BlogPostToCategory,
    Core\Model\Admin\Blog\BlogPost as BlogPost,
    Core\Model\Admin\Blog\BlogTag as BlogTag,
    Core\Model\Admin\Blog\BlogComment as BlogComment,
    Core\Form\Admin\Blog\Category as FormCategory,
    Core\Form\Admin\Blog\Tag as FormTag,
    Core\Form\Admin\Blog\Post as FormPost,
    \Zend_Controller_Request_Http as Request;

class BlogController extends ControllerAbstract
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

    public function indexAction(BlogPost $modelBlogPost)
    {
        // All posts fetching
        $allPosts = ORM::Mapper_Search($modelBlogPost, [
            'orders' => [
                'date_published' => ORM::COLUMN_DESC
            ]
        ]);

        $this->view->posts = $allPosts;
    }

    /**
     * @param int $categoryId
     */
    public function categoryAction($categoryId, BlogCategory $modelBlogCategory,
                                                FormCategory $form,
                                                Request $request )
    {
        // All categories fetching
        $allCategories = ORM::Mapper_SearchAll($modelBlogCategory);

        if ($request->isXmlHttpRequest()) {
            $category = $modelBlogCategory->find($categoryId)->current();

            if (count($category) > 0 && $category !== NULL) {
                $data = $category->name;
                $this->_helper->json($data);
            }

        }

        if ($request->isPost()) {
            try {

                if ($request->getPost('task') == 'update') {

                    if ($request->getPost('id') !== NULL && $request->getPost('id') !== '') {
                        $categoryId = $request->getPost('id');
                    } else {
                        throw new \Exception('ID broj kategorije nije prosledjen.');
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['status'] = BlogCategory::STATUS_VISIBLE;
                    $formData['name'] = $request->getPost('name');

                    // Insert to database
                    $modelBlogCategory->editCategory($categoryId, $formData);

                    $this->_flashMessenger->addMessage('Kategorija je uspešno izmenjena.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_blog',
                            'action' => 'category',
                         ], 'default', true);

                } elseif ($request->getPost('task') == 'create') {

                    // Check form is valid
                    if (!$form->isValid($request->getPost())) {
                        var_dump($form->getMessages()); exit();
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['status'] = BlogCategory::STATUS_VISIBLE;

                    // Insert to database
                    $modelBlogCategory->addCategory($formData);

                    $this->_flashMessenger->addMessage('Kategorija je uspešno kreirana.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_blog',
                            'action' => 'category',
                         ], 'default', true);
                }

            } catch (\Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }

        }

        $this->view->form = $form;
        $this->view->categories = $allCategories;
    }

    /**
     * @param int $tagId
     */
    public function tagAction($tagId, BlogTag $modelBlogTag,
                                      FormTag $form,
                                      Request $request)
    {
        // All tags fetching
        $allTags = ORM::Mapper_SearchAll($modelBlogTag);

        if ($request->isXmlHttpRequest()) {
            $tag = $modelBlogTag->find($tagId)->current();

            if (count($tag) > 0 && $tag !== NULL) {
                $data = $tag->title;
                $this->_helper->json($data);
            }
        }

        if ($request->isPost()) {
            try {

                if ($request->getPost('task') == 'update') {

                    if ($request->getPost('id') !== NULL && $request->getPost('id') !== '') {
                        $tagId = $request->getPost('id');
                    } else {
                        throw new \Exception('ID broj oznake nije prosledjen.');
                    }

                    // Get form data
                    $formData = $form->getValues();
                    $formData['title'] = $request->getPost('title');

                    // Insert to database
                    $modelBlogTag->editTag($tagId, $formData);

                    $this->_flashMessenger->addMessage('Oznaka je uspešno izmenjena.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_blog',
                            'action' => 'tag',
                        ], 'default', true);

                } elseif ($request->getPost('task') == 'create') {

                    // Check form is valid
                    if (!$form->isValid($request->getPost())) {
                        var_dump($form->getMessages()); exit();
                    }

                    // Get form data
                    $formData = $form->getValues();

                    // Insert to database
                    $modelBlogTag->addTag($formData);

                    $this->_flashMessenger->addMessage('Oznaka je uspešno kreirana.', 'success');

                    $this->_redirector->setExit(true)
                        ->gotoRoute([
                            'controller' => 'admin_blog',
                            'action' => 'tag',
                        ], 'default', true);
                }

            } catch (\Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }

        }

        $this->view->form = $form;
        $this->view->tags = $allTags;
    }

    public function commentAction(BlogComment $modelBlogComment, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $commentId = $request->getParam('pk');
            $commentValue = $request->getParam('value');

            if ($commentId !== NULL && $commentId !== '') {
                $comment = $modelBlogComment->find($commentId)->current();
                $comment->comment = $commentValue;
                $comment->save();
            }

        }

        // Filtered comments fetching
        $approvedComments = ORM::Mapper_Search($modelBlogComment, [
            'filters' => [
                'mark_approved' => BlogComment::STATUS_APPROVED,
                'mark_read' => BlogComment::COMMENT_READED
            ],
            'order' => ['date_created' => ORM::COLUMN_ASC]
        ]);

        $newComments = ORM::Mapper_Search($modelBlogComment, [
           'filters' => [
               'mark_read' => BlogComment::COMMENT_PENDING
           ],
           'order' => ['date_created' => ORM::COLUMN_ASC]
        ]);


//        $cache = Zend_Registry::get('Cache');
//        $cacheKey = 'approvedComments';
//
//        if (empty($cacheKey) || ($approvedComments = $cache->load($cacheKey)) == false) {
//            $cache->save($approvedComments, $cacheKey);
//        }
//
//        $approvedCommentsCached = $cache->load($cacheKey);

        $this->view->newcomments = $newComments;
        $this->view->approvedcomments = $approvedComments;
    }

    public function createAction(FormPost $form, BlogPost $modelBlogPost, BlogTag $modelBlogTag,
                                                 BlogPostToCategory $modelBlogPostToCategory,
                                                 Request $request)
    {

        if ($request->isPost() && $request->getPost('task') === 'create') {

            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                    var_dump([
                        'Nevalidni podaci prosledjeni za formu', nl2br(null),
                        $form->getMessages()
                    ]);
                }

                // get form data
                $formData = $form->getValues();

                if ($form->featured_image->isUploaded()) {

                    $form->featured_image->receive();
                    $fileExtension =  pathinfo($form->featured_image->getFileName(), PATHINFO_EXTENSION);
                    $image = '/uploads/posts/' .basename($form->featured_image->getFileName());

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtXL, $this->_heightXL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-xl.".$fileExtension);

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtL, $this->_heightL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-l.".$fileExtension);

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtS, $this->_heightS, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-s.".$fileExtension);

                    $formData['featured_image'] = $image;

                }

                // inserting data to tables
                $modelBlogPost->createPostCategoryTagData($formData, $modelBlogPostToCategory, $modelBlogTag);

                // redirect after successfull insert
                $this->_redirector->setExit(true)
                    ->goToRoute([
                        'controller' => 'admin_blog',
                        'action' => 'index',
                    ], 'default', true);

                // set sticky message
                $this->_flashMessenger->addMessage('Task is successfull', 'success');

            } catch (\Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->form = $form;
    }

    /**
     * @param int $id
     */
    public function editAction($id, FormPost $form,     BlogPost $modelBlogPost,
                               BlogTag $modelBlogTag,   BlogPostToCategory $modelBlogPostToCategory,
                               BlogCategory $modelBlogCategory,
                               Request $request)
    {

        if (isset($id) && $id !== '' && $id !== null) {

            $currentPost = $modelBlogPost->getPostCategoryTagData(
                $id,           $modelBlogPostToCategory,
                $modelBlogTag, $modelBlogCategory
            );

        } else {
            // set sticky message
            $this->_flashMessenger->addMessage('Post with id: ' . $id . ' is not valid.', 'success');
            $this->_redirector->setExit(true)
                ->goToRoute([
                    'controller' => 'admin_blog',
                    'action' => 'index'
                ],  'default', true);
        }


        if ($request->isPost() && $request->getPost('task') === 'create') {

            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                    var_dump([
                        'Nevalidni podaci prosledjeni za formu', nl2br(null),
                        $form->getMessages()
                    ]);
                }

                // get form data
                $formData = $form->getValues();

                if ($form->featured_image->isUploaded()) {

                    $form->featured_image->receive();
                    $fileExtension =  pathinfo($form->featured_image->getFileName(), PATHINFO_EXTENSION);
                    $image = '/uploads/posts/' .basename($form->featured_image->getFileName());

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtXL, $this->_heightXL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-xl.".$fileExtension);

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtL, $this->_heightL, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-l.".$fileExtension);

                    $resizeImage = new ImageResize(APP_PUBLIC . $image);
                    $resizeImage->resizeTo($this->_widhtS, $this->_heightS, 'exact');
                    $resizeImage->saveImage(APP_PUBLIC . substr($image, 0, -(strlen($fileExtension)+1))."-s.".$fileExtension);

                    $formData['featured_image'] = $image;

                }

                // inserting data to tables
                $modelBlogPost->editPost($id, $formData, $modelBlogPostToCategory, $modelBlogTag);

                // set sticky message
                $this->_flashMessenger->addMessage('Post is successfully updated.', 'success');

                // redirect after successfull insert
                $this->_redirector->setExit(true)
                    ->goToRoute([
                        'controller' => 'admin_blog',
                        'action' => 'index',
                    ],  'default', true);

            } catch (\Exception $ex) {
                $this->_systemMessages['errors'][] = $ex->getMessage();
            }
        } else {
            $form->populate($currentPost);
        }

        $this->view->currentpost = $currentPost;
        $this->view->form = $form;
    }

    public function statusAction()
    {
    }
}