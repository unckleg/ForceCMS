<?php

namespace Core\Model\Admin\Blog;

use \Zend_Db_Table_Abstract as ZendDbAbstract,
    ForceCMS\Model\ORM as ORM;

class BlogPost extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_post';

    // soft delete constants
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    // status constatns
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;
    const STATUS_ONHOLD = 2;
    const STATUS_FOR_MODERATOR = 3;

    // comments enabled
    const COMMENT_ENABLED = 1;
    const COMMENT_DISABLED = 2;

    /**
     * Return array-object of all posts from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $posts = $this->fetchAll($select);
        return $posts;
    }

    /**
     * If post exist return row-object else return null
     * @param int $pageId
     * @return Object\Zend_Db_Table_Row
     */
    public function getPostById($postId) {
        $select = $this->select();
        $select->where('id = (?)', $postId);

        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else
            return;
    }

    /**
     * @param array $postData
     * @return object of newly created post
     */
    public function addPost($postData) {
        $post = $this->insert($postData);
        return $post;
    }

    /**
     * Inserting formData passed from new post to 3 tables
     * 1. cms_blog_post, 2. cms_blog_post_to_category, 3. cms_blog_tag
     * @param $formData
     * @param \Zend_Db_Table_Abstract $modelBlogPostToCategory
     * @param \Zend_Db_Table_Abstract $modelBlogTag
     * @return int postId | id of newly created post
     */
    public function createPostCategoryTagData($formData, ZendDbAbstract $modelBlogPostToCategory,
                                                         ZendDbAbstract $modelBlogTag)
    {
        // models array variables
        $postData = [];
        $formData['date_published'] = date('Y-m-d H:i:s', strtotime($formData['date_published']));

        $postDataFields = $this->info(ZendDbAbstract::COLS);
        if (!empty($formData)) {

            // Post inserting
            foreach ($formData as $field => $value) {
                if (in_array($field, $postDataFields)) {
                    $postData[$field] = $value;
                }
            }
            $postId = $this->insert($postData);

            // Categories inserting
            foreach ($formData['categories'] as $key => $value) {
                $postToCategoryData = ['category_id' => $value, 'post_id' => $postId];
                $modelBlogPostToCategory->insert($postToCategoryData);
            }

            // Tags inserting
            foreach (explode(',', $formData['tags']) as $key => $value) {
                $postTagData = ['post_id' => $postId, 'title' => $value];
                $modelBlogTag->insert($postTagData);
            }
        }

        return $postId;
    }

    public function getPostCategoryTagData($postId, ZendDbAbstract $modelBlogPostToCategory,
                                                    ZendDbAbstract $modelBlogTag,
                                                    ZendDbAbstract $modelBlogCategory)
    {
        // check if post id is valid
        if ($postId !== '' && $postId !== null && $postId !== 0) {

            // general array variable that is passed to form populate method
            $postCategoryTagData = [];
            $postCategoryData = [];
            // permanent array variables
            $postToCategoryPermData = [];

            // populating array with general post data
            // title, description, date, status
            $postCategoryTagData[] = ORM::Mapper_SearchByOne($this, [
                'filters' => [
                    'id' => $postId
                ]
            ], ORM::TO_ARRAY);

            // fetching categories data from junction table and preparing for
            // array reorder
            $postToCategoryJunctionData = ORM::Mapper_Search($modelBlogPostToCategory, [
                'filters' => [
                    'post_id' => $postId
                ]
            ]);

            foreach ($postToCategoryJunctionData as $key => $value) {
                $postToCategoryPermData[$key] = ORM::Mapper_SearchByOne($modelBlogCategory, [
                   'filters' => [
                       'id' => $value['category_id']
                   ]
                ], ORM::TO_ARRAY);
            }

            // array categories reorder for merging with master array
            // that is passed to form populate method
            foreach ($postToCategoryPermData as $key => $value) {
                $postCategoryData[] = $value['id'];
            }

            $postTagJunctionData = ORM::Mapper_Search($modelBlogTag, [
                'filters' => [
                    'post_id' => $postId
                ]
            ], ORM::TO_ARRAY);

            foreach ($postTagJunctionData as $key => $value) {
                $postTagPermData[] = $value['title'];
            }

            $data = array_shift($postCategoryTagData);
            $data['tags'] = implode(', ', $postTagPermData);
            $data['categories'] = implode(', ', $postCategoryData);

            return $data;
        }

    }

    public function editPost($postId, $postData, ZendDbAbstract $modelBlogPostToCategory,
                                                 ZendDbAbstract $modelBlogTag)
    {
        if (isset($postData['id'])) {
            unset($postData['id']);
        }

        if (is_array($postData['categories']) &&
            !empty($postData['categories']))
        {
            $data['post_id'] = $postId;
            $data['categories'] = $postData['categories'];
            $modelBlogPostToCategory->deleteOldRelationsAndUpdate($postId, $data);
        }

        $tagData['tags'] = explode(',', $postData['tags']);
        $modelBlogTag->deleteOldRelationsAndUpdate($postId, $tagData);

        $postDataFields = $this->info(ZendDbAbstract::COLS);

        // Post data updating
        $newPostData = [];
        foreach ($postData as $field => $value) {
            if (in_array($field, $postDataFields)) {
                $newPostData[$field] = $value;
            }
        }
        $this->update($newPostData, 'id = ' . $postId);
    }

    /**
     * If post with given id is found do soft-delete else throw Exception
     * @param int $postId
     * @return Row Delete|Exception
     */
    public function deletePost($postId) {
        $select = $this->select();
        $select->where('id = (?)', $postId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update([
                'deleted' => self::IS_DELETED
            ], 'id = ' . $postId);
        } else
            return new Exception('No post found for given id;');
    }
}