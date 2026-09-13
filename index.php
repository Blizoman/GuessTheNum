<?php
require_once __DIR__ . '/game.php';

$game = $_SESSION['game'] ?? null;
$bestScores = getBestScores();
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hádaj číslo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="page">
    <h1 class="title">🎯 Hádaj číslo</h1>
    <p class="subtitle">Vyber si obtiažnosť a skús uhádnuť tajné číslo na čo najmenej pokusov.</p>

    <?php if ($game === null): ?>

        <div class="card">
            <form method="post" action="index.php">
                <input type="hidden" name="action" value="start">
                <div class="difficulty-grid">
                    <?php foreach (DIFFICULTIES as $key => $config): ?>
                        <button type="submit" name="difficulty" value="<?= htmlspecialchars($key) ?>" class="difficulty-card">
                            <strong><?= htmlspecialchars($config['label']) ?></strong>
                            <span><?= htmlspecialchars($config['description']) ?></span>
                            <?php if (isset($bestScores[$key])): ?>
                                <div class="best">🏆 Najlepšie: <?= (int)$bestScores[$key] ?> pokusov</div>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>

        <img src="target.png" alt="Terč" class="footer-illustration">

    <?php else:
        $config = DIFFICULTIES[$game['difficulty']];
        $progress = (int)round(($game['attempts'] / $game['max_attempts']) * 100);
        ?>

        <div class="card">
            <div class="status-row">
                <span class="badge"><?= htmlspecialchars($config['label']) ?></span>
                <span class="badge">Pokusy: <?= (int)$game['attempts'] ?> / <?= (int)$game['max_attempts'] ?></span>
            </div>

            <?php if ($game['status'] === 'playing'): ?>
                <p class="range-hint">Tajné číslo je medzi <strong><?= (int)$game['min'] ?></strong> a <strong><?= (int)$game['max'] ?></strong>.</p>

                <div class="progress-track">
                    <div class="progress-fill" style="width: <?= min(100, $progress) ?>%"></div>
                </div>
                <div class="progress-label"><?= (int)$game['max_attempts'] - (int)$game['attempts'] ?> pokusov ostáva</div>

                <form method="post" action="index.php" class="guess-form">
                    <input type="hidden" name="action" value="guess">
                    <input
                        type="number"
                        name="guess"
                        min="<?= (int)$game['min'] ?>"
                        max="<?= (int)$game['max'] ?>"
                        placeholder="Tvoj tip..."
                        autofocus
                        required
                    >
                    <button type="submit" class="primary">Hádať</button>
                </form>

            <?php elseif ($game['status'] === 'won'): ?>
                <div class="result-banner won">
                    <span class="big">🎉 Výhra!</span>
                    Uhádol si číslo <strong><?= (int)$game['secret'] ?></strong> na <?= (int)$game['attempts'] ?> pokusov.
                    <?php if (!empty($game['is_new_best'])): ?>
                        <div class="new-best">✨ Nový osobný rekord!</div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="result-banner lost">
                    <span class="big">😢 Prehra</span>
                    Minul si všetky pokusy. Tajné číslo bolo <strong><?= (int)$game['secret'] ?></strong>.
                </div>
            <?php endif; ?>

            <?php if (!empty($game['history'])): ?>
                <ul class="history">
                    <?php foreach (array_reverse($game['history']) as $entry): ?>
                        <li>
                            <span>Tip: <strong><?= (int)$entry['guess'] ?></strong></span>
                            <?php if ($entry['result'] === 'correct'): ?>
                                <span class="result-tag correct">✔ Presne!</span>
                            <?php elseif ($entry['result'] === 'higher'): ?>
                                <span class="result-tag higher">▲ Vyššie</span>
                            <?php else: ?>
                                <span class="result-tag lower">▼ Nižšie</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="actions">
                <?php if ($game['status'] !== 'playing'): ?>
                    <form method="post" action="index.php">
                        <input type="hidden" name="action" value="start">
                        <input type="hidden" name="difficulty" value="<?= htmlspecialchars($game['difficulty']) ?>">
                        <button type="submit" class="primary" style="width:100%;">Hrať znova (<?= htmlspecialchars($config['label']) ?>)</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="reset">
                    <button type="submit" class="secondary">Zmeniť obtiažnosť</button>
                </form>
            </div>
        </div>

    <?php endif; ?>
</div>
</body>
</html>
