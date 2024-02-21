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

    static public function sortBy(string $value, bool $reverse = false): static
    {
        switch($value) {
            case "name":
                usort(self::$events, function ($a, $b)
        {
            return strcmp($a->getName(),  $b->getName());
        });
            break;
            case "date":
                if(!$reverse)
                {
                    usort(self::$events, function ($a, $b)
                    {
                        return strcasecmp($b->getStartDate()->format('Y-m-d'), $a->getStartDate()->format('Y-m-d'));
                    });
                }
                else {
                    usort(self::$events, function ($a, $b)
                    {
                        return strcasecmp($a->getStartDate()->format('Y-m-d'),$b->getStartDate()->format('Y-m-d'));
                    });
                }
                break;
            default:
                throw new \Exception("Merci de renseigner un champ à tri");
        }

        return new static;
    }
}
