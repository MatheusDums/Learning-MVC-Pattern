<?php 
namespace App\Controller\Pages;

USE App\Utils\View;

class Page {

    private static function getHeader() {
    // Método responsável por renderixar o conteúdo do header
        return View::render('pages/header', [
            'title' => 'MVC Structure'
        ]);
    }

    private static function getFooter() {
    // Método responsável por renderixar o conteúdo do footer
        return View::render('pages/footer', [
            'author' => 'Matheus Dums'
        ]);
    }

    public static function getPage($title, $content){
    // Método responsável por retornar o conteúdo (view) da page
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }



}