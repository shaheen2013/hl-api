<?php

/*
 * DEFAULT CUSTOM SURVEY CATEGORIES
 * When an hotel activates for first time the custom surveys feature
 * We get this categories and create as default categories for this hotel.
 * After this, hotel can delete or modify this categories.
 * Whenever a nwe lang is activated we need to update this array
 */
return [
    'default_questions_and_categories' => [
        [
            'es' =>
                [
                    'category' => 'Limpieza',
                    'question' => 'Grado de satisfacción con la limpieza del hotel.',
                ],
            'en' =>
                [
                    'category' => 'Cleanliness',
                    'question' => 'Degree of satisfaction with hotel cleanliness.',
                ],
            'de' =>
                [
                    'category' => 'Reinigung',
                    'question' => 'Zufriedenheitsgrad mit der Reinigung im Hotel.',
                ],
            'fr' =>
                [
                    'category' => 'Propreté',
                    'question' => 'Degré de satisfaction avec la propreté de l’hôtel.',
                ],
            'it' =>
                [
                    'category' => 'Pulizia',
                    'question' => 'Grado di soddisfazione della pulizia dell\'hotel.',
                ],
            'ca' =>
                [
                    'category' => 'Neteja',
                    'question' => 'Grau de satisfacció amb la neteja de l\'hotel..',
                ]
        ],
        [
            'es' =>

                [
                    'category' => 'Confort',
                    'question' => 'Grado de satisfacción con el confort de las instalaciones.'
                ],
            'en' =>
                [
                    'category' => 'Comfort',
                    'question' => 'Degree of satisfaction with the comfort of the facilities.'
                ],
            'de' =>

                [
                    'category' => 'Komfort',
                    'question' => 'Zufriedenheitsgrad mit dem Komfort der Einrichtungen.'
                ],
            'fr' =>
                [
                    'category' => 'Confort',
                    'question' => 'Degré de satisfaction avec le confort des installations.'
                ],
            'it' =>
                [
                    'category' => 'Comfort',
                    'question' => 'Grado di soddisfazione del comfort delle strutture.'
                ],
            'ca' =>
                [
                    'category' => 'Confort',
                    'question' => 'Grau de satisfacció amb el confort de les instal·lacions.'
                ]
        ],
        [
            'es' =>

                [
                    'category' => 'Instalaciones',
                    'question' => 'Grado de satisfacción con el mantenimiento general de las instalaciones.'
                ],

            'en' =>
                [
                    'category' => 'Facilities',
                    'question' => 'Degree of satisfaction with the general maintenance of the facilities.'
                ],
            'de' =>
                [
                    'category' => 'Einrichtungen',
                    'question' => 'Zufriedenheitsgrad mit der allgemeinen Instandhaltung der Einrichtungen.'
                ],
            'fr' =>
                [
                    'category' => 'Installations',
                    'question' => 'Degré de satisfaction avec l’entretien général des installations.'
                ],
            'it' =>
                [
                    'category' => 'Strutture',
                    'question' => 'Grado di soddisfazione della conservazione generale delle strutture. '
                ],
            'ca' =>
                [
                    'category' => 'Instal·lacions',
                    'question' => 'Grau de satisfacció amb el manteniment general de les instal·lacions.'
                ],

        ],
        [
            'es' =>
                [
                    'category' => 'Personal',
                    'question' => 'Grado de satisfacción con el trato y profesionalidad del personal del hotel.'
                ],
            'en' =>
                [
                    'category' => 'Staff',
                    'question' => 'Degree of satisfaction with the treatment and professionalism of hotel staff.'
                ],
            'de' =>
                [
                    'category' => 'Personal ',
                    'question' => 'Zufriedenheitsgrad mit Verhalten und fachlicher Qualifikation des Hotelpersonals.'
                ],
            'fr' =>
                [
                    'category' => 'Personnel',
                    'question' => 'Degré de satisfaction avec le traitement et le professionnalisme du personnel de l’hôtel.'
                ],
            'it' =>
                [
                    'category' => 'Personale',
                    'question' => 'Grado di soddisfazione del servizio e della professionalità del personale dell\'hotel'
                ],
            'ca' =>
                [
                    'category' => 'Personal',
                    'question' => 'Grau de satisfacció amb el tracte i professionalitat del personal de l\'hotel.'
                ]
        ],
        [
            'es' =>
                [
                    'category' => 'Calidad precio',
                    'question' => 'Grado de satisfacción con la relación calidad precio de la reserva.'
                ],
            'en' =>
                [
                    'category' => 'Value for money',
                    'question' => 'Degree of satisfaction with the value for money of the reservation.'
                ],
            'de' =>
                [
                    'category' => 'Preis-Leistungsverhältnis',
                    'question' => 'Zufriedenheitsgrad mit dem für die Kosten der Buchung erhaltenen Gegenwert.'
                ],
            'fr' =>
                [
                    'category' => 'Valeur par prix',
                    'question' => 'Degré de satisfaction de la valeur reçue par le coût de la réservation.'
                ],
            'it' =>
                [
                    'category' => 'Prezzo valore',
                    'question' => 'Grado di soddisfazione del valore ricevuto in base al prezzo della prenotazione.'
                ],
            'ca' =>
                [
                    'category' => 'Valor per preu',
                    'question' => 'Grau de satisfacció del valor rebut pel cost de la reserva.'
                ],
        ],
        [
            'es' =>
                [
                    'category' => 'Ubicación',
                    'question' => 'Grado de satisfacción con la ubicación del hotel.'
                ],
            'en' =>
                [
                    'category' => 'Location',
                    'question' => 'Degree of satisfaction with hotel location.'
                ],
            'de' =>
                [
                    'category' => 'Lage',
                    'question' => 'Zufriedenheitsgrad mit der Lage des Hotels.'
                ],
            'fr' =>
                [
                    'category' => 'Emplacement',
                    'question' => 'Degré de satisfaction avec l’emplacement de l’hôtel.'
                ],
            'it' =>
                [
                    'category' => 'Ubicazione',
                    'question' => 'Grado di soddisfazione dell\'ubicazione dell\'hotel.'
                ],
            'ca' =>
                [
                    'category' => 'Localització',
                    'question' => 'Grau de satisfacció amb la localització de l\'hotel.'
                ],
        ]
    ],
    'othersResponse' =>  [
        [
            'lang_value' => 'es',
            'text' => 'Otros'
        ],
        [
            'lang_value' => 'en',
            'text' => 'Others'
        ],
        [
            'lang_value' => 'de',
            'text' => 'Sonstiges'
        ],
        [
            'lang_value' => 'fr',
            'text' => 'Autres'
        ],
        [
            'lang_value' => 'it',
            'text' => 'Altro'
        ],
        [
            'lang_value' => 'ca',
            'text' => 'Altres'
        ],
        [
            'lang_value' => 'zh',
            'text' => '其他'
        ],
    ],
    'steps' => 2,
    'days_after_first_step' => 2
];
