<?php

namespace App\src\controller;

use App\config\Parameter;

class BackController extends Controller
{
    public function addArticle(Parameter $post)
    {
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $this->articleDAO->addArticle($post);
                $this->session->set('add_article', 'Article bien ajouté !');
                header('Location: ../public/index.php');
            }
            echo $this->twig->render('add_article.html.twig',[
                'post'=>$post,
                'errors' => $errors
                ] );
        }
        echo $this->twig->render('add_article.html.twig' );
    }


    public function editArticle(Parameter $post, $articleId)
    {
        var_dump($articleId);
        $article = $this->articleDAO->getArticle($articleId);
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors) {
                $this->articleDAO->editArticle($post, $articleId);
                $this->session->set('edit_article', 'L\' article a bien été modifié');
                header('Location: ../public/index.php');
            }
            echo $this->twig->render('edit_article.html.twig', [
                'post' => $post,
                'errors' => $errors
            ]);

        }
        $post->set('id', $article->getId());
        $post->set('title', $article->getTitle());
        $post->set('content', $article->getContent());
        echo $this->twig->render('edit_article.html.twig', [
            'post' => $post
        ]);
    }


    public function deleteArticle($articleId)
    {
        $this->articleDAO->deleteArticle($articleId);
        $this->session->set('delete_article', 'L\'article a bien été supprimé');
        header('Location: ../public/index.php');
    }


    public function deleteComment($commentId)
    {
        $this->commentDAO->deleteComment($commentId);
        $this->session->set('delete_comment', 'Le commentaire a bien été supprimé');
        header('Location: ../public/index.php');
    }

    public function profile()
    {
        echo $this->twig->render('profile.html.twig');
    }
}
