<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if (!function_exists('convert_number_to_words')) {

  // Indian Numbering System: Crore, Lakh, Thousand, Hundred
  // Accepts an optional second parameter to avoid warnings when called with extra args.
  function convert_number_to_words($number, $precision_ignored = null) {
    $negative = 'negative ';
    $dictionary = [
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
    ];

    if (!is_numeric($number)) {
      return false;
    }

    // Handle negative numbers
    if ($number < 0) {
      return $negative . convert_number_to_words(abs($number));
    }

    // Support decimals (paise) consistently for numeric inputs
    $fraction = null;
    $intPart = $number;
    if (is_numeric($number)) {
      // Normalize to string with 2 decimals to capture paise if present
      $normalized = number_format((float)$number, 2, '.', '');
      if (strpos($normalized, '.') !== false) {
        list($intPart, $fraction) = explode('.', $normalized, 2);
      } else {
        $intPart = $normalized;
      }
    } elseif (is_string($number) && strpos($number, '.') !== false) {
      list($intPart, $fraction) = explode('.', $number, 2);
    }

    // Work with integer part only for words
    $number = (int) $intPart;

    if ($number === 0) {
      $words = $dictionary[0];
    } else {
      $words = '';

      $crore = (int) floor($number / 10000000);
      $number %= 10000000;
      $lakh = (int) floor($number / 100000);
      $number %= 100000;
      $thousand = (int) floor($number / 1000);
      $number %= 1000;
      $hundred = (int) floor($number / 100);
      $remainder = $number % 100;

      $parts = [];
      if ($crore) {
        $parts[] = convert_number_to_words($crore) . ' crore';
      }
      if ($lakh) {
        $parts[] = convert_number_to_words($lakh) . ' lakh';
      }
      if ($thousand) {
        $parts[] = convert_number_to_words($thousand) . ' thousand';
      }
      if ($hundred) {
        $parts[] = convert_number_to_words($hundred) . ' hundred';
      }
      if ($remainder) {
        // Two-digit handling without hyphen
        if ($remainder < 20) {
          $parts[] = $dictionary[$remainder];
        } else {
          $tens = ((int) ($remainder / 10)) * 10;
          $units = $remainder % 10;
          $tens_word = $dictionary[$tens];
          $parts[] = $units ? ($tens_word . ' ' . $dictionary[$units]) : $tens_word;
        }
      }

      $words = trim(implode(' ', $parts));
    }

    // If there is a fraction (paise), append in words as well (optional)
    if ($fraction !== null && is_numeric($fraction)) {
      $fraction = substr(str_pad($fraction, 2, '0'), 0, 2); // ensure 2 digits
      if ($fraction !== '' && (int)$fraction > 0) {
        $words .= ' and ' . convert_number_to_words((int)$fraction) . ' paise';
      }
    }

    return $words;
  }

  if (!function_exists('format_inr')) {

    function format_inr($amount) {
      return number_format($amount, 2, '.', ',');
    }

  }
}
