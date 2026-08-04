<?php
$lines = file(__DIR__ . '/.env');
$out = array_slice($lines, 0, 67);
$out[] = "GEMINI_API_KEY=your_gemini_api_key_here\n";
file_put_contents(__DIR__ . '/.env', implode('', $out));
echo "Fixed .env";

$linesEx = file(__DIR__ . '/.env.example');
foreach($linesEx as $k => $v) {
    if (strpos(bin2hex($v), '00') !== false) {
        unset($linesEx[$k]);
    }
}
file_put_contents(__DIR__ . '/.env.example', implode('', $linesEx));
echo "Fixed .env.example";
