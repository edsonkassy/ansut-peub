<?php

namespace App\Helpers;

class RegionHelper
{
    /**
     * Les 31 régions officielles de Côte d'Ivoire + 2 districts
     */
    public static function getRegions()
    {
        return [
            'Abidjan' => 'Abidjan (District)',
            'Yamoussoukro' => 'Yamoussoukro (District)',
            'Agnéby‑Tiassa' => 'Agnéby‑Tiassa',
            'Bafing' => 'Bafing',
            'Bagoué' => 'Bagoué',
            'Bélier' => 'Bélier',
            'Béré' => 'Béré',
            'Bounkani' => 'Bounkani',
            'Cavally' => 'Cavally',
            'Folon' => 'Folon',
            'Gbêkê' => 'Gbêkê',
            'Gbôklé' => 'Gbôklé',
            'Gôh' => 'Gôh',
            'Gontougo' => 'Gontougo',
            'Grands‑Ponts' => 'Grands‑Ponts',
            'Guémon' => 'Guémon',
            'Hambol' => 'Hambol',
            'Haut‑Sassandra' => 'Haut‑Sassandra',
            'Iffou' => 'Iffou',
            'Indénié‑Djuablin' => 'Indénié‑Djuablin',
            'Kabadougou' => 'Kabadougou',
            'La Mé' => 'La Mé',
            'LôhDjiboua' => 'LôhDjiboua',
            'Marahoué' => 'Marahoué',
            'Moronou' => 'Moronou',
            'Nawa' => 'Nawa',
            'Nzi' => 'Nzi',
            'Poro' => 'Poro',
            'San‑Pédro' => 'San‑Pédro',
            'Sud‑Comoé' => 'Sud‑Comoé',
            'Tchologo' => 'Tchologo',
            'Tonkpi' => 'Tonkpi',
            'Worodougou' => 'Worodougou',
        ];
    }

    /**
     * Coordonnées géographiques des régions (chef-lieu de région)
     */
    public static function getRegionCoordinates()
    {
        return [
            // Districts
            'Abidjan' => [-4.0167, 5.3167],
            'Yamoussoukro' => [-5.2767, 6.8205],
            
            // Régions
            'Agnéby‑Tiassa' => [-4.2139, 5.9267], // Agboville
            'Bafing' => [-7.6833, 8.2833], // Touba
            'Bagoué' => [-6.4833, 9.5167], // Boundiali
            'Bélier' => [-5.0305, 7.6922], // Bouaké (approximation)
            'Béré' => [-6.1858, 8.0583], // Mankono
            'Bounkani' => [-2.9833, 9.2667], // Bouna
            'Cavally' => [-7.4978, 6.5439], // Guiglo
            'Folon' => [-8.1500, 7.2667], // Danané (approximation)
            'Gbêkê' => [-5.0305, 7.6922], // Bouaké
            'Gbôklé' => [-6.0919, 4.9500], // Sassandra
            'Gôh' => [-5.9500, 6.1333], // Gagnoa
            'Gontougo' => [-2.8000, 8.0333], // Bondoukou
            'Grands‑Ponts' => [-3.7378, 5.2111], // Grand-Bassam
            'Guémon' => [-7.5539, 7.4122], // Man
            'Hambol' => [-5.1000, 8.1333], // Katiola
            'Haut‑Sassandra' => [-6.4442, 6.8770], // Daloa
            'Iffou' => [-4.7058, 6.6475], // Dimbokro
            'Indénié‑Djuablin' => [-3.4972, 6.7289], // Abengourou
            'Kabadougou' => [-7.5667, 9.5086], // Odienné
            'La Mé' => [-3.8633, 6.1089], // Adzopé
            'LôhDjiboua' => [-5.3572, 5.8397], // Divo
            'Marahoué' => [-5.7450, 6.9900], // Bouaflé
            'Moronou' => [-3.1692, 7.8008], // Tanda
            'Nawa' => [-6.5944, 5.7856], // Soubré
            'Nzi' => [-4.7058, 6.6475], // Dimbokro (approximation)
            'Poro' => [-5.6283, 9.4583], // Korhogo
            'San‑Pédro' => [-6.6370, 4.7467], // San-Pédro
            'Sud‑Comoé' => [-3.2067, 5.4706], // Aboisso
            'Tchologo' => [-5.1967, 9.6000], // Ferkessédougou
            'Tonkpi' => [-7.5539, 7.4122], // Man (approximation)
            'Worodougou' => [-6.6733, 7.9611], // Séguéla
        ];
    }

