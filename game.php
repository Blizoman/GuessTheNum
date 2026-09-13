<?php
/**
 * Game logic. Handles any incoming POST request (Post/Redirect/Get pattern,
 * so a page refresh doesn't resubmit the last guess) and leaves the current
 * game state in $_SESSION['game'] ready for index.php.
 */

require_once __DIR__ . '/config.php';

session_start();

function startNewGame(string $difficulty): void
{
    $config = DIFFICULTIES[$difficulty];

    $_SESSION['game'] = [
        'difficulty' => $difficulty,
        'secret' => random_int($config['min'], $config['max']),
        'min' => $config['min'],
        'max' => $config['max'],
        'max_attempts' => $config['max_attempts'],
        'attempts' => 0,
        'history' => [],
        'status' => 'playing',
        'is_new_best' => false,
    ];
}

function processGuess(int $guess): void
{
    if (!isset($_SESSION['game']) || $_SESSION['game']['status'] !== 'playing') {
        return;
    }

    $game = &$_SESSION['game'];

    $guess = max($game['min'], min($game['max'], $guess));
    $game['attempts']++;

    if ($guess === $game['secret']) {
        $game['status'] = 'won';
        $game['is_new_best'] = maybeUpdateBestScore($game['difficulty'], $game['attempts']);
        $result = 'correct';
    } elseif ($guess < $game['secret']) {
        $result = 'higher';
    } else {
        $result = 'lower';
    }

    if ($game['status'] === 'playing' && $game['attempts'] >= $game['max_attempts']) {
        $game['status'] = 'lost';
    }

    $game['history'][] = ['guess' => $guess, 'result' => $result];
    unset($game);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'start') {
        $difficulty = (string)($_POST['difficulty'] ?? '');
        if (isValidDifficulty($difficulty)) {
            startNewGame($difficulty);
        }
    } elseif ($action === 'guess' && filter_var($_POST['guess'] ?? null, FILTER_VALIDATE_INT) !== false) {
        processGuess((int)$_POST['guess']);
    } elseif ($action === 'reset') {
        unset($_SESSION['game']);
    }

    header('Location: index.php');
    exit;
}
