<?php


namespace App\src\model;


class Article
{
    private $id;

    private $title;

    private $content;

    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
       $this->title = $title;
    }


    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }


    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
}