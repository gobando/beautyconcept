<?php
/*
 * File name: helpers.php
 * Last modified: 2022.02.12 at 19:17:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use InfyOm\Generator\Common\GeneratorField;
use InfyOm\Generator\Utils\GeneratorFieldsInputUtil;
use InfyOm\Generator\Utils\HTMLFieldGenerator;
use Symfony\Component\ErrorHandler\Error\FatalError;

/**
 * @param $bytes
 * @param int $precision
 * @return string
 */
function formatedSize($bytes, $precision = 1)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getMediaColumn($mediaModel, $mediaCollectionName = '', $optionClass = '', $mediaThumbnail = 'icon')
{
    $optionClass = $optionClass == '' ? ' rounded ' : $optionClass;

    if ($mediaModel->hasMedia($mediaCollectionName)) {
        return "<img class='" . $optionClass . "' style='height:50px' src='" . $mediaModel->getFirstMediaUrl($mediaCollectionName, $mediaThumbnail) . "' alt='" . $mediaModel->getFirstMedia($mediaCollectionName)->name . "'>";
    } else {
        return "<img class='" . $optionClass . "' style='height:50px' src='" . asset('images/image_default.png') . "' alt='image_default'>";
    }
}

/**
 * @param $modelObject
 * @param string $attributeName
 * @return null|string|string[]
 */
function getDateColumn($modelObject, $attributeName = 'updated_at')
{
    if (setting('is_human_date_format', false)) {
        $html = '<span data-toggle="tooltip" data-placement="left" title="${date}">${dateHuman}</span>';
    } else {
        $html = '<span data-toggle="tooltip" data-placement="left" title="${dateHuman}">${date}</span>';
    }
    if (!isset($modelObject[$attributeName])) {
        return '';
    }
    $dateObj = new Carbon($modelObject[$attributeName]);
    $replace = preg_replace('/\$\{date\}/', $dateObj->format(setting('date_format', 'l jS F Y (h:i:s)')), $html);
    $replace = preg_replace('/\$\{dateHuman\}/', $dateObj->diffForHumans(), $replace);
    return $replace;
}

/**
 * @param $modelObject
 * @param string $attributeName
 * @param Currency $currency
 * @return string
 */
function getPriceColumn($modelObject, string $attributeName = 'price', Currency $currency = null): string
{
    if ($currency == null) {
        $currency = new Currency();
        $currency->symbol = setting('default_currency');
        $currency->decimal_digits = setting('default_currency_decimal_digits', 2);
    }
    if ($modelObject[$attributeName] != null && strlen($modelObject[$attributeName]) > 0) {
        $modelObject[$attributeName] = number_format((float)$modelObject[$attributeName], $currency->decimal_digits, '.', '');
        if (setting('currency_right', false) != false) {
            return $modelObject[$attributeName] . "<span>" . $currency->symbol . "</span>";
        } else {
            return "<span>" . $currency->symbol . "</span>" . $modelObject[$attributeName];
        }
    }
    return '-';
}

/**
 * @param float $price
 * @return string
 */
function getPrice($price = 0): string
{
    if (setting('currency_right', false) != false) {
        return number_format((float)$price, setting('default_currency_decimal_digits', 2), '.', '') . "<span>" . setting('default_currency') . "</span>";
    } else {
        return "<span>" . setting('default_currency') . "</span>" . number_format((float)$price, 2, '.', ' ');
    }
}

/**
 * generate boolean column for datatable
 * @param $column
 * @return string
 */
function getBooleanColumn($column, $attributeName, $trueText = null, $falseText = null): string
{
    if (!isset($trueText)) {
        $trueText = trans('lang.yes');
    }
    if (!isset($falseText)) {
        $falseText = trans('lang.no');
    }
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-success'>" . $trueText . "</span>";
        } else {
            return "<span class='badge badge-danger'>" . $falseText . "</span>";
        }
    }
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-success'>" . trans('lang.yes') . "</span>";
        } else {
            return "<span class='badge badge-danger'>" . trans('lang.no') . "</span>";
        }
    }
}

