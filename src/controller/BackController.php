<?php

namespace App\src\controller;

use App\config\Parameter;

class BackController extends Controller
{
    public function administration()
    {
        $articles = $this->articleDAO->getArticles();
        echo $this->twig->render('administration.html.twig', [
            'articles' => $articles
        ]);
    }
    public function addArticle(Parameter $post)
    {
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $this->articleDAO->addArticle($post, $this->session->get('id'));
                $this->session->set('add_article', 'Article bien ajouté !');
                header('Location: ../public/index.php?route=administration');
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
        $article = $this->articleDAO->getArticle($articleId);
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors) {
                $this->articleDAO->editArticle($post, $articleId, $this->session->get('id'));
                $this->session->set('edit_article', 'L\' article a bien été modifié');
                header('Location: ../public/index.php?route=administration');
            }
            echo $this->twig->render('edit_article.html.twig', [
                'post' => $post,
                'errors' => $errors
            ]);

        }
        $post->set('id', $article->getId());
        $post->set('title', $article->getTitle());
        $post->set('content', $article->getContent());
        $post->set('author', $article->getAuthor());

        echo $this->twig->render('edit_article.html.twig', [
            'post' => $post
        ]);
    }


    public function deleteArticle($articleId)
    {
        $this->articleDAO->deleteArticle($articleId);
        $this->session->set('delete_article', 'L\'article a bien été supprimé');
        header('Location: ../public/index.php?route=administration');
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

    public function updatePassword(Parameter $post)
    {
        if($post->get('submit')) {
            $this->userDAO->updatePassword($post, $this->session->get('pseudo'));
            $this->session->set('update_password', 'Le mot de passe a été mis à jour');
            header('Location: ../public/index.php?route=profile');
        }
        echo $this->twig->render('update_password.html.twig', [
            'session'=>$this->session
        ]);
    }

    public function logout()
    {
        $this->logoutOrDelete('logout');
    }

    public function deleteAccount()
    {
        $this->userDAO->deleteAccount($this->session->get('pseudo'));
        $this->logoutOrDelete('delete_account');
    }

    private function logoutOrDelete($param)
    {
        $this->session->stop();
        $this->session->start();
        if ($param === 'logout'){
            $this->session->set($param, 'A bientôt');
        }
        else{
            $this->session->set($param, 'Votre compte a bien été supprimé');
        }
        header('Location: ../public/index.php');
    }
}
