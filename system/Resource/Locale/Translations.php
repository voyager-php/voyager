<?php

namespace Voyager\Resource\Locale;

abstract class Translations
{

    /**
     * ISO 639-1 Codes
     */

    protected $languages = [

        'ab' => 'Abkhazian',

        'aa' => 'Afar',

        'af' => 'Afrikaans',

        'ak' => 'Akan',

        'sq' => 'Albanian',

        'am' => 'Amharic',

        'ar' => 'Arabic',

        'an' => 'Aragonese',

        'hy' => 'Armenian',

        'as' => 'Assamese',

        'av' => 'Avaric',

        'ae' => 'Avestan',

        'ay' => 'Aymara',

        'az' => 'Azerbaijani',

        'bm' => 'Bambara',

        'ba' => 'Bashkir',

        'eu' => 'Basque',

        'be' => 'Belarusian',

        'bn' => 'Bengali',

        'bh' => 'Bihari Languages',

        'bi' => 'Bislama',

        'bs' => 'Bosnian',

        'br' => 'Breton',

        'bg' => 'Bulgarian',

        'my' => 'Burmese',

        'ca' => 'Catalan',

        'ch' => 'Chamorro',

        'ce' => 'Chechen',

        'ny' => 'Chichewa',

        'zh' => 'Chinese',

        'cv' => 'Chuvash',

        'kw' => 'Cornish',

        'co' => 'Corsican',

        'cr' => 'Cree',

        'hr' => 'Croatian',

        'cs' => 'Czech',

        'da' => 'Danish',

        'dv' => 'Divehi',

        'nl' => 'Dutch',

        'dz' => 'Dzongkha',

        'en' => 'English',

        'eo' => 'Esperanto',

        'et' => 'Estonian',

        'ee' => 'Ewe',

        'fo' => 'Faroese',

        'fj' => 'Fijian',

        'fi' => 'Finnish',

        'fr' => 'French',

        'ff' => 'Fulah',

        'gl' => 'Galician',

        'ka' => 'Georgian',

        'de' => 'German',

        'el' => 'Greek',

        'gn' => 'Guarani',

        'gu' => 'Gujarati',

        'ht' => 'Haitian',

        'ha' => 'Hausa',

        'he' => 'Hebrew',

        'hz' => 'Herero',

        'hi' => 'Hindi',

        'ho' => 'Hiri Motu',

        'hu' => 'Hungarian',

        'ia' => 'Interlingua',

        'id' => 'Indonesian',

        'ie' => 'Interlingue',

        'ga' => 'Irish',

        'ig' => 'Igbo',

        'ik' => 'Inupiaq',

        'io' => 'Ido',

        'is' => 'Icelandic',

        'it' => 'Italian',

        'iu' => 'Inuktitut',

        'ja' => 'Japanese',

        'jv' => 'Javanese',

        'kl' => 'Kalaallisut',

        'kn' => 'Kannada',

        'kr' => 'Kanuri',

        'ks' => 'Kashmiri',

        'kk' => 'Kazakh',

        'km' => 'Central Khmer',

        'ki' => 'Kikuyu',

        'rw' => 'Kinyarwanda',

        'ky' => 'Kirghiz',

        'kv' => 'Komi',

        'kg' => 'Kongo',

        'ko' => 'Korean',

        'ku' => 'Kurdish',

        'kj' => 'Kuanyama',

        'la' => 'Latin',

        'lb' => 'Luxembourgish',

        'lg' => 'Ganda',

        'li' => 'Limburgan',

        'ln' => 'Lingala',

        'lo' => 'Lao',

        'lt' => 'Lithuanian',

        'lu' => 'Luba-Katanga',

        'lv' => 'Latvian',

        'gv' => 'Manx',

        'mk' => 'Macedonian',

        'mg' => 'Malagasy',

        'ms' => 'Malay',

        'ml' => 'Malayalam',

        'mt' => 'Maltese',

        'mi' => 'Maori',

        'mr' => 'Marathi',

        'mh' => 'Marshallese',

        'mn' => 'Mongolian',

        'na' => 'Nauru',

        'nv' => 'Navajo',

        'nd' => 'North Ndebele',

        'ne' => 'Nepali',

        'ng' => 'Ndonga',

        'nb' => 'Norwegian Bokmål',

        'nn' => 'Norwegian Nynorsk',

        'no' => 'Norwegian',

        'ii' => 'Sichuan Yi',

        'nr' => 'South Ndebele',

        'oc' => 'Occitan',

        'oj' => 'Ojibwa',

        'cu' => 'Church Slavic',

        'om' => 'Oromo',

        'or' => 'Oriya',

        'os' => 'Ossetian',

        'pa' => 'Punjabi',

        'pi' => 'Pali',

        'fa' => 'Persian',

        'pl' => 'Polish',

        'ps' => 'Pashto',

        'pt' => 'Portuguese',

        'qu' => 'Quechua',

        'rm' => 'Romanish',

        'rn' => 'Rundi',

        'ro' => 'Romanian',

        'ru' => 'Russian',

        'sa' => 'Sanskrit',

        'sc' => 'Sardinian',

        'sd' => 'Sindhi',

        'se' => 'Northerm Sami',

        'sm' => 'Samoan',

        'sg' => 'Sango',

        'sr' => 'Serbian',

        'gd' => 'Gaelic',

        'sn' => 'Shona',

        'si' => 'Sinhala',

        'sk' => 'Slovak',

        'sl' => 'Slovenian',

        'so' => 'Somali',

        'st' => 'Southern Sotho',

        'es' => 'Spanish',

        'su' => 'Sundanese',

        'sw' => 'Swahili',

        'ss' => 'Swati',

        'sv' => 'Swedish',

        'ta' => 'Tamil',

        'te' => 'Telugu',

        'tg' => 'Tajik',

        'th' => 'Thai',

        'ti' => 'Tigrinya',

        'bo' => 'Tibetan',

        'tk' => 'Turkmen',

        'tl' => 'Tagalog',

        'tn' => 'Tswana',

        'to' => 'Tonga',

        'tr' => 'Turkish',

        'ts' => 'Tsonga',

        'tt' => 'Tatar',

        'tw' => 'Twi',

        'ty' => 'Tahitian',

        'ug' => 'Uighur',

        'uk' => 'Ukranian',

        'ur' => 'Urdu',

        'uz' => 'Uzbek',

        've' => 'Venda',

        'vi' => 'Vietnamese',

        'vo' => 'Volapük',

        'wa' => 'Walloon',

        'cy' => 'Welsh',

        'wo' => 'Wolof',

        'fy' => 'Western Frisian',

        'xh' => 'Xhosa',

        'yi' => 'Yiddish',

        'yo' => 'Yoruba',

        'za' => 'Zhuang',

        'zu' => 'Zulu',

    ];

}