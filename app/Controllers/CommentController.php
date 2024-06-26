<?php

namespace Controllers;

use Validators\Validator;

require_once __DIR__ . '/../Models/Comment.php';
require_once __DIR__ . '/../Validators/Validator.php';

class CommentController
{
    private $commentModel;

    public function __construct($pdo)
    {
        $this->commentModel = new \Models\Comment($pdo);
    }
    public function create($post_id, $user_id, $comment)
    {
        $data = [];

        if (!Validator::validateBody($comment)) {
            $data['error'] = 'Comment must have at least 4 characters.';
            return $data;
        }

        try {
            $postId = $this->commentModel->create($post_id, $user_id, $comment);
            $data['commentId'] = $postId;
            $data['success'] = 'Successfully left comment!';
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return $data;
    }

    public function updateForm($id)
    {
        $comment = $this->commentModel->find($id);
        require '../app/Views/comment/update.blade.php';
    }

    public function update($id, $comment)
    {
        $data = [];

        if (!Validator::validateBody($comment)) {
            $data['error'] = 'Comment must have at least 4 characters.';
            return $data;
        }

        try {
            $updated = $this->commentModel->update($id, $comment);
            if($updated > 0) {
                $data['success'] = 'Comment changed successfully!';
            }
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return $data;
    }

    public function delete($id)
    {
        $data = [];

        try {
            $deleted = $this->commentModel->delete($id);
            if($deleted > 0) {
                $data['success'] = 'Comment deleted successfully!';
            }
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return $data;
    }
}