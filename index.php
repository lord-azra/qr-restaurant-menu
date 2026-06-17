<?php
declare(strict_types=1);

$dataDir = __DIR__ . '/data';

function load_json(string $file, array $default = []): array {
    if (!file_exists($file)) return $default;
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

$config = load_json($dataDir . '/config.json', [
    'site_title' => 'Restoran Menü'
]);

$categories = load_json($dataDir . '/categories.json', []);

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($config['site_title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="header">
    <?php echo h($config['site_title']); ?>
</header>

<main class="container">

    <div class="grid">

        <?php foreach ($categories as $cat): ?>
            <a class="card" href="category.php?id=<?php echo h((string)$cat['id']); ?>">

                <div class="card-img"
                     style="background-image:url('<?php echo h($cat['image'] ?? ''); ?>')">
                </div>

                <div class="card-title">
                    <?php echo h($cat['name']); ?>
                </div>

            </a>
        <?php endforeach; ?>

    </div>

</main>

</body>
</html>
