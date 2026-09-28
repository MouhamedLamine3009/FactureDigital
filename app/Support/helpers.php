<?php

use App\Support\Initials;

if (! function_exists('initials')) {
    /**
     * Génère les initiales d'un nom complet : première lettre du prénom
     * suivie de la première lettre du nom.
     *
     * "Muhamed Sene"        => "MS"
     *  "Jean Pierre Dupont" => "JD"
     *  "Prince"             => "PR"
     */
    function initials(?string $name): string
    {
        return Initials::make($name);
    }
}
