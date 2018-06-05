<?php
function fnIsNull($data) {
    return (@trim($data) === "" or $data === null or ! isset($data) or ! count($data) ) ? true : false;
}

// your rules here //// if value is zero then return true  ;// Dmpatel
function fnIsNotNull($data) {
    return (@trim($data) === "" or $data === null or ! isset($data) or ! count($data) ) ? false : true;
}
function isCallByAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp('XMLHttpRequest', $_SERVER['HTTP_X_REQUESTED_WITH']) === 0);
}

function truncate_html($s, $l, $e = '&hellip;', $isHTML = true) {
    $s = trim($s);
    if ($isHTML) {
        $s = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $s);
        $s = strip_tags($s);
    }
    $e = (strlen(strip_tags($s)) > $l) ? $e : '';
    $i = 0;
    $tags = array();

    if ($isHTML) {
        preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($m as $o) {
            if ($o[0][1] - $i >= $l) {
                break;
            }
            $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
            if ($t[0] != '/') {
                $tags[] = $t;
            } elseif (end($tags) == substr($t, 1)) {
                array_pop($tags);
            }
            $i += $o[1][1] - $o[0][1];
        }
    }
    $output = substr($s, 0, $l = min(strlen($s), $l + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . $e;
    return $output;
}

function sendJsonEncode($returnData = array(), $exit = 0) {
    echo json_encode($returnData);
    if ($exit) {
        exit;
    }
}
