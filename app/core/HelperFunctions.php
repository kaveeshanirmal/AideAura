<?php

function esc($string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) return $diff . ' seconds ago';
    $diff = round($diff / 60);
    if ($diff < 60) return $diff . ' mins ago';
    $diff = round($diff / 60);
    if ($diff < 24) return $diff . ' hours ago';
    $diff = round($diff / 24);
    if ($diff < 7) return $diff . ' days ago';

    return date('M d, Y', $timestamp);
}
