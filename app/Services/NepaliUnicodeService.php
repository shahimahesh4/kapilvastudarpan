<?php

namespace App\Services;

class NepaliUnicodeService
{
    /**
     * Preeti to Unicode Character Mapping.
     */
    protected const PREETI_TO_UNICODE = [
        'cf]' => 'ओ', 'cf}' => 'औ', 'cf' => 'आ', 'c+' => 'अं', 'cM' => 'अः', 'c' => 'अ',
        'O{' => 'ई', 'O' => 'इ', 'pm' => 'ऊ', 'p' => 'उ', 'P]' => 'ऐ', 'P' => 'ए', 'C' => 'ऋ',
        'f]' => 'ो', 'f}' => 'ौ', 'f' => 'ा', 'L' => 'ी', "'" => 'ु', '"' => 'ू',
        ']' => 'े', '}' => 'ै', 'F' => 'ँ', '+' => 'ं', 'M' => 'ः', '\\' => '्',
        '?' => 'रु', '¿' => 'रू',
        's' => 'क', 'v' => 'ख', 'u' => 'ग', '3' => 'घ', 'ª' => 'ङ',
        'r' => 'च', '5' => 'छ', 'h' => 'ज', 'em' => 'झ', '`' => 'ञ',
        '6' => 'ट', '7' => 'ठ', '8' => 'ड', '9' => 'ढ', '0f' => 'ण', '0' => 'ण्',
        't' => 'त', 'y' => 'थ', 'b' => 'द', 'w' => 'ध', 'g' => 'न',
        'k' => 'प', 'km' => 'फ', 'a' => 'ब', 'e' => 'भ', 'd' => 'म',
        'o' => 'य', '/' => 'र', 'n' => 'ल', 'j' => 'व',
        'z' => 'श', 'if' => 'ष', ';' => 'स', 'x' => 'ह',
        'qm' => 'क्र', 'If' => 'क्ष', 'q' => 'त्र', '1' => 'ज्ञ', '!' => 'ज्ञ',
        'S' => 'क्', 'V' => 'ख्', 'U' => 'ग्', '#' => 'घ्',
        'R' => 'च्', 'T' => 'त्', 'Y' => 'थ्', 'W' => 'ध्',
        'G' => 'न्', 'K' => 'प्', 'A' => 'ब्', 'E' => 'भ्',
        'D' => 'म्', 'N' => 'ल्', 'J' => 'व्', 'Z' => 'श्',
        'B' => 'द्य',
        ':f' => 'ष्', ':' => 'स्', 'H' => 'ह्',
        'ˆ' => 'फ्',
        '|' => '्र', '.' => '।',
        '¡' => 'द्म', '¢' => 'द्य', '£' => 'द्ध', '¤' => 'द्व', '¥' => 'झ',
        '§' => 'ट्ट', '¨' => 'ठ्ठ', '©' => 'ड्ड', '«' => '्र', '®' => '्र',
        '°' => 'त्त', '±' => 'द्द', '²' => 'द्ध', '³' => 'द्य', '´' => 'द्व', 'µ' => 'ह्य', '¶' => 'हृ',
        '·' => 'ह्ण', '¸' => 'ह्र', '¹' => 'ह्म', 'º' => 'ह्क', '»' => 'ह्ल', '¼' => 'ल्ल',
        '½' => 'ष्ट्र', '¾' => 'ष्ट', 'À' => 'ह्न', 'Á' => 'ह्व',
        'Ã' => 'ङ्क', 'Ä' => 'ङ्ख', 'Å' => 'ङ्ग', 'Æ' => 'ङ्घ', 'Ç' => 'च्च', 'È' => 'च्छ',
        'É' => 'ज्ज', 'Ê' => 'ज्झ', 'Ë' => 'ट्ट', 'Ì' => 'ट्ठा', 'Í' => 'ड्ड', 'Î' => 'ड्ढ',
        'Ï' => 'ण', 'Ð' => 'त्क', 'Ñ' => 'त्त', 'Ò' => 'त्थ', 'Ó' => 'त्न', 'Ô' => 'त्प',
        'Õ' => 'त्फ', 'Ö' => 'त्म', '×' => 'त्य', 'Ø' => 'त्र', 'Ù' => 'त्व', 'Ú' => 'त्स',
        'Û' => 'थ्य', 'Ü' => 'थ्व', 'Ý' => 'द्ग', 'Þ' => 'द्घ', 'ß' => 'द्द', 'à' => 'द्धा',
        'á' => 'द्न', 'â' => 'द्ब', 'ã' => 'द्भ', 'ä' => 'द्म', 'å' => 'द्य', 'æ' => 'द्र',
        'ç' => 'द्व', 'è' => 'ध्न', 'é' => 'ध्म', 'ê' => 'ध्य', 'ë' => 'ध्व', 'ì' => 'न्क',
        'í' => 'न्त', 'î' => 'न्थ', 'ï' => 'न्द', 'ð' => 'न्ध', 'ñ' => 'न्न', 'ò' => 'न्म',
        'ó' => 'न्य', 'ô' => 'न्व', 'õ' => 'न्स', 'ö' => 'न्ह', '÷' => 'प्त', 'ø' => 'प्न',
        'ù' => 'प्स', 'ú' => 'बब', 'û' => 'ब्ध', 'ü' => 'ब्न', 'ý' => 'ब्य', 'þ' => 'भ्य',
        'ÿ' => 'भ्व',
    ];

