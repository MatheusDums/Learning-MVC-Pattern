<?php

namespace App\Utils;

class View{

    // Variáveis padrões da View
    private static $vars = [];

    // Método responsável por definir os dados iniciais da classe
    public static function init($vars = []) {
        self::$vars = $vars;
    }

    private static function getContentView($view) {
        // Método responsável por retornar o conteúdo de uma view
        $file = __DIR__ . '/../../resources/View/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : 'Erro 404 - Página não encontrada';
    }

    public static function render($view, $vars = []) {
        // Método responsável por retornar o conteúdo renderizado de uma view
        $contentView = self::getContentView($view); //Conteúdo da View
        
        // Merge de variáveis da view
        $vars = array_merge(self::$vars, $vars);

        //Chave e valor do array de variáveis
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
    }
}