/**
 * generate not boolean column for datatable
 * @param $column
 * @return string
 */
function getNotBooleanColumn($column, $attributeName, $trueText = null, $falseText = null): string
{
    if (!isset($trueText)) {
        $trueText = trans('lang.yes');
    }
    if (!isset($falseText)) {
        $falseText = trans('lang.no');
    }
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-danger'>" . $trueText . "</span>";
        } else {
            return "<span class='badge badge-success'>" . $falseText . "</span>";
        }
    }
    return "";
}

/**
 * generate color column for datatable
 * @param $column
 * @return string
 */
function getColorColumn($column, $attributeName)
{
    if (isset($column)) {
        return "<span class='badge badge-default p-2' style='background-color:$column[$attributeName]'>" . $column[$attributeName] . "</span>";
    }
    return "";
}

/**
 * generate striped html column for datatable
 * @param $column
 * @return string
 */
function getStripedHtmlColumn($column, $attributeName, $limit = 40)
{
    if (isset($column)) {
        if ($limit == 0) {
            return strip_tags($column[$attributeName]);
        }
        return Str::limit(strip_tags($column[$attributeName]), $limit);
    }
    return "";
}


/**
 * generate order payment column for datatable
 * @param $column
 * @return string
 */
function getPayment($column, $attributeName)
{
    if (isset($column) && $column[$attributeName]) {
        return "<span class='badge badge-success'>" . $column[$attributeName] . "</span> ";
    } else {
        return "<span class='badge badge-danger'>" . trans('lang.order_not_paid') . "</span>";
    }
}

/**
 * @param array $array
 * @param $baseUrl
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumn($array = [], $baseUrl = '', $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $result[] = $replace;
    }
    return implode(', ', $result);
}

/**
 * @param array $array
 * @param $routeName
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumnByRouteName($array = [], $routeName = '', $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        if (!empty($link)) {
            $replace = preg_replace('/\$\{href\}/', route($routeName, $link[$idAttribute]), $html);
            $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
            $result[] = $replace;
        }
    }
    return implode(', ', $result);
}

function getArrayColumn($array = [], $titleAttribute = 'title', $optionClass = '', $separator = ', ')
{
    $result = [];
    foreach ($array as $link) {
        $title = $link[$titleAttribute];
//        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
//        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $html = "<span class='{$optionClass}'>{$title}</span>";
        $result[] = $html;
    }
    return implode($separator, $result);
}

function getEmailColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<a class='btn btn-outline-secondary btn-sm' href='mailto:" . $column[$attributeName] . "'><i class='fa fa-envelope mr-1'></i>" . $column[$attributeName] . "</a>";
        } else {
            return '';
        }
    }
}

/**
 * get available languages on the application
 */
function getAvailableLanguages()
{
    $dir = base_path('resources/lang');
    $languages = array_diff(scandir($dir), array('..', '.'));
    $languages = array_map(function ($value) {
        return ['id' => $value, 'value' => trans('lang.app_setting_' . $value)];
    }, $languages);

    return array_column($languages, 'value', 'id');
}

/**
 * get all languages
 */

