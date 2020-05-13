<?php

namespace App\src\controller;

use App\config\Parameter;

class BackController extends Controller
{
    private function checkLoggedIn()
    {
        if(!$this->session->get('pseudo')){
            $this->session->set('need_login', 'Vous devez vous connecter pour accéder à cette page');
            header('Location: ../public/index.php?route=login');
        } else {
            return true;
        }
    }

    private function checkAdmin()
    {
        $this->checkLoggedIn();
        if(!($this->session->get('role') === 'admin')){
            $this->session->set('no_admin', 'Vous n\'avez pas les droits d\'accès à cette page');
            header('Location: ../public/index.php?route=profile');
        } else {
            return true;
        }
    }
    public function administration()
    {
        if($this->checkAdmin()){
            $articles = $this->articleDAO->getArticles();
            $comments = $this->commentDAO->getFlagComments();
            $users = $this->userDAO->getUsers();
            echo $this->twig->render('administration.html.twig', [
                'articles' => $articles,
                'comments' => $comments,
                'users' => $users
            ]);
        }

    }
    public function addArticle(Parameter $post)
    {
        if($this->checkAdmin()) {
            $errors = $this->validation->validate($post, 'Article');
            if($post->get('submit')) {

                if(!$errors){
                    $this->articleDAO->addArticle($post, $this->session->get('id'));
                    $this->session->set('add_article', 'Article bien ajouté !');
                    header('Location: ../public/index.php?route=administration');
                }

            }
            echo $this->twig->render('add_article.html.twig', [
                'post' => $post,
                'errors' => $errors
            ]);
        }

    }

    public function editArticle(Parameter $post, $articleId)
    {
        if($this->checkAdmin()){
            $article = $this->articleDAO->getArticle($articleId);
            $post->set('id', $article->getId());
            if($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Article');
                if(!$errors) {
                    $this->articleDAO->editArticle($post, $articleId, $this->session->get('id'));
                    $this->session->set('edit_article', 'L\' article a bien été modifié');
                    header('Location: ../public/index.php?route=administration');
                }

                echo $this->twig->render('edit_article.html.twig', [
                    'errors' => $errors,
                    'post' => $post
                ]);
                var_dump('toto');

            }
            else {

                $post->set('title', $article->getTitle());
                $post->set('content', $article->getContent());
                $post->set('author', $article->getAuthor());

                echo $this->twig->render('edit_article.html.twig', [
                    'post' => $post
                ]);
                var_dump('tata');

            }
        }

    }

    public function deleteArticle($articleId)
    {
        if($this->checkAdmin()){
            $this->articleDAO->deleteArticle($articleId);
            $this->session->set('delete_article', 'L\'article a bien été supprimé.');
            header('Location: ../public/index.php?route=administration');
        }

    }

    public function unflagComment($commentId)
    {
        if($this->checkAdmin()){
            $this->commentDAO->unflagComment($commentId);
            $this->session->set('unflag_comment', 'Le commentaire a bien été désignalé.');
            header('Location: ../public/index.php?route=administration');
        }

    }

    public function deleteComment($commentId)
    {
        if($this->checkAdmin()){
            $this->commentDAO->deleteComment($commentId);
            $this->session->set('delete_comment', 'Le commentaire a bien été supprimé.');
            header('Location: ../public/index.php?route=administration');
        }

    }

    public function profile()
    {
        if($this->checkLoggedIn()){
            echo $this->twig->render('profile.html.twig');
        }

    }

    public function updatePassword(Parameter $post)
    {
        if($this->checkLoggedIn()){
            $errors = $this->validation->validate($post, 'User');
            var_dump($errors);
            if(!$errors && $post->get('submit')) {
                $this->userDAO->updatePassword($post, $this->session->get('pseudo'));
                $this->session->set('update_password', 'Le mot de passe a été mis à jour');
                header('Location: ../public/index.php?route=profile');
            }

            echo $this->twig->render('update_password.html.twig', [
                'session'=>$this->session,
                'errors' =>$errors
            ]);
        }

    }

    public function logout()
    {
        if($this->checkLoggedIn()){
            $this->logoutOrDelete('logout');
        }

    }

    public function deleteAccount()
    {
        if($this->checkLoggedIn()){
            $this->userDAO->deleteAccount($this->session->get('pseudo'));
            $this->logoutOrDelete('delete_account');
        }

    }

    public function deleteUser($userId)
    {
        if($this->checkAdmin()){
            $this->userDAO->deleteUser($userId);
            $this->session->set('delete_user', 'Le compte de l\'utilisateur a bien été supprimé');
            header('Location: ../public/index.php?route=administration');
        }

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
