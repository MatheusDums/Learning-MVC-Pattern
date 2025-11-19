<?php 
namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Organization;

class About extends Page {

    // Método responsável por retornar o conteúdo (view) da página sobre (about)
    public static function getAbout(){

        // Organização
        $organization = new Organization();

        // View da home
        $content = View::render('pages/about', [
            'name' => $organization->name,
            'description' => $organization->description
        ]);

        // Retorna a view da página
        return parent::getPage('Teste - Sobre', $content);
    }



}