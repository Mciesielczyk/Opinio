<?php

function calculateSimilarity($s1, $s2) {
    $axes = [
        'score_lewa_prawa', 
        'score_wladza_wolnosc', 
        'score_postep_konserwa', 
        'score_globalizm_nacjonalizm'
    ];

    // Sprawdzamy, czy użytkownik 1 ma dane (np. Ty)
    $hasData1 = false;
    if ($s1) {
        foreach ($axes as $axis) {
            if (isset($s1[$axis]) && $s1[$axis] !== null) {
                $hasData1 = true;
                break;
            }
        }
    }

    // Sprawdzamy, czy użytkownik 2 ma dane (np. znajomy)
    $hasData2 = false;
    if ($s2) {
        foreach ($axes as $axis) {
            if (isset($s2[$axis]) && $s2[$axis] !== null) {
                $hasData2 = true;
                break;
            }
        }
    }

    // Jeśli ktokolwiek nie wypełnił ani jednej ankiety (brak rekordu), dajemy 50%
    if (!$hasData1 || !$hasData2) {
        return 50;
    }

    $sumOfSquares = 0;
    foreach ($axes as $axis) {
        $val1 = (float)($s1[$axis] ?? 0);
        $val2 = (float)($s2[$axis] ?? 0);
        $sumOfSquares += pow($val1 - $val2, 2);
    }

    $distance = sqrt($sumOfSquares);
    $maxDistance = 10; // Skalowanie: im wyższy, tym wolniej spada podobieństwo
    
    $similarity = 100 * (1 - ($distance / $maxDistance));
    
    return (int) max(0, min(100, round($similarity)));
}