    /**
     * Unicode to Preeti Mapping.
     */
    protected const UNICODE_TO_PREETI = [
        'ओ' => 'cf]', 'औ' => 'cf}', 'आ' => 'cf', 'अं' => 'c+', 'अः' => 'cM', 'अ' => 'c',
        'ई' => 'O{', 'इ' => 'O', 'ऊ' => 'pm', 'उ' => 'p', 'ऐ' => 'P]', 'ए' => 'P', 'ऋ' => 'C',
        'ो' => 'f]', 'ौ' => 'f}', 'ा' => 'f', 'ी' => 'L', 'ु' => "'", 'ू' => '"',
        'े' => ']', 'ै' => '}', 'ँ' => 'F', 'ं' => '+', 'ः' => 'M', '्' => '\\',
        'रु' => '?', 'रू' => '¿',
        'क्र' => 'qm', 'क्ष' => 'If', 'त्र' => 'q', 'ज्ञ' => '1', 'श्र' => 'z|',
        'क' => 's', 'ख' => 'v', 'ग' => 'u', 'घ' => '3', 'ङ' => 'ª',
        'च' => 'r', 'छ' => '5', 'ज' => 'h', 'झ' => 'em', 'ञ' => '`',
        'ट' => '6', 'ठ' => '7', 'ड' => '8', 'ढ' => '9', 'ण' => '0f',
        'त' => 't', 'थ' => 'y', 'द' => 'b', 'ध' => 'w', 'न' => 'g',
        'प' => 'k', 'फ' => 'km', 'ब' => 'a', 'भ' => 'e', 'म' => 'd',
        'य' => 'o', 'र' => '/', 'ल' => 'n', 'व' => 'j',
        'श' => 'z', 'ष' => 'if', 'स' => ';', 'ह' => 'x',
        'क्' => 'S', 'ख्' => 'V', 'ग्' => 'U', 'घ्' => '#',
        'च्' => 'R', 'त्' => 'T', 'थ्' => 'Y', 'ध्' => 'W',
        'न्' => 'G', 'प्' => 'K', 'ब्' => 'A', 'भ्' => 'E',
        'म्' => 'D', 'ल्' => 'N', 'व्' => 'J', 'श्' => 'Z',
        'ष्' => ':f', 'स्' => ':', 'ह्' => 'H',
        'द्य' => 'B', 'द्म' => '¡', 'द्द' => '±', 'द्ध' => '£', 'द्व' => '¤',
        '०' => ')', '१' => '!', '२' => '@', '३' => '#', '४' => '$',
        '५' => '%', '६' => '^', '७' => '&', '८' => '*', '९' => '(',
        '।' => '.',
    ];

