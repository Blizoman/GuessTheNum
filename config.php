<?php
/**
 * Difficulty configuration and best-score handling (stored in a cookie,
 * since the project has no database).
 */

const DIFFICULTIES = [
    'easy' => [
        'label' => 'Ľahká',
        'min' => 1,
        'max' => 50,
        'max_attempts' => 8,
        'description' => '1 - 50, 8 pokusov',
    ],
    'medium' => [
        'label' => 'Stredná',
        'min' => 1,
        'max' => 100,
        'max_attempts' => 10,
        'description' => '1 - 100, 10 pokusov',
    ],
    'hard' => [
        'label' => 'Ťažká',
        'min' => 1,
        'max' => 500,
        'max_attempts' => 12,
        'description' => '1 - 500, 12 pokusov',
    ],
    'expert' => [
        'label' => 'Expert',
        'min' => 1,
        'max' => 1000,
        'max_attempts' => 15,
        'description' => '1 - 1000, 15 pokusov',
    ],
];

const BEST_SCORE_COOKIE = 'gtn_best_scores';

function isValidDifficulty(string $key): bool
{
    return array_key_exists($key, DIFFICULTIES);
}

/**
 * @return array<string,int> map of difficulty => fewest attempts needed to win
 */
function getBestScores(): array
{
    if (!isset($_COOKIE[BEST_SCORE_COOKIE])) {
        return [];
    }

    $decoded = json_decode($_COOKIE[BEST_SCORE_COOKIE], true);
    if (!is_array($decoded)) {
        return [];
    }

    $scores = [];
    foreach ($decoded as $difficulty => $attempts) {
        if (isValidDifficulty((string)$difficulty) && is_int($attempts)) {
            $scores[$difficulty] = $attempts;
        }
    }

    return $scores;
}

/**
 * Stores a new best score if it's better (or the first one) for the given difficulty.
 * @return bool true if this is a new record
 */
function maybeUpdateBestScore(string $difficulty, int $attempts): bool
{
    $scores = getBestScores();
    $isNewBest = !isset($scores[$difficulty]) || $attempts < $scores[$difficulty];

    if ($isNewBest) {
        $scores[$difficulty] = $attempts;
        setcookie(
            BEST_SCORE_COOKIE,
            json_encode($scores),
            [
                'expires' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => '/',
                'samesite' => 'Lax',
            ]
        );
        // make the value available immediately within this request too
        $_COOKIE[BEST_SCORE_COOKIE] = json_encode($scores);
    }

    return $isNewBest;
}