function getLanguages()
{

    return array(
        'aa' => 'Afar',
        'ab' => 'Abkhaz',
        'ae' => 'Avestan',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'am' => 'Amharic',
        'an' => 'Aragonese',
        'ar' => 'Arabic',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'be' => 'Belarusian',
        'bg' => 'Bulgarian',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bm' => 'Bambara',
        'bn' => 'Bengali',
        'bo' => 'Tibetan Standard, Tibetan, Central',
        'br' => 'Breton',
        'bs' => 'Bosnian',
        'ca' => 'Catalan; Valencian',
        'ce' => 'Chechen',
        'ch' => 'Chamorro',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'cs' => 'Czech',
        'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
        'cv' => 'Chuvash',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'dv' => 'Divehi; Dhivehi; Maldivian;',
        'dz' => 'Dzongkha',
        'ee' => 'Ewe',
        'el' => 'Greek, Modern',
        'en' => 'English',
        'eo' => 'Esperanto',
        'es' => 'Spanish; Castilian',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'ff' => 'Fula; Fulah; Pulaar; Pular',
        'fi' => 'Finnish',
        'fj' => 'Fijian',
        'fo' => 'Faroese',
        'fr' => 'French',
        'fy' => 'Western Frisian',
        'ga' => 'Irish',
        'gd' => 'Scottish Gaelic; Gaelic',
        'gl' => 'Galician',
        'gn' => 'GuaranÃƒÂ­',
        'gu' => 'Gujarati',
        'gv' => 'Manx',
        'ha' => 'Hausa',
        'he' => 'Hebrew (modern)',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hr' => 'Croatian',
        'ht' => 'Haitian; Haitian Creole',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'hz' => 'Herero',
        'ia' => 'Interlingua',
        'id' => 'Indonesian',
        'ie' => 'Interlingue',
        'ig' => 'Igbo',
        'ii' => 'Nuosu',
        'ik' => 'Inupiaq',
        'io' => 'Ido',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'iu' => 'Inuktitut',
        'ja' => 'Japanese (ja)',
        'jv' => 'Javanese (jv)',
        'ka' => 'Georgian',
        'kg' => 'Kongo',
        'ki' => 'Kikuyu, Gikuyu',
        'kj' => 'Kwanyama, Kuanyama',
        'kk' => 'Kazakh',
        'kl' => 'Kalaallisut, Greenlandic',
        'km' => 'Khmer',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'ku' => 'Kurdish',
        'kv' => 'Komi',
        'kw' => 'Cornish',
        'ky' => 'Kirghiz, Kyrgyz',
        'la' => 'Latin',
        'lb' => 'Luxembourgish, Letzeburgesch',
        'lg' => 'Luganda',
        'li' => 'Limburgish, Limburgan, Limburger',
        'ln' => 'Lingala',
        'lo' => 'Lao',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'lv' => 'Latvian',
        'mg' => 'Malagasy',
        'mh' => 'Marshallese',
        'mi' => 'Maori',
        'mk' => 'Macedonian',
        'ml' => 'Malayalam',
        'mn' => 'Mongolian',
        'mr' => 'Marathi (Mara?hi)',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'my' => 'Burmese',
        'na' => 'Nauru',
        'nb' => 'Norwegian BokmÃƒÂ¥l',
        'nd' => 'North Ndebele',
        'ne' => 'Nepali',
        'ng' => 'Ndonga',
        'nl' => 'Dutch',
        'nn' => 'Norwegian Nynorsk',
        'no' => 'Norwegian',
        'nr' => 'South Ndebele',
        'nv' => 'Navajo, Navaho',
        'ny' => 'Chichewa; Chewa; Nyanja',
        'oc' => 'Occitan',
        'oj' => 'Ojibwe, Ojibwa',
        'om' => 'Oromo',
        'or' => 'Oriya',
        'os' => 'Ossetian, Ossetic',
        'pa' => 'Panjabi, Punjabi',
        'pi' => 'Pali',
        'pl' => 'Polish',
        'ps' => 'Pashto, Pushto',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'rn' => 'Kirundi',
        'ro' => 'Romanian, Moldavian, Moldovan',
        'ru' => 'Russian',
        'rw' => 'Kinyarwanda',
        'sa' => 'Sanskrit (Sa?sk?ta)',
        'sc' => 'Sardinian',
        'sd' => 'Sindhi',
        'se' => 'Northern Sami',
        'sg' => 'Sango',
        'si' => 'Sinhala, Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovene',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'so' => 'Somali',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'ss' => 'Swati',
        'st' => 'Southern Sotho',
        'su' => 'Sundanese',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'ti' => 'Tigrinya',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tn' => 'Tswana',
        'to' => 'Tonga (Tonga Islands)',
        'tr' => 'Turkish',
        'ts' => 'Tsonga',
        'tt' => 'Tatar',
        'tw' => 'Twi',
        'ty' => 'Tahitian',
        'ug' => 'Uighur, Uyghur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'VolapÃƒÂ¼k',
        'wa' => 'Walloon',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang, Chuang',
        'zh' => 'Chinese',
        'zu' => 'Zulu',
    );

}

