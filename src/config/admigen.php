<?php

    return [
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



    ];