    /**
     * Direct Romanized Words.
     */
    protected const DIRECT_WORDS = [
        'namaste' => 'नमस्ते',
        'namaskar' => 'नमस्कार',
        'nepal' => 'नेपाल',
        'nepali' => 'नेपाली',
        'nepalko' => 'नेपालको',
        'dhanyabad' => 'धन्यवाद',
        'dhanyabaad' => 'धन्यवाद',
        'swagatam' => 'स्वागतम्',
        'kapilvastu' => 'कपिलवस्तु',
        'darpan' => 'दर्पण',
        'tapai' => 'तपाईं',
        'tapaiko' => 'तपाईंको',
        'tapailai' => 'तपाईंलाई',
        'mero' => 'मेरो',
        'hamro' => 'हाम्रो',
        'timro' => 'तिम्रो',
        'timi' => 'तिमी',
        'timilai' => 'तिमीलाई',
        'uha' => 'उहाँ',
        'uhako' => 'उहाँको',
        'aaja' => 'आज',
        'aajako' => 'आजको',
        'bholi' => 'भोलि',
        'hijo' => 'हिजो',
        'pani' => 'पनि',
        'paani' => 'पानी',
        'kaam' => 'काम',
        'kam' => 'कम',
        'bhaat' => 'भात',
        'kitab' => 'किताब',
        'kitaab' => 'किताब',
        'sathi' => 'साथी',
        'saathi' => 'साथी',
        'sathibhai' => 'साथीभाइ',
        'manche' => 'मान्छे',
        'manchhe' => 'मान्छे',
        'manis' => 'मानिस',
        'desh' => 'देश',
        'deshko' => 'देशको',
        'ghar' => 'घर',
        'gharma' => 'घरमा',
        'samachar' => 'समाचार',
        'khabar' => 'खबर',
        'kathmandu' => 'काठमाडौँ',
        'pokhara' => 'पोखरा',
        'lumbini' => 'लुम्बिनी',
        'taulihawa' => 'तौलिहवा',
        'butwal' => 'बुटवल',
        'biratnagar' => 'विराटनगर',
        'chitwan' => 'चितवन',
        'nepalgunj' => 'नेपालगन्ज',
        'dhangadhi' => 'धनगढी',
        'rastrapati' => 'राष्ट्रपति',
        'pradhanmantri' => 'प्रधानमन्त्री',
        'sarwochha' => 'सर्वोच्च',
        'adalat' => 'अदालत',
        'sambidhan' => 'संविधान',
        'sansad' => 'संसद्',
        'sarkar' => 'सरकार',
        'mantralaya' => 'मन्त्रालय',
        'rajniti' => 'राजनीति',
        'artha' => 'अर्थ',
        'bajar' => 'बजार',
        'khelkud' => 'खेलकुद',
        'bichar' => 'विचार',
        'samaj' => 'समाज',
        'swasthya' => 'स्वास्थ्य',
        'shiksha' => 'शिक्षा',
        'paryatan' => 'पर्यटन',
        'sahitya' => 'साहित्य',
        'shanti' => 'शान्ति',
        'prem' => 'प्रेम',
        'subha' => 'शुभ',
        'bihan' => 'बिहान',
        'saajh' => 'साँझ',
        'raat' => 'रात',
        'ramro' => 'राम्रो',
        'naramro' => 'नराम्रो',
        'thik' => 'ठीक',
        'mitho' => 'मीठो',
        'kasto' => 'कस्तो',
        'kina' => 'किन',
        'kaha' => 'कहाँ',
        'kasari' => 'कसरी',
        'kati' => 'कति',
        'ko' => 'को',
        'ke' => 'के',
        'baba' => 'बाबा',
        'aama' => 'आमा',
        'bhai' => 'भाइ',
        'didi' => 'दिदी',
        'bahini' => 'बहिनी',
        'daju' => 'दाजु',
        'chhora' => 'छोरा',
        'chhori' => 'छोरी',
        'janata' => 'जनता',
        'bikas' => 'विकास',
        'vikas' => 'विकास',
    ];

