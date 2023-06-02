<?php

class Constants {
    
    public const DEV_MODE = false;
    public const DEBUG_MODE = false;

    public const WEB_SITE_NAME = "Chercher & Trouver";
    public const EMAIL_NO_REPLY = "noreply@cherchertrouver.jeromepourra.fr";
    public const WEB_SITE_DEFAULT_DESC = "Chercher & Trouver la référence des petites annonces de matériel informatique sur Sète et ses alentours";

    public const SIGNUP_REQUIRED_AGE = 18;
    public const PASSWORD_HASH_OPTIONS = ["cost" => 12];
    public const ANNONCE_PICTURES_MAX = 5;
    public const ANNONCE_MAX_PER_PAGE = 5;
    public const USER_MAX_PER_PAGE = 15;

    public const CONTACTS_FAVORI = [
        0 => "Aucune préférence",
        1 => "Téléphone",
        2 => "Email",
        3 => "Message sur le site"
    ];

    public const USER_ROLES = [
        0 => [
            "name" => "Utilisateur"
        ],
        1 => [
            "name" => "Modérateur",
            "menu-name" => "Modérer"
        ],
        2 => [
            "name" => "Administrateur",
            "menu-name" => "Administrer"
        ],
        3 => [
            "name" => "L'Architecte",
            "menu-name" => "Administrer"
        ]
    ];

    public const RATE_VAL_MIN = 1;
    public const RATE_VAL_MAX = 5;

    // PATH
    public const PATH_IMG = "/views/public/img";
    public const PATH_UPLOAD = self::PATH_IMG . "/upload";
    public const PATH_ANNONCES = self::PATH_UPLOAD . "/annonces";

}