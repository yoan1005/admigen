<?php

    return [
        /*
        * Model à gérer model => Icone à afficher
        */
        'icons' => [
          'Model' => 'pe-7s-film'
        ],
        /*
        * Model à gérer model => nom à afficher
        */
        'models' => [
          'Model' => 'Model name'
        ],
        /*
        * Champs à afficher dans le tableau listing / model
        */
        'fields' => [
            'Model' => ["id","name","created_at"]
        ],

        /*
        * MODELS POUVANT PAS UTILISER LES FONCTIONS SUIVANTES
        */
        'cant' => [
            'show' => [],
            'add' => [],
            'edit' => [],
            'delete' => [],
            'order' => [],
            'paginate' => [],

        ],
        /*
        * Label des champs à afficher dans le tableau ci-dessus
        */
        'trads' => [
            'Model' => [
              "id" => "#",
              "name" => "Nom",
              "created_at" => "Crée le "
            ]
        ],

        /*
        * Condition spécifique pour la récupération dans le tableau listing / model
        */
        'conditions' => [
            'Model' => [
              ['field' => 'field', 'operator' => '==', 'value' => 'test']
            ]
        ],

        /*
        * Ordre spécifique pour la récupération dans le tableau listing / model
        */
        'orderby' => [
            'Model' => [
              ['field' => 'id', 'value' => 'ASC']
            ]
        ],


        /*
        * Trad à afficher à la place des clés dans les labels
        */
        'transKey' => [

        ],





    ];