/**
 * get all country codes
 */

function getCountries()
{
    return [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];
}

function generateCustomField($fields, $fieldsValues = null)
{
    $htmlFields = [];
    $startSeparator = '<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">';
    $endSeparator = '</div>';
    foreach ($fields as $field) {
        $dynamicVars = [
            '$RANDOM_VARIABLE$' => 'var' . time() . rand() . 'ble',
            '$FIELD_NAME$' => $field->name,
            '$DISABLED$' => $field->disabled === true ? '"disabled" => "disabled",' : '',
            '$REQUIRED$' => $field->required === true ? '"required" => "required",' : '',
            '$MODEL_NAME_SNAKE$' => getOnlyClassName($field->custom_field_model),
            '$FIELD_VALUE$' => 'null',
            '$INPUT_ARR_SELECTED$' => '[]',

        ];
        $gf = new GeneratorField();
        if ($fieldsValues) {
            foreach ($fieldsValues as $value) {
                if ($field->id === $value->customField->id) {
                    $dynamicVars['$INPUT_ARR_SELECTED$'] = $value->value ? $value->value : '[]';
                    $dynamicVars['$FIELD_VALUE$'] = '\'' . addslashes($value->value) . '\'';
                    $gf->validations[] = $value->value;
                    continue;
                }
            }
        }
        // dd($gf->validations);
        $gf->htmlType = $field['type'];
        $gf->htmlValues = $field['values'];
        $gf->dbInput = '';
        if ($field['type'] === 'selects') {
            $gf->htmlType = 'select';
            $gf->dbInput = 'hidden,mtm';
        }
        $fieldTemplate = HTMLFieldGenerator::generateCustomFieldHTML($gf, config('infyom.laravel_generator.templates', 'adminlte-templates'));


        if (!empty($fieldTemplate)) {
            foreach ($dynamicVars as $variable => $value) {
                $fieldTemplate = str_replace($variable, $value, $fieldTemplate);
            }
            $htmlFields[] = $fieldTemplate;
        }
//    dd($fieldTemplate);
    }
    foreach ($htmlFields as $index => $field) {
        if (round(count($htmlFields) / 2) == $index + 1) {
            $htmlFields[$index] = $htmlFields[$index] . "\n" . $endSeparator . "\n" . $startSeparator;
        }
    }
    $htmlFieldsString = implode("\n\n", $htmlFields);
    $htmlFieldsString = $startSeparator . "\n" . $htmlFieldsString . "\n" . $endSeparator;
//    dd($htmlFieldsString);
    $renderedHtml = "";
    try {
        $renderedHtml = render(Blade::compileString($htmlFieldsString));
//        dd($renderedHtml);
    } catch (FatalError $e) {
    }
    return $renderedHtml;
}

/**
 * render php code in string give with compiling data
 *
 * @param $__php
 * @param null $__data
 * @return string
 * @throws FatalError
 */
function render($__php, $__data = null)
{
    $obLevel = ob_get_level();
    ob_start();
    if ($__data) {
        optionct($__data, EXTR_SKIP);
    }
    try {
        eval('?' . '>' . $__php);
    } catch (Exception $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw $e;
    } catch (Throwable $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw new FatalError($e->getMessage(), 500, []);
    }
    return ob_get_clean();
}

/**
 * get custom field value from custom fields collection given
 * @param null $customFields
 * @param $request
 * @return array
 */
