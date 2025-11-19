<?php 
namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Organization;

class Home extends Page {

    // Método responsável por retornar o conteúdo (view) da página home
    public static function getHome(){

        // Organização
        $organization = new Organization();

        // View da home
        $content = View::render('pages/home', [
            'name' => $organization->name
        ]);

        // Retorna a view da página
        return parent::getPage('Teste - Home', $content);
    }



}