<?php

namespace App\Helpers;

use DateTime;
use Exception;
use Carbon\Carbon;

class DateConverter
{
    /**
     * Converts date from 'Sunday 17th of August 2025' format to 'YYYY-MM-DD'
     * 
     * @param string $dateString The date string in format 'Weekday DDth of Month YYYY'
     * @return string|false The date in YYYY-MM-DD format or false on failure
     * 
     * @example
     * DateConverter::convertToYMD('Sunday 17th of August 2025') // Returns '2025-08-17'
     * DateConverter::convertToYMD('Monday 1st of January 2024') // Returns '2024-01-01'
     */
    public static function convertToYMD($dateString)
    {
        try {
            // Trim whitespace
            $dateString = trim($dateString);
            
            // Remove ordinal suffixes (st, nd, rd, th) from the day
            $cleanedDate = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $dateString);
            
            // Remove the day name (everything before the first number)
            $cleanedDate = preg_replace('/^[A-Za-z]+\s+/', '', $cleanedDate);
            
            // Remove 'of' between day and month
            $cleanedDate = str_replace(' of ', ' ', $cleanedDate);
            
            // Create DateTime object from the cleaned string
            $dateTime = DateTime::createFromFormat('j F Y', $cleanedDate);
            
            if ($dateTime === false) {
                return false;
            }
            
            // Return in YYYY-MM-DD format
            return $dateTime->format('Y-m-d');
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Converts date using Carbon (Laravel's preferred date library)
     * 
     * @param string $dateString The date string in format 'Weekday DDth of Month YYYY'
     * @return string|false The date in YYYY-MM-DD format or false on failure
     */
    public static function convertToYMDCarbon($dateString)
    {
        try {
            // Trim whitespace
            $dateString = trim($dateString);
            
            // Remove ordinal suffixes and clean the string
            $cleanedDate = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $dateString);
            $cleanedDate = preg_replace('/^[A-Za-z]+\s+/', '', $cleanedDate);
            $cleanedDate = str_replace(' of ', ' ', $cleanedDate);
            
            // Use Carbon to parse the date
            $carbon = Carbon::createFromFormat('j F Y', $cleanedDate);
            
            return $carbon->format('Y-m-d');
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * More robust function that handles various edge cases and formats
     * 
     * @param string $dateString The date string in format 'Weekday DDth of Month YYYY'
     * @return string|false The date in YYYY-MM-DD format or false on failure
     */
    public static function convertToYMDRobust($dateString)
    {
        try {
            // Trim whitespace
            $dateString = trim($dateString);
            
            // Remove ordinal suffixes (st, nd, rd, th) from the day
            $cleanedDate = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $dateString);
            
            // Remove the day name (everything before the first number)
            $cleanedDate = preg_replace('/^[A-Za-z]+\s+/', '', $cleanedDate);
            
            // Remove 'of' between day and month
            $cleanedDate = str_replace(' of ', ' ', $cleanedDate);
            
            // Try different date formats
            $formats = [
                'j F Y',     // 17 August 2025
                'd F Y',     // 17 August 2025 (with leading zero)
                'j M Y',     // 17 Aug 2025
                'd M Y'      // 17 Aug 2025 (with leading zero)
            ];
            
            foreach ($formats as $format) {
                $dateTime = DateTime::createFromFormat($format, $cleanedDate);
                if ($dateTime !== false) {
                    return $dateTime->format('Y-m-d');
                }
            }
            
            // If none of the formats work, try strtotime as last resort
            $timestamp = strtotime($cleanedDate);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
            
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Converts date and validates that it's a valid date
     * 
     * @param string $dateString The date string in format 'Weekday DDth of Month YYYY'
     * @return array Returns array with 'success', 'date', and 'error' keys
     */
    public static function convertWithValidation($dateString)
    {
        $result = [
            'success' => false,
            'date' => null,
            'error' => null
        ];

        if (empty($dateString) || !is_string($dateString)) {
            $result['error'] = 'Invalid input: Date string is required';
            return $result;
        }

        $convertedDate = self::convertToYMDRobust($dateString);

        if ($convertedDate === false) {
            $result['error'] = 'Unable to parse date string: ' . $dateString;
            return $result;
        }

        // Validate the converted date
        $dateParts = explode('-', $convertedDate);
        if (count($dateParts) !== 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
            $result['error'] = 'Invalid date: ' . $dateString;
            return $result;
        }

        $result['success'] = true;
        $result['date'] = $convertedDate;
        return $result;
    }
}