function getCustomFieldsValues($customFields = null, $request = null)
{

    if (!$customFields) {
        return [];
    }
    if (!$request) {
        return [];
    }
    $customFieldsValues = [];
    foreach ($customFields as $cf) {
        $value = $request->input($cf->name);
        $view = $value;
        $fieldType = $cf->type;
        if ($fieldType === 'selects') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_filter($view, function ($v) use ($value) {
                return in_array($v, $value);
            });
            $view = implode(', ', array_flip($view));
            $value = json_encode($value);
        } elseif ($fieldType === 'select' || $fieldType === 'radio') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_flip($view)[$value];
        } elseif ($fieldType === 'boolean') {
            $view = getBooleanColumn(['0' => $view], '0');

        } elseif ($fieldType === 'password') {
            $view = str_repeat('•', strlen($value));
            $value = bcrypt($value);
        } elseif ($fieldType === 'date') {
            $view = getDateColumn(['date' => $view], 'date');
        } elseif ($fieldType === 'email') {
            $view = getEmailColumn(['email' => $view], 'email');
        } elseif ($fieldType === 'textarea') {
            $view = strip_tags($view);
        }


        $customFieldsValues[] = [
            'custom_field_id' => $cf->id,
            'value' => $value,
            'view' => $view
        ];
    }

    return $customFieldsValues;
}


/**
 * convert an array to assoc array using one attribute in the array
 * 0 => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 *
 * to
 *
 * The_Name => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 */
function convertToAssoc($collection, $key)
{
    $newCollection = [];
    foreach ($collection as $c) {
        $newCollection[$c[$key]] = $c;
    }
    return $newCollection;
}

/**
 * Get class name by giving the full name of th class
 * Ex:
 * $fullClassName = App\Models\UserModel
 * $isSnake = true
 * return
 * user_model
 * $fullClassName = App\Models\UserModel
 * $isSnake = false
 * return
 * UserModel
 * @param $fullClassName
 * @param bool $isSnake
 * @return mixed|string
 */
function getOnlyClassName($fullClassName, $isSnake = true)
{
    $modelNames = preg_split('/\\\\/', $fullClassName);
    if ($isSnake) {
        return Str::snake(end($modelNames));
    }
    return end($modelNames);

}

function getModelsClasses(string $dir, array $excepts = null)
{
    if ($excepts === null) {
        $excepts = [
            'App\Models\Upload',
            'App\Models\CustomField',
            'App\Models\Media',
            'App\Models\CustomFieldValue',
        ];
    }
    $customFieldModels = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $customFieldModels[$value] = getModelsClasses($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $fullClassName = "App\\Models\\" . basename($value, '.php');
                if (!in_array($fullClassName, $excepts)) {
                    $customFieldModels[$fullClassName] = trans('lang.' . Str::snake(basename($value, '.php')) . '_plural');
                }

            }
        }
    }
    return $customFieldModels;
}

function getNeededArray($delimiter = '|', $string = '', $input = '')
{
    $array = explode($delimiter, $string, 2);
    if (count($array) === 1) {
        return [$array[0] => $input];
    } else {
        return [$array[0] => getNeededArray($delimiter, $array[1], $input)];
    }
}

/**
 * get Days array with keys and translated values
 * @return array
 */
function getDaysArray()
{
    $daysValues = array_values(Carbon::getDays());
    $daysKeys = array_map(function ($element) {
        return Str::lower($element);
    }, $daysValues);

    $daysValuesTranslated = array_map(function ($element) {
        return translateDay($element);
    }, $daysKeys);

    return array_combine($daysKeys, $daysValuesTranslated);
}

function convertDateTime(DateTime $date, string $format = null, string $timezone = null): string
{
    $date = Carbon::createFromFormat($format != null ? $format : 'Y-m-d H:i:s', $date, $date->getTimezone());
    $date = $date->setTimezone($timezone != null ? $timezone : setting('timezone'));
    return $date->toAtomString();
}

/**
 * @param string $element
 * @return string
 */
function translateDay(string $element): string
{
    return Str::title(Carbon::createFromIsoFormat('dddd', $element)->isoFormat('dddd'));
}

function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
