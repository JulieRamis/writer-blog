<?php


namespace App\src\controller;

use App\config\Request;
use App\src\constraint\Validation;
use App\src\DAO\ArticleDAO;
use App\src\DAO\CommentDAO;
use App\src\model\View;
use App\src\DAO\UserDAO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


abstract class Controller
{
    protected $articleDAO;
    protected $commentDAO;
    protected $userDAO;
    protected $view;
    private $request;
    protected $get;
    protected $post;
    protected $session;
    protected $loader;
    protected $twig;
    protected $validation;

    public function __construct()
    {

        $this->articleDAO = new ArticleDAO();
        $this->commentDAO = new CommentDAO();
        $this->userDAO = new userDAO();
        $this->view = new View();
        $this->validation = new Validation();
        $this->request = new Request();
        $this->get = $this->request->getGet();
        $this->post = $this->request->getPost();
        $this->session = $this->request->getSession();
        $this->loader=new FilesystemLoader('../templates/');
        $this->twig = new Environment($this->loader,['debug'=>true]);
        $this->twig->addGlobal("session", $this->session);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

    }


}

