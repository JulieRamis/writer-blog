<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Comment;


class CommentDAO extends DAO
{
    private function buildObject($row, $user)
    {
        $comment = new Comment();
        $comment->setId($row['id']);
        $comment->setContent($row['content']);
        $comment->setDate($row['date']);
        $comment->setFlag($row['flag']);
        $comment->setUser($user);
        return $comment;
    }
    public function getComments($articleId)
    {
        $sql = 'SELECT * FROM comment WHERE article_id = ? ORDER BY date DESC';
        $result = $this->createQuery($sql, [$articleId]);
        $comments = [];
        foreach ($result as $row){
            $userid=$row['user_id'];
            $sql = 'SELECT * FROM user WHERE id = ?';
            $result=$this->createQuery($sql, [$userid]);
            $user = $result->fetch();
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row, $user['pseudo']);

        }

        $result->closeCursor();
        return $comments;

    }



    public function addComment(Parameter $post, $userId, $articleId)
    {
        $sql = 'INSERT INTO comment (user_id, content, date, flag, article_id) VALUES (?, ?, NOW(), ?, ?)';
        $this->createQuery($sql, [$userId, $post->get('content'), 0, $articleId]);
    }

    public function flagComment($commentId)
    {
        $sql = 'UPDATE comment SET flag = ? WHERE id = ?';
        $this->createQuery($sql,[1, $commentId]);
    }

    public function unflagComment($commentId)
    {
        $sql = 'UPDATE comment SET flag = ? WHERE id = ?';
        $this->createQuery($sql, [0, $commentId]);
    }

    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comment WHERE id = ?';
        $this->createQuery($sql, [$commentId]);
    }

    public function getFlagComments()
    {
        $sql ='SELECT * FROM comment WHERE flag = ? ORDER BY date DESC';
        $result = $this->createQuery($sql, [1]);
        $comments = [];
        foreach ($result as $row){
            $userid=$row['user_id'];
            $sql = 'SELECT * FROM user WHERE id = ?';
            $result=$this->createQuery($sql, [$userid]);
            $user = $result->fetch();
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row, $user['pseudo']);
        }
        $result->closeCursor();
        return $comments;
    }
}