<?php 

namespace App\Service;

use App\Entity\Event;
use DateTime;

class EventFilter
{
    static private array $events;
    /** @var Event[] */
    static public function use(array $events): static
    {
        self::$events = $events;
        return new static;
    }

    static public function return(): array
    {
        return self::$events;
    }

    static public function sortBy(string $value): static
    {
        if($value == "name")
        {
        usort(self::$events, function ($a, $b)
        {
            return strcmp($a->getName(),  $b->getName());
        });
    }
    else{

        uasort(self::$events, function ($a, $b)
        {
            return strcasecmp($b->getStartDate() , $a->getStartDate());
        });

    }

        return new static;
    }
}