    /**
     * Convert Preeti (legacy font) text to Devanagari Unicode.
     */
    public function preetiToUnicode(string $text): string
    {
        if (trim($text) === '') {
            return $text;
        }

        $s = $text;

        // Rakar combinations
        $rakarMap = [
            's|' => 'क्र', 'v|' => 'ख्र', 'u|' => 'ग्र', '3|' => 'घ्र', 'r|' => 'च्र',
            '5|' => 'छ्र', 'h|' => 'ज्र', 'em|' => 'झ्र', 't|' => 'त्र', 'y|' => 'थ्र',
            'b|' => 'द्र', 'w|' => 'ध्र', 'g|' => 'न्र', 'k|' => 'प्र', 'km|' => 'फ्र',
            'a|' => 'ब्र', 'e|' => 'भ्र', 'd|' => 'म्र', 'o|' => 'य्र', 'n|' => 'ल्र',
            'j|' => 'व्र', 'z|' => 'श्र', 'if|' => 'ष्र', ';|' => 'स्र', 'x|' => 'ह्र',
        ];
        foreach ($rakarMap as $k => $v) {
            $s = str_replace($k, $v, $s);
        }

        // Half and full chars regex patterns
        $halfChars = '([SVU#RTYWGKAEFDNMZOJZH]|:[f]?|\\\\)';
        $fullChars = '(km|em|if|qm|If|cf\\]|cf\\}|cf|c\\+|cM|c|O\\{|O|pm|p|P\\]|P|C|\\?|¿|[svu3ªr5h`67890tybwgkaedo/njz;xq1!±²³´µ¶·¸¹º»¼½¾ÀÁÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ¡¢£¤¥§¨©])';

        // 1. Move 'l' (chhoti i matra) after consonant cluster
        $s = preg_replace('~l((' . $halfChars . ')*' . $fullChars . ')~u', '$1l', $s);

        // 2. Move '{' (reph) before consonant cluster
        $s = preg_replace('~((' . $halfChars . ')*' . $fullChars . '[f\'"\\\\\\]\\}FL\\+FM\\?\\¿]*)\\{~u', 'र्$1', $s);

        // 3. Multi-character replacements in order of descending key length
        $map = self::PREETI_TO_UNICODE;
        uksort($map, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($map as $k => $v) {
            $s = str_replace($k, $v, $s);
        }

        // Replace 'l' which has been moved after the consonant with Unicode 'ि'
        $s = str_replace('l', 'ि', $s);

        return $s;
    }

    /**
     * Convert Devanagari Unicode text to Preeti font format.
     */
    public function unicodeToPreeti(string $text): string
    {
        if (trim($text) === '') {
            return $text;
        }

        $s = $text;

        // 1. Handle Reph: र् followed by consonant and optional matras -> consonant + {
        $s = preg_replace('/र्([क-ह](्[क-ह])*[ािीुूेैोौँंः]?)/u', '$1{', $s);

        // 2. Handle 'ि' (chhoti i matra): move to front as 'l'
        $s = preg_replace('/(([क-ह]्)*[क-ह])ि/u', 'l$1', $s);

        // 3. Replace based on dictionary sorted by key length descending
        $map = self::UNICODE_TO_PREETI;
        uksort($map, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($map as $k => $v) {
            $s = str_replace($k, $v, $s);
        }

        return $s;
    }

    /**
     * Convert Romanized Nepali text to Devanagari Unicode.
     */
    public function romanizedToUnicode(string $text): string
    {
        if (trim($text) === '') {
            return $text;
        }

        $tokens = preg_split('/(\s+|[.,!?:;()\[\]{}"\'])/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $result = [];

        foreach ($tokens as $token) {
            $lower = strtolower($token);
            if (isset(self::DIRECT_WORDS[$lower])) {
                $result[] = self::DIRECT_WORDS[$lower];
            } else {
                $result[] = $this->convertRomanWord($token);
            }
        }

        return implode('', $result);
    }

    /**
     * Convert an individual romanized word.
     */
    protected function convertRomanWord(string $w): string
    {
        if ($w === '' || ! preg_match('/[a-zA-Z]/', $w)) {
            return $w;
        }

        $consonants = [
            'ksha' => 'क्ष्', 'ksh' => 'क्ष्', 'x' => 'क्ष्',
            'shra' => 'श्र्', 'shr' => 'श्र्',
            'gnya' => 'ज्ञ्', 'gny' => 'ज्ञ्', 'gya' => 'ज्ञ्', 'gy' => 'ज्ञ्',
            'chha' => 'छ्', 'chh' => 'छ्', 'Ch' => 'छ्',
            'tra' => 'त्र्', 'tr' => 'त्र्',
            'kh' => 'ख्',
            'gh' => 'घ्',
            'ng' => 'ङ्',
            'ch' => 'च्',
            'jh' => 'झ्',
            'yn' => 'ञ्', 'ny' => 'ञ्',
            'Th' => 'ठ्',
            'Dh' => 'ढ्',
            'th' => 'थ्',
            'dh' => 'ध्',
            'ph' => 'फ्',
            'bh' => 'भ्',
            'sh' => 'श्',
            'Sh' => 'ष्',
            'k' => 'क्', 'g' => 'ग्', 'j' => 'ज्', 'T' => 'ट्', 'D' => 'ड्', 'N' => 'ण्',
            't' => 'त्', 'd' => 'द्', 'n' => 'न्', 'p' => 'प्', 'f' => 'फ्', 'b' => 'ब्',
            'm' => 'म्', 'y' => 'य्', 'r' => 'र्', 'l' => 'ल्', 'w' => 'व्', 'v' => 'व्',
            's' => 'स्', 'h' => 'ह्', 'c' => 'च्',
        ];

        $vowelMatras = [
            'aa' => 'ा', 'A' => 'ा',
            'ai' => 'ै', 'au' => 'ौ', 'ee' => 'ी', 'oo' => 'ू',
            'ri' => 'ृ', 'R' => 'ृ',
            'i' => 'ि', 'I' => 'ी', 'u' => 'ु', 'U' => 'ू',
            'e' => 'े', 'E' => 'ै', 'o' => 'ो', 'O' => 'ौ',
            'a' => '',
        ];

        $initialVowels = [
            'aa' => 'आ', 'A' => 'आ',
            'ai' => 'ऐ', 'au' => 'औ', 'ee' => 'ई', 'oo' => 'ऊ',
            'ri' => 'ऋ', 'R' => 'ऋ',
            'a' => 'अ', 'i' => 'इ', 'I' => 'ई', 'u' => 'उ', 'U' => 'ऊ',
            'e' => 'ए', 'E' => 'ऐ', 'o' => 'ओ', 'O' => 'औ',
        ];

        $out = '';
        $i = 0;
        $len = strlen($w);

        while ($i < $len) {
            $lastChar = mb_substr($out, -1, 1);
            $isInitial = ($i === 0 || str_ends_with($out, ' ') || preg_match('/[अ-औ]/u', $lastChar));

            if ($isInitial) {
                $matchedV = false;
                foreach ($initialVowels as $pat => $rep) {
                    $pLen = strlen($pat);
                    if (substr($w, $i, $pLen) === $pat) {
                        $out .= $rep;
                        $i += $pLen;
                        $matchedV = true;
                        break;
                    }
                }
                if ($matchedV) {
                    continue;
                }
            }

            $matchedC = false;
            foreach ($consonants as $pat => $halantaC) {
                $pLen = strlen($pat);
                if (substr($w, $i, $pLen) === $pat) {
                    $i += $pLen;
                    $matchedC = true;

                    $matchedM = false;
                    foreach ($vowelMatras as $vPat => $vMat) {
                        $vLen = strlen($vPat);
                        if (substr($w, $i, $vLen) === $vPat) {
                            $baseChar = mb_substr($halantaC, 0, -1);
                            $out .= $baseChar . $vMat;
                            $i += $vLen;
                            $matchedM = true;
                            break;
                        }
                    }

                    if (! $matchedM) {
                        if ($i < $len && $w[$i] === 'M') {
                            $baseChar = mb_substr($halantaC, 0, -1);
                            $out .= $baseChar . 'ं';
                            $i++;
                        } elseif ($i >= $len && ! str_ends_with($w, '_')) {
                            $out .= mb_substr($halantaC, 0, -1);
                        } else {
                            $out .= $halantaC;
                        }
                    }
                    break;
                }
            }

            if (! $matchedC) {
                $matchedV = false;
                foreach ($initialVowels as $pat => $rep) {
                    $pLen = strlen($pat);
                    if (substr($w, $i, $pLen) === $pat) {
                        $out .= $rep;
                        $i += $pLen;
                        $matchedV = true;
                        break;
                    }
                }

                if (! $matchedV) {
                    $digits = ['0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४', '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९'];
                    $ch = $w[$i];
                    $out .= $digits[$ch] ?? $ch;
                    $i++;
                }
            }
        }

        return $out;
    }
}