    /**
     * Coordonnées des principales villes pour un mapping plus précis
     */
    public static function getCityCoordinates()
    {
        return [
            // District d'Abidjan
            'Abidjan' => [-4.0167, 5.3167],
            'Anyama' => [-4.0522, 5.4933],
            'Bingerville' => [-3.8956, 5.3583],
            'Cocody' => [-4.0104, 5.3472],
            'Plateau' => [-4.0278, 5.3258],
            'Marcory' => [-4.0083, 5.2917],
            'Treichville' => [-4.0167, 5.3167],
            'Adjamé' => [-4.0278, 5.3472],
            'Attécoubé' => [-4.0556, 5.3472],
            'Yopougon' => [-4.0889, 5.3472],
            'Abobo' => [-4.0167, 5.4167],
            'Port-Bouët' => [-3.9167, 5.2333],
            'Koumassi' => [-3.9667, 5.2917],
            'Songon' => [-4.1333, 5.2917],

            // District de Yamoussoukro
            'Yamoussoukro' => [-5.2767, 6.8205],

            // Région Agnéby-Tiassa
            'Agboville' => [-4.2139, 5.9267],
            'Sikensi' => [-4.5833, 5.6833],
            'Tiassalé' => [-4.8211, 5.8978],

            // Région Bafing
            'Touba' => [-7.6833, 8.2833],
            'Koro' => [-7.8500, 8.4500],

            // Région Bagoué
            'Boundiali' => [-6.4833, 9.5167],
            'Kouto' => [-6.0833, 9.8333],
            'Tengrela' => [-6.4500, 9.8333],

            // Région Bélier
            'Toumodi' => [-5.0167, 6.5500],
            'Diédougou' => [-5.2167, 6.3167],

            // Région Béré
            'Mankono' => [-6.1858, 8.0583],
            'Marandallah' => [-6.0833, 8.1333],

            // Région Bounkani
            'Bouna' => [-2.9833, 9.2667],
            'Doropo' => [-3.3500, 9.7833],
            'Tehini' => [-3.3167, 9.4667],

            // Région Cavally
            'Guiglo' => [-7.4978, 6.5439],
            'Bloléquin' => [-8.0167, 6.5833],
            'Toulepleu' => [-8.4167, 6.5833],

            // Région Folon
            'Danané' => [-8.1500, 7.2667],
            'Mahapleu' => [-7.9167, 7.4167],
            'Zouan-Hounien' => [-8.2167, 7.0833],

            // Région Gbêkê
            'Bouaké' => [-5.0305, 7.6922],
            'Béoumi' => [-5.5833, 7.6667],
            'Bodokro' => [-5.1167, 7.8333],
            'Kondrobo' => [-5.0833, 7.5833],
            'Sakassou' => [-5.2917, 7.4500],

            // Région Gbôklé
            'Sassandra' => [-6.0919, 4.9500],
            'Fresco' => [-5.5833, 5.1167],

            // Région Gôh
            'Gagnoa' => [-5.9500, 6.1333],
            'Oumé' => [-5.4169, 6.3831],
            'Bayota' => [-5.9167, 6.3833],

            // Région Gontougo
            'Bondoukou' => [-2.8000, 8.0333],
            'Sandégué' => [-2.8667, 8.2833],
            'Transua' => [-2.6167, 8.1333],

            // Région Grands-Ponts
            'Grand-Bassam' => [-3.7378, 5.2111],
            'Dabou' => [-4.3792, 5.3253],
            'Grand-Lahou' => [-5.2428, 5.2506],
            'Jacqueville' => [-4.4167, 5.2167],

            // Région Guémon
            'Man' => [-7.5539, 7.4122],
            'Biankouma' => [-7.7389, 7.7453],
            'Danané' => [-8.1500, 7.2667],
            'Sipilou' => [-8.0167, 7.4167],
            'Zouan-Hounien' => [-8.2167, 7.0833],

            // Région Hambol
            'Katiola' => [-5.1000, 8.1333],
            'Fronan' => [-5.2167, 8.3667],
            'Niakramandougou' => [-5.3167, 8.6333],
            'Niakaramadougou' => [-5.0167, 8.6667],

            // Région Haut-Sassandra
            'Daloa' => [-6.4442, 6.8770],
            'Issia' => [-6.4939, 6.4933],
            'Saïoua' => [-6.5833, 6.8333],
            'Vavoua' => [-6.4781, 7.3833],
            'Zuénoula' => [-6.0508, 7.4333],

            // Région Iffou
            'Dimbokro' => [-4.7058, 6.6475],
            'Daoukro' => [-4.0167, 7.0833],
            'M\'Bahiakro' => [-4.3333, 7.4500],

            // Région Indénié-Djuablin
            'Abengourou' => [-3.4972, 6.7289],
            'Agnibilékrou' => [-3.2000, 7.1167],
            'Bettié' => [-3.1667, 6.8333],
            'Niablé' => [-3.1000, 6.6167],

            // Région Kabadougou
            'Odienné' => [-7.5667, 9.5086],
            'Gbéléban' => [-7.6833, 9.3833],
            'Madinani' => [-7.4167, 9.6667],
            'Minignan' => [-7.2500, 9.5000],
            'Samatiguila' => [-7.5833, 9.7667],
            'Séguélon' => [-7.4333, 9.8333],

            // Région La Mé
            'Adzopé' => [-3.8633, 6.1089],
            'Akoupé' => [-3.7167, 6.3833],
            'Alépé' => [-3.6667, 5.4833],
            'Yakassé-Attobrou' => [-3.7667, 6.0833],

            // Région Lôh-Djiboua
            'Divo' => [-5.3572, 5.8397],
            'Fresco' => [-5.5833, 5.1167],
            'Guitry' => [-5.2333, 5.4167],
            'Hiré' => [-5.8333, 5.9167],
            'Lakota' => [-5.8508, 5.8508],

            // Région Marahoué
            'Bouaflé' => [-5.7450, 6.9900],
            'Sinfra' => [-5.9108, 6.6219],
            'Zukougbeu' => [-5.8333, 6.8333],

            // Région Moronou
            'Tanda' => [-3.1692, 7.8008],
            'Assuefry' => [-3.0833, 7.7333],
            'Koun-Fao' => [-3.2167, 9.6167],

            // Région Nawa
            'Soubré' => [-6.5944, 5.7856],
            'Buyo' => [-6.2833, 6.2667],
            'Grand-Zattry' => [-6.4167, 5.9167],
            'Guéyo' => [-6.8333, 5.8333],

            // Région Nzi
            'Dimbokro' => [-4.7058, 6.6475],
            'Bocanda' => [-4.4833, 7.0667],
            'Kouassi-Kouassikro' => [-4.8333, 7.2833],

            // Région Poro
            'Korhogo' => [-5.6283, 9.4583],
            'Dikodougou' => [-5.7000, 9.7000],
            'Kouto' => [-6.0833, 9.8333],
            'Lataha' => [-5.8333, 9.4167],
            'M\'Bengué' => [-5.4167, 9.6500],
            'Napié' => [-5.6167, 9.6167],
            'Sinématiali' => [-5.5833, 9.5833],

            // Région San-Pédro
            'San-Pédro' => [-6.6370, 4.7467],
            'Tabou' => [-7.3500, 4.4231],

            // Région Sud-Comoé
            'Aboisso' => [-3.2067, 5.4706],
            'Adiaké' => [-3.2833, 5.2833],
            'Grand-Bassam' => [-3.7378, 5.2111],
            'Tiapoum' => [-3.1167, 5.4167],

            // Région Tchologo
            'Ferkessédougou' => [-5.1967, 9.6000],
            'Kong' => [-4.6083, 9.1500],
            'Ouangolodougou' => [-5.1333, 9.9667],

            // Région Tonkpi
            'Man' => [-7.5539, 7.4122],
            'Biankouma' => [-7.7389, 7.7453],
            'Danané' => [-8.1500, 7.2667],
            'Facobly' => [-8.3500, 7.3833],
            'Gouiné' => [-8.1833, 7.5833],
            'Logoualé' => [-8.0333, 7.6167],
            'Sangouiné' => [-8.0000, 7.8000],
            'Sipilou' => [-8.0167, 7.4167],
            'Zouan-Hounien' => [-8.2167, 7.0833],

            // Région Worodougou
            'Séguéla' => [-6.6733, 7.9611],
            'Kani' => [-6.7167, 8.1333],
            'Morondo' => [-6.4167, 8.0833],
        ];
    }

