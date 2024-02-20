<?php 

namespace App\Service;
use App\Entity\User;
use DateTime;

class FilterManager
{
    function filterBy(array $dates, string $tri = 'croissant', string $dateDebut = null, string $dateFin = null) {
        if ($tri === 'croissant') {
            sort($dates);
        } elseif ($tri === 'descroissant') {
            rsort($dates);
        }
        
        if ($dateDebut !== null && $dateFin !== null) {
            $timestampDebut = strtotime($dateDebut);
            $timestampFin = strtotime($dateFin);
            
            $dates = array_filter($dates, function($date) use ($timestampDebut, $timestampFin) {
                $timestamp = strtotime($date);
                return ($timestamp >= $timestampDebut && $timestamp <= $timestampFin);
            });
        }
        
        return $dates;
    }
    
}
