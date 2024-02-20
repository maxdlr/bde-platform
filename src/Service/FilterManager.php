<?php 

namespace App\Service;
use App\Entity\User;
use DateTime;

class FilterManager
{
    function filterBy(array $dates, string $tri = 'croissant', string $dateDebut = null, string $dateFin = null) {
        // Tri des dates
        if ($tri === 'croissant') {
            sort($dates);
        } elseif ($tri === 'descroissant') {
            rsort($dates);
        }
        
        // Filtrage des dates dans l'intervalle spécifié
        if ($dateDebut !== null && $dateFin !== null) {
            // Convertir les dates de début et de fin en timestamps
            $timestampDebut = strtotime($dateDebut);
            $timestampFin = strtotime($dateFin);
            
            // Filtrer les dates qui se situent dans l'intervalle spécifié
            $dates = array_filter($dates, function($date) use ($timestampDebut, $timestampFin) {
                $timestamp = strtotime($date);
                return ($timestamp >= $timestampDebut && $timestamp <= $timestampFin);
            });
        }
        
        return $dates;
    }
    
}