    /**
     * Obtenir les coordonnées d'une ville ou région
     */
    public static function getCoordinates($location)
    {
        $cityCoordinates = self::getCityCoordinates();
        $regionCoordinates = self::getRegionCoordinates();

        // Chercher d'abord dans les villes
        if (isset($cityCoordinates[$location])) {
            return $cityCoordinates[$location];
        }

        // Ensuite dans les régions
        if (isset($regionCoordinates[$location])) {
            return $regionCoordinates[$location];
        }

        // Par défaut, retourner Abidjan
        return $cityCoordinates['Abidjan'];
    }

    /**
     * Obtenir les régions formatées pour les sélects HTML
     */
    public static function getRegionsForSelect()
    {
        $regions = [];
        foreach (self::getRegions() as $key => $value) {
            $regions[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $regions;
    }

    /**
     * Obtenir les principales villes d'une région
     */
    public static function getCitiesForRegion($region)
    {
        $regionCities = [
            'Abidjan' => [
                'Abidjan', 'Anyama', 'Bingerville', 'Cocody', 'Plateau', 'Marcory', 
                'Treichville', 'Adjamé', 'Attécoubé', 'Yopougon', 'Abobo', 'Port-Bouët', 
                'Koumassi', 'Songon'
            ],
            'Yamoussoukro' => ['Yamoussoukro'],
            'Agnéby‑Tiassa' => ['Agboville', 'Sikensi', 'Tiassalé'],
            'Bafing' => ['Touba', 'Koro'],
            'Bagoué' => ['Boundiali', 'Kouto', 'Tengrela'],
            'Bélier' => ['Toumodi', 'Diédougou'],
            'Béré' => ['Mankono', 'Marandallah'],
            'Bounkani' => ['Bouna', 'Doropo', 'Tehini'],
            'Cavally' => ['Guiglo', 'Bloléquin', 'Toulepleu'],
            'Folon' => ['Danané', 'Mahapleu', 'Zouan-Hounien'],
            'Gbêkê' => ['Bouaké', 'Béoumi', 'Bodokro', 'Kondrobo', 'Sakassou'],
            'Gbôklé' => ['Sassandra', 'Fresco'],
            'Gôh' => ['Gagnoa', 'Oumé', 'Bayota'],
            'Gontougo' => ['Bondoukou', 'Sandégué', 'Transua'],
            'Grands‑Ponts' => ['Grand-Bassam', 'Dabou', 'Grand-Lahou', 'Jacqueville'],
            'Guémon' => ['Man', 'Biankouma', 'Danané', 'Sipilou', 'Zouan-Hounien'],
            'Hambol' => ['Katiola', 'Fronan', 'Niakramandougou', 'Niakaramadougou'],
            'Haut‑Sassandra' => ['Daloa', 'Issia', 'Saïoua', 'Vavoua', 'Zuénoula'],
            'Iffou' => ['Dimbokro', 'Daoukro', 'M\'Bahiakro'],
            'Indénié‑Djuablin' => ['Abengourou', 'Agnibilékrou', 'Bettié', 'Niablé'],
            'Kabadougou' => ['Odienné', 'Gbéléban', 'Madinani', 'Minignan', 'Samatiguila', 'Séguélon'],
            'La Mé' => ['Adzopé', 'Akoupé', 'Alépé', 'Yakassé-Attobrou'],
            'LôhDjiboua' => ['Divo', 'Fresco', 'Guitry', 'Hiré', 'Lakota'],
            'Marahoué' => ['Bouaflé', 'Sinfra', 'Zukougbeu'],
            'Moronou' => ['Tanda', 'Assuefry', 'Koun-Fao'],
            'Nawa' => ['Soubré', 'Buyo', 'Grand-Zattry', 'Guéyo'],
            'Nzi' => ['Dimbokro', 'Bocanda', 'Kouassi-Kouassikro'],
            'Poro' => ['Korhogo', 'Dikodougou', 'Kouto', 'Lataha', 'M\'Bengué', 'Napié', 'Sinématiali'],
            'San‑Pédro' => ['San-Pédro', 'Tabou'],
            'Sud‑Comoé' => ['Aboisso', 'Adiaké', 'Grand-Bassam', 'Tiapoum'],
            'Tchologo' => ['Ferkessédougou', 'Kong', 'Ouangolodougou'],
            'Tonkpi' => ['Man', 'Biankouma', 'Danané', 'Facobly', 'Gouiné', 'Logoualé', 'Sangouiné', 'Sipilou', 'Zouan-Hounien'],
            'Worodougou' => ['Séguéla', 'Kani', 'Morondo'],
        ];

        return $regionCities[$region] ?? [];
    }

    /**
     * Mapper les anciennes régions vers les nouvelles régions administratives
     */
    public static function mapOldRegionToNew($oldRegion)
    {
        $mapping = [
            // Anciennes régions groupées -> nouvelles régions administratives
            'Vallée du Bandama' => 'Gbêkê', // Bouaké est dans Gbêkê
            'Lagunes' => 'Grands‑Ponts', // Grand-Bassam est dans Grands-Ponts
            'Savanes' => 'Poro', // Korhogo est dans Poro
            'Montagnes' => 'Tonkpi', // Man est dans Tonkpi
            'Sassandra-Marahoué' => 'San‑Pédro', // San-Pédro est dans San-Pédro
            'Zanzan' => 'Gontougo', // Bondoukou est dans Gontougo
            'Gôh-Djiboua' => 'Gôh', // Gagnoa est dans Gôh
            'Lôh-Djiboua' => 'LôhDjiboua', // Divo est dans Lôh-Djiboua
            'N\'Zi-Comoé' => 'Indénié‑Djuablin', // Abengourou est dans Indénié-Djuablin
            'Haut-Sassandra' => 'Haut‑Sassandra', // Daloa est dans Haut-Sassandra
        ];

        return $mapping[$oldRegion] ?? $oldRegion;
    }

    /**
     * Normaliser une région (mapper les anciennes vers les nouvelles)
     */
    public static function normalizeRegion($region)
    {
        // Si la région existe déjà dans les nouvelles régions, la retourner
        if (array_key_exists($region, self::getRegions())) {
            return $region;
        }

        // Sinon, essayer de la mapper
        return self::mapOldRegionToNew($region);
    }

    /**
     * Retourne une liste de pays.
     */
    public static function getCountries()
    {
        return [
            "Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola", "Antigua-et-Barbuda",
            "Arabie Saoudite", "Argentine", "Arménie", "Australie", "Autriche", "Azerbaïdjan", "Bahamas", "Bahreïn",
            "Bangladesh", "Barbade", "Belgique", "Belize", "Bénin", "Bhoutan", "Biélorussie", "Bolivie",
            "Bosnie-Herzégovine", "Botswana", "Brésil", "Brunei", "Bulgarie", "Burkina Faso", "Burundi", "Cambodge",
            "Cameroun", "Canada", "Cap-Vert", "Chili", "Chine", "Chypre", "Colombie", "Comores", "Congo-Brazzaville",
            "Congo-Kinshasa", "Corée du Nord", "Corée du Sud", "Costa Rica", "Côte d'Ivoire", "Croatie", "Cuba",
            "Danemark", "Djibouti", "Dominique", "Égypte", "Émirats arabes unis", "Équateur", "Érythrée", "Espagne",
            "Estonie", "Eswatini", "États-Unis", "Éthiopie", "Fidji", "Finlande", "France", "Gabon", "Gambie",
            "Géorgie", "Ghana", "Grèce", "Grenade", "Guatemala", "Guinée", "Guinée-Bissau", "Guinée équatoriale",
            "Guyana", "Haïti", "Honduras", "Hongrie", "Inde", "Indonésie", "Irak", "Iran", "Irlande", "Islande",
            "Israël", "Italie", "Jamaïque", "Japon", "Jordanie", "Kazakhstan", "Kenya", "Kirghizistan", "Kiribati",
            "Koweït", "Laos", "Lesotho", "Lettonie", "Liban", "Liberia", "Libye", "Liechtenstein", "Lituanie",

            "Luxembourg", "Macédoine du Nord", "Madagascar", "Malaisie", "Malawi", "Maldives", "Mali", "Malte",
            "Maroc", "Marshall", "Maurice", "Mauritanie", "Mexique", "Micronésie", "Moldavie", "Monaco", "Mongolie",
            "Monténégro", "Mozambique", "Myanmar", "Namibie", "Nauru", "Népal", "Nicaragua", "Niger", "Nigeria",
            "Norvège", "Nouvelle-Zélande", "Oman", "Ouganda", "Ouzbékistan", "Pakistan", "Palaos", "Panama",
            "Papouasie-Nouvelle-Guinée", "Paraguay", "Pays-Bas", "Pérou", "Philippines", "Pologne", "Portugal",
            "Qatar", "République centrafricaine", "République dominicaine", "République tchèque", "Roumanie",
            "Royaume-Uni", "Russie", "Rwanda", "Saint-Kitts-et-Nevis", "Saint-Marin", "Saint-Vincent-et-les-Grenadines",
            "Sainte-Lucie", "Salomon", "Salvador", "Samoa", "Sao Tomé-et-Principe", "Sénégal", "Serbie", "Seychelles",
            "Sierra Leone", "Singapour", "Slovaquie", "Slovénie", "Somalie", "Soudan", "Soudan du Sud", "Sri Lanka",
            "Suède", "Suisse", "Suriname", "Syrie", "Tadjikistan", "Tanzanie", "Tchad", "Thaïlande", "Timor oriental",
            "Togo", "Tonga", "Trinité-et-Tobago", "Tunisie", "Turkménistan", "Turquie", "Tuvalu", "Ukraine", "Uruguay",
            "Vanuatu", "Vatican", "Venezuela", "Viêt Nam", "Yémen", "Zambie", "Zimbabwe"
        ];
    }
}