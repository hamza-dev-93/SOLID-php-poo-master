<?php


class Http{
    public static function redirect(string $url): void{
        // 4. Redirection vers l'article en question :
        header("Location: " . $url);
        exit();
    }